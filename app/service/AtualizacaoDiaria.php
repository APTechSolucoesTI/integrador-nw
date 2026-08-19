<?php

class AtualizacaoDiaria
{

    /*
    atualizarGrupoEstoque
    atualizarRepresentantes
    atualizarFamiliaComercial
    atualizarFamiliaIndustrial
    atualizarGrupoCliente
    */
    // -- ATUALIZAÇÃO COMPLETA -- //
    public static function atualizarTudo(){
        AtualizacaoDiaria::atualizarCidades();
        AtualizacaoDiaria::atualizarGrupoEstoque();
        AtualizacaoDiaria::atualizarRepresentantes();
        AtualizacaoDiaria::atualizarFamiliaComercial();
        AtualizacaoDiaria::atualizarFamiliaIndustrial();
        AtualizacaoDiaria::atualizarGrupoCliente();
        AtualizacaoDiaria::atualizarItens();
        AtualizacaoDiaria::atualizarTabelaPreco();
        
        TTransaction::open('log');
        SystemSqlLog::where('id','>',0)->delete();
        TTransaction::close();
    }
    
    // ------------------------------------- CIDADE ------------------------------------- //
    public static function atualizarCidades(){
        try{
            TTransaction::open('nw');
            $conn = TTransaction::get();
            $result = $conn->query('
                SELECT 
                	trim(cidade.cod_cidade) as cod_cidade, 
                	trim(cidade.descricao) as cidade,
                	trim(cidade_ibge.cod_estado) as cod_estado,
                	trim(estado.descricao) as estado
                FROM 
                	public.cidade, 
                	public.estado,
                	public.cidade_ibge
                WHERE
                	cidade.cod_ibge = cidade_ibge.cod_cidadeibge
                	AND cidade_ibge.cod_estado = estado.cod_estado
                ORDER BY
                	cidade.cod_cidade;
            ');
            
            $nw_cidades = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            TTransaction::close();
            
            
            TTransaction::open('integrador');
            
            $array_estado = ApEstado::where('cod_estado','is not',null)->getIndexedArray('id','cod_estado');
            
            foreach($nw_cidades as $nw_cidade){
                $cidade = ApCidade::where('cod_cidade','like',$nw_cidade->cod_cidade)->first() ?? new ApCidade();
                
                $cidade->cod_cidade = $nw_cidade->cod_cidade;
                $cidade->nome = $nw_cidade->cidade;
                $cidade->estado_id = array_search($nw_cidade->cod_estado, $array_estado) ?? null;
                $cidade->store();
            }
            
            TTransaction::close();
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização Diária", __METHOD__, 0, "Cidades atualizadas.", "Arquivo: AtualizacaoDiaria.\nLinha: ".__LINE__.".");
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização Diária", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "\nLinha: " . $e->getLine() . "\n");
        }
    }
    
    // -------------------------------- ITENS -------------------------------- //
        public static function atualizarItens()
        {
            try {
                // 1) Lê NW
                TTransaction::open('nw');
                $connNw = TTransaction::get();

                $stmt = $connNw->prepare("
                    SELECT 
                        trim(cod_item) as cod_item,
                        trim(cod_clifor) as cod_clifor,
                        trim(cod_subgrupoestoque) as cod_subgrupoestoque, 
                        trim(cod_grupoestoque) as cod_grupoestoque,
                        trim(cod_fmcomercial) as cod_fmcomercial, 
                        trim(cod_fmindustrial) as cod_fmindustrial, 
                        trim(codigo)  as codigo,
                        trim(descricao) as descricao,
                        trim(cod_unidade) as cod_unidade,
                        trim(ativo) as ativo
                    FROM public.item
                ");
                $stmt->execute();

                // 2) Abre integrador uma vez e monta mapas
                TTransaction::close();

                TTransaction::open('integrador');
                $system_unit_id = TSession::getValue('userunitid') ?? 1;

                $mapGrupo = [];
                foreach (ApGrupoEstoque::where('system_unit_id', '=', $system_unit_id)->load() as $g) {
                    $mapGrupo[trim($g->cod_grupoestoque)] = (int) $g->id;
                }

                $mapSub = [];
                foreach (ApSubgrupoEstoque::where('system_unit_id', '=', $system_unit_id)->load() as $s) {
                    $k = trim($s->cod_grupoestoque) . '|' . trim($s->cod_subgrupoestoque);
                    $mapSub[$k] = (int) $s->id;
                }

                $mapFmCom = ApFamiliaComercial::where('system_unit_id', '=', $system_unit_id)
                    ->getIndexedArray('cod_fmcomercial', 'id');

                $mapFmInd = ApFamiliaIndustrial::where('system_unit_id', '=', $system_unit_id)
                    ->getIndexedArray('cod_fmindustrial', 'id');

                // 3) Processa em lote, sem fechar transação por item
                $count = 0;
                while ($nw_item = $stmt->fetch(PDO::FETCH_OBJ)) {

                    $item = ApItem::where('cod_item', '=', $nw_item->cod_item)
                        ->where('codigo', '=', $nw_item->codigo)
                        ->first();

                    if (!$item) {
                        $item = new ApItem();
                    }

                    $item->cod_item   = $nw_item->cod_item;
                    $item->cod_clifor = $nw_item->cod_clifor;

                    $item->grupo_estoque_id = $mapGrupo[trim($nw_item->cod_grupoestoque)] ?? null;

                    $kSub = trim($nw_item->cod_grupoestoque) . '|' . trim($nw_item->cod_subgrupoestoque);
                    $item->subgrupo_estoque_id = $mapSub[$kSub] ?? null;

                    $item->familia_comercial_id  = $mapFmCom[trim($nw_item->cod_fmcomercial)] ?? null;
                    $item->familia_industrial_id = $mapFmInd[trim($nw_item->cod_fmindustrial)] ?? null;

                    $item->codigo      = $nw_item->codigo;
                    $item->descricao   = $nw_item->descricao;
                    $item->cod_unidade = $nw_item->cod_unidade;
                    $item->ativo       = $nw_item->ativo;

                    $item->store();

                    $count++;

                    if (($count % 2000) === 0) {
                        TTransaction::close();
                        TTransaction::open('integrador');
                    }
                }

                TTransaction::close();

                LogCrontab::registrarLog("Atualização Diária", __METHOD__, 0, "Itens atualizados.", "Linha: ".__LINE__.".");
            }
            catch (Exception $e) {
                if (TTransaction::get()) {
                    TTransaction::rollback();
                }
                LogCrontab::registrarLog("Atualização Diária", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "\nLinha: " . $e->getLine() . "\n");
            }
        }
    
    
    // -------------------------------- GRUPO DE CLIENTE -------------------------------- //
    
    public static function atualizarGrupoCliente(){
        try{
            TTransaction::open('nw');
            $coon = TTransaction::get();
            $result = $coon->query('
                SELECT 
                    trim(cod_grpcliente) as cod_grpcliente,
                    trim(descricao) as descricao
                FROM 
                    public.grupo_cliente
            ');
            $nw_grupos = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            TTransaction::close();
            
            foreach($nw_grupos as $nw_grupo)
            {
                
                TTransaction::open('integrador');
                $grupo = ApGrupoCliente::where('cod_grpcliente', 'like', $nw_grupo->cod_grpcliente)
                                   ->where('system_unit_id', '=', TSession::getValue('userunitid') ?? 1)
                                   ->first() ?? new ApGrupoCliente();
                
                $grupo->system_unit_id = TSession::getValue('userunitid') ?? 1;
        		$grupo->cod_grpcliente = trim($nw_grupo->cod_grpcliente);
        		$grupo->descricao = trim($nw_grupo->descricao);
        		$grupo->pontuacao = $grupo->pontuacao;
        		$grupo->valor_inicial = $grupo->valor_inicial;
        		$grupo->valor_final = $grupo->valor_final;
        		
        		$grupo->store();
        		
        		TTransaction::close();
            }
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização Diária", __METHOD__, 0, "Categorias atualizadas.", "Arquivo: AtualizacaoDiaria.\nLinha: ".__LINE__.".");
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização Diária", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "\nLinha: " . $e->getLine() . "\n");
        }
    }
    
    
    // -------------------------------- GRUPO DE ESTOQUE -------------------------------- //
    public static function atualizarGrupoEstoque(){
    try {
        $system_unit_id = TSession::getValue('userunitid') ?? 1;

        // =========================
        // GRUPO DE ESTOQUE (NW)
        // =========================
        TTransaction::open('nw');
        $coon = TTransaction::get();

        $result = $coon->query("
            SELECT 
                trim(cod_grupoestoque) as cod_grupoestoque,
                trim(descricao)        as descricao,
                seq_exibicao           as seq_exibicao
            FROM grupo_estoque
        ");
        $grupos_estoque = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
        TTransaction::close();

        // =========================
        // GRUPO DE ESTOQUE (INTEGRADOR)
        // =========================
        TTransaction::open('integrador');

        // mapa pra usar no subgrupo depois
        $mapGrupoCodToId = [];

        foreach ($grupos_estoque as $grupo_estoque) {

            if (empty($grupo_estoque->cod_grupoestoque)) {
                continue;
            }

            $ap_grupo_estoque = ApGrupoEstoque::where('cod_grupoestoque', '=', $grupo_estoque->cod_grupoestoque)
                ->where('system_unit_id', '=', $system_unit_id)
                ->first() ?? new ApGrupoEstoque();

            $ap_grupo_estoque->system_unit_id       = $system_unit_id;
            $ap_grupo_estoque->cod_grupoestoque     = $grupo_estoque->cod_grupoestoque;
            $ap_grupo_estoque->descricao            = $grupo_estoque->descricao;
            $ap_grupo_estoque->sequencia_exibicao   = $grupo_estoque->seq_exibicao;
            $ap_grupo_estoque->store();

            $mapGrupoCodToId[trim((string)$grupo_estoque->cod_grupoestoque)] = $ap_grupo_estoque->id;
        }

        TTransaction::close();

        // =========================
        // SUBGRUPO DE ESTOQUE (NW)
        // =========================
        TTransaction::open('nw');
        $coon = TTransaction::get();

        $result = $coon->query("
            SELECT 
                trim(cod_grupoestoque)     as cod_grupoestoque,
                trim(cod_subgrupoestoque)  as cod_subgrupoestoque,
                trim(descricao)            as descricao
            FROM subgrupo_estoque
        ");
        $subgrupos_estoque = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
        TTransaction::close();

        // =========================
        // SUBGRUPO DE ESTOQUE (INTEGRADOR)
        // =========================
        TTransaction::open('integrador');

        foreach ($subgrupos_estoque as $subgrupo_estoque) {

            $codGrupo = trim((string)$subgrupo_estoque->cod_grupoestoque);
            $codSub   = trim((string)$subgrupo_estoque->cod_subgrupoestoque);

            if ($codGrupo === '' || $codSub === '') {
                continue;
            }

            $grupoId = $mapGrupoCodToId[$codGrupo] ?? null;

            if (!$grupoId) {
                continue;
            }

            $ap_subgrupo_estoque = ApSubgrupoEstoque::where('system_unit_id', '=', $system_unit_id)
                ->where('cod_grupoestoque', '=', $codGrupo)
                ->where('cod_subgrupoestoque', '=', $codSub)
                ->first() ?? new ApSubgrupoEstoque();

            $ap_subgrupo_estoque->system_unit_id        = $system_unit_id;
            $ap_subgrupo_estoque->grupo_estoque_id      = $grupoId;
            $ap_subgrupo_estoque->cod_grupoestoque      = $codGrupo;
            $ap_subgrupo_estoque->cod_subgrupoestoque   = $codSub;
            $ap_subgrupo_estoque->descricao             = $subgrupo_estoque->descricao;
            $ap_subgrupo_estoque->store();

        }

        TTransaction::close();

        // Registro de log
        LogCrontab::registrarLog(
            "Atualização Diária",
            __METHOD__,
            0,
            "Grupos e Subgrupos de Estoque atualizados.",
            "Arquivo: AtualizacaoDiaria.\nLinha: ".__LINE__."."
        );

        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização Diária", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "\nLinha: " . $e->getLine() . "\n");
        }
    }
    
    // ---------------------------------- REPRESENTANTE --------------------------------- //
    public static function atualizarRepresentantes(){
        try{
            TTransaction::open('nw');
            $coon = TTransaction::get();
            $result = $coon->query('
                SELECT 
                    trim(cod_repres) as cod_repres,
                    trim(ativo) as ativo,
                    trim(email) as email,
                    trim(razao) as razao,
                    trim(fantasia) as fantasia
                FROM 
                    representante
            ');
        	$nw_representantes = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
        	TTransaction::close();
        	foreach($nw_representantes as $nw_representante)
            {
                TTransaction::open('integrador');
            	$repres = ApRepresentante::where('cod_repres','like',$nw_representante->cod_repres)->where('system_unit_id','=',TSession::getValue('userunitid') ?? 1)->first() ?? new ApRepresentante();
            	
        		$repres->system_unit_id = TSession::getValue('userunitid') ?? 1;
        		$repres->cod_repres = $nw_representante->cod_repres;
        		$repres->ativo = $nw_representante->ativo;
        		$repres->email = $nw_representante->email;
        		$repres->razao = strtoupper($nw_representante->razao);
        		$repres->fantasia = $nw_representante->fantasia;
        		$repres->store();
        		TTransaction::close();
            }
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização Diária", __METHOD__, 0, "Consultores(as) atualizados(as).", "Arquivo: AtualizacaoDiaria.\nLinha: ".__LINE__.".");
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização Diária", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "\nLinha: " . $e->getLine() . "\n");
        }
    }
    
    // ---------------------------------- FAMILIA COMERCIAL --------------------------------- //
    public static function atualizarFamiliaComercial(){
        try{
            TTransaction::open('nw');
            $coon = TTransaction::get();
            $result = $coon->query('
                SELECT 
                    trim(cod_fmcomercial) as cod_fmcomercial,
                    trim(descricao) as descricao
                FROM 
                    familia_comercial
            ');
            $nw_familias = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            TTransaction::close();
            foreach($nw_familias as $nw_familia)
            {
                TTransaction::open('integrador');
            	$familia = ApFamiliaComercial::where('cod_fmcomercial','like',$nw_familia->cod_fmcomercial)->where('system_unit_id','=',TSession::getValue('userunitid') ?? 1)->first() ?? new ApFamiliaComercial();
            	
        		$familia->system_unit_id = TSession::getValue('userunitid') ?? 1;
        		$familia->cod_fmcomercial = $nw_familia->cod_fmcomercial;
        		$familia->descricao = $nw_familia->descricao;
        		$familia->store();
        		TTransaction::close();
            }
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização Diária", __METHOD__, 0, "Familias Comerciais atualizadas.", "Arquivo: AtualizacaoDiaria.\nLinha: ".__LINE__.".");
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização Diária", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "\nLinha: " . $e->getLine() . "\n");
        }
    }
    
    // ---------------------------------- FAMILIA INDUSTRIAL --------------------------------- //
    public static function atualizarFamiliaIndustrial(){
        try{
            TTransaction::open('nw');
            $coon = TTransaction::get();
            $result = $coon->query('
                SELECT 
                    trim(cod_fmindustrial) as cod_fmindustrial,
                    trim(descricao) as descricao
                FROM 
                    familia_industrial
            ');
            $nw_familias = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            TTransaction::close();
            foreach($nw_familias as $nw_familia)
            {
                TTransaction::open('integrador');
            	$familia = ApFamiliaIndustrial::where('cod_fmindustrial','like',$nw_familia->cod_fmindustrial)->where('system_unit_id','=',TSession::getValue('userunitid') ?? 1)->first() ?? new ApFamiliaIndustrial();
            	
        		$familia->system_unit_id = TSession::getValue('userunitid') ?? 1;
        		$familia->cod_fmindustrial = $nw_familia->cod_fmindustrial;
        		$familia->descricao = $nw_familia->descricao;
        		$familia->store();
        		TTransaction::close();
            }
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização Diária", __METHOD__, 0, "Familias Industriais atualizadas.", "Arquivo: AtualizacaoDiaria.\nLinha: ".__LINE__.".");
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização Diária", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "\nLinha: " . $e->getLine() . "\n");
        }
    }

    // ------------------------------- TABELA PRECO ------------------------------- //
        public static function atualizarTabelaPreco(){
            
        try {
            $system_unit_id = TSession::getValue('userunitid') ?? 1;

            // Consulta no banco nw
            TTransaction::open('nw');
            $conn = TTransaction::get();
            $result = $conn->query('
                SELECT 
                    trim(cod_tabelapreco) as cod_tabelapreco,
                    trim(descricao) as descricao,
                    dt_valfim
                FROM 
                    tabela_preco
            ');
            $nw_tabelas = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            TTransaction::close();

            // Loop de sincronização
            foreach ($nw_tabelas as $nw_tabela) {
                TTransaction::open('integrador');

                // Verifica se já existe
                $tabelaExistente = ApTabelaPreco::where('cod_tabelapreco', '=', $nw_tabela->cod_tabelapreco)
                                                ->where('system_unit_id', '=', $system_unit_id)
                                                ->first();

                // Se já existir, pula
                if ($tabelaExistente) {
                    TTransaction::close();
                    continue;
                }

                // Se não existir, insere
                $tabela = new ApTabelaPreco();
                $tabela->system_unit_id = $system_unit_id;
                $tabela->cod_tabelapreco = $nw_tabela->cod_tabelapreco;
                $tabela->descricao = $nw_tabela->descricao;

                // Ativo sempre S por enquanto
                $tabela->ativo = 'S';

                // Se necssario usar a lógica do dt_valfim depois, descomenta isso:
                // $agora = date("Y-m-d H:i:s");
                // $tabela->ativo = (is_null($nw_tabela->dt_valfim) || $nw_tabela->dt_valfim > $agora) ? 'S' : 'N';

                $tabela->store();
                TTransaction::close();
            }

                LogCrontab::registrarLog("Atualização Diária", __METHOD__, 0, "Tabelas de Preço atualizadas.", "Arquivo: AtualizacaoDiaria.\nLinha: ".__LINE__.".");
            } catch (Exception $e) {
                LogCrontab::registrarLog("Atualização Diária", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "\nLinha: " . $e->getLine() . "\n");
            }
    }
}
