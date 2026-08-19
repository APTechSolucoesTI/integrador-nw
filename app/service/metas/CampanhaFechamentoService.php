<?php

class CampanhaFechamentoService
{
    private static $dbNw = 'nw';
    private static $dbAp = 'integrador';

    /**
     * Fecha uma campanha conforme o tipo:
     *  - tipo_id = 1 (Ranking)
     *  - tipo_id = 2 (Quantidade)
     */

    public static function fecharCampanha($campanha_id)
    {
        try {
            TTransaction::open(self::$dbAp);
            $campanha = Campanha::find($campanha_id);
            TTransaction::close();

            if (!$campanha) {
                throw new Exception('Campanha não encontrada');
            }

            $tipo = $campanha->campanha_tipo_id;

            if ($tipo === 2) {
                self::obterCampanhaQtd($campanha_id);
            } else if ($tipo === 1) {
                self::preencherQuantidadeRankingCampanha($campanha_id);
                self::verificaPremioProspeccaoReativacao($campanha_id);
            }
        } catch (Exception $e) {
            LogCrontab::registrarLog("Campanha", __METHOD__, 1, $e->getMessage(), "Arquivo: ".__CLASS__.".<br/>Linha: ".__LINE__.".");
        }

    }
            /**
            * TIPO 2 CAMPANHA POR (Quantidade): 
            */

        public static function obterCampanhaQtd($campanha_id){
           
            try {
            $sessao = TSession::getValue('userunitid') ?? 1;

            /* ===================== 1) Buscar campanha e dados ===================== */
            TTransaction::open(self::$dbAp);

            $campanha = Campanha::find($campanha_id);
            if (!$campanha) {
                throw new Exception('Campanha não encontrada');
            }

            // MAP APONTANDO para Cód do Representante
            $arrayRepres = ApRepresentante::where('system_unit_id', '=', $sessao)
                ->getIndexedArray('id', 'cod_repres');

            // IMPORTANTE: Buscar TODOS os representantes que devem participar da campanha
            $todosRepresentantes = ApRepresentante::where('system_unit_id', '=', $sessao)->load();

            //Itens Inseridos na Campanha
            $itensDaCampanha = [];
            foreach ($campanha->getCampanhaItems() as $item) {
                $itensDaCampanha[] = trim($item->cod_item);
            }

            //Aqui se faz a busca por Tabelas de preço pertencentes a essa campanha que fazparte = 'S'
            $conn = TTransaction::get();
            $rsTabs = $conn->query("
                SELECT ap.cod_tabelapreco
                FROM campanha_tabela_preco ctp
                JOIN ap_tabela_preco ap ON ap.id = ctp.ap_tabela_preco_id
                WHERE ctp.campanha_id = {$campanha_id}
                AND ctp.fazparte = 'S'
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

            //Variáveis Básicas setadas a partir de informação recebida pelo CampanhaForm
            $dtInicial        = $campanha->data_inicial;
            $dtFinal          = $campanha->data_final;
            $valorMinUnitario = ($campanha->valor_unitario_min ?? 0);
            $minimo           = ($campanha->min ?? 0);
            $premioUni        = ($campanha->qtde ?? 0);

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

           
           // echo "<pre>SQL Query: " . $sql . "</pre>";
            
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
            // $conn->query("DELETE FROM campanha_fechamento WHERE campanha_id = {$campanha_id}");

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
                foreach ($listaVendas as $venda) {
                    if (in_array(trim($venda->cod_item), $itensDaCampanha)) {
                        $totalVendida += floatval($venda->quantidade);
                    }
                }

                $premioTotal = 0.0;
                $alcancou = 'N';
                if ($totalVendida >= $minimo) {
                    $premioTotal = $totalVendida * $premioUni;
                    $alcancou = 'S';
                }
              

                // Verificar se já existe fechamento nesse ID da campanha
                $fechamentoExistente = CampanhaFechamento::where('campanha_id', '=', $campanha_id)
                                                        ->where('ap_representante_id', '=', $repres_id)
                                                        ->first();
                
                if ($fechamentoExistente) {
                    // Se tem atualiza o existente
                    $fechamento = $fechamentoExistente;
                } else {
                    // Se não tem cria um novo
                    $fechamento = new CampanhaFechamento;
                    $fechamento->campanha_id = $campanha_id;
                    $fechamento->ap_representante_id = $repres_id;
                }

                $fechamento->premio = $premioTotal;
                $fechamento->alcancou_quantidade = $alcancou;
                $fechamento->alcancou_ranking = '';
                $fechamento->quantidade_total = $totalVendida;


                $fechamento->store();

                
            }

            TTransaction::close();

        } catch (Exception $e) {
            if (TTransaction::isOpen()) {
                TTransaction::rollback();
            }
            
            return $e->getMessage() . "<br/>" . __METHOD__ . " Line: " . $e->getLine() . " Arquivo: " . $e->getFile();
        }
    }


