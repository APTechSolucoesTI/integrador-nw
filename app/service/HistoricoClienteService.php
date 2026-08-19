<?php

class HistoricoClienteService
{
    private static $dbNw = 'nw';
    private static $dbAp = 'integrador';

    public static function atualizarTudo(){
        HistoricoClienteService::registrarHistoricoCliente();
        HistoricoClienteService::buscarCoordenadasClientes();
    }
    
    public static function get_meta_aberta(){
        try{
            $sessao = TSession::getValue('userunitid') ?? 1; 

            TTransaction::open(self::$dbAp);

            $conn = TTransaction::get();
            $metaAberta = Meta::where('system_unit_id','=',$sessao)->where('status','=',1)->first();

            TTransaction::close();

            if (!$metaAberta) {
                throw new Exception('Meta aberta não encontrada');
            }
            return $metaAberta;
        } catch (Exception $e) {
            LogCrontab::registrarLog("Fechamento do mês", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }

    public static function registrarHistoricoCliente()
    {
        try{
            TTransaction::open(self::$dbNw);
            $openTransaction = TTransaction::getDatabase() == self::$dbNw ? true : false;
            TTransaction::close();
            if(!$openTransaction){
                throw new Exception("Sem conexão com a base de dados '$dbNw', verifique o arquivo de configuração e tente novamente.");
            }
            
            $sessao = TSession::getValue('userunitid') ?? 1; 
            
            TTransaction::open(self::$dbAp);
            $conn = TTransaction::get();
            
            $metaAberta = self::get_meta_aberta();
            
            $arrayGrupo  = ApGrupoCliente::where('system_unit_id','=',$sessao)->where('cod_grpcliente','is not',null)->getIndexedArray('id','cod_grpcliente');
            $arrayRepres = ApRepresentante::where('system_unit_id','=',$sessao)->getIndexedArray('id','cod_repres');
            $arrayCidade = ApCidade::where('id','is not',null)->getIndexedArray('id','cod_cidade');
                        
            $result = $conn->query("
                SELECT 
                    dt_atualizacao as dt_atualizacao,
                    dt_change as dt_change
                FROM 
                    historico_cli_repres 
                WHERE 
                    mes = {$metaAberta->mes} 
                    AND ano = {$metaAberta->ano}
                    AND (dt_atualizacao IS NOT NULL 
                    OR dt_change IS NOT NULL)
                ORDER BY dt_change DESC 
                LIMIT(1)
            ");
            $hora = $result->fetch(PDO::FETCH_ASSOC);
            $dt_atualizacao = ($hora !== false && isset($hora['dt_atualizacao'])) 
                ? date('Y-m-d 00:00:00', strtotime('-1 day', strtotime($hora['dt_atualizacao']))) 
                : null;

            $dt_change = ($hora !== false && isset($hora['dt_change'])) 
                ? date('Y-m-d 00:00:00', strtotime('-1 day', strtotime($hora['dt_change']))) 
                : null;


            $filtroAtualizacao = "";
            if($dt_atualizacao !== null && $dt_change !== null){
                $filtroAtualizacao = "AND (cli.dt_atualizacao > '$dt_atualizacao' OR cli.dt_change > '$dt_change')";
            }elseif($dt_atualizacao !== null){
                $filtroAtualizacao = "AND cli.dt_atualizacao > '$dt_atualizacao'";
            }elseif($dt_change !== null){
                $filtroAtualizacao = "AND cli.dt_atualizacao > '$dt_change'";
            }

            TTransaction::close();

            TTransaction::open(self::$dbNw);
            $conn = TTransaction::get();
            $result = $conn->query("
                SELECT 
                    trim(cli.cod_clifor) as cod_clifor,
                    trim(cli.razao) as razao,
                    trim(cli.cod_grpcliente) as cod_grpcliente,
                    trim(cli.cod_cidade) as cod_cidade,
                    trim(cli.cod_estado) as cod_estado,
                    trim(cli.fantasia) as fantasia,
                    cli.dt_cadastro as dt_cadastro,
                    cli.cod_repres as cod_repres,
                    trim(cli.pessoa) as tipo_pessoa,
                    trim(cli.ativo) as ativo,
                    trim(cli.agente_regularanp) as agente_regularanp,
                    trim(rota_cadastro.descricao) as rota,
                    trim(cli.cep) as cep,
                    cli.dt_atualizacao as dt_atualizacao,
                    cli.dt_change as dt_change
                FROM
                    clifor cli
                    LEFT JOIN tabela_planilha rota_clifor 
                        ON rota_clifor.chave_tabela = cli.cod_clifor 
                        AND rota_clifor.nome_tabela = 'CLIFOR' 
                        AND rota_clifor.seq_campos = 2
                    LEFT JOIN tabela_padraoreg rota_cadastro 
                        ON rota_cadastro.cod_tabelapadraoreg = rota_clifor.cod_tabelapadraoreg 
                WHERE
                	cli.cliente = 'S'
                	AND cli.cod_grpcliente is not null
                    
                ORDER BY
                    cli.cod_clifor::numeric
            ");


            $clientes = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            
            TTransaction::close();
            
            $dataBaseCalc = new DateTime("$metaAberta->ano-$metaAberta->mes");
            $ultimoDia = $dataBaseCalc->format('t');
    
            $ultimoDiaMes = new DateTime("$metaAberta->ano-$metaAberta->mes-$ultimoDia 23:59");
            $count = 0;
            foreach($clientes as $cliente){
                
                $dt_cadastro = new DateTime($cliente->dt_cadastro);
                if($dt_cadastro <= $ultimoDiaMes){
                    
                    TTransaction::open(self::$dbAp);
                    $histCliRepres = HistoricoCliRepres::where('mes','=',$metaAberta->mes)->where('ano','=',$metaAberta->ano)
                                    ->where('system_unit_id','=',$sessao)->where('cod_clifor','=',$cliente->cod_clifor)
                                    ->first() ?? new HistoricoCliRepres();

                    $count++;
                    $histCliRepres->ano = $metaAberta->ano;
                    $histCliRepres->mes = $metaAberta->mes;
                    $histCliRepres->system_unit_id = $sessao;
                    
                    $histCliRepres->cod_clifor = $cliente->cod_clifor;
                    $histCliRepres->razao_clifor = strtoupper($cliente->razao);
                    
                    if($cliente->cod_grpcliente!=null && !empty($cliente->cod_grpcliente)){
                        $histCliRepres->grupo_id = array_search($cliente->cod_grpcliente, $arrayGrupo) !== false 
                                                    ? array_search($cliente->cod_grpcliente, $arrayGrupo) 
                                                    : null;
                    }
                    
                    $histCliRepres->repres_id = array_search($cliente->cod_repres, $arrayRepres) !== false 
                                                    ? array_search($cliente->cod_repres, $arrayRepres) 
                                                    : null;

                    $histCliRepres->ap_cidade_id = array_search($cliente->cod_cidade, $arrayCidade) !== false 
                                                    ? array_search($cliente->cod_cidade, $arrayCidade) 
                                                    : null;
                                                    
                    $histCliRepres->cod_estado = $cliente->cod_estado;
                    $histCliRepres->ativo = $cliente->ativo;
                    $histCliRepres->rota = $cliente->rota;
                    $histCliRepres->tipo_pessoa = $cliente->tipo_pessoa;
                    $histCliRepres->agente_regular_anp = $cliente->agente_regularanp;
                    $histCliRepres->cep = preg_replace('/\D/', '', $cliente->cep);
                    $histCliRepres->dt_cadastro = $cliente->dt_cadastro;
                    $histCliRepres->dt_atualizacao = $cliente->dt_atualizacao;
                    $histCliRepres->dt_change = $cliente->dt_change;
                    $histCliRepres->prospeccao = 'N';
                    
                    $cadastro = new DateTime($cliente->dt_cadastro);
                    $abertura = new DateTime($metaAberta->data_abertura);
                    $inicial  = new DateTime($metaAberta->data_inicial);
                    $final    = new DateTime($metaAberta->data_final);
                    $mes_ano  = $metaAberta->mes.'/'.$metaAberta->ano;
                    
                    if($histCliRepres->agente_regular_anp == $mes_ano){
                    	$histCliRepres->tipo = "Reativado";                    	
                    }else if($cadastro >= $inicial && $cadastro <= $final){									
                    	$histCliRepres->tipo = 'Novo';
                    	$histCliRepres->prospeccao = 'S';
                    	
                    }else if($cadastro >= $abertura && $cadastro < $inicial){										
                    	$histCliRepres->tipo = 'Normal';
                    	$histCliRepres->prospeccao = 'S';
                    	
                    }else{
                    	$histCliRepres->tipo = 'Normal';
                    }
                    
                    $histCliRepres->store();
                    
                    $arrayHistorico[$histCliRepres->id] = $histCliRepres->cod_clifor;
                    
                    TTransaction::close();
                }
            }
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização de Clientes", __METHOD__, 0, "Histórico de Cliente do mês $metaAberta->mes registrado. $count clientes atualizados", "Arquivo: HistoricoClienteService.<br/>Linha: ".__LINE__.".",$sessao);
            
            HistoricoClienteService::marcarPertencentesGrupo();
            HistoricoClienteService::verificarProspeccaoGrupo();
            HistoricoClienteService::vincularPrincipalFiliais();
            
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização de Clientes", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }
    
    public static function marcarPertencentesGrupo(){
        try{
            
            TTransaction::open(self::$dbNw);
            $openTransaction = TTransaction::getDatabase() == self::$dbNw ? true : false;
            TTransaction::close();
            if(!$openTransaction){
                throw new Exception("Sem conexão com a base de dados '$dbNw', verifique o arquivo de configuração e tente novamente.");
            }
            
            $sessao = TSession::getValue('userunitid') ?? 1; 
            
            TTransaction::open(self::$dbAp);
            
            $metaAberta = self::get_meta_aberta();
            TTransaction::close();
            
            TTransaction::open(self::$dbNw);
            
            $conn = TTransaction::get();
            $result = $conn->query("
            SELECT 
                x.codigo as codigo
            FROM (
                SELECT 
                    cod_clifor AS codigo
                FROM 
                    clifor_filial
                UNION ALL
                SELECT 
                    cod_filial AS codigo
                FROM 
                    clifor_filial
            ) AS x,
                clifor c
            WHERE
                x.codigo = c.cod_clifor
                AND c.cod_grpcliente is not null
            GROUP BY 
                x.codigo
            ORDER BY 
                x.codigo");
                
            $result = $result->fetchAll(); 
            
            
            
            TTransaction::close();
            
            TTransaction::open(self::$dbAp);
            
            foreach($result as $resultado){
                HistoricoCliRepres::where('system_unit_id','=',$sessao)
                                ->where('mes','=',$metaAberta->mes)
                                ->where('ano','=',$metaAberta->ano)
                                ->where('cod_clifor','=',$resultado['codigo'])
                                ->set('grupo_empresarial','S')
                                ->set('prospeccao','N')
                                ->update();
            }
                        
            TTransaction::close();
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização de Clientes", __METHOD__, 0, "Grupo Empresarial marcado para clientes com grupo.", "Arquivo: HistoricoClienteService.<br/>Linha: ".__LINE__.".",$sessao);
            
        }catch(Exception $e){
            LogCrontab::registrarLog("Atualização de Clientes", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }
    
    public static function verificarProspeccaoGrupo(){
        try{
            TTransaction::open(self::$dbNw);
            $openTransaction = TTransaction::getDatabase() == self::$dbNw ? true : false;
            TTransaction::close();
            if(!$openTransaction){
                throw new Exception("Sem conexão com a base de dados '$dbNw', verifique o arquivo de configuração e tente novamente.");
            }
            
            $sessao = TSession::getValue('userunitid') ?? 1; 
            
            TTransaction::open(self::$dbAp);
            
            $metaAberta = self::get_meta_aberta();
            $clientes =  HistoricoCliRepres::where('system_unit_id','=',$sessao)
                                            ->where('mes','=',$metaAberta->mes)
                                            ->where('ano','=',$metaAberta->ano)
                                            ->where('grupo_empresarial','=','S')
                                            ->orderby('cod_clifor')
                                            ->load();
                                            
            $prospeccao = array();
            
            foreach($clientes as $cliente){
                $data_cadastro = new DateTime($cliente->dt_cadastro);  
                $data_abertura = new DateTime($metaAberta->data_abertura);  
                
                if ($data_cadastro >= $data_abertura) {  
                    $prospeccao[] = "'$cliente->cod_clifor'";
                } 
            }
            TTransaction::close();
            
            TTransaction::open(self::$dbNw);
            
            $conn = TTransaction::get();
            $sql = 'SELECT
                    	c.cod_clifor as "cod_principal",
                    	c.dt_cadastro as "cadastro_principal",
                    	c.ativo as "ativo_principal",
                    	f.cod_clifor as "cod_filial",
                    	f.dt_cadastro as "cadastro_filial",
                    	f.ativo as "ativo_filial"
                    FROM
                    	clifor c
                    	INNER JOIN clifor_filial v
                    		ON c.cod_clifor = v.cod_clifor
                    	LEFT JOIN  clifor f 
                    		ON f.cod_clifor = v.cod_filial
                    WHERE
                        c.cod_clifor in ('.implode(", ",$prospeccao).')
                    ORDER BY c.cod_clifor, f.cod_clifor';
                        
            $query = $conn->query($sql);
            
            $resultados = $query->fetchAll(PDO::FETCH_CLASS, "stdClass"); 
            
            TTransaction::close();
            
            $prospeccao = array();
            foreach($resultados as $resultado){
                $cadastro_principal = new DateTime($resultado->cadastro_principal);
                $cadastro_filial    = new DateTime($resultado->cadastro_filial);
                $data_abertura      = new DateTime($metaAberta->data_abertura);
                if($cadastro_principal >= $data_abertura && $cadastro_filial >= $data_abertura){
                    $prospeccao[] = $resultado->cod_principal;
                    $prospeccao[] = $resultado->cod_filial;
                }
            }
            sort($prospeccao);
            
            TTransaction::open(self::$dbAp);
            foreach(array_unique($prospeccao) as $cod_clifor){
                HistoricoCliRepres::where('cod_clifor','=',$cod_clifor)
                                    ->where('system_unit_id','=',(int) $sessao)
                                    ->where('mes','=',(int) $metaAberta->mes)
                                    ->where('ano','=',(int) $metaAberta->ano)
                                    ->set('prospeccao','S')
                                    ->update();
            }
            TTransaction::close();
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização de Clientes", __METHOD__, 0, "Verificação de prospecção realizada.", "Arquivo: HistoricoClienteService.<br/>Linha: ".__LINE__.".",$sessao);
            
        }catch(Exception $e){
            LogCrontab::registrarLog("Verificar prospecção de clientes com grupo", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }
    
    public static function vincularPrincipalFiliais(){
        try{
            TTransaction::open(self::$dbNw);
            $openTransaction = TTransaction::getDatabase() == self::$dbNw ? true : false;
            TTransaction::close();
            if(!$openTransaction){
                throw new Exception("Sem conexão com a base de dados '$dbNw', verifique o arquivo de configuração e tente novamente.");
            }
            
            $sessao = TSession::getValue('userunitid') ?? 1; 
            
            TTransaction::open(self::$dbAp);
            
            $metaAberta = self::get_meta_aberta();
            $clientes =  HistoricoCliRepres::where('system_unit_id','=',$sessao)
                                            ->where('mes','=',$metaAberta->mes)
                                            ->where('ano','=',$metaAberta->ano)
                                            ->where('grupo_empresarial','=','S')
                                            ->orderby('cod_clifor')
                                            ->load();
                                            
            $array_clientes = array();
            
            foreach($clientes as $cliente){
                $array_clientes[] = "'$cliente->cod_clifor'";
            }
            TTransaction::close();
            
            TTransaction::open(self::$dbNw);
            
            $conn = TTransaction::get();
            $sql =  "select 	
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
                    order by y.principal";
                        
            $query = $conn->query($sql);
            
            $resultados = $query->fetchAll(PDO::FETCH_CLASS, "stdClass"); 
            
            TTransaction::close();
            
            TTransaction::open(self::$dbAp);
            foreach($resultados as $dado){
                
                $filial = $dado->cod_clifor == $dado->cod_principal ? $filial = 'N' : $filial = 'S';
                
                HistoricoCliRepres::where('cod_clifor','=',$dado->cod_clifor)
                                    ->where('system_unit_id','=',(int) $sessao)
                                    ->where('mes','=',(int) $metaAberta->mes)
                                    ->where('ano','=',(int) $metaAberta->ano)
                                    ->set('cod_principal',$dado->cod_principal)
                                    ->set('filial',$filial)
                                    ->update();
            }
            TTransaction::close();
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização de Clientes", __METHOD__, 0, "Marcado principal das filiais ativas.", "Arquivo: HistoricoClienteService.<br/>Linha: ".__LINE__.".",$sessao);
            
        }catch(Exception $e){
            LogCrontab::registrarLog("Vincular principais e filiais", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }

    public static function buscarCoordenadasClientes(){
        try{
            $sessao = TSession::getValue('userunitid') ?? 1; 
            
            TTransaction::open(self::$dbAp);
            
            $metaAberta = self::get_meta_aberta();

            $conn = TTransaction::get();
            $result = $conn->query("
                SELECT 
                    h.id as id,
                    h.cod_clifor as cod_clifor,
                    h.latitude as latitude,
                    h.longitude as longitude,
                    h.cep as cep,
                    c.nome as cidade,
                    e.nome as estado
                FROM 
                    historico_cli_repres h
                    LEFT JOIN ap_cidade c ON h.ap_cidade_id = c.id 
                    LEFT JOIN ap_estado e ON c.estado_id = e.id
                WHERE
                    h.mes = {$metaAberta->mes}
                    AND h.ano = {$metaAberta->ano}
            ");
            $clientes = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");

            $msg = '';

            foreach ($clientes as $cliente) {
                $cep = preg_replace('/\D/', '', $cliente->cep);
                $coordenadas = CepCoordenadasService::cepParaCoordenadas($cep);

                $histCliRepres = HistoricoCliRepres::find($cliente->id);
                $mensagem = '';

                if ($coordenadas && !empty($coordenadas['lat']) && !empty($coordenadas['lon'])) {
                    $histCliRepres->latitude  = $coordenadas['lat'];
                    $histCliRepres->longitude = $coordenadas['lon'];
                } else {
                    $mensagem = "01. Coordenadas por CEP não encontradas";

                    if ($cliente->cidade && $cliente->estado) {
                        $coordenadas = CepCoordenadasService::cidadeParaCoordenadas($cliente->cidade, $cliente->estado);

                        if ($coordenadas && !empty($coordenadas['lat']) && !empty($coordenadas['lon'])) {
                            $histCliRepres->latitude  = $coordenadas['lat'];
                            $histCliRepres->longitude = $coordenadas['lon'];
                        } else {
                            $mensagem = "02. Coordenadas por cidade/estado não encontradas";
                        }
                    } else {
                        $mensagem = "03. Coordenadas não encontradas";
                    }
                }

                if(empty($histCliRepres->latitude) && empty($histCliRepres->longitude)){
                    $msg .= $mensagem;
                }
            }
            TTransaction::close();

            if ($msg !== '') {
                LogCrontab::enviarAppChat("Avisos: {$msg}");
            }

            //Registro de log de execução
            LogCrontab::registrarLog("Atualização de Clientes", __METHOD__, 0, "Busca de coordenadas.", "Arquivo: HistoricoClienteService.<br/>Linha: ".__LINE__.".",$sessao);
            
        }catch(Exception $e){
            LogCrontab::registrarLog("Busca de coordenadas", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }
}
