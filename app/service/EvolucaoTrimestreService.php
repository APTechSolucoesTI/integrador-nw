<?php
class EvolucaoTrimestreService{

    private static $db_nw = 'nw';
  
    private static $db_ap = 'integrador';
  

    public static function getTrimestreAtivo(){
        try{
            TTransaction::open(self::$db_ap);
            $metaTrimestral = MetaTrimestral::where('status','=',1)->first();  
            TTransaction::close();

            if(!$metaTrimestral){
                throw new Exception("Sem meta trimestral aberta.");
            }
            
            return $metaTrimestral;
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }

    public static function calcularTrimestre(){
        try {
            $hora0 = new DateTime('now');
            
            $sessao = TSession::getValue('userunitid') ?? 1;         
            $metaTrimestral = self::getTrimestreAtivo();

            TTransaction::open(self::$db_ap);
            $inicial = TrimestreClienteInicial::where('meta_trimestral_id','=',$metaTrimestral->id)->count();
            TTransaction::close();
            
            $obs = "sem registro inicial";
            if($inicial == 0){
                self::registrarInicioTrimestre();
                $obs = "com registro inicial";
            }
            self::registrarAtualTrimestre();
            self::calcularPontuacaoAtual();

            $hora1 = new DateTime('now');
            $intervalo = $hora1->diff($hora0);
            $segundosTotais = ($intervalo->days * 24 * 60 * 60) + ($intervalo->h * 60 * 60) + ($intervalo->i * 60) + $intervalo->s;
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 0, "Registro do trimestre $obs atualizado em $segundosTotais segundos.", "Arquivo: TrimestreService.<br/>Linha: ".__LINE__.".");
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");            
        }
    }