    /**
     * TIPO 1 (Ranking): 
    */
    public static function verificaPremioRankingCampanha($campanha_id)
        {
            try {
                $sessao = TSession::getValue('userunitid') ?? 1;

                TTransaction::open(self::$dbAp);

                // Valida campanha
                $campanha = Campanha::find($campanha_id);
                if (!$campanha) {
                    throw new Exception("Campanha não encontrada.");
                }

                if ($campanha->campanha_tipo_id != 1) {
                    throw new Exception("Campanha não é do tipo Ranking.");
                }

                // Carrega prêmios do tipo RANKING
                $premios = CampanhaPremio::where('campanha_id', '=', $campanha_id)
                                        ->where('tipo_premio_id', '=', 1) // 1 = Ranking
                                        ->getIndexedArray('id', 'id');

                $regras = CampanhaPremioRegra::where('campanha_premio_id', 'in', $premios)
                                            ->orderby('premio', 'desc')
                                            ->load();

                // Fechamentos ordenados por quantidade/pontuação
                $fechamentos = CampanhaFechamento::where('campanha_id', '=', $campanha_id)
                                                ->orderby('quantidade_total', 'desc') // ou outro campo: 'pontuacao'
                                                ->load();

                $regrasAplicadas = [];

                foreach ($fechamentos as $fechamento) {
                    $fechamento->alcancou_ranking = 'N';
                    $fechamento->premio_ranking = 0;

                    foreach ($regras as $key => $regra) {
                        if (in_array($regra->id, $regrasAplicadas)) {
                            continue;
                        }

                        // Se quiser validar mínimo:
                        // if ($fechamento->quantidade_total < $regra->dado_0) continue;

                        // Aplica regra
                        $fechamento->alcancou_ranking = 'S';
                        $fechamento->premio_ranking = $regra->bonus;
                        $regrasAplicadas[] = $regra->id;
                        unset($regras[$key]);
                        break;
                    }

                    $fechamento->store();
                }

                TTransaction::close();
                return true;

            } catch (Exception $e) {
                return $e->getMessage() . "<br/>" . __METHOD__ . " Line: " . $e->getLine() . " Arquivo: " . $e->getFile();
            }
        }

