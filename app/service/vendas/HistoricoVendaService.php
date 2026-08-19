<?php

class HistoricoVendaService
{
    
    private static $dbNw = 'nw';
    private static $dbAp = 'integrador';
    
    public static function registrarVendas(){
        try{
            
            TTransaction::open(self::$dbNw);
            $openTransaction = TTransaction::getDatabase() == self::$dbNw ? true : false;
            TTransaction::close();
            if(!$openTransaction){
                throw new Exception("Sem conexão com a base de dados '$dbNw', verifique o arquivo de configuração e tente novamente.");
            }
            
            $sessao = TSession::getValue('userunitid') ?? 1;
            
            TTransaction::open('integrador');
            
            $arrayGrupo  = ApGrupoCliente::where('system_unit_id','=',$sessao)->where('cod_grpcliente','is not',null)->getIndexedArray('id','cod_grpcliente');
            $arrayRepres = ApRepresentante::where('system_unit_id','=',$sessao)->getIndexedArray('id','cod_repres');
            $arrayCidade = ApCidade::where('cod_cidade','is not', null)->getIndexedArray('id','cod_cidade');
            $arrayEstado = ApEstado::where('cod_estado','is not', null)->getIndexedArray('id','cod_estado');
            $arrayItem   = ApItem::where('id','is not', null)->getIndexedArray('id','cod_item');
            $arrayEsto   = ApGrupoEstoque::where('id','is not', null)->getIndexedArray('id','cod_grupoestoque');
            
            $arrayVendas = array();
            
            $meta = Meta::where('status','=',1)->first();
            
            //$meta = Meta::where('system_unit_id','=',$sessao)->where('mes','=',4)->where('ano','=',2025)->first();
            //$meta = Meta::where('system_unit_id','=',$sessao)->where('mes','=',5)->where('ano','=',2025)->first();
            //$meta = Meta::where('system_unit_id','=',$sessao)->where('mes','=',6)->where('ano','=',2025)->first();

            if(!$meta){
                TTransaction::close();
                throw new Exception("Sem meta aberta.");
            }
            
            TTransaction::close();
            
            TTransaction::open('nw');
            $conn = TTransaction::get();
            
            $result = $conn->query("
                select
                    nota_fiscal.dt_emissao as data_emissao,
                    clifor.pessoa as tipo_pessoa,
                    clifor.dt_cadastro as clifor_data_cadastro,
                    trim(nota_fiscal.nro_nfiscal) as nro_nf,
                    trim(nota_fiscal.cod_clifor) as cod_clifor,
                    trim(clifor.razao) as clifor_razao,
                    trim(clifor.agente_regularanp) as clifor_agente,
                    trim(cidade.cod_cidade) as cod_cidade,
                    trim(clifor.cod_estado) as cod_estado,
                    trim(grupo_cliente.cod_grpcliente) as cod_grpcliente,
                    trim(representante.cod_repres) as cod_repres,
                    trim(rota_cadastro.descricao) as rota
                from
                    nota_fiscal
                    inner join clifor on clifor.cod_clifor = nota_fiscal.cod_clifor
                    inner join representante on representante.cod_repres = nota_fiscal.cod_repres
                    inner join cidade on cidade.cod_cidade = clifor.cod_cidade
                    inner join estado on estado.cod_estado = clifor.cod_estado
                    inner join grupo_cliente on grupo_cliente.cod_grpcliente = clifor.cod_grpcliente
                    LEFT JOIN tabela_planilha rota_clifor ON rota_clifor.chave_tabela = clifor.cod_clifor AND rota_clifor.nome_tabela = 'CLIFOR' AND rota_clifor.seq_campos = 2
                    LEFT JOIN tabela_padraoreg rota_cadastro ON rota_cadastro.cod_tabelapadraoreg = rota_clifor.cod_tabelapadraoreg 
                where
                nota_fiscal.cod_empresa = '101'
                    and nota_fiscal.situacao <> 'C'
                    and nota_fiscal.dt_emissao >= '$meta->data_inicial'
                    and nota_fiscal.dt_emissao <= '$meta->data_final'
                    and nota_fiscal.entrada_saida in ('S','E')
            ");
            $objects = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            
            TTransaction::close();
            
            TTransaction::open('integrador');
            
            $vendas = HistoricoVenda::where('meta_id','=',$meta->id)->getIndexedArray('id', 'id');
            if($vendas){
                HistoricoVendaItem::where('venda_id','in',$vendas)->delete();
                HistoricoVenda::where('meta_id','=',$meta->id)->delete(); 
            }
            
            if($objects)
            {
                foreach($objects as $object) 
                {
                    $venda = new HistoricoVenda();
                    
                    $venda->meta_id = $meta->id;
                    $venda->system_unit_id = $sessao;
                    $venda->data_emissao = $object->data_emissao;
                    $venda->data_cadastro = $object->clifor_data_cadastro;
                    $venda->tipo_pessoa = $object->tipo_pessoa;
                    $venda->nro = $object->nro_nf;
                    $venda->cod_clifor = $object->cod_clifor;
                    $venda->razao = $object->clifor_razao;
                    $venda->agente = $object->clifor_agente;
                    $venda->rota = $object->rota;
                    $venda->grupo_cliente_id = ($result = array_search($object->cod_grpcliente, $arrayGrupo)) !== false ? $result : null;
                    $venda->repres_id = ($result = array_search($object->cod_repres, $arrayRepres)) !== false ? $result : null;
                    $venda->cidade_id = ($result = array_search($object->cod_cidade, $arrayCidade)) !== false ? $result : null;
                    $venda->estado_id = ($result = array_search($object->cod_estado, $arrayEstado)) !== false ? $result : null;
                    $venda->store();
                    $arrayVendas[$venda->id] = $venda->nro;
                }
            }
            TTransaction::close();
            
            TTransaction::open('nw');
            $conn = TTransaction::get();
            $result = $conn->query("
                select 
                    CASE
                        WHEN coalesce(trim(item.aplicacao), '') = 'X' THEN 'IMPORT'
                        WHEN coalesce(trim(grupo_estoque.auxiliar_string1), '') = 'FP' THEN 'PERSIANAS'
                        ELSE 'DECOR'
                    END AS empresa,
                    trim(nota_fiscal.nro_nfiscal) as nro_nf,
                    trim(item.codigo) AS cod_item,
                    item.cod_grupoestoque as grupo_estoque, 
                    item_notafiscal.sequencia as item_sequencia,
                    item_notafiscal.quantidade AS item_quantidade,
                    item_notafiscal.valor_unitario AS item_valor_unitario,
                    (item_notafiscal.quantidade * item_notafiscal.valor_unitario) as item_valor_mercadoria,
                    item_notafiscal.perc_descto AS item_percentual_desconto,
                    item_notafiscal.valor_descto as item_valor_desconto,
                    (item_notafiscal.valor_total + item_notafiscal.valor_frete + ITEM_NOTAFISCAL.VALOR_DESPESAS) as item_valor_total
                from
                    nota_fiscal
                    inner join item_notafiscal on (item_notafiscal.cod_empresa = nota_fiscal.cod_empresa
                        and item_notafiscal.serie = nota_fiscal.serie
                        and item_notafiscal.cod_clifor = nota_fiscal.cod_clifor
                        and item_notafiscal.nro_nfiscal = nota_fiscal.nro_nfiscal)
                    inner join item on item.cod_item = item_notafiscal.cod_item
                    inner join grupo_estoque on grupo_estoque.cod_grupoestoque = item.cod_grupoestoque
                    inner join clifor on clifor.cod_clifor = nota_fiscal.cod_clifor
                    inner join representante on representante.cod_repres = nota_fiscal.cod_repres
                    inner join cidade on cidade.cod_cidade = clifor.cod_cidade
                    inner join estado on estado.cod_estado = clifor.cod_estado
                    inner join grupo_cliente on grupo_cliente.cod_grpcliente = clifor.cod_grpcliente
                where
                nota_fiscal.cod_empresa = '101'
                    and nota_fiscal.situacao <> 'C'
                    and nota_fiscal.dt_emissao >= '$meta->data_inicial'
                    and nota_fiscal.dt_emissao <= '$meta->data_final'
                    and nota_fiscal.entrada_saida = 'S'
                    and item_notafiscal.cod_tiponatureza = '008'
                    and item_notafiscal.cfo not in ('5997', '6997', '5998', '6998')
                union all
                select
                    CASE
                        WHEN coalesce(trim(item.aplicacao), '') = 'X' THEN 'IMPORT'
                        WHEN coalesce(trim(grupo_estoque.auxiliar_string1), '') = 'FP' THEN 'PERSIANAS'
                        ELSE 'DECOR'
                    END AS empresa,
                    trim(nota_fiscal.nro_nfiscal) as nro_nf,
                    trim(item.codigo) AS CODIGO_ITEM,
                    item.cod_grupoestoque as grupo_estoque, 
                    item_notafiscal.sequencia as item_sequencia,
                    (item_notafiscal.quantidade * -1) AS QTDE,
                    item_notafiscal.valor_unitario AS VLR_UNITARIO,
                    (item_notafiscal.quantidade * item_notafiscal.valor_unitario) as VALOR_MERCADORIA,
                    item_notafiscal.perc_descto AS PERC_DESCONTO,
                    (item_notafiscal.valor_descto * -1) as VALOR_DESCTO,
                    ((item_notafiscal.valor_total + item_notafiscal.valor_frete + ITEM_NOTAFISCAL.VALOR_DESPESAS )*-1) as VALOR_TOTAL
                from
                    nota_fiscal
                    inner join item_notafiscal on (item_notafiscal.cod_empresa = nota_fiscal.cod_empresa
                                                    and item_notafiscal.serie = nota_fiscal.serie
                                                    and item_notafiscal.cod_clifor = nota_fiscal.cod_clifor
                                                    and item_notafiscal.nro_nfiscal = nota_fiscal.nro_nfiscal)
                    inner join item on item.cod_item = item_notafiscal.cod_item
                    inner join grupo_estoque on grupo_estoque.cod_grupoestoque = item.cod_grupoestoque
                    inner join clifor on clifor.cod_clifor = nota_fiscal.cod_clifor
                    inner join representante on representante.cod_repres = nota_fiscal.cod_repres
                    inner join cidade on cidade.cod_cidade = clifor.cod_cidade
                    inner join estado on estado.cod_estado = clifor.cod_estado
                    inner join grupo_cliente on grupo_cliente.cod_grpcliente = clifor.cod_grpcliente
                where
                    nota_fiscal.cod_empresa = '101'
                    and nota_fiscal.situacao <> 'C'
                    and nota_fiscal.dt_emissao >= '$meta->data_inicial'
                    and nota_fiscal.dt_emissao <= '$meta->data_final'
                    and nota_fiscal.entrada_saida = 'E'
                    and item_notafiscal.cod_tiponatureza = '012'
                    and item_notafiscal.cfo not in ('5997', '6997', '5998', '6998')
            ");
            $objects = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            
            TTransaction::close();
            
            TTransaction::open('integrador');
            
            $alerta = array();
            
            if($objects)
            {
                foreach($objects as $object){
                    $venda_item = new HistoricoVendaItem();
                    if(array_search($object->nro_nf, $arrayVendas) === false){
                        $alerta[] = $nro_nf;
                    }else{
                        $venda_item->venda_id = array_search($object->nro_nf, $arrayVendas);
                        $venda_item->empresa = $object->empresa;
                        $venda_item->cod_item = $object->cod_item;
                        $venda_item->grupo_estoque_id = ($result = array_search($object->grupo_estoque, $arrayEsto)) !== false ? $result : null;
                        $venda_item->quantidade = $object->item_quantidade;
                        $venda_item->valor_unitario = $object->item_valor_unitario;
                        $venda_item->valor_mercadoria = $object->item_valor_mercadoria;
                        $venda_item->perc_desconto = $object->item_percentual_desconto;
                        $venda_item->valor_desconto = $object->item_valor_desconto;
                        $venda_item->valor_total = $object->item_valor_total;
                        $venda_item->sequencia = $object->item_sequencia;
                        $venda_item->store();
                    }
                }
            }
            TTransaction::close();
            
            $erro = "<br/>Nota(s) não encotradas: ".implode(',',$alerta).".";
            
            //Registro de log de execução
          LogCrontab::registrarLog("Atualização de Vendas", __METHOD__, 0, "Histórico de Vendas do mês $meta->mes registrado.", "Arquivo: HistoricoVendaService.<br/>Linha: ".__LINE__.".",$sessao);
        } catch (Exception $e) {
         LogCrontab::registrarLog("Atualização de Vendas", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }
}
