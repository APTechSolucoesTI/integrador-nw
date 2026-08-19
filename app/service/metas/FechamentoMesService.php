<?php

class FechamentoMesService
{
    private static $dbNw = 'nw';
    private static $dbAp = 'integrador';
    private static $dbSite = 'site';

    //FECHAMENTO COMPLETO
    public static function fechar() {
        try{
            $sessao = TSession::getValue('userunitid') ?? 1; 
        
            $metaAberta = self::get_meta_aberta();
            
            $agora      = new DateTime();       // Data e hora atual
            $data_final = new DateTime($metaAberta->data_final);

            if ($data_final < $agora) {
                $ret_vendido    = FechamentoMesService::obterValorVendido();
                $ret_bonus      = FechamentoMesService::verificarBonusComissao();
                $ret_prosp_reat = FechamentoMesService::verificaPremioProspeccaoReativacao();
                
                if($ret_vendido && $ret_bonus && $ret_prosp_reat){
                    LogCrontab::registrarLog("Fechamento do mês", __METHOD__, 0, "Registros do mês {$metaAberta->mes} realizados.", "Arquivo: ".__CLASS__.".<br/>Linha: ".__LINE__.".",$sessao);
                    LogCrontab::registrarLog("Fechamento do mês", __METHOD__, 0, "Metas do mês {$metaAberta->mes} realizados.", "Arquivo: ".__CLASS__.".<br/>Linha: ".__LINE__.".",$sessao);
                }else{
                    if (!$ret_vendido)    $erros[] = 'ret_vendido';
                    if (!$ret_bonus)      $erros[] = 'ret_bonus';
                    if (!$ret_prosp_reat) $erros[] = 'ret_prosp_reat';
                   

                    if (!empty($erros)) {
                        throw new Exception(implode(', ', $erros));
                    }
                }
            }
        } catch (Exception $e) {
            LogCrontab::registrarLog("Fechamento do mês", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }

     public static function get_meta_aberta(){
        try{
            $sessao = TSession::getValue('userunitid') ?? 1; 

            TTransaction::open(self::$dbAp);

            $conn = TTransaction::get();
            $metaAberta = Meta::where('system_unit_id','=',$sessao)->where('status','=',1)->first();
            //$metaAberta = Meta::where('system_unit_id','=',$sessao)->where('mes','=',5)->where('ano','=',2025)->first();
            //$metaAberta = Meta::where('system_unit_id','=',$sessao)->where('mes','=',4)->where('ano','=',2025)->first();
            //$metaAberta = Meta::where('system_unit_id','=',$sessao)->where('mes','=',6)->where('ano','=',2025)->first();

            TTransaction::close();

            if (!$metaAberta) {
                throw new Exception('Meta aberta não encontrada');
            }
            return $metaAberta;
        } catch (Exception $e) {
            LogCrontab::registrarLog("Fechamento do mês", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }


    public static function obterValorVendido(){
        try{
            $sessao = TSession::getValue('userunitid') ?? 1; 
            TTransaction::open(self::$dbAp);
            $conn = TTransaction::get();
            $arrayRepres = ApRepresentante::where('system_unit_id','=',$sessao)->getIndexedArray('id','cod_repres');

            $metaAberta = self::get_meta_aberta();

            $cli_prospeccao = HistoricoCliRepres::where('mes','=',$metaAberta->mes)
                                                ->where('ano','=',$metaAberta->ano)
                                                ->where('prospeccao','=','S')
                                                ->getIndexedArray('cod_clifor','cod_clifor');

            $cli_reativacao = HistoricoCliRepres::where('mes','=',$metaAberta->mes)
                                                ->where('ano','=',$metaAberta->ano)
                                                ->where('tipo','=','Reativado')
                                                ->getIndexedArray('cod_clifor','cod_clifor');

            $grupo_id_excluir = ApGrupoCliente::where('descricao','in',['ORÇAMENTO (COMERCIAL)', 'COLABORADOR', 'INATIVO', 'NÃO APLICÁVEL', 'TRIANGULAR'])->getIndexedArray('id', 'id');
            
            $cli_ativos     = HistoricoCliRepres::where('mes','=',$metaAberta->mes)
                                                ->where('ano','=',$metaAberta->ano)
                                                ->where('ativo','=','S')
                                                ->where('grupo_id','not in',$grupo_id_excluir)
                                                ->select('cod_clifor')
                                                ->count();
            // Adiciona aspas simples a cada valor
            $cli_prospeccao = array_map(function($item) {
                return "'$item'";
            }, $cli_prospeccao);

            // Adiciona aspas simples a cada valor
            $cli_reativacao = array_map(function($item) {
                return "'$item'";
            }, $cli_reativacao);

            MetaFechamento::where('meta_id','=',$metaAberta->id)->delete();

            TTransaction::close();
            
            TTransaction::open(self::$dbNw);
            $conn = TTransaction::get();
            $result = $conn->query("
                SELECT 
                    FINAL.cod_repres as cod_repres,
                    FINAL.valor_total as valor_total,
                    FINAL.decor as decor,
                    FINAL.import as import,
                    FINAL.persianas as persianas,
                    FINAL.cortina_pronta as cortina_pronta,
                    FINAL.mostruario as mostruario,
                    FINAL.prospeccao as prospeccao,
                    FINAL.reativacao as reativacao
                FROM 
                    (
                        SELECT 
                            AUX.cod_repres as cod_repres,
                            SUM(AUX.valor_total) as valor_total,
                            SUM(AUX.decor) as decor,
                            SUM(AUX.import) as import,
                            SUM(AUX.persianas) as persianas,
                            SUM(AUX.cortina_pronta) as cortina_pronta,
                            SUM(AUX.mostruario) as mostruario,
                            SUM(AUX.prospeccao) as prospeccao,
                            SUM(AUX.reativacao) as reativacao
                        FROM 
                            (
                                SELECT
                                    v.cod_repres as cod_repres,
                                    SUM(v.item_valor_total) as valor_total,
                                    CASE WHEN v.empresa = 'DECOR' THEN SUM(v.item_valor_total) ELSE 0 END as decor,
                                    CASE WHEN v.empresa = 'IMPORT' THEN SUM(v.item_valor_total) ELSE 0 END as import,
                                    CASE WHEN v.empresa = 'PERSIANAS' THEN SUM(v.item_valor_total) ELSE 0 END as persianas,
                                    CASE WHEN v.item_grpestoque_seq = 2  THEN SUM(v.item_valor_total) ELSE 0 END as cortina_pronta,
                                    CASE WHEN v.item_grpestoque_seq = 12 THEN SUM(v.item_valor_total) ELSE 0 END as mostruario,                                    
                                    CASE WHEN v.cod_clifor in (".implode(', ',$cli_prospeccao).") THEN SUM(v.item_valor_total) ELSE 0 END as prospeccao,
                                    CASE WHEN v.cod_clifor in (".implode(', ',$cli_reativacao).") THEN SUM(v.item_valor_total) ELSE 0 END as reativacao
                                FROM
                                    (
                                        SELECT
                                            TAB.EMPRESA AS empresa,
                                            (TAB.DATA_DE_EMISSAO) AS data_emissao,
                                            (TAB.DATA_CADASTRO) AS clifor_data_cadastro,
                                            TRIM(TAB.NRO_NOTA_FISCAL) AS nro_nf,
                                            TRIM(TAB.CODIGO_CLIENTE) AS cod_clifor,
                                            TRIM(TAB.RAZAO_SOCIAL) AS clifor_razao,
                                            TRIM(TAB.AGENTE_REGULARANP) AS clifor_agente,
                                            TRIM(TAB.MUNICIPIO) AS clifor_cidade,
                                            TRIM(TAB.ESTADO) AS clifor_estado,
                                            TRIM(TAB.UF) AS clifor_uf,
                                            TRIM(TAB.GRUPO_CLIENTE) AS clifor_grp,
                                            TRIM(TAB.CODIGO_ITEM) AS cod_item,
                                            TRIM(TAB.DESCRICAO_ITEM) AS item_descricao,
                                            TRIM(TAB.GRUPO_DE_ESTOQUE) AS item_grupoestoque,
                                            TRIM(TAB.SUBGRUPO_DE_ESTOQUE) AS item_subgrupoestoque,
                                            (TAB.GRUPO_ESTOQUE_SEQ_EXIBICAO) AS item_grpestoque_seq,
                                            TRIM(TAB.FAM_COMERCIAL) AS item_familia_comercial,
                                            TRIM(TAB.FAM_INDUSTRIAL) AS item_familia_industrial,
                                            TRIM(TAB.COD_REPRES) AS cod_repres,
                                            TRIM(TAB.ATIVO_REPRES) AS ativo_repres,
                                            (TAB.SEQUENCIA) AS seq_item,
                                            TRIM(TAB.RAZAO_REPRES) AS repres_razao,
                                            TRIM(TAB.FANTASIA_REPRES) AS repres_fantasia,
                                            TRIM(TAB.EMAIL_REPRES) AS repres_email,
                                            (TAB.QTDE) AS item_quantidade,
                                            (TAB.VLR_UNITARIO) AS item_valor_unitario,
                                            (TAB.VALOR_MERCADORIA) AS item_valor_mercadoria,
                                            (TAB.PERC_DESCONTO) AS item_percentual_desconto,
                                            SUM(TAB.VALOR_DESCTO) AS item_valor_desconto,
                                            SUM(TAB.VALOR_TOTAL) AS item_valor_total,
                                            CASE WHEN TAB.EMPRESA = 'IMPORT' THEN SUM(TAB.VALOR_TOTAL) ELSE 0 END AS import_valor_total,
                                            CASE WHEN TAB.EMPRESA = 'PERSIANAS' THEN SUM(TAB.VALOR_TOTAL) ELSE 0 END AS persianas_valor_total
                                        FROM
                                            (
                                                SELECT
                                                    CASE
                                                        WHEN COALESCE(TRIM(ITEM.APLICACAO), '') = 'X' THEN 'IMPORT'
                                                        WHEN COALESCE(TRIM(GRUPO_ESTOQUE.AUXILIAR_STRING1), '') = 'FP' THEN 'PERSIANAS'
                                                        ELSE 'DECOR'
                                                    END AS EMPRESA,
                                                    NOTA_FISCAL.DT_EMISSAO AS DATA_DE_EMISSAO,
                                                    CLIFOR.DT_CADASTRO AS DATA_CADASTRO,
                                                    NOTA_FISCAL.NRO_NFISCAL AS NRO_NOTA_FISCAL,
                                                    NOTA_FISCAL.COD_CLIFOR AS CODIGO_CLIENTE,
                                                    CLIFOR.RAZAO AS RAZAO_SOCIAL,
                                                    CLIFOR.AGENTE_REGULARANP AS AGENTE_REGULARANP,
                                                    CIDADE.DESCRICAO AS MUNICIPIO,
                                                    ESTADO.DESCRICAO AS ESTADO,
                                                    CLIFOR.COD_ESTADO AS UF,
                                                    GRUPO_CLIENTE.DESCRICAO AS GRUPO_CLIENTE,
                                                    ITEM.CODIGO AS CODIGO_ITEM,
                                                    ITEM.DESCRICAO AS DESCRICAO_ITEM,
                                                    GRUPO_ESTOQUE.DESCRICAO AS GRUPO_DE_ESTOQUE,
                                                    SUBGRUPO_ESTOQUE.DESCRICAO AS SUBGRUPO_DE_ESTOQUE,
                                                    GRUPO_ESTOQUE.SEQ_EXIBICAO AS GRUPO_ESTOQUE_SEQ_EXIBICAO,
                                                    FAMILIA_COMERCIAL.DESCRICAO AS FAM_COMERCIAL,
                                                    FAMILIA_INDUSTRIAL.DESCRICAO AS FAM_INDUSTRIAL,
                                                    REPRESENTANTE.COD_REPRES AS COD_REPRES,
                                                    REPRESENTANTE.RAZAO AS RAZAO_REPRES,
                                                    REPRESENTANTE.FANTASIA AS FANTASIA_REPRES,
                                                    REPRESENTANTE.EMAIL AS EMAIL_REPRES,
                                                    REPRESENTANTE.ATIVO AS ATIVO_REPRES,
                                                    ITEM_NOTAFISCAL.SEQUENCIA AS SEQUENCIA,
                                                    ITEM_NOTAFISCAL.QUANTIDADE AS QTDE,
                                                    ITEM_NOTAFISCAL.VALOR_UNITARIO AS VLR_UNITARIO,
                                                    (ITEM_NOTAFISCAL.QUANTIDADE * ITEM_NOTAFISCAL.VALOR_UNITARIO) AS VALOR_MERCADORIA,
                                                    ITEM_NOTAFISCAL.PERC_DESCTO AS PERC_DESCONTO,
                                                    ITEM_NOTAFISCAL.VALOR_DESCTO AS VALOR_DESCTO,
                                                    (ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE + ITEM_NOTAFISCAL.VALOR_DESPESAS ) AS VALOR_TOTAL
                                                FROM
                                                    NOTA_FISCAL
                                                    INNER JOIN ITEM_NOTAFISCAL ON (ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA AND ITEM_NOTAFISCAL.SERIE = NOTA_FISCAL.SERIE AND ITEM_NOTAFISCAL.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL)
                                                    INNER JOIN CLIFOR ON CLIFOR.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                                                    INNER JOIN REPRESENTANTE ON REPRESENTANTE.COD_REPRES = NOTA_FISCAL.COD_REPRES
                                                    INNER JOIN CIDADE ON CIDADE.COD_CIDADE = CLIFOR.COD_CIDADE
                                                    INNER JOIN ESTADO ON ESTADO.COD_ESTADO = CLIFOR.COD_ESTADO
                                                    INNER JOIN ITEM ON ITEM.COD_ITEM = ITEM_NOTAFISCAL.COD_ITEM
                                                    INNER JOIN GRUPO_ESTOQUE ON GRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE
                                                    INNER JOIN FAMILIA_COMERCIAL ON FAMILIA_COMERCIAL.COD_FMCOMERCIAL = ITEM.COD_FMCOMERCIAL
                                                    LEFT JOIN GRUPO_CLIENTE ON CLIFOR.COD_GRPCLIENTE = GRUPO_CLIENTE.COD_GRPCLIENTE
                                                    LEFT JOIN REPRES_COMISSAO ON REPRES_COMISSAO.COD_CLIFOR = CLIFOR.COD_CLIFOR
                                                    LEFT JOIN REPRESENTANTE VE ON VE.COD_REPRES = REPRES_COMISSAO.COD_REPRES
                                                    LEFT JOIN SUBGRUPO_ESTOQUE ON (SUBGRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE AND SUBGRUPO_ESTOQUE.COD_SUBGRUPOESTOQUE = ITEM.COD_SUBGRUPOESTOQUE)
                                                    LEFT JOIN FAMILIA_INDUSTRIAL ON FAMILIA_INDUSTRIAL.COD_FMINDUSTRIAL = ITEM.COD_FMINDUSTRIAL
                                                WHERE
                                                    NOTA_FISCAL.COD_EMPRESA = '101'
                                                    AND NOTA_FISCAL.SITUACAO <> 'C' 
                                                    AND NOTA_FISCAL.DT_EMISSAO >= '{$metaAberta->data_inicial}'
                                                    AND NOTA_FISCAL.DT_EMISSAO <= '{$metaAberta->data_final}'
                                                    AND NOTA_FISCAL.ENTRADA_SAIDA = 'S'
                                                    AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '008'
                                                    AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001','99')
                                                    AND ITEM_NOTAFISCAL.CFO NOT IN ('5997', '6997', '5998', '6998')
                                                UNION ALL
                                                SELECT
                                                    CASE
                                                        WHEN COALESCE(TRIM(ITEM.APLICACAO), '') = 'X' THEN 'IMPORT'
                                                        WHEN COALESCE(TRIM(GRUPO_ESTOQUE.AUXILIAR_STRING1), '') = 'FP' THEN 'PERSIANAS'
                                                        ELSE 'DECOR'
                                                    END AS EMPRESA,
                                                    NOTA_FISCAL.DT_EMISSAO AS DATA_DE_EMISSAO,
                                                    CLIFOR.DT_CADASTRO AS DATA_CADASTRO,
                                                    NOTA_FISCAL.NRO_NFISCAL AS NRO_NOTA_FISCAL,
                                                    NOTA_FISCAL.COD_CLIFOR AS CODIGO_CLIENTE,
                                                    CLIFOR.RAZAO AS RAZAO_SOCIAL,
                                                    CLIFOR.AGENTE_REGULARANP AS AGENTE_REGULARANP,
                                                    CIDADE.DESCRICAO AS MUNICIPIO,
                                                    ESTADO.DESCRICAO AS ESTADO,
                                                    CLIFOR.COD_ESTADO AS UF,
                                                    GRUPO_CLIENTE.DESCRICAO AS GRUPO_CLIENTE,
                                                    ITEM.CODIGO AS CODIGO_ITEM,
                                                    ITEM.DESCRICAO AS DESCRICAO_ITEM,
                                                    GRUPO_ESTOQUE.DESCRICAO AS GRUPO_DE_ESTOQUE,
                                                    SUBGRUPO_ESTOQUE.DESCRICAO AS SUBGRUPO_DE_ESTOQUE,
                                                    GRUPO_ESTOQUE.SEQ_EXIBICAO AS GRUPO_ESTOQUE_SEQ_EXIBICAO,
                                                    FAMILIA_COMERCIAL.DESCRICAO AS FAM_COMERCIAL,
                                                    FAMILIA_INDUSTRIAL.DESCRICAO AS FAM_INDUSTRIAL,
                                                    REPRESENTANTE.COD_REPRES AS COD_REPRES,
                                                    REPRESENTANTE.RAZAO AS RAZAO_REPRES,
                                                    REPRESENTANTE.FANTASIA AS FANTASIA_REPRES,
                                                    REPRESENTANTE.EMAIL AS EMAIL_REPRES,
                                                    REPRESENTANTE.ATIVO AS ATIVO_REPRES,
                                                    ITEM_NOTAFISCAL.SEQUENCIA AS SEQUENCIA,
                                                    ITEM_NOTAFISCAL.QUANTIDADE * -1 AS QTDE,
                                                    ITEM_NOTAFISCAL.VALOR_UNITARIO AS VLR_UNITARIO,
                                                    (ITEM_NOTAFISCAL.QUANTIDADE * ITEM_NOTAFISCAL.VALOR_UNITARIO) AS VALOR_MERCADORIA,
                                                    ITEM_NOTAFISCAL.PERC_DESCTO AS PERC_DESCONTO,
                                                    (ITEM_NOTAFISCAL.VALOR_DESCTO) AS VALOR_DESCTO,
                                                    ((ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE + ITEM_NOTAFISCAL.VALOR_DESPESAS) * -1) AS VALOR_TOTAL
                                                FROM
                                                    NOTA_FISCAL
                                                    INNER JOIN ITEM_NOTAFISCAL ON (ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA AND ITEM_NOTAFISCAL.SERIE = NOTA_FISCAL.SERIE AND ITEM_NOTAFISCAL.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL)
                                                    INNER JOIN CLIFOR ON CLIFOR.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                                                    INNER JOIN REPRESENTANTE ON REPRESENTANTE.COD_REPRES = NOTA_FISCAL.COD_REPRES
                                                    INNER JOIN CIDADE ON CIDADE.COD_CIDADE = CLIFOR.COD_CIDADE
                                                    INNER JOIN ESTADO ON ESTADO.COD_ESTADO = CLIFOR.COD_ESTADO
                                                    INNER JOIN ITEM ON ITEM.COD_ITEM = ITEM_NOTAFISCAL.COD_ITEM
                                                    INNER JOIN GRUPO_ESTOQUE ON GRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE
                                                    INNER JOIN FAMILIA_COMERCIAL ON FAMILIA_COMERCIAL.COD_FMCOMERCIAL = ITEM.COD_FMCOMERCIAL
                                                    LEFT JOIN GRUPO_CLIENTE ON CLIFOR.COD_GRPCLIENTE = GRUPO_CLIENTE.COD_GRPCLIENTE
                                                    LEFT JOIN REPRES_COMISSAO ON REPRES_COMISSAO.COD_CLIFOR = CLIFOR.COD_CLIFOR
                                                    LEFT JOIN REPRESENTANTE VE ON VE.COD_REPRES = REPRES_COMISSAO.COD_REPRES
                                                    LEFT JOIN SUBGRUPO_ESTOQUE ON (SUBGRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE AND SUBGRUPO_ESTOQUE.COD_SUBGRUPOESTOQUE = ITEM.COD_SUBGRUPOESTOQUE)
                                                    LEFT JOIN FAMILIA_INDUSTRIAL ON FAMILIA_INDUSTRIAL.COD_FMINDUSTRIAL = ITEM.COD_FMINDUSTRIAL
                                                WHERE
                                                    NOTA_FISCAL.COD_EMPRESA = '101'
                                                    AND NOTA_FISCAL.SITUACAO <> 'C'
                                                    AND NOTA_FISCAL.DT_EMISSAO >= '{$metaAberta->data_inicial}'
                                                    AND NOTA_FISCAL.DT_EMISSAO <= '{$metaAberta->data_final}'
                                                    AND NOTA_FISCAL.ENTRADA_SAIDA = 'E'
                                                    AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '012'
                                                    AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001','99')
                                                    AND ITEM_NOTAFISCAL.CFO NOT IN ('5997', '6997', '5998', '6998')
                                            ) TAB
                                        GROUP BY
                                            TAB.EMPRESA,
                                            TAB.DATA_DE_EMISSAO,
                                            TAB.DATA_CADASTRO,
                                            TAB.NRO_NOTA_FISCAL,
                                            TAB.CODIGO_CLIENTE,
                                            TAB.RAZAO_SOCIAL,
                                            TAB.AGENTE_REGULARANP,
                                            TAB.MUNICIPIO,
                                            TAB.ESTADO,
                                            TAB.UF,
                                            TAB.GRUPO_CLIENTE,
                                            TAB.CODIGO_ITEM,
                                            TAB.DESCRICAO_ITEM,
                                            TAB.GRUPO_DE_ESTOQUE,
                                            TAB.SUBGRUPO_DE_ESTOQUE,
                                            TAB.GRUPO_ESTOQUE_SEQ_EXIBICAO,
                                            TAB.FAM_COMERCIAL,
                                            TAB.FAM_INDUSTRIAL,
                                            TAB.COD_REPRES,
                                            TAB.RAZAO_REPRES,
                                            TAB.FANTASIA_REPRES,
                                            TAB.EMAIL_REPRES,
                                            TAB.ATIVO_REPRES,
                                            TAB.SEQUENCIA,
                                            TAB.QTDE,
                                            TAB.VLR_UNITARIO,
                                            TAB.VALOR_MERCADORIA,
                                            TAB.PERC_DESCONTO,
                                            TAB.VALOR_DESCTO,
                                            TAB.VALOR_TOTAL
                                        ORDER BY
                                            TAB.DATA_DE_EMISSAO,
                                            TAB.NRO_NOTA_FISCAL,
                                            TAB.SEQUENCIA
                                    ) v
                                GROUP BY
                                    v.cod_repres,
                                    v.cod_clifor,
                                    v.item_grpestoque_seq,
                                    v.empresa
                            ) AUX
                            GROUP BY
                            AUX.cod_repres
                    ) FINAL
            ");
            $objects = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            TTransaction::close();
            foreach($objects as $object){

                $repres_id = array_search($object->cod_repres, $arrayRepres) !== false 
                                ? array_search($object->cod_repres, $arrayRepres) 
                                : false;
                if($repres_id){
                    TTransaction::open(self::$dbAp);
                    $metaRepres = MetaRepres::where('meta_id','=',$metaAberta->id)->where('repres_id','=',$repres_id)->first();

                    if($metaRepres){
                        $fechamento = new MetaFechamento();
                        $fechamento->meta_id                = $metaAberta->id;
                        $fechamento->meta_repres_id         = $metaRepres->id;
                        $fechamento->repres_id              = $metaRepres->repres_id;
                        $fechamento->faturamento_total      = $object->valor_total;
                        $fechamento->faturamento_cortina    = $object->cortina_pronta;
                        $fechamento->faturamento_mostruario = $object->mostruario;
                        $fechamento->faturamento_prospeccao = $object->prospeccao;
                        $fechamento->faturamento_reativacao = $object->reativacao;
                        $fechamento->faturamento_prosp_reat = $object->prospeccao + $object->reativacao;

                        $fechamento->store();
                    }
                    TTransaction::close();
                }
            }
            

            //Registro de log de execução
            return true;
            
        } catch (Exception $e) {
            return $e->getMessage(). "<br/>".__METHOD__." Line: ".$e->getLine()."Arquivo: " . $e->getFile();
        }
    }

    public static function verificarBonusComissao(){
        try{
                $sessao = TSession::getValue('userunitid') ?? 1; 
                TTransaction::open(self::$dbAp);
                $conn = TTransaction::get();
                
                $arrayRepres = ApRepresentante::where('system_unit_id','=',$sessao)->getIndexedArray('id','cod_repres');

                $metaAberta = self::get_meta_aberta();
                TTransaction::close();
                
                TTransaction::open(self::$dbNw);
                $conn = TTransaction::get();
                $result = $conn->query("
                    
                        SELECT
                            v.cod_repres as cod_repres,
                            v.cod_clifor as cod_clifor,
                            SUM(v.import_valor_total) as import,
                            SUM(v.persianas_valor_total) as persianas
                        FROM
                            (
                                SELECT
                                    TAB.EMPRESA AS empresa,
                                    (TAB.DATA_DE_EMISSAO) AS data_emissao,
                                    (TAB.DATA_CADASTRO) AS clifor_data_cadastro,
                                    TRIM(TAB.NRO_NOTA_FISCAL) AS nro_nf,
                                    TRIM(TAB.CODIGO_CLIENTE) AS cod_clifor,
                                    TRIM(TAB.RAZAO_SOCIAL) AS clifor_razao,
                                    TRIM(TAB.AGENTE_REGULARANP) AS clifor_agente,
                                    TRIM(TAB.MUNICIPIO) AS clifor_cidade,
                                    TRIM(TAB.ESTADO) AS clifor_estado,
                                    TRIM(TAB.UF) AS clifor_uf,
                                    TRIM(TAB.GRUPO_CLIENTE) AS clifor_grp,
                                    TRIM(TAB.CODIGO_ITEM) AS cod_item,
                                    TRIM(TAB.DESCRICAO_ITEM) AS item_descricao,
                                    TRIM(TAB.GRUPO_DE_ESTOQUE) AS item_grupoestoque,
                                    TRIM(TAB.SUBGRUPO_DE_ESTOQUE) AS item_subgrupoestoque,
                                    (TAB.GRUPO_ESTOQUE_SEQ_EXIBICAO) AS item_grpestoque_seq,
                                    TRIM(TAB.FAM_COMERCIAL) AS item_familia_comercial,
                                    TRIM(TAB.FAM_INDUSTRIAL) AS item_familia_industrial,
                                    TRIM(TAB.COD_REPRES) AS cod_repres,
                                    TRIM(TAB.ATIVO_REPRES) AS ativo_repres,
                                    (TAB.SEQUENCIA) AS seq_item,
                                    TRIM(TAB.RAZAO_REPRES) AS repres_razao,
                                    TRIM(TAB.FANTASIA_REPRES) AS repres_fantasia,
                                    TRIM(TAB.EMAIL_REPRES) AS repres_email,
                                    (TAB.QTDE) AS item_quantidade,
                                    (TAB.VLR_UNITARIO) AS item_valor_unitario,
                                    (TAB.VALOR_MERCADORIA) AS item_valor_mercadoria,
                                    (TAB.PERC_DESCONTO) AS item_percentual_desconto,
                                    SUM(TAB.VALOR_DESCTO) AS item_valor_desconto,
                                    SUM(TAB.VALOR_TOTAL) AS item_valor_total,
                                    CASE WHEN TAB.EMPRESA = 'IMPORT' THEN SUM(TAB.VALOR_TOTAL) ELSE 0 END AS import_valor_total,
                                    CASE WHEN TAB.EMPRESA = 'PERSIANAS' THEN SUM(TAB.VALOR_TOTAL) ELSE 0 END AS persianas_valor_total
                                FROM
                                    (
                                        SELECT
                                            CASE
                                                WHEN COALESCE(TRIM(ITEM.APLICACAO), '') = 'X' THEN 'IMPORT'
                                                WHEN COALESCE(TRIM(GRUPO_ESTOQUE.AUXILIAR_STRING1), '') = 'FP' THEN 'PERSIANAS'
                                                ELSE 'DECOR'
                                            END AS EMPRESA,
                                            NOTA_FISCAL.DT_EMISSAO AS DATA_DE_EMISSAO,
                                            CLIFOR.DT_CADASTRO AS DATA_CADASTRO,
                                            NOTA_FISCAL.NRO_NFISCAL AS NRO_NOTA_FISCAL,
                                            NOTA_FISCAL.COD_CLIFOR AS CODIGO_CLIENTE,
                                            CLIFOR.RAZAO AS RAZAO_SOCIAL,
                                            CLIFOR.AGENTE_REGULARANP AS AGENTE_REGULARANP,
                                            CIDADE.DESCRICAO AS MUNICIPIO,
                                            ESTADO.DESCRICAO AS ESTADO,
                                            CLIFOR.COD_ESTADO AS UF,
                                            GRUPO_CLIENTE.DESCRICAO AS GRUPO_CLIENTE,
                                            ITEM.CODIGO AS CODIGO_ITEM,
                                            ITEM.DESCRICAO AS DESCRICAO_ITEM,
                                            GRUPO_ESTOQUE.DESCRICAO AS GRUPO_DE_ESTOQUE,
                                            SUBGRUPO_ESTOQUE.DESCRICAO AS SUBGRUPO_DE_ESTOQUE,
                                            GRUPO_ESTOQUE.SEQ_EXIBICAO AS GRUPO_ESTOQUE_SEQ_EXIBICAO,
                                            FAMILIA_COMERCIAL.DESCRICAO AS FAM_COMERCIAL,
                                            FAMILIA_INDUSTRIAL.DESCRICAO AS FAM_INDUSTRIAL,
                                            REPRESENTANTE.COD_REPRES AS COD_REPRES,
                                            REPRESENTANTE.RAZAO AS RAZAO_REPRES,
                                            REPRESENTANTE.FANTASIA AS FANTASIA_REPRES,
                                            REPRESENTANTE.EMAIL AS EMAIL_REPRES,
                                            REPRESENTANTE.ATIVO AS ATIVO_REPRES,
                                            ITEM_NOTAFISCAL.SEQUENCIA AS SEQUENCIA,
                                            ITEM_NOTAFISCAL.QUANTIDADE AS QTDE,
                                            ITEM_NOTAFISCAL.VALOR_UNITARIO AS VLR_UNITARIO,
                                            (ITEM_NOTAFISCAL.QUANTIDADE * ITEM_NOTAFISCAL.VALOR_UNITARIO) AS VALOR_MERCADORIA,
                                            ITEM_NOTAFISCAL.PERC_DESCTO AS PERC_DESCONTO,
                                            ITEM_NOTAFISCAL.VALOR_DESCTO AS VALOR_DESCTO,
                                            (ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE + ITEM_NOTAFISCAL.VALOR_DESPESAS) AS VALOR_TOTAL
                                        FROM
                                            NOTA_FISCAL
                                            INNER JOIN ITEM_NOTAFISCAL ON (ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA AND ITEM_NOTAFISCAL.SERIE = NOTA_FISCAL.SERIE AND ITEM_NOTAFISCAL.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL)
                                            INNER JOIN CLIFOR ON CLIFOR.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                                            INNER JOIN REPRESENTANTE ON REPRESENTANTE.COD_REPRES = NOTA_FISCAL.COD_REPRES
                                            INNER JOIN CIDADE ON CIDADE.COD_CIDADE = CLIFOR.COD_CIDADE
                                            INNER JOIN ESTADO ON ESTADO.COD_ESTADO = CLIFOR.COD_ESTADO
                                            INNER JOIN ITEM ON ITEM.COD_ITEM = ITEM_NOTAFISCAL.COD_ITEM
                                            INNER JOIN GRUPO_ESTOQUE ON GRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE
                                            INNER JOIN FAMILIA_COMERCIAL ON FAMILIA_COMERCIAL.COD_FMCOMERCIAL = ITEM.COD_FMCOMERCIAL
                                            LEFT JOIN GRUPO_CLIENTE ON CLIFOR.COD_GRPCLIENTE = GRUPO_CLIENTE.COD_GRPCLIENTE
                                            LEFT JOIN REPRES_COMISSAO ON REPRES_COMISSAO.COD_CLIFOR = CLIFOR.COD_CLIFOR
                                            LEFT JOIN REPRESENTANTE VE ON VE.COD_REPRES = REPRES_COMISSAO.COD_REPRES
                                            LEFT JOIN SUBGRUPO_ESTOQUE ON (SUBGRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE AND SUBGRUPO_ESTOQUE.COD_SUBGRUPOESTOQUE = ITEM.COD_SUBGRUPOESTOQUE)
                                            LEFT JOIN FAMILIA_INDUSTRIAL ON FAMILIA_INDUSTRIAL.COD_FMINDUSTRIAL = ITEM.COD_FMINDUSTRIAL
                                        WHERE
                                            NOTA_FISCAL.COD_EMPRESA = '101'
                                            AND NOTA_FISCAL.SITUACAO <> 'C' 
                                            AND NOTA_FISCAL.DT_EMISSAO >= '{$metaAberta->data_inicial}'
                                            AND NOTA_FISCAL.DT_EMISSAO <= '{$metaAberta->data_final}'
                                            AND NOTA_FISCAL.ENTRADA_SAIDA = 'S'
                                            AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '008'
                                            AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001','99')
                                            AND ITEM_NOTAFISCAL.CFO NOT IN ('5997', '6997', '5998', '6998')
                                        UNION ALL
                                        SELECT
                                            CASE
                                                WHEN COALESCE(TRIM(ITEM.APLICACAO), '') = 'X' THEN 'IMPORT'
                                                WHEN COALESCE(TRIM(GRUPO_ESTOQUE.AUXILIAR_STRING1), '') = 'FP' THEN 'PERSIANAS'
                                                ELSE 'DECOR'
                                            END AS EMPRESA,
                                            NOTA_FISCAL.DT_EMISSAO AS DATA_DE_EMISSAO,
                                            CLIFOR.DT_CADASTRO AS DATA_CADASTRO,
                                            NOTA_FISCAL.NRO_NFISCAL AS NRO_NOTA_FISCAL,
                                            NOTA_FISCAL.COD_CLIFOR AS CODIGO_CLIENTE,
                                            CLIFOR.RAZAO AS RAZAO_SOCIAL,
                                            CLIFOR.AGENTE_REGULARANP AS AGENTE_REGULARANP,
                                            CIDADE.DESCRICAO AS MUNICIPIO,
                                            ESTADO.DESCRICAO AS ESTADO,
                                            CLIFOR.COD_ESTADO AS UF,
                                            GRUPO_CLIENTE.DESCRICAO AS GRUPO_CLIENTE,
                                            ITEM.CODIGO AS CODIGO_ITEM,
                                            ITEM.DESCRICAO AS DESCRICAO_ITEM,
                                            GRUPO_ESTOQUE.DESCRICAO AS GRUPO_DE_ESTOQUE,
                                            SUBGRUPO_ESTOQUE.DESCRICAO AS SUBGRUPO_DE_ESTOQUE,
                                            GRUPO_ESTOQUE.SEQ_EXIBICAO AS GRUPO_ESTOQUE_SEQ_EXIBICAO,
                                            FAMILIA_COMERCIAL.DESCRICAO AS FAM_COMERCIAL,
                                            FAMILIA_INDUSTRIAL.DESCRICAO AS FAM_INDUSTRIAL,
                                            REPRESENTANTE.COD_REPRES AS COD_REPRES,
                                            REPRESENTANTE.RAZAO AS RAZAO_REPRES,
                                            REPRESENTANTE.FANTASIA AS FANTASIA_REPRES,
                                            REPRESENTANTE.EMAIL AS EMAIL_REPRES,
                                            REPRESENTANTE.ATIVO AS ATIVO_REPRES,
                                            ITEM_NOTAFISCAL.SEQUENCIA AS SEQUENCIA,
                                            ITEM_NOTAFISCAL.QUANTIDADE * -1 AS QTDE,
                                            ITEM_NOTAFISCAL.VALOR_UNITARIO AS VLR_UNITARIO,
                                            (ITEM_NOTAFISCAL.QUANTIDADE * ITEM_NOTAFISCAL.VALOR_UNITARIO) AS VALOR_MERCADORIA,
                                            ITEM_NOTAFISCAL.PERC_DESCTO AS PERC_DESCONTO,
                                            (ITEM_NOTAFISCAL.VALOR_DESCTO -1) AS VALOR_DESCTO,
                                            ((ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE + ITEM_NOTAFISCAL.VALOR_DESPESAS) * -1) AS VALOR_TOTAL
                                        FROM
                                            NOTA_FISCAL
                                            INNER JOIN ITEM_NOTAFISCAL ON (ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA AND ITEM_NOTAFISCAL.SERIE = NOTA_FISCAL.SERIE AND ITEM_NOTAFISCAL.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL)
                                            INNER JOIN CLIFOR ON CLIFOR.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                                            INNER JOIN REPRESENTANTE ON REPRESENTANTE.COD_REPRES = NOTA_FISCAL.COD_REPRES
                                            INNER JOIN CIDADE ON CIDADE.COD_CIDADE = CLIFOR.COD_CIDADE
                                            INNER JOIN ESTADO ON ESTADO.COD_ESTADO = CLIFOR.COD_ESTADO
                                            INNER JOIN ITEM ON ITEM.COD_ITEM = ITEM_NOTAFISCAL.COD_ITEM
                                            INNER JOIN GRUPO_ESTOQUE ON GRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE
                                            INNER JOIN FAMILIA_COMERCIAL ON FAMILIA_COMERCIAL.COD_FMCOMERCIAL = ITEM.COD_FMCOMERCIAL
                                            LEFT JOIN GRUPO_CLIENTE ON CLIFOR.COD_GRPCLIENTE = GRUPO_CLIENTE.COD_GRPCLIENTE
                                            LEFT JOIN REPRES_COMISSAO ON REPRES_COMISSAO.COD_CLIFOR = CLIFOR.COD_CLIFOR
                                            LEFT JOIN REPRESENTANTE VE ON VE.COD_REPRES = REPRES_COMISSAO.COD_REPRES
                                            LEFT JOIN SUBGRUPO_ESTOQUE ON (SUBGRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE AND SUBGRUPO_ESTOQUE.COD_SUBGRUPOESTOQUE = ITEM.COD_SUBGRUPOESTOQUE)
                                            LEFT JOIN FAMILIA_INDUSTRIAL ON FAMILIA_INDUSTRIAL.COD_FMINDUSTRIAL = ITEM.COD_FMINDUSTRIAL
                                        WHERE
                                            NOTA_FISCAL.COD_EMPRESA = '101'
                                            AND NOTA_FISCAL.SITUACAO <> 'C'
                                            AND NOTA_FISCAL.DT_EMISSAO >= '{$metaAberta->data_inicial}'
                                            AND NOTA_FISCAL.DT_EMISSAO <= '{$metaAberta->data_final}'
                                            AND NOTA_FISCAL.ENTRADA_SAIDA = 'E'
                                            AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '012'
                                            AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001','99')
                                            AND ITEM_NOTAFISCAL.CFO NOT IN ('5997', '6997', '5998', '6998')
                                    ) TAB
                                GROUP BY
                                    TAB.EMPRESA,
                                    TAB.DATA_DE_EMISSAO,
                                    TAB.DATA_CADASTRO,
                                    TAB.NRO_NOTA_FISCAL,
                                    TAB.CODIGO_CLIENTE,
                                    TAB.RAZAO_SOCIAL,
                                    TAB.AGENTE_REGULARANP,
                                    TAB.MUNICIPIO,
                                    TAB.ESTADO,
                                    TAB.UF,
                                    TAB.GRUPO_CLIENTE,
                                    TAB.CODIGO_ITEM,
                                    TAB.DESCRICAO_ITEM,
                                    TAB.GRUPO_DE_ESTOQUE,
                                    TAB.SUBGRUPO_DE_ESTOQUE,
                                    TAB.GRUPO_ESTOQUE_SEQ_EXIBICAO,
                                    TAB.FAM_COMERCIAL,
                                    TAB.FAM_INDUSTRIAL,
                                    TAB.COD_REPRES,
                                    TAB.RAZAO_REPRES,
                                    TAB.FANTASIA_REPRES,
                                    TAB.EMAIL_REPRES,
                                    TAB.ATIVO_REPRES,
                                    TAB.SEQUENCIA,
                                    TAB.QTDE,
                                    TAB.VLR_UNITARIO,
                                    TAB.VALOR_MERCADORIA,
                                    TAB.PERC_DESCONTO,
                                    TAB.VALOR_DESCTO,
                                    TAB.VALOR_TOTAL
                                ORDER BY
                                    TAB.DATA_DE_EMISSAO,
                                    TAB.NRO_NOTA_FISCAL,
                                    TAB.SEQUENCIA
                            ) v
                        GROUP BY
                            v.cod_repres,
                            v.cod_clifor
                ");
                $objects = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
                
                TTransaction::close();
                TTransaction::open(self::$dbAp);
                
                $qtde_atingida = array();

                foreach($objects as $object){
                    $repres = ApRepresentante::where('cod_repres','=',$object->cod_repres)->first();
                    $meta_repres = MetaRepres::where('meta_id','=',$metaAberta->id)->where('repres_id','=',$repres->id)->first();

                    if($meta_repres){
                        if(!isset($qtde_atingida[$repres->id])){
                            $qtde_atingida[$repres->id]['import'] = 0;
                            $qtde_atingida[$repres->id]['persianas'] = 0;
                        }

                        $qtde_atingida[$repres->id]['import']    += ($object->import >= $meta_repres->valor_import)       ? 1 : 0;
                        $qtde_atingida[$repres->id]['persianas'] += ($object->persianas >= $meta_repres->valor_persianas) ? 1 : 0;
                    }
                }

                $conn       = TTransaction::get();
                $result     = $conn->query("SELECT repres_id, COUNT(cod_clifor) as qtde FROM historico_cli_repres 
                                            WHERE mes = {$metaAberta->mes} AND ano = {$metaAberta->ano} 
                                            AND ativo = 'S' AND grupo_id not in (3,4,8,10,16,17)
                                            GROUP BY repres_id");
                $clientes = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
                
                $perc = array();
                foreach($clientes as $data){
                    $import    = $qtde_atingida[$data->repres_id]['import'] ?? 0;
                    $persianas = $qtde_atingida[$data->repres_id]['persianas'] ?? 0;

                    $perc[$data->repres_id] = [
                        'import' => ($import*100)/$data->qtde,
                        'persianas' => ($persianas*100)/$data->qtde
                    ];
                }        
                
                $premio = Premio::where('mes','=',$metaAberta->mes)->where('ano','=',$metaAberta->ano)->getIndexedArray('id','id');
                $regras = PremioRegra::where('premio_id','in',$premio)->load();
                
                $tipos = array();

               foreach($metaAberta->getMetaFechamentos() as $fechamento){
                $premio_cortina = 0;
                $premio_mostruario = 0;

                $metaRepres = MetaRepres::find($fechamento->meta_repres_id);

                $repres     = $metaRepres->fantasia;
                $fechamento->percentual_import    = $perc[$fechamento->repres_id]['import']    ?? 0;
                $fechamento->percentual_persianas = $perc[$fechamento->repres_id]['persianas'] ?? 0;

                foreach($regras as $regra){
                    $tipo = ($regra->get_premio())->tipo_premio_id;
                    $tipos[$fechamento->repres_id][] = $tipo;

                    // NOVO: para o rep 168, só processa o tipo 11. Ignora os demais.
                    if ((int)$fechamento->repres_id === 168 && $tipo !== 11) {
                        continue;
                    }

                    switch($tipo){
                        case 11:
                            // NOVO: comissão fixa 5% para o rep 168, senão usa a regra normal
                            if (!isset($comissao)) { // evita recalcular se houver mais de uma regra 11
                                if ((int)$fechamento->repres_id === 168) {
                                    $comissao = $fechamento->faturamento_total * 0.05;
                                } else {
                                    $comissao = $fechamento->faturamento_total * ($regra->bonus / 100);
                                }
                            }
                            break;

                        case 3: // Ranking Meta
                            if (!isset($perc_meta)){
                                if($fechamento->faturamento_total >= $metaRepres->valor_meta){
                                    $fechamento->alcancou_meta = 'S';
                                    $perc_meta = $regra->bonus;
                                }else{
                                    $perc_meta = 0;
                                    $fechamento->alcancou_meta = 'N';
                                }
                            }
                            break;

                        case 8: // Ranking Super Meta
                            if (!isset($perc_super_meta)){
                                if($fechamento->faturamento_total >= $metaRepres->valor_super_meta){
                                    $fechamento->alcancou_super_meta = 'S';
                                    $perc_super_meta = $regra->bonus;
                                }else{
                                    $perc_super_meta = 0;
                                    $fechamento->alcancou_super_meta = 'N';
                                }
                            }
                            break;

                        case 10: // Meta de IMPORT
                            if (!isset($perc_import)){
                                if($fechamento->percentual_import >= $metaRepres->perc_import){
                                    $fechamento->alcancou_import = 'S';
                                    $perc_import = $regra->bonus;
                                }else{
                                    $perc_import = 0;
                                    $fechamento->alcancou_import = 'N';
                                }
                            }
                            break;

                        case 9: // Meta de PERSIANAS
                            if (!isset($perc_persianas)){
                                if($fechamento->percentual_persianas >= $metaRepres->perc_persianas){
                                    $fechamento->alcancou_persianas = 'S';
                                    $perc_persianas = $regra->bonus;
                                }else{
                                    $perc_persianas = 0;
                                    $fechamento->alcancou_persianas = 'N';
                                }
                            }
                            break;

                        case 2: // Ranking Cortina Pronta
                            if ($premio_cortina == 0){
                                if($fechamento->faturamento_cortina >= $metaRepres->valor_cortina) {
                                    $fechamento->alcancou_cortina_pronta = 'S';
                                    $perc_cortina = $regra->bonus;
                                    $premio_cortina = $fechamento->faturamento_cortina * ($regra->bonus / 100);
                                }else{
                                    $premio_cortina = 0;
                                    $fechamento->alcancou_cortina_pronta = 'N';
                                }
                            }
                            break;

                        case 4: // Ranking Mostruários
                            if ($premio_mostruario == 0){
                                if($fechamento->faturamento_mostruario >= $metaRepres->valor_mostruario){
                                    $fechamento->alcancou_mostruario = 'S';
                                    $perc_mostruario = $regra->bonus;
                                    $premio_mostruario = $fechamento->faturamento_mostruario * ($regra->bonus / 100);
                                }else{
                                    $premio_mostruario = 0;
                                    $fechamento->alcancou_mostruario = 'N';
                                }
                            }
                            break;

                        case 5:
                            if($fechamento->alcancou_prospeccao == 'N'){
                                if($fechamento->faturamento_prospeccao >= $metaRepres->valor_prospeccao){
                                    $fechamento->alcancou_prospeccao = 'S';
                                }else{
                                    $fechamento->alcancou_prospeccao = 'N';
                                }
                            }
                            break;

                        case 6:
                            if($fechamento->alcancou_reativacao == 'N'){
                                if($fechamento->faturamento_reativacao >= $metaRepres->valor_reativacao){
                                    $fechamento->alcancou_reativacao = 'S';
                                }else{
                                    $fechamento->alcancou_reativacao = 'N';
                                }
                            }
                            break;

                        case 12:
                            if($fechamento->alcancou_prosp_reat == 'N'){
                                if($fechamento->faturamento_reativacao >= $metaRepres->valor_prosp_reat){
                                    $fechamento->alcancou_prosp_reat = 'S';
                                }else{
                                    $fechamento->alcancou_prosp_reat = 'N';
                                }
                            }
                            break;

                        default:
                            break;
                    }
                }

                // NOVO: defaults seguros quando for o 168 (ou qualquer caso sem set prévio)
                $perc_meta        = $perc_meta        ?? 0;
                $perc_super_meta  = $perc_super_meta  ?? 0;
                $perc_import      = $perc_import      ?? 0;
                $perc_persianas   = $perc_persianas   ?? 0;
                $premio_cortina   = $premio_cortina   ?? 0;
                $premio_mostruario= $premio_mostruario?? 0;
                $comissao         = $comissao         ?? 0;

                $perc_bonus    = $perc_meta + $perc_super_meta;
                $soma_perc_mini  = $perc_import + $perc_persianas;

                $fechamento->comissao          = $comissao;
                $fechamento->bonus_meta        = $comissao * ($perc_bonus / 100);
                $fechamento->bonus_mini        = $comissao * ($soma_perc_mini / 100);
                $fechamento->bonus_mini_import = $comissao * ($perc_import / 100);
                $fechamento->bonus_mini_pers   = $comissao * ($perc_persianas / 100);
                $fechamento->premio_cortina    = $premio_cortina;
                $fechamento->premio_mostruario = $premio_mostruario;
                $fechamento->premio_estrategico= $premio_cortina + $premio_mostruario;

                $fechamento->store();

                unset($perc_meta,$perc_super_meta,$perc_import,$perc_persianas);
                unset($bonus,$comissao,$perc_bonus,$premio_cortina,$premio_mostruario,$soma_perc_mini);
            }


                TTransaction::close();

                return true;
            } catch (Exception $e) {
                return $e->getMessage(). "<br/>".__METHOD__." Line: ".$e->getLine()."Arquivo: " . $e->getFile();
            }
        }
        
    public static function verificaPremioProspeccaoReativacao(){

        try {
            $sessao = TSession::getValue('userunitid') ?? 1;
            
            TTransaction::open(self::$dbAp);

            $conn = TTransaction::get();

           $arrayRepres = ApRepresentante::where('system_unit_id', '=', $sessao)
                        ->getIndexedArray('id', 'cod_repres');
             unset($arrayRepres[168]); 


            $metaAberta = self::get_meta_aberta();
            
            $premio = Premio::where('mes','=',$metaAberta->mes)
                            ->where('ano','=',$metaAberta->ano)
                            ->where('tipo_premio_id','=',12)
                            ->getIndexedArray('id','id');



            $regras = PremioRegra::where('premio_id','in',$premio)->orderby('bonus','desc')->load();


             $fechamentosPR = MetaFechamento::where('meta_id', '=', $metaAberta->id)
                            ->where('repres_id', '<>', 168) 
                            ->orderby('faturamento_prosp_reat', 'desc')
                            ->load();


            $regrasAplicadas = [];

            foreach($fechamentosPR as $fechamentoPR){

                $fechamentoPR->alcancou_prosp_reat = 'N';
                $fechamentoPR->premio_prosp_reat  = 0;

                if (!$fechamentoPR->meta_repres_id) {
                    continue;
                }

                $metaRepres = MetaRepres::find($fechamentoPR->meta_repres_id);
                if (!$metaRepres) {
                    continue;
                }


                foreach ($regras as $key => $regra) {

                    if (in_array($regra->id, $regrasAplicadas)) {
                        continue;
                    }

                    if ($fechamentoPR->faturamento_prosp_reat >= $metaRepres->valor_prosp_reat) {
                        $fechamentoPR->alcancou_prosp_reat = 'S';
                    }

                    if ($fechamentoPR->faturamento_prosp_reat >= $regra->dado_0) {
                        $fechamentoPR->premio_prosp_reat = $regra->bonus;
                        $regrasAplicadas[] = $regra->id;
                        unset($regras[$key]);
                        break;
                    } else {
                    }
                }

                $fechamentoPR->store();
            }

            TTransaction::close();
            return true;

        } catch (Exception $e) {
            return $e->getMessage() . "<br/>" . __METHOD__ . " Line: " . $e->getLine() . " Arquivo: " . $e->getFile();
        }
    }
    public static function obterST($mini_meta_id){

         try {

            $sessao = TSession::getValue('userunitid') ?? 1;

            /* ===================== 1) Buscar ST/MetaFechamento e dados ===================== */
            TTransaction::open(self::$dbAp);

            $miniMeta = MiniMeta::find($mini_meta_id);
            if (!$miniMeta) {
                throw new Exception('ST não encontrado');
            }

            $metaAberta = self::get_meta_aberta();
            if (!$metaAberta) {
                throw new Exception('Meta aberta não encontrada');
            }

            // MAP APONTANDO para Cód do Representante
            $arrayRepres = ApRepresentante::where('system_unit_id', '=', $sessao)
                ->getIndexedArray('id', 'cod_repres');

            // IMPORTANTE: Buscar TODOS os representantes que devem participar do ST
            $todosRepresentantes = ApRepresentante::where('system_unit_id', '=', $sessao)->load();

            //Itens Inseridos no ST
            $itensST = [];
            foreach ($miniMeta->getMiniMetaItems() as $item) {
                $itensST[] = trim($item->cod_item);
            }

            //Aqui se faz a busca por Tabelas de preço pertencentes a essa ST que fazparte = 'S'
            $conn = TTransaction::get();
            $rsTabs = $conn->query("
                SELECT ap.cod_tabelapreco
                FROM minimeta_tabela_preco mtp
                JOIN ap_tabela_preco ap ON ap.id = mtp.ap_tabela_preco_id
				 WHERE mtp.mini_meta_id = {$mini_meta_id}
                AND mtp.fazparte = 'S'
            ");

            //Aqui percorre-se um Array entre as tabelas de preço
            $tabs = [];
            foreach ($rsTabs as $row) {
                if (!empty($row['cod_tabelapreco'])) {
                    $tabs[] = addslashes(trim($row['cod_tabelapreco']));
                }
            }

            //Se cadastrado vazio, não aplica filtro por tabela
            $tabsFilter = '';
            if (!empty($tabs)) {
                $tabsFilter = "AND ITEM_NOTAFISCAL.COD_TABELAPRECO IN ('" . implode("','", $tabs) . "')";
            }

            //Variáveis Básicas setadas a partir de informação recebida pelo ArtigoComStForm
            $dtInicial        = $miniMeta->data_inicial;
            $dtFinal          = $miniMeta->data_final;
            $valorMinUnitario = ($miniMeta->valor_unitario_min ?? 0);
            $minimo           = ($miniMeta->min ?? 0);
            $premioUni        = ($miniMeta->qtde ?? 0);

            TTransaction::close();

            /* ===================== 2) Buscar vendas (NO NW) ===================== */
            TTransaction::open(self::$dbNw);
            $conn = TTransaction::get();

            $sql = "SELECT
                    y.cod_repres,
                    y.cod_item,
                    SUM(y.quantidade_filtrada) AS quantidade
                    FROM (
                    SELECT
                        v.cod_repres,
                        v.cod_item,
                        v.cod_tabela_preco,
                        CASE
                        WHEN v.item_quantidade <> 0
                            AND (v.item_valor_total / NULLIF(v.item_quantidade, 0)) >= {$valorMinUnitario}
                            THEN v.item_quantidade
                        ELSE 0
                        END AS quantidade_filtrada
                    FROM (
                        SELECT
                        TRIM(TAB.COD_REPRES)        AS cod_repres,
                        TRIM(TAB.CODIGO_ITEM)       AS cod_item,
                        TRIM(TAB.COD_TABELAPRECO)   AS cod_tabela_preco,
                        TAB.QTDE                    AS item_quantidade,
                        TAB.VALOR_TOTAL             AS item_valor_total
                        FROM (
                    
                        SELECT
                            CASE
                            WHEN COALESCE(TRIM(ITEM.APLICACAO), '') = 'X' THEN 'IMPORT'
                            WHEN COALESCE(TRIM(GRUPO_ESTOQUE.AUXILIAR_STRING1), '') = 'FP' THEN 'PERSIANAS'
                            ELSE 'DECOR'
                            END                                 AS EMPRESA,
                            NOTA_FISCAL.DT_EMISSAO              AS DATA_DE_EMISSAO,
                            CLIFOR.DT_CADASTRO                  AS DATA_CADASTRO,
                            NOTA_FISCAL.NRO_NFISCAL             AS NRO_NOTA_FISCAL,
                            NOTA_FISCAL.COD_CLIFOR              AS CODIGO_CLIENTE,
                            CLIFOR.RAZAO                        AS RAZAO_SOCIAL,
                            CLIFOR.AGENTE_REGULARANP            AS AGENTE_REGULARANP,
                            CIDADE.DESCRICAO                    AS MUNICIPIO,
                            ESTADO.DESCRICAO                    AS ESTADO,
                            CLIFOR.COD_ESTADO                   AS UF,
                            GRUPO_CLIENTE.DESCRICAO             AS GRUPO_CLIENTE,
                            ITEM.CODIGO                         AS CODIGO_ITEM,
                            ITEM.DESCRICAO                      AS DESCRICAO_ITEM,
                            GRUPO_ESTOQUE.DESCRICAO             AS GRUPO_DE_ESTOQUE,
                            SUBGRUPO_ESTOQUE.DESCRICAO          AS SUBGRUPO_DE_ESTOQUE,
                            GRUPO_ESTOQUE.SEQ_EXIBICAO          AS GRUPO_ESTOQUE_SEQ_EXIBICAO,
                            FAMILIA_COMERCIAL.DESCRICAO         AS FAM_COMERCIAL,
                            FAMILIA_INDUSTRIAL.DESCRICAO        AS FAM_INDUSTRIAL,
                            REPRESENTANTE.COD_REPRES            AS COD_REPRES,
                            REPRESENTANTE.RAZAO                 AS RAZAO_REPRES,
                            REPRESENTANTE.FANTASIA              AS FANTASIA_REPRES,
                            REPRESENTANTE.EMAIL                 AS EMAIL_REPRES,
                            REPRESENTANTE.ATIVO                 AS ATIVO_REPRES,
                            ITEM_NOTAFISCAL.SEQUENCIA           AS SEQUENCIA,
                            ITEM_NOTAFISCAL.QUANTIDADE          AS QTDE,
                            ITEM_NOTAFISCAL.VALOR_UNITARIO      AS VLR_UNITARIO,
                            (ITEM_NOTAFISCAL.QUANTIDADE * ITEM_NOTAFISCAL.VALOR_UNITARIO)              AS VALOR_MERCADORIA,
                            ITEM_NOTAFISCAL.PERC_DESCTO         AS PERC_DESCONTO,
                            ITEM_NOTAFISCAL.VALOR_DESCTO        AS VALOR_DESCTO,
                            (ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE + ITEM_NOTAFISCAL.VALOR_DESPESAS)                AS VALOR_TOTAL,
                            ITEM_NOTAFISCAL.COD_TABELAPRECO     AS COD_TABELAPRECO
                        FROM NOTA_FISCAL
                        INNER JOIN ITEM_NOTAFISCAL ON (
                            ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA
                            AND ITEM_NOTAFISCAL.SERIE = NOTA_FISCAL.SERIE
                            AND ITEM_NOTAFISCAL.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                            AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL
                        )
                        INNER JOIN CLIFOR             ON CLIFOR.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                        INNER JOIN REPRESENTANTE      ON REPRESENTANTE.COD_REPRES = NOTA_FISCAL.COD_REPRES
                        INNER JOIN CIDADE             ON CIDADE.COD_CIDADE = CLIFOR.COD_CIDADE
                        INNER JOIN ESTADO             ON ESTADO.COD_ESTADO = CLIFOR.COD_ESTADO
                        INNER JOIN ITEM               ON ITEM.COD_ITEM = ITEM_NOTAFISCAL.COD_ITEM
                        INNER JOIN GRUPO_ESTOQUE      ON GRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE
                        INNER JOIN FAMILIA_COMERCIAL  ON FAMILIA_COMERCIAL.COD_FMCOMERCIAL = ITEM.COD_FMCOMERCIAL
                        LEFT  JOIN GRUPO_CLIENTE      ON CLIFOR.COD_GRPCLIENTE = GRUPO_CLIENTE.COD_GRPCLIENTE
                        LEFT  JOIN REPRES_COMISSAO    ON REPRES_COMISSAO.COD_CLIFOR = CLIFOR.COD_CLIFOR
                        LEFT  JOIN REPRESENTANTE VE   ON VE.COD_REPRES = REPRES_COMISSAO.COD_REPRES
                        LEFT  JOIN SUBGRUPO_ESTOQUE   ON (SUBGRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE
                                                            AND SUBGRUPO_ESTOQUE.COD_SUBGRUPOESTOQUE = ITEM.COD_SUBGRUPOESTOQUE)
                        LEFT  JOIN FAMILIA_INDUSTRIAL ON FAMILIA_INDUSTRIAL.COD_FMINDUSTRIAL = ITEM.COD_FMINDUSTRIAL
                        WHERE
                            NOTA_FISCAL.COD_EMPRESA = '101'
                            AND NOTA_FISCAL.SITUACAO <> 'C'
                            AND NOTA_FISCAL.DT_EMISSAO >=  '{$dtInicial}'
                            AND NOTA_FISCAL.DT_EMISSAO <=  '{$dtFinal}'
                            AND NOTA_FISCAL.ENTRADA_SAIDA = 'S'
                            AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '008'
                            AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001','99')
                            AND ITEM_NOTAFISCAL.CFO NOT IN ('5997', '6997', '5998', '6998')
                        {$tabsFilter}

                        UNION ALL

                        SELECT
                            CASE
                            WHEN COALESCE(TRIM(ITEM.APLICACAO), '') = 'X' THEN 'IMPORT'
                            WHEN COALESCE(TRIM(GRUPO_ESTOQUE.AUXILIAR_STRING1), '') = 'FP' THEN 'PERSIANAS'
                            ELSE 'DECOR'
                            END                                 AS EMPRESA,
                            NOTA_FISCAL.DT_EMISSAO              AS DATA_DE_EMISSAO,
                            CLIFOR.DT_CADASTRO                  AS DATA_CADASTRO,
                            NOTA_FISCAL.NRO_NFISCAL             AS NRO_NOTA_FISCAL,
                            NOTA_FISCAL.COD_CLIFOR              AS CODIGO_CLIENTE,
                            CLIFOR.RAZAO                        AS RAZAO_SOCIAL,
                            CLIFOR.AGENTE_REGULARANP            AS AGENTE_REGULARANP,
                            CIDADE.DESCRICAO                    AS MUNICIPIO,
                            ESTADO.DESCRICAO                    AS ESTADO,
                            CLIFOR.COD_ESTADO                   AS UF,
                            GRUPO_CLIENTE.DESCRICAO             AS GRUPO_CLIENTE,
                            ITEM.CODIGO                         AS CODIGO_ITEM,
                            ITEM.DESCRICAO                      AS DESCRICAO_ITEM,
                            GRUPO_ESTOQUE.DESCRICAO             AS GRUPO_DE_ESTOQUE,
                            SUBGRUPO_ESTOQUE.DESCRICAO          AS SUBGRUPO_DE_ESTOQUE,
                            GRUPO_ESTOQUE.SEQ_EXIBICAO          AS GRUPO_ESTOQUE_SEQ_EXIBICAO,
                            FAMILIA_COMERCIAL.DESCRICAO         AS FAM_COMERCIAL,
                            FAMILIA_INDUSTRIAL.DESCRICAO        AS FAM_INDUSTRIAL,
                            REPRESENTANTE.COD_REPRES            AS COD_REPRES,
                            REPRESENTANTE.RAZAO                 AS RAZAO_REPRES,
                            REPRESENTANTE.FANTASIA              AS FANTASIA_REPRES,
                            REPRESENTANTE.EMAIL                 AS EMAIL_REPRES,
                            REPRESENTANTE.ATIVO                 AS ATIVO_REPRES,
                            ITEM_NOTAFISCAL.SEQUENCIA           AS SEQUENCIA,
                            (ITEM_NOTAFISCAL.QUANTIDADE * -1)   AS QTDE,
                            ITEM_NOTAFISCAL.VALOR_UNITARIO      AS VLR_UNITARIO,
                            (ITEM_NOTAFISCAL.QUANTIDADE * ITEM_NOTAFISCAL.VALOR_UNITARIO) AS VALOR_MERCADORIA,
                            ITEM_NOTAFISCAL.PERC_DESCTO         AS PERC_DESCONTO,
                            (ITEM_NOTAFISCAL.VALOR_DESCTO)      AS VALOR_DESCTO,
                            ((ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE + ITEM_NOTAFISCAL.VALOR_DESPESAS) * -1) AS VALOR_TOTAL,
                            ITEM_NOTAFISCAL.COD_TABELAPRECO     AS COD_TABELAPRECO
                        FROM NOTA_FISCAL
                        INNER JOIN ITEM_NOTAFISCAL ON (
                            ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA
                            AND ITEM_NOTAFISCAL.SERIE = NOTA_FISCAL.SERIE
                            AND ITEM_NOTAFISCAL.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                            AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL
                        )
                        INNER JOIN CLIFOR             ON CLIFOR.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                        INNER JOIN REPRESENTANTE      ON REPRESENTANTE.COD_REPRES = NOTA_FISCAL.COD_REPRES
                        INNER JOIN CIDADE             ON CIDADE.COD_CIDADE = CLIFOR.COD_CIDADE
                        INNER JOIN ESTADO             ON ESTADO.COD_ESTADO = CLIFOR.COD_ESTADO
                        INNER JOIN ITEM               ON ITEM.COD_ITEM = ITEM_NOTAFISCAL.COD_ITEM
                        INNER JOIN GRUPO_ESTOQUE      ON GRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE
                        INNER JOIN FAMILIA_COMERCIAL  ON FAMILIA_COMERCIAL.COD_FMCOMERCIAL = ITEM.COD_FMCOMERCIAL
                        LEFT  JOIN GRUPO_CLIENTE      ON CLIFOR.COD_GRPCLIENTE = GRUPO_CLIENTE.COD_GRPCLIENTE
                        LEFT  JOIN REPRES_COMISSAO    ON REPRES_COMISSAO.COD_CLIFOR = CLIFOR.COD_CLIFOR
                        LEFT  JOIN REPRESENTANTE VE   ON VE.COD_REPRES = REPRES_COMISSAO.COD_REPRES
                        LEFT  JOIN SUBGRUPO_ESTOQUE   ON (SUBGRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE
                                                            AND SUBGRUPO_ESTOQUE.COD_SUBGRUPOESTOQUE = ITEM.COD_SUBGRUPOESTOQUE)
                        LEFT  JOIN FAMILIA_INDUSTRIAL ON FAMILIA_INDUSTRIAL.COD_FMINDUSTRIAL = ITEM.COD_FMINDUSTRIAL
                        WHERE
                            NOTA_FISCAL.COD_EMPRESA = '101'
                            AND NOTA_FISCAL.SITUACAO <> 'C'
                            AND NOTA_FISCAL.DT_EMISSAO >=  '{$dtInicial}'
                            AND NOTA_FISCAL.DT_EMISSAO <=  '{$dtFinal}'
                            AND NOTA_FISCAL.ENTRADA_SAIDA = 'E'
                            AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '012'
                            AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001','99')
                            AND ITEM_NOTAFISCAL.CFO NOT IN ('5997', '6997', '5998', '6998')
                        ) TAB
                    ) v
                ) y
                GROUP BY y.cod_repres, 
                y.cod_item 
                ";

           
            
            $vendas = $conn->query($sql)->fetchAll(PDO::FETCH_CLASS, "stdClass");


            TTransaction::close();

            /* ===================== 3) Agrupar VENDAS por representante ===================== */
            $vendasPorRepres = [];
            foreach ($vendas as $venda) {
                $repres = trim($venda->cod_repres);
                if (!isset($vendasPorRepres[$repres])) {
                    $vendasPorRepres[$repres] = [];
                }
                $vendasPorRepres[$repres][] = $venda;
            }

            /* ===================== 4) Processar TODOS os representantes (DBAP) ===================== */
            TTransaction::open(self::$dbAp);

          
            $conn = TTransaction::get();
            
            // Caso precise fechar novamente, não precisa dar DELETE from manualmente no banco, da pra usar isso

            $totalProcessados = 0;
            $totalComVendas = 0;
            $totalSemVendas = 0;

            // Processar TODOS os representantes, mesmo caso o representante não tenha vendido
            // No Foreach $vendas as $venda foi feito essa validação do que o mesmo vendeu
            
                foreach ($todosRepresentantes as $representante) {
                    $repres_id = $representante->id;
                    $cod_repres = $representante->cod_repres;
                    
                    $totalProcessados++;
                    
                    // Buscar as vendas deste representante, batendo com o cod_item recebido pelo cadastro de ITEM e cruzando com o da NW
                    $listaVendas = isset($vendasPorRepres[$cod_repres]) ? $vendasPorRepres[$cod_repres] : [];
                    
                    if (empty($listaVendas)) {
                        $totalSemVendas++;
                    } else {
                        $totalComVendas++;
                    }

                   $totalVendida = 0.0;
                    $premioTotal  = 0.0;
                    $alcancou     = 'N';

                    if ($miniMeta->mini_meta_tipo_id == 3) {
                        // Tipo 3 = premiação por item
                        $quantidadePorItem = [];

                        foreach ($listaVendas as $venda) {
                            $codItem = trim($venda->cod_item);

                            if (in_array($codItem, $itensST)) {
                                if (!isset($quantidadePorItem[$codItem])) {
                                    $quantidadePorItem[$codItem] = 0;
                                }

                                $quantidadePorItem[$codItem] += floatval($venda->quantidade);
                            }
                        }

                        foreach ($quantidadePorItem as $codItem => $quantidadeItem) {
                            // só começa a premiar o item se ele atingir o mínimo
                            if ($quantidadeItem >= $minimo) {
                                $totalVendida += $quantidadeItem;
                                $premioTotal += ($quantidadeItem * $premioUni);
                                $alcancou = 'S';
                            }
                        }
                    } else {
                        foreach ($listaVendas as $venda) {
                            if (in_array(trim($venda->cod_item), $itensST)) {
                                $totalVendida += floatval($venda->quantidade);
                            }
                        }

                        if ($totalVendida >= $minimo) {
                            $premioTotal = $totalVendida * $premioUni;
                            $alcancou = 'S';
                        }
                    }
                    //verifica se já existe fechamento nesse id do ST
                    $fechamentoExistente = MiniMetaFechamento::where('mini_meta_id', '=', $mini_meta_id)
                                                             ->where('ap_representante_id', '=', $repres_id)
                                                             ->first();

                    if ($fechamentoExistente) {
                        // Se tem atualiza o existente
                        $fechamento = $fechamentoExistente;

                        } else {
                        // Se não tem cria um novo
                        $fechamento = new MiniMetaFechamento;
                        $fechamento->mini_meta_id = $mini_meta_id;
                        $fechamento->ap_representante_id = $repres_id;
                        }

                        $fechamento->premio_st = $premioTotal;
                        $fechamento->alcancou_st = $alcancou;
                        $fechamento->quantidade_st = $totalVendida;
                        $fechamento->store();


                        // upsert MetaFechamento (meta aberta + representante)
                        $fech = MetaFechamento::where('meta_id', '=', $metaAberta->id)
                                                ->where('repres_id', '=', $repres_id)
                                                ->first();
                                            
                        if (!$fech) {
                            continue;
                        }

                        $fech->st = $fech->st + $premioTotal;
                        $fech->store();
                    }

                TTransaction::close();

        } catch (Exception $e) {
            return $e->getMessage(). "<br/>".__METHOD__." Line: ".$e->getLine()."Arquivo: " . $e->getFile();
        }
            
    }

    public static function fechamentoAutomaticoCrontab(){
    try {
        
        $sessao = TSession::getValue('userunitid') ?? 1;
        $erros  = [];

        /*
         * PASSO 1 - Obter meta aberta e validar se ela já venceu
         */
        $metaAberta = self::get_meta_aberta();

        if (!$metaAberta) {
            LogCrontab::registrarLog(
                "Fechamento automático do mês",
                __METHOD__,
                0,
                "Nenhuma meta aberta encontrada para a unidade {$sessao}. Nada a processar.",
                "Arquivo: " . __CLASS__ . ".<br/>Linha: " . __LINE__ . ".",
                $sessao
            );
            return;
        }

        $agora      = new DateTime();
        $data_final = new DateTime($metaAberta->data_final);

        // Só realiza os fechamentos SE a data atual for MAIOR que a data final da meta
        if ($agora <= $data_final) {
            LogCrontab::registrarLog(
                "Fechamento automático do mês",
                __METHOD__,
                0,
                "Meta {$metaAberta->id} ainda em andamento (data_final {$metaAberta->data_final}). Fechamento automático não executado.",
                "Arquivo: " . __CLASS__ . ".<br/>Linha: " . __LINE__ . ".",
                $sessao
            );
            return;
        }

        $metaId = (int) $metaAberta->id;
        $mes    = (int) $metaAberta->mes;
        $ano    = (int) $metaAberta->ano;

        /*
         * PASSO 2 - Fechamento padrão do mês
         * (obterValorVendido, verificarBonusComissao, verificaPremioProspeccaoReativacao)
         * Agora só roda porque já garantimos que a meta venceu.
         */
        self::fechar();

        /*
         * PASSO 3 - Processar todos os ST (MiniMeta) abertos do mesmo mês/ano da Meta
         *   - status = 1
         *   - mes/ano da MiniMeta = mes/ano da Meta
         */
        TTransaction::open(self::$dbAp);
        $miniMetasAbertas = MiniMeta::where('mes', '=', $mes)
                                    ->where('ano', '=', $ano)
                                    ->where('status', '=', 1)
                                    ->load();
        TTransaction::close();

        if ($miniMetasAbertas) {
            foreach ($miniMetasAbertas as $miniMeta) {

                // Chama o obterST para esse ST específico
                $retSt = self::obterST($miniMeta->id);

                if (is_string($retSt) && trim($retSt) !== '') {
                    $erros[] = "Erro ao processar ST (MiniMeta {$miniMeta->id}): {$retSt}";
                    continue;
                }

                // se chegou aqui, considera ST processado com sucesso e fecha o ST (status = 2)
                TTransaction::open(self::$dbAp);
                $miniFech = MiniMeta::find($miniMeta->id);
                if ($miniFech) {
                    $miniFech->status = 2;
                    $miniFech->store();
                }
                TTransaction::close();
            }
        }

        /*
         * PASSO 4 - Processar MetaImport do mesmo mês/ano da Meta
         *   - status = 1
         *   - mes/ano da MetaImport = mes/ano da Meta
         */
        TTransaction::open(self::$dbAp);
        $metasImportAbertas = MetaImport::where('mes', '=', $mes)
                                        ->where('ano', '=', $ano)
                                        ->where('status', '=', 1)
                                        ->load();
        TTransaction::close();

        if ($metasImportAbertas) {
            foreach ($metasImportAbertas as $mi) {

                $retImport = MetaImportService::obterMetaImport($mi->id);

                if ($retImport !== true) {
                    $erros[] = "Erro ao processar MetaImport {$mi->id}: {$retImport}";
                    continue;
                }

                // Marca a MetaImport como fechada (status = 2)
                TTransaction::open(self::$dbAp);
                $miFech = MetaImport::find($mi->id);
                if ($miFech) {
                    $miFech->status = 2;
                    $miFech->store();
                }
                TTransaction::close();
            }
        }

        /*
         * PASSO 5 - Se houve qualquer erro em ST ou MetaImport, loga e NÃO fecha a meta
         */
        if (!empty($erros)) {
            $msgErros = implode(' | ', $erros);

            LogCrontab::registrarLog(
                "Fechamento automático do mês",
                __METHOD__,
                1,
                $msgErros,
                "Arquivo: " . __CLASS__ . ".<br/>Linha: " . __LINE__ . ".",
                $sessao
            );

            // Não muda o status da meta, para você poder tratar depois
                return;
            }

            /*
            * PASSO 6 - Se tudo passou sem erro:
            *   - Marca a Meta atual como status = 2 (fechada)
            */
            TTransaction::open(self::$dbAp);
            $meta = Meta::find($metaId);
            if ($meta) {
                $meta->status = 2;
                $meta->store();
            }
            TTransaction::close();

            LogCrontab::registrarLog(
                "Fechamento automático do mês",
                __METHOD__,
                0,
                "Fechamento completo da meta {$metaId} executado com sucesso (Meta, STs e MetaImport).",
                "Arquivo: " . __CLASS__ . ".<br/>Linha: " . __LINE__ . ".",
                $sessao
            );
        }
        catch (Exception $e) {
            LogCrontab::registrarLog(
                "Fechamento automático do mês",
                __METHOD__,
                1,
                $e->getMessage(),
                "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>"
            );
        }
    }
    
    
    
/*
    public static function obterCompradoresSite(){
        try{
            $metaAberta = self::get_meta_aberta();
            $quantidades = array();
            $fechamentos = array();

            TTransaction::open(self::$dbAp);
            $objects = MetaFechamento::where('meta_id','=',$metaAberta->id)->load();
            foreach($objects as $object){
                $codigo = ($object->get_repres())->cod_repres;
                $fechamentos[$codigo] = $object->id;
            }
            TTransaction::close();
            
            TTransaction::open(self::$dbNw);
            $conn = TTransaction::get();
            $result = $conn->query("
            SELECT
                AUX.cod_repres as cod_repres,
                COUNT(AUX.cod_clifor) as qtde_clifor
            FROM(
                SELECT
                    DISTINCT
                    v.cod_repres as cod_repres,
                    v.cod_clifor as cod_clifor
                FROM
                    (
                        SELECT
                            TAB.EMPRESA AS empresa,
                            (TAB.DATA_DE_EMISSAO) AS data_emissao,
                            (TAB.DATA_CADASTRO) AS clifor_data_cadastro,
                            TRIM(TAB.NRO_NOTA_FISCAL) AS nro_nf,
                            TRIM(TAB.CODIGO_CLIENTE) AS cod_clifor,
                            TRIM(TAB.RAZAO_SOCIAL) AS clifor_razao,
                            TRIM(TAB.AGENTE_REGULARANP) AS clifor_agente,
                            TRIM(TAB.MUNICIPIO) AS clifor_cidade,
                            TRIM(TAB.ESTADO) AS clifor_estado,
                            TRIM(TAB.UF) AS clifor_uf,
                            TRIM(TAB.GRUPO_CLIENTE) AS clifor_grp,
                            TRIM(TAB.CODIGO_ITEM) AS cod_item,
                            TRIM(TAB.DESCRICAO_ITEM) AS item_descricao,
                            TRIM(TAB.GRUPO_DE_ESTOQUE) AS item_grupoestoque,
                            TRIM(TAB.SUBGRUPO_DE_ESTOQUE) AS item_subgrupoestoque,
                            (TAB.GRUPO_ESTOQUE_SEQ_EXIBICAO) AS item_grpestoque_seq,
                            TRIM(TAB.FAM_COMERCIAL) AS item_familia_comercial,
                            TRIM(TAB.FAM_INDUSTRIAL) AS item_familia_industrial,
                            TRIM(TAB.COD_REPRES) AS cod_repres,
                            TRIM(TAB.ATIVO_REPRES) AS ativo_repres,
                            (TAB.SEQUENCIA) AS seq_item,
                            TRIM(TAB.RAZAO_REPRES) AS repres_razao,
                            TRIM(TAB.FANTASIA_REPRES) AS repres_fantasia,
                            TRIM(TAB.EMAIL_REPRES) AS repres_email,
                            (TAB.QTDE) AS item_quantidade,
                            (TAB.VLR_UNITARIO) AS item_valor_unitario,
                            (TAB.VALOR_MERCADORIA) AS item_valor_mercadoria,
                            (TAB.PERC_DESCONTO) AS item_percentual_desconto,
                            SUM(TAB.VALOR_DESCTO) AS item_valor_desconto,
                            SUM(TAB.VALOR_TOTAL) AS item_valor_total,
                            CASE WHEN TAB.EMPRESA = 'IMPORT' THEN SUM(TAB.VALOR_TOTAL) ELSE 0 END AS import_valor_total,
                            CASE WHEN TAB.EMPRESA = 'PERSIANAS' THEN SUM(TAB.VALOR_TOTAL) ELSE 0 END AS persianas_valor_total
                        FROM
                            (
                                SELECT
                                    CASE
                                        WHEN COALESCE(TRIM(ITEM.APLICACAO), '') = 'X' THEN 'IMPORT'
                                        WHEN COALESCE(TRIM(GRUPO_ESTOQUE.AUXILIAR_STRING1), '') = 'FP' THEN 'PERSIANAS'
                                        ELSE 'DECOR'
                                    END AS EMPRESA,
                                    NOTA_FISCAL.DT_EMISSAO AS DATA_DE_EMISSAO,
                                    CLIFOR.DT_CADASTRO AS DATA_CADASTRO,
                                    NOTA_FISCAL.NRO_NFISCAL AS NRO_NOTA_FISCAL,
                                    NOTA_FISCAL.COD_CLIFOR AS CODIGO_CLIENTE,
                                    CLIFOR.RAZAO AS RAZAO_SOCIAL,
                                    CLIFOR.AGENTE_REGULARANP AS AGENTE_REGULARANP,
                                    CIDADE.DESCRICAO AS MUNICIPIO,
                                    ESTADO.DESCRICAO AS ESTADO,
                                    CLIFOR.COD_ESTADO AS UF,
                                    GRUPO_CLIENTE.DESCRICAO AS GRUPO_CLIENTE,
                                    ITEM.CODIGO AS CODIGO_ITEM,
                                    ITEM.DESCRICAO AS DESCRICAO_ITEM,
                                    GRUPO_ESTOQUE.DESCRICAO AS GRUPO_DE_ESTOQUE,
                                    SUBGRUPO_ESTOQUE.DESCRICAO AS SUBGRUPO_DE_ESTOQUE,
                                    GRUPO_ESTOQUE.SEQ_EXIBICAO AS GRUPO_ESTOQUE_SEQ_EXIBICAO,
                                    FAMILIA_COMERCIAL.DESCRICAO AS FAM_COMERCIAL,
                                    FAMILIA_INDUSTRIAL.DESCRICAO AS FAM_INDUSTRIAL,
                                    REPRESENTANTE.COD_REPRES AS COD_REPRES,
                                    REPRESENTANTE.RAZAO AS RAZAO_REPRES,
                                    REPRESENTANTE.FANTASIA AS FANTASIA_REPRES,
                                    REPRESENTANTE.EMAIL AS EMAIL_REPRES,
                                    REPRESENTANTE.ATIVO AS ATIVO_REPRES,
                                    ITEM_NOTAFISCAL.SEQUENCIA AS SEQUENCIA,
                                    ITEM_NOTAFISCAL.QUANTIDADE AS QTDE,
                                    ITEM_NOTAFISCAL.VALOR_UNITARIO AS VLR_UNITARIO,
                                    (ITEM_NOTAFISCAL.QUANTIDADE * ITEM_NOTAFISCAL.VALOR_UNITARIO) AS VALOR_MERCADORIA,
                                    ITEM_NOTAFISCAL.PERC_DESCTO AS PERC_DESCONTO,
                                    ITEM_NOTAFISCAL.VALOR_DESCTO AS VALOR_DESCTO,
                                    (ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE) AS VALOR_TOTAL
                                FROM
                                    NOTA_FISCAL
                                    INNER JOIN ITEM_NOTAFISCAL ON (ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA AND ITEM_NOTAFISCAL.SERIE = NOTA_FISCAL.SERIE AND ITEM_NOTAFISCAL.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL)
                                    INNER JOIN CLIFOR ON CLIFOR.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                                    INNER JOIN REPRESENTANTE ON REPRESENTANTE.COD_REPRES = NOTA_FISCAL.COD_REPRES
                                    INNER JOIN CIDADE ON CIDADE.COD_CIDADE = CLIFOR.COD_CIDADE
                                    INNER JOIN ESTADO ON ESTADO.COD_ESTADO = CLIFOR.COD_ESTADO
                                    INNER JOIN ITEM ON ITEM.COD_ITEM = ITEM_NOTAFISCAL.COD_ITEM
                                    INNER JOIN GRUPO_ESTOQUE ON GRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE
                                    INNER JOIN FAMILIA_COMERCIAL ON FAMILIA_COMERCIAL.COD_FMCOMERCIAL = ITEM.COD_FMCOMERCIAL
                                    LEFT JOIN GRUPO_CLIENTE ON CLIFOR.COD_GRPCLIENTE = GRUPO_CLIENTE.COD_GRPCLIENTE
                                    LEFT JOIN REPRES_COMISSAO ON REPRES_COMISSAO.COD_CLIFOR = CLIFOR.COD_CLIFOR
                                    LEFT JOIN REPRESENTANTE VE ON VE.COD_REPRES = REPRES_COMISSAO.COD_REPRES
                                    LEFT JOIN SUBGRUPO_ESTOQUE ON (SUBGRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE AND SUBGRUPO_ESTOQUE.COD_SUBGRUPOESTOQUE = ITEM.COD_SUBGRUPOESTOQUE)
                                    LEFT JOIN FAMILIA_INDUSTRIAL ON FAMILIA_INDUSTRIAL.COD_FMINDUSTRIAL = ITEM.COD_FMINDUSTRIAL
                                WHERE
                                    NOTA_FISCAL.COD_EMPRESA = '101'
                                    AND NOTA_FISCAL.SITUACAO <> 'C' 
                                    AND NOTA_FISCAL.DT_EMISSAO >= '2025-06-01'
                                    AND NOTA_FISCAL.DT_EMISSAO <= '2025-06-30'
                                    AND NOTA_FISCAL.ENTRADA_SAIDA = 'S'
                                    AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '008'
                                    AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001','99')
                                UNION ALL
                                SELECT
                                    CASE
                                        WHEN COALESCE(TRIM(ITEM.APLICACAO), '') = 'X' THEN 'IMPORT'
                                        WHEN COALESCE(TRIM(GRUPO_ESTOQUE.AUXILIAR_STRING1), '') = 'FP' THEN 'PERSIANAS'
                                        ELSE 'DECOR'
                                    END AS EMPRESA,
                                    NOTA_FISCAL.DT_EMISSAO AS DATA_DE_EMISSAO,
                                    CLIFOR.DT_CADASTRO AS DATA_CADASTRO,
                                    NOTA_FISCAL.NRO_NFISCAL AS NRO_NOTA_FISCAL,
                                    NOTA_FISCAL.COD_CLIFOR AS CODIGO_CLIENTE,
                                    CLIFOR.RAZAO AS RAZAO_SOCIAL,
                                    CLIFOR.AGENTE_REGULARANP AS AGENTE_REGULARANP,
                                    CIDADE.DESCRICAO AS MUNICIPIO,
                                    ESTADO.DESCRICAO AS ESTADO,
                                    CLIFOR.COD_ESTADO AS UF,
                                    GRUPO_CLIENTE.DESCRICAO AS GRUPO_CLIENTE,
                                    ITEM.CODIGO AS CODIGO_ITEM,
                                    ITEM.DESCRICAO AS DESCRICAO_ITEM,
                                    GRUPO_ESTOQUE.DESCRICAO AS GRUPO_DE_ESTOQUE,
                                    SUBGRUPO_ESTOQUE.DESCRICAO AS SUBGRUPO_DE_ESTOQUE,
                                    GRUPO_ESTOQUE.SEQ_EXIBICAO AS GRUPO_ESTOQUE_SEQ_EXIBICAO,
                                    FAMILIA_COMERCIAL.DESCRICAO AS FAM_COMERCIAL,
                                    FAMILIA_INDUSTRIAL.DESCRICAO AS FAM_INDUSTRIAL,
                                    REPRESENTANTE.COD_REPRES AS COD_REPRES,
                                    REPRESENTANTE.RAZAO AS RAZAO_REPRES,
                                    REPRESENTANTE.FANTASIA AS FANTASIA_REPRES,
                                    REPRESENTANTE.EMAIL AS EMAIL_REPRES,
                                    REPRESENTANTE.ATIVO AS ATIVO_REPRES,
                                    ITEM_NOTAFISCAL.SEQUENCIA AS SEQUENCIA,
                                    ITEM_NOTAFISCAL.QUANTIDADE * -1 AS QTDE,
                                    ITEM_NOTAFISCAL.VALOR_UNITARIO AS VLR_UNITARIO,
                                    (ITEM_NOTAFISCAL.QUANTIDADE * ITEM_NOTAFISCAL.VALOR_UNITARIO) AS VALOR_MERCADORIA,
                                    ITEM_NOTAFISCAL.PERC_DESCTO AS PERC_DESCONTO,
                                    (ITEM_NOTAFISCAL.VALOR_DESCTO -1) AS VALOR_DESCTO,
                                    ((ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE) * -1) AS VALOR_TOTAL
                                FROM
                                    NOTA_FISCAL
                                    INNER JOIN ITEM_NOTAFISCAL ON (ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA AND ITEM_NOTAFISCAL.SERIE = NOTA_FISCAL.SERIE AND ITEM_NOTAFISCAL.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL)
                                    INNER JOIN CLIFOR ON CLIFOR.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                                    INNER JOIN REPRESENTANTE ON REPRESENTANTE.COD_REPRES = NOTA_FISCAL.COD_REPRES
                                    INNER JOIN CIDADE ON CIDADE.COD_CIDADE = CLIFOR.COD_CIDADE
                                    INNER JOIN ESTADO ON ESTADO.COD_ESTADO = CLIFOR.COD_ESTADO
                                    INNER JOIN ITEM ON ITEM.COD_ITEM = ITEM_NOTAFISCAL.COD_ITEM
                                    INNER JOIN GRUPO_ESTOQUE ON GRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE
                                    INNER JOIN FAMILIA_COMERCIAL ON FAMILIA_COMERCIAL.COD_FMCOMERCIAL = ITEM.COD_FMCOMERCIAL
                                    LEFT JOIN GRUPO_CLIENTE ON CLIFOR.COD_GRPCLIENTE = GRUPO_CLIENTE.COD_GRPCLIENTE
                                    LEFT JOIN REPRES_COMISSAO ON REPRES_COMISSAO.COD_CLIFOR = CLIFOR.COD_CLIFOR
                                    LEFT JOIN REPRESENTANTE VE ON VE.COD_REPRES = REPRES_COMISSAO.COD_REPRES
                                    LEFT JOIN SUBGRUPO_ESTOQUE ON (SUBGRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE AND SUBGRUPO_ESTOQUE.COD_SUBGRUPOESTOQUE = ITEM.COD_SUBGRUPOESTOQUE)
                                    LEFT JOIN FAMILIA_INDUSTRIAL ON FAMILIA_INDUSTRIAL.COD_FMINDUSTRIAL = ITEM.COD_FMINDUSTRIAL
                                WHERE
                                    NOTA_FISCAL.COD_EMPRESA = '101'
                                    AND NOTA_FISCAL.SITUACAO <> 'C'
                                    AND NOTA_FISCAL.DT_EMISSAO >= '2025-06-01'
                                    AND NOTA_FISCAL.DT_EMISSAO <= '2025-06-30'
                                    AND NOTA_FISCAL.ENTRADA_SAIDA = 'E'
                                    AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '012'
                                    AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001','99')
                            ) TAB
                        GROUP BY
                            TAB.EMPRESA,
                            TAB.DATA_DE_EMISSAO,
                            TAB.DATA_CADASTRO,
                            TAB.NRO_NOTA_FISCAL,
                            TAB.CODIGO_CLIENTE,
                            TAB.RAZAO_SOCIAL,
                            TAB.AGENTE_REGULARANP,
                            TAB.MUNICIPIO,
                            TAB.ESTADO,
                            TAB.UF,
                            TAB.GRUPO_CLIENTE,
                            TAB.CODIGO_ITEM,
                            TAB.DESCRICAO_ITEM,
                            TAB.GRUPO_DE_ESTOQUE,
                            TAB.SUBGRUPO_DE_ESTOQUE,
                            TAB.GRUPO_ESTOQUE_SEQ_EXIBICAO,
                            TAB.FAM_COMERCIAL,
                            TAB.FAM_INDUSTRIAL,
                            TAB.COD_REPRES,
                            TAB.RAZAO_REPRES,
                            TAB.FANTASIA_REPRES,
                            TAB.EMAIL_REPRES,
                            TAB.ATIVO_REPRES,
                            TAB.SEQUENCIA,
                            TAB.QTDE,
                            TAB.VLR_UNITARIO,
                            TAB.VALOR_MERCADORIA,
                            TAB.PERC_DESCONTO,
                            TAB.VALOR_DESCTO,
                            TAB.VALOR_TOTAL
                        ORDER BY
                            TAB.DATA_DE_EMISSAO,
                            TAB.NRO_NOTA_FISCAL,
                            TAB.SEQUENCIA
                    ) v
                )AUX
            GROUP BY
                AUX.cod_repres
            ");
            $objects = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            foreach ($objects as $object) {
                $quantidades[$object->cod_repres] = $object->qtde_clifor;
            }
            TTransaction::close();

            TTransaction::open(self::$dbSite);
            $conn = TTransaction::get();
            if(!$conn) throw new Exception("Conexão inválida");
            $result = $conn->query("
                SELECT 
                    x.cod_repres as 'cod_repres',
                    COUNT(DISTINCT x.cod_cliente) as 'qtde_clifor'
                FROM
                (
                    SELECT
                        c.tx_cod as cod_cliente,
                        DATE_FORMAT(nwp.dt_alteracao, '%m') as mes,
                        DATE_FORMAT(nwp.dt_alteracao, '%Y') as ano,
                        nwp.dt_alteracao as data,
                        v.id_integracao as cod_repres
                    FROM 
                        tb_nwpedidos nwp
                        INNER JOIN tb_clientes c ON c.id_cliente=nwp.id_cliente
                        INNER JOIN tb_vendedores v ON c.id_vendedor = v.id_vendedor
                    WHERE
                        EXTRACT(YEAR  FROM nwp.dt_cadastro) >= 2024
                        AND nwp.dt_cadastro < ADDDATE(now(), 1)
                        AND v.id_integracao NOT IN ('0000001','99')
                    UNION ALL
                    SELECT 
                        c.tx_cod cod_cliente,
                        DATE_FORMAT(pp.dt_fechamento, '%m') as mes,
                        DATE_FORMAT(pp.dt_fechamento, '%Y') as ano,
                        pp.dt_fechamento as data,
                        v.id_integracao as cod_repres
                    FROM 
                        tb_persiana_pedidos pp
                    INNER JOIN tb_clientes c ON c.id_cliente=pp.id_cliente
                    INNER JOIN tb_vendedores v ON c.id_vendedor = v.id_vendedor
                    WHERE 
                        EXTRACT(YEAR  FROM pp.dt_fechamento) >= 2024
                        AND pp.dt_fechamento < ADDDATE(now(), 1)
                        AND v.id_integracao NOT IN ('0000001','99')
                ) as x
                WHERE
                    x.mes = {$metaAberta->mes}
                    AND x.ano = {$metaAberta->ano}
                GROUP BY
                    x.cod_repres
            ");
            $objects = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            TTransaction::close();

            TTransaction::open(self::$dbAp);
            foreach ($objects as $object) {
                $fechamento = MetaFechamento::find($fechamentos[$object->cod_repres]);
                
                $total = isset($quantidades[$object->cod_repres]) ? $quantidades[$object->cod_repres] : 0;
                $site = $object->qtde_clifor;

                $fechamento->site_porcentagem = ($site*100)/$total;
                $fechamento->store();
            }
            TTransaction::close();
            //Registro de log de execução
            return true;
        } catch (Exception $e) {
            return $e->getMessage(). "<br/>".__METHOD__." Line: ".$e->getLine()."Arquivo: " . $e->getFile();
        }
    }

    public static function verificaPremioSite(){
        try{
            $sessao = TSession::getValue('userunitid') ?? 1; 
            TTransaction::open(self::$dbAp);
            $conn = TTransaction::get();
            
            $arrayRepres = ApRepresentante::where('system_unit_id','=',$sessao)->getIndexedArray('id','cod_repres');

            $metaAberta = self::get_meta_aberta();

            $premio = Premio::where('mes','=',$metaAberta->mes)->where('ano','=',$metaAberta->ano)->where('tipo_premio_id','=',7)->getIndexedArray('id','id');
            $regras = PremioRegra::where('premio_id','in',$premio)->orderby('premio','desc')->load();
            $fechamentosS = MetaFechamento::where('meta_id','=',$metaAberta->id)->orderby('site_porcentagem','desc')->load();

            // Vamos controlar quais regras já foram aplicadas para não reaplicar
            $regrasAplicadas = [];

            // Percorre os fechamentos (representantes)
            foreach ($fechamentosS as $fechamentoS) {
                $fechamentoS->premio_site = 0;
                $fechamentoS->alcancou_site = 'N';

                $metaRepres = MetaRepres::find($fechamentoS->meta_repres_id);

                // Para cada regra disponível, verifica se pode aplicar ao representante atual
                foreach ($regras as $key => $regra) {
                    // Se a regra já foi aplicada, pula
                    if (in_array($regra->id, $regrasAplicadas)) {
                        continue;
                    }

                    if($fechamentoS->site_porcentagem >= $metaRepres->perc_site){
                        $fechamentoS->alcancou_site = 'S';
                    }

                    // Verifica se o representante atingiu o valor mínimo da regra
                    if ($fechamentoS->site_porcentagem >= $regra->dado_0) {
                        // Aplica o prêmio
                        $fechamentoS->premio_site = $regra->bonus;

                        $regrasAplicadas[] = $regra->id;
                        unset($regras[$key]);
                        break;
                    }
                }
                $fechamentoS->bonus_premiacao = $fechamentoS->premio_prospeccao + $fechamentoS->premio_reativacao + $fechamentoS->premio_site;
                $fechamentoS->store();
            }
            TTransaction::close();
            //Registro de log de execução
            return true;
        } catch (Exception $e) {
            return $e->getMessage(). "<br/>".__METHOD__." Line: ".$e->getLine()."Arquivo: " . $e->getFile();
        }
    }
    public static function verificaPremioProspeccao(){
        try{
            $sessao = TSession::getValue('userunitid') ?? 1; 
            TTransaction::open(self::$dbAp);
            $conn = TTransaction::get();
            
            $arrayRepres = ApRepresentante::where('system_unit_id','=',$sessao)->getIndexedArray('id','cod_repres');

            $metaAberta = self::get_meta_aberta();
            
            $premio = Premio::where('mes','=',$metaAberta->mes)->where('ano','=',$metaAberta->ano)->where('tipo_premio_id','=',5)->getIndexedArray('id','id');
            $regras = PremioRegra::where('premio_id','in',$premio)->orderby('premio','desc')->load();
            $fechamentosP = MetaFechamento::where('meta_id','=',$metaAberta->id)->orderby('faturamento_prospeccao','desc')->load();

            // Vamos controlar quais regras já foram aplicadas para não reaplicar
            $regrasAplicadas = [];

            foreach($fechamentosP as $fechamentoP){
                $fechamentoP->alcancou_prospeccao = 'N';
                $fechamentoP->premio_prospeccao  = 0;

                $metaRepres = MetaRepres::find($fechamentoP->meta_repres_id);

                foreach ($regras as $key => $regra) {
                    // Se a regra já foi aplicada, pula
                    if (in_array($regra->id, $regrasAplicadas)) {
                        continue;
                    }

                    if($fechamentoP->faturamento_prospeccao >= $metaRepres->valor_prospeccao){
                        $fechamentoP->alcancou_prospeccao = 'S';
                    }

                    // Verifica se o representante atingiu o valor mínimo da regra
                    if ($fechamentoP->faturamento_prospeccao >= $regra->dado_0) {
                        // Aplica o prêmio
                        $fechamentoP->premio_prospeccao = $regra->bonus;

                        $regrasAplicadas[] = $regra->id;
                        unset($regras[$key]);
                        break;
                    }
                }
                $fechamentoP->store();
            }
            TTransaction::close();
            //Registro de log de execução
            return true;
        } catch (Exception $e) {
            return $e->getMessage(). "<br/>".__METHOD__." Line: ".$e->getLine()."Arquivo: " . $e->getFile();
        }
    }

    public static function verificaPremioReativacao(){
        try{
            $sessao = TSession::getValue('userunitid') ?? 1; 
            TTransaction::open(self::$dbAp);
            $conn = TTransaction::get();
            
            $arrayRepres = ApRepresentante::where('system_unit_id','=',$sessao)->getIndexedArray('id','cod_repres');

            $metaAberta = self::get_meta_aberta();

            $premio = Premio::where('mes','=',$metaAberta->mes)->where('ano','=',$metaAberta->ano)->where('tipo_premio_id','=',6)->getIndexedArray('id','id');
            $regras = PremioRegra::where('premio_id','in',$premio)->orderby('premio','desc')->load();
            $fechamentosR = MetaFechamento::where('meta_id','=',$metaAberta->id)->orderby('faturamento_reativacao','desc')->load();

            // Vamos controlar quais regras já foram aplicadas para não reaplicar
            $regrasAplicadas = [];

            // Percorre os fechamentos (representantes)
            foreach ($fechamentosR as $fechamentoR) {
                $fechamentoR->alcancou_reativacao = 'N';
                $fechamentoR->premio_reativacao = 0;

                $metaRepres = MetaRepres::find($fechamentoR->meta_repres_id);

                // Para cada regra disponível, verifica se pode aplicar ao representante atual
                foreach ($regras as $key => $regra) {
                    // Se a regra já foi aplicada, pula
                    if (in_array($regra->id, $regrasAplicadas)) {
                        continue;
                    }

                    if($fechamentoR->faturamento_reativacao >= $metaRepres->valor_reativacao){
                        $fechamentoR->alcancou_reativacao = 'S';
                    }

                    // Verifica se o representante atingiu o valor mínimo da regra
                    if ($fechamentoR->faturamento_reativacao >= $regra->dado_0) {
                        // Aplica o prêmio
                        $fechamentoR->premio_reativacao = $regra->bonus;

                        $regrasAplicadas[] = $regra->id;
                        unset($regras[$key]);
                        break;
                    }
                }
                $fechamentoR->store();
            }
            TTransaction::close();
            //Registro de log de execução
            return true;
        } catch (Exception $e) {
            return $e->getMessage(). "<br/>".__METHOD__." Line: ".$e->getLine()."Arquivo: " . $e->getFile();
        }
    }
*/
}