    public static function preencherQuantidadeRankingCampanha($campanha_id)
    {
        try {
            TTransaction::open(self::$dbAp);

            $campanha = Campanha::find($campanha_id);
            if (!$campanha) {
                throw new Exception('Campanha não encontrada');
            }

            if ($campanha->campanha_tipo_id != 1) {
                throw new Exception('Campanha não é do tipo Ranking');
            }

            $sessao = TSession::getValue('userunitid') ?? 1;
            $arrayRepres = ApRepresentante::where('system_unit_id', '=', $sessao)->getIndexedArray('id', 'cod_repres');

            // Itens da campanha
            $itensCampanha = CampanhaItem::where('campanha_id', '=', $campanha_id)->getIndexedArray('cod_item', 'cod_item');

            // Tabelas de preço permitidas
          $tabsPreco = CampanhaTabelaPreco::where('campanha_id', '=', $campanha_id)
                                        ->where('fazparte', '=', 'S')
                                        ->join('ap_tabela_preco', 'ap_tabela_preco.id', '=', 'campanha_tabela_preco.ap_tabela_preco_id')
                                        ->pluck('ap_tabela_preco.cod_tabela_preco')  // coleta só os códigos
                                        ->toArray(); // vira array normal

            $tabsPermitidas = "('" . implode("','", array_map('addslashes', $tabsPreco)) . "')";

            $dtInicial = $campanha->data_inicial;
            $dtFinal = $campanha->data_final;
            $valorMinUnitario = $campanha->valor_unitario_min;

            TTransaction::close();

            // CONSULTA NO DBNW
            TTransaction::open(self::$dbNw);
            $conn = TTransaction::get();

            $result = $conn->query("

            
                SELECT  
                y.cod_repres,
                y.cod_item,
				y.cod_tabela_preco,
                SUM(y.quantidade_filtrada) AS quantidade

                FROM(
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

                    FROM(
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
                        TRIM(TAB.COD_TABELAPRECO)  AS cod_tabela_preco

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
                                (ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE + ITEM_NOTAFISCAL.VALOR_DESPESAS) AS VALOR_TOTAL,
                                ITEM_NOTAFISCAL.COD_TABELAPRECO AS COD_TABELAPRECO

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
                                AND NOTA_FISCAL.DT_EMISSAO >=  '{$dtInicial}'
								AND NOTA_FISCAL.DT_EMISSAO <=  '{$dtFinal}'
                                AND NOTA_FISCAL.ENTRADA_SAIDA = 'S'
                                AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '008'
                                AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001','99')
                                AND ITEM_NOTAFISCAL.COD_TABELAPRECO IN {$tabsPermitidas}
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
                                ((ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE + ITEM_NOTAFISCAL.VALOR_DESPESAS) * -1) AS VALOR_TOTAL,
                                ITEM_NOTAFISCAL.COD_TABELAPRECO AS COD_TABELAPRECO

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
                                AND NOTA_FISCAL.DT_EMISSAO >=  '{$dtInicial}'
								AND NOTA_FISCAL.DT_EMISSAO <=  '{$dtFinal}'
                                AND NOTA_FISCAL.ENTRADA_SAIDA = 'E'
                                AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '012'
                                AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001','99')
                                AND ITEM_NOTAFISCAL.COD_TABELAPRECO IN {$tabsPermitidas}
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
                        TAB.VALOR_TOTAL,
                        TAB.COD_TABELAPRECO

                    ORDER BY
                        TAB.DATA_DE_EMISSAO,
                        TAB.NRO_NOTA_FISCAL,
                        TAB.SEQUENCIA
                    ) v
                ) y
                GROUP BY 
                    y.cod_repres, 
                    y.cod_item,
					y.cod_tabela_preco
            ");

            $vendas = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            TTransaction::close();

        // AGRUPA VENDAS POR REPRESENTANTE
        $vendasPorRepres = [];
        foreach ($vendas as $venda) {
            $repres = $venda->cod_repres;
            if (!isset($vendasPorRepres[$repres])) {
                $vendasPorRepres[$repres] = 0;
            }
            if (in_array($venda->cod_item, $itensCampanha)) {
                $vendasPorRepres[$repres] += $venda->quantidade;
            }
        }

        // PREENCHE CampanhaFechamento.quantidade_total
        TTransaction::open(self::$dbAp);
        foreach ($vendasPorRepres as $cod_repres => $quantidadeTotal) {
            $repres_id = array_search($cod_repres, $arrayRepres);
            if (!$repres_id) continue;

            $fechamento = CampanhaFechamento::where('campanha_id', '=', $campanha_id)
                                            ->where('repres_id', '=', $repres_id)
                                            ->first();
            if ($fechamento) {
                $fechamento->quantidade_total = $quantidadeTotal;
                $fechamento->store();
            }
        }
        TTransaction::close();

        return true;

    } catch (Exception $e) {
        return $e->getMessage() . "<br/>" . __METHOD__ . " Line: " . $e->getLine() . " Arquivo: " . $e->getFile();
    }
    }   
}