    public static function registrarInicioTrimestre(){
        try {            
            $sessao = TSession::getValue('userunitid') ?? 1;         
            $metaTrimestral = self::getTrimestreAtivo();

            $data_inicial = new DateTime($metaTrimestral->data_inicial);
            $data = clone $data_inicial;
            $data->sub(new DateInterval('P1M'));

            $mesAnterior = (int)$data->format('m');
            $anoAnterior = (int)$data->format('Y');

            TTransaction::open(self::$db_ap);
            $meta_fechada = Meta::where('mes','=',$mesAnterior)->where('ano','=',$anoAnterior)->where('status','=',2)->orderBy('id')->last();

            $clientes    = HistoricoCliRepres::where('mes','=',$meta_fechada->mes)->where('ano','=',$meta_fechada->ano)->load();
            $arrayRepres = ApRepresentante::where('system_unit_id','=',$sessao)->getIndexedArray('id','cod_repres');
            
            TTransaction::close();
            TTransaction::open(self::$db_nw);
            $conn = TTransaction::get();
            $result = $conn->query("
                SELECT
                    clifor.cod_clifor as cod_clifor,
                    clifor.cod_repres as cod_repres
                FROM
                    clifor
                WHERE
                    clifor.cliente = 'S' 
                ORDER BY
                    clifor.cod_clifor::numeric
            ");
            $objects = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            TTransaction::close();
            
            foreach($objects as $object){
                $clifor_repres[$object->cod_clifor] = array_search($object->cod_repres, $arrayRepres) !== false 
                                                    ? array_search($object->cod_repres, $arrayRepres) 
                                                    : null;
            }
            TTransaction::open(self::$db_ap);
            foreach($clientes as $historico){
                $inicial = new TrimestreClienteInicial();
                $inicial->meta_trimestral_id = $metaTrimestral->id;
                $inicial->data_insercao      = date('Y-m-d H:i:s');                
                $inicial->cod_clifor         = $historico->cod_clifor;
                $inicial->dt_cadastro        = $historico->dt_cadastro;
                $inicial->grupo_id           = $historico->grupo_id;
                $inicial->ativo              = $historico->ativo;
                $inicial->tipo_pessoa        = $historico->tipo_pessoa;
                $inicial->razao_social       = $historico->razao_clifor;
                $inicial->reativacao         = $historico->agente_regular_anp;
                $inicial->rota               = $historico->rota;
                $inicial->filial             = $historico->filial;
                $inicial->cod_principal      = $historico->cod_principal;

                if(array_key_exists($inicial->cod_clifor,$clifor_repres))
                    $inicial->repres_id      = $clifor_repres[$inicial->cod_clifor];

                if($historico->ap_cidade_id){
                    $cidade = ApCidade::find($historico->ap_cidade_id);
                    $inicial->cidade_id = $cidade->id;
                    $inicial->estado_id = $cidade->estado_id;
                }
                
                $inicial->store();
            }
            
            TTransaction::close();
        
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");            
        }
    }

    public static function registrarAtualTrimestre(){
        try {
            $sessao = TSession::getValue('userunitid') ?? 1;         
            $metaTrimestral = self::getTrimestreAtivo();
            $agora        = new DateTime();
            $data_inicial = new DateTime($metaTrimestral->data_inicial);
            $data_final = new DateTime($metaTrimestral->data_final);
            $data = clone $data_inicial;
            while ($data <= $data_final) {
                $meses[] = (int)$data->format('n'); // pega o número do mês
                $meses_anos[] = "'".(string) ((int)$data->format('n')."/".(int)$data->format('Y'))."'";
                $data->modify('+1 month');
            }
            
            TTransaction::open(self::$db_nw);
            $conn = TTransaction::get();
            $result = $conn->query("
                SELECT 
                    trim(cli.cod_clifor) as cod_clifor,
                    trim(cli.razao) as razao_social,
                    cli.dt_cadastro as dt_cadastro,
                    trim(cli.cod_grpcliente) as cod_grpcliente,
                    cli.cod_repres as cod_repres,
                    trim(cli.ativo) as ativo,
                    trim(cli.agente_regularanp) as reativacao,
                    trim(cli.pessoa) as tipo_pessoa,
                    trim(cli.cod_cidade) as cod_cidade,
                    trim(cli.cod_estado) as cod_estado,
                    trim(rota_cadastro.descricao) as rota,
                    CASE
                        WHEN cli.dt_cadastro >= '$metaTrimestral->data_inicial' THEN EXTRACT(MONTH FROM cli.dt_cadastro)
		                WHEN cli.agente_regularanp in (".implode(",",$meses_anos).") THEN split_part(cli.agente_regularanp, '/', 1)::int
		                ELSE EXTRACT(MONTH FROM '$metaTrimestral->data_inicial'::date)
                    END as mes_inicial,
                    COALESCE(SUM(
                        CASE
                            WHEN v.entrada_saida = 'S' THEN (i.valor_total + i.valor_frete)
                            WHEN v.entrada_saida = 'E' THEN ((i.valor_total + i.valor_frete) * -1)
                            ELSE 0
                        END),0
                    ) as valor_total,
                    COALESCE(SUM(CASE WHEN i.sequencia = 12 THEN
                        CASE
                            WHEN v.entrada_saida = 'S' THEN (i.valor_total + i.valor_frete)
                            WHEN v.entrada_saida = 'E' THEN ((i.valor_total + i.valor_frete) * -1)
                            ELSE 0
                        END ELSE 0 END),0
                    ) as valor_mostruario
                FROM
                    clifor cli
                    LEFT JOIN tabela_planilha rota_clifor 
                        ON rota_clifor.chave_tabela = cli.cod_clifor 
                        AND rota_clifor.nome_tabela = 'CLIFOR' 
                        AND rota_clifor.seq_campos = 2
                    LEFT JOIN tabela_padraoreg rota_cadastro 
                        ON rota_cadastro.cod_tabelapadraoreg = rota_clifor.cod_tabelapadraoreg 
                    LEFT JOIN nota_fiscal v 
                        ON v.cod_clifor = cli.cod_clifor
                        AND v.dt_emissao >= '$metaTrimestral->data_inicial'
                        AND v.dt_emissao <= '$metaTrimestral->data_final'
                        AND v.cod_empresa = '101'
                        and v.situacao <> 'C'
                        and v.entrada_saida in ('S','E')
                    LEFT JOIN item_notafiscal i on 
                        i.cod_empresa = v.cod_empresa
                        and i.serie = v.serie
                        and i.cod_clifor = v.cod_clifor
                        and i.nro_nfiscal = v.nro_nfiscal
                        and ((v.entrada_saida = 'S'and i.cod_tiponatureza = '008') or (v.entrada_saida = 'E' and i.cod_tiponatureza = '012'))
                        and i.cfo not in ('5997', '6997', '5998', '6998')
                WHERE
                    cli.cliente = 'S'
                GROUP BY
                    cli.cod_clifor,
                    rota_cadastro.descricao
                ORDER BY
                    cli.cod_clifor::numeric
            "); 
            $clientes = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");

            $result = $conn->query("
                select 	
                        y.principal as \"cod_principal\", 
                        y.codigo as \"cod_clifor\"
                from (
                select  x.consultor , x.repres_email , x.cod_repres , x.grupo_empresarial ,
                        (case when (x.Grupo_Empresarial = 'Sim') then
                            (case when x.ativo = 'S' then 
                                (case when (x.cod_clifor > (select f.cod_filial from 	clifor_filial as f , clifor as c 
                                                            where 	c.cod_clifor = f.cod_filial and f.cod_clifor = x.cod_clifor and c.ativo = 'S' and c.cod_grpcliente <> 'TR' 
                                                            order by f.cod_filial limit 1) ) then 
                                        (select f.cod_filial from 	clifor_filial as f , clifor as c 
                                        where 	c.cod_clifor = f.cod_filial and f.cod_clifor = x.cod_clifor and c.ativo = 'S' and c.cod_grpcliente <> 'TR'
                                        order by f.cod_filial limit 1)											 
                                    else
                                    x.cod_clifor
                                    end)
                            else
                                (case when (select f.cod_filial from clifor_filial as f , clifor as c 
                                    where 	c.cod_clifor = f.cod_filial and f.cod_clifor = x.cod_clifor and c.ativo = 'S' and c.cod_grpcliente <> 'TR'
                                    order by f.cod_filial limit 1) is not null then 
                                        (select f.cod_filial from 	clifor_filial as f , clifor as c 
                                        where 	c.cod_clifor = f.cod_filial and f.cod_clifor = x.cod_clifor and c.ativo = 'S' and c.cod_grpcliente <> 'TR'
                                        order by f.cod_filial limit 1)
                                    else
                                        (case when (x.cod_clifor > (select f.cod_filial from 	clifor_filial as f , clifor as c 
                                                                    where 	c.cod_clifor = f.cod_filial and f.cod_clifor = x.cod_clifor and c.ativo = 'N' and c.cod_grpcliente <> 'TR'
                                                                    order by f.cod_filial limit 1) ) then 
                                                (select f.cod_filial from 	clifor_filial as f , clifor as c 
                                                where 	c.cod_clifor = f.cod_filial and f.cod_clifor = x.cod_clifor and c.ativo = 'N' and c.cod_grpcliente <> 'TR'
                                                order by f.cod_filial limit 1)											 
                                        else
                                            x.cod_clifor
                                        end)
                                    end)
                            end)
                            else
                            x.cod_clifor
                            end) as Principal , 
                        
                            x.cod_clifor as codigo ,  x.razao , x.grupo_cliente , x.dt_cadastro, 
                    
                        (case when (x.Grupo_Empresarial = 'Sim') then
                            (case when x.ativo = 'S' then 
                                x.ativo			
                            else
                                (case when (select f.cod_filial from 	clifor_filial as f , clifor as c 
                                    where 	c.cod_clifor = f.cod_filial and f.cod_clifor = x.cod_clifor and c.ativo = 'S' and c.cod_grpcliente <> 'TR'
                                    order by f.cod_filial limit 1) is not null then 
                                        (select c.ativo from 	clifor_filial as f , clifor as c 
                                        where 	c.cod_clifor = f.cod_filial and f.cod_clifor = x.cod_clifor and c.ativo = 'S' and c.cod_grpcliente <> 'TR'
                                        order by f.cod_filial limit 1)
                                    else
                                        x.ativo
                                    end)
                            end)
                            else
                            x.ativo
                            end) as Ativo  
                    
                from (
                        select 		c.cod_clifor , c.razao , g.descricao as Grupo_Cliente , c.dt_cadastro , c.ativo , r.fantasia as consultor , r.email as repres_email , r.cod_repres ,
                                    (case when 
                                        (select f.cod_clifor from clifor_filial as f where f.cod_clifor = c.cod_clifor group by f.cod_clifor) is not null then
                                                'Sim'
                                        else
                                                'Não'
                                        end) as Grupo_Empresarial
                        from 		clifor as c , grupo_cliente as g , representante as r
                        where 	c.cod_grpcliente is not null and c.cod_grpcliente = g.cod_grpcliente and g.descricao not in ('ORÇAMENTO (COMERCIAL)','NÃO APLICÁVEL','COLABORADOR','BLOQUEADO - NÃO PODE REATIVAR')
                                and r.cod_repres = c.cod_repres and r.ativo = 'S'
                ) as x
                ) as y
                where y.grupo_empresarial = 'Sim'
                order by y.principal
            ");

            $filiais = $result->fetchAll(PDO::FETCH_CLASS, "stdClass"); 
            TTransaction::close();
            TTransaction::open(self::$db_ap);

            TrimestreClienteAtual::where('meta_trimestral_id','=',(int) $metaTrimestral->id)->delete();

            $arrayRepres = ApRepresentante::where('system_unit_id','=',$sessao)->getIndexedArray('id','cod_repres');
            $arrayCidade = ApCidade::where('id','is not',null)->getIndexedArray('id','cod_cidade');
            $arrayEstado = ApEstado::where('id','is not',null)->getIndexedArray('id','cod_estado');

            foreach ($clientes as $cliente) {
                $atual = new TrimestreClienteAtual();
                $atual->meta_trimestral_id = $metaTrimestral->id;
                $atual->data_insercao      = date('Y-m-d H:i:s');
                $atual->cod_clifor         = $cliente->cod_clifor;
                $atual->razao_social       = $cliente->razao_social;
                $atual->dt_cadastro        = $cliente->dt_cadastro;
                $atual->ativo              = $cliente->ativo;
                $atual->reativacao         = $cliente->reativacao;
                $atual->rota               = $cliente->rota;
                $atual->tipo_pessoa        = $cliente->tipo_pessoa;
                $atual->mes_inicial        = $cliente->mes_inicial;
                $atual->valor_total        = $cliente->valor_total;
                $atual->valor_mostruario   = $cliente->valor_mostruario;
                
                $atual->repres_id   = array_search($cliente->cod_repres, $arrayRepres) !== false 
                                    ? array_search($cliente->cod_repres, $arrayRepres) 
                                    : null;
                
                $atual->cidade_id   = array_search($cliente->cod_cidade, $arrayCidade) !== false 
                                    ? array_search($cliente->cod_cidade, $arrayCidade) 
                                    : null;

                $atual->estado_id   = array_search($cliente->cod_estado, $arrayEstado) !== false 
                                    ? array_search($cliente->cod_estado, $arrayEstado) 
                                    : null;
                
                $mes_final      = (int) ($data_final->format('n'));
                $mes_inicial    = (int) $cliente->mes_inicial;
                $meses_passados = $mes_final - $mes_inicial+1;
                $meses_passados = max($meses_passados, 1);
                $media_mensal   = $atual->valor_total/$meses_passados;

                $grupo_busca = ApGrupoCliente::where('valor_inicial', '<=', $media_mensal)
                                                ->where('valor_final', '>=', $media_mensal)
                                                ->first();
                
                $atual->valor_media        = $media_mensal;
                $atual->grupo_id           = $grupo_busca->id ?? null;
                $atual->store();                
            }
            TTransaction::close();
            TTransaction::open(self::$db_ap);
            
            foreach($filiais as $dado){                
                $filial = $dado->cod_clifor == $dado->cod_principal ? $filial = 'N' : $filial = 'S';
                
                TrimestreClienteAtual::where('cod_clifor','=',$dado->cod_clifor)
                                    ->where('meta_trimestral_id','=',(int) $metaTrimestral->id)
                                    ->set('cod_principal',$dado->cod_principal)
                                    ->set('filial',$filial)
                                    ->update();
            }
            TTransaction::close();
            
            TTransaction::open(self::$db_ap);
            $conn = TTransaction::get();
            $result = $conn->query("
                SELECT
                    cod_principal,
                    SUM(valor_total) as valor_total
                FROM
                    trimestre_cliente_atual
                WHERE
                    meta_trimestral_id = {$metaTrimestral->id}
                    AND cod_principal is not null
                GROUP BY
                    cod_principal
            ");
            
            $somas = $result->fetchAll(PDO::FETCH_CLASS, "stdClass"); 
            foreach($somas as $soma){
                $media_mensal = $soma->valor_total/3;
                $grupo_busca = ApGrupoCliente::where('valor_inicial', '<=', $media_mensal)
                                                ->where('valor_final', '>=', $media_mensal)
                                                ->first();
                
                TrimestreClienteAtual::where('cod_principal','=',$soma->cod_principal)
                                    ->where('meta_trimestral_id','=',(int) $metaTrimestral->id)
                                    ->set('valor_grupo',$soma->valor_total)
                                    ->set('grupo_id',$grupo_busca->id ?? null)
                                    ->update();
            }
            TTransaction::close();
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");            
        }
    }

    public static function calcularPontuacaoAtual(){
        try{
            $metaTrimestral = self::getTrimestreAtivo();
            
            TTransaction::open(self::$db_ap);            
            $conn = TTransaction::get();
            
            $result = $conn->query("
                SELECT
                    repres_atual.id as repres_id,
                    repres_atual.fantasia AS consultor,
                    SUM(grupo_atual.pontuacao) AS pontuacao
                FROM 
                    trimestre_cliente_atual atual
                    LEFT JOIN ap_grupo_cliente grupo_atual
                        ON atual.grupo_id = grupo_atual.id
                    LEFT JOIN ap_representante repres_atual
                        ON atual.repres_id = repres_atual.id
                WHERE 
                    atual.meta_trimestral_id = {$metaTrimestral->id}
                    AND (atual.filial is null or atual.filial = 'N')
                    AND atual.repres_id in (SELECT ap_representante_id FROM meta_trimestral_repres WHERE meta_trimestral_id = {$metaTrimestral->id})
                GROUP BY
                    repres_atual.id,
                    repres_atual.fantasia
                ORDER BY
                    repres_atual.fantasia
            ");
            
            $pontos = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            foreach($pontos as $ponto){
                $meta = MetaTrimestralRepres::where('meta_trimestral_id','=',$metaTrimestral->id)->where('ap_representante_id','=',$ponto->repres_id)->first();
                if($meta){
                    $meta->pontuacao_atual = $ponto->pontuacao;
                    $meta->store();
                }
            }
            
            TTransaction::close();
            
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }
}
