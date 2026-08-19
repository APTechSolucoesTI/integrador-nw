<?php

class MetaImportService
{
    private static $dbNw = 'nw';
    private static $dbAp = 'integrador';
    private static $dbSite = 'site';

    public static function obterMetaImport($meta_import_id)
    {
        try {
            $sessao = TSession::getValue('userunitid') ?? 1;

            /* ===================== 1) Buscar MetaImport ===================== */
            TTransaction::open(self::$dbAp);

            $metaImport = MetaImport::find($meta_import_id);
            if (!$metaImport) {
                throw new Exception('Meta Import não encontrada');
            }

            // Validar se a meta está aberta
            if ($metaImport->status != 1) {
                throw new Exception('Meta Import não está aberta');
            }

            // Carregar apenas representantes vinculados na meta_import_repres
            $repsVinculados = MetaImportRepres::where('meta_import_id', '=', $meta_import_id)->load();

            // Variáveis de período
            $dtInicial = $metaImport->data_inicial;
            $dtFinal   = $metaImport->data_final;

            TTransaction::close();

            /* ===================== 2) Buscar vendas (NO NW) ===================== */
            TTransaction::open(self::$dbNw);
            $conn = TTransaction::get();

            $sql = "SELECT 
                        y.cod_repres, 
                        y.cod_item, 
                        SUM(y.item_quantidade) AS quantidade 
                        FROM 
                        (
                            SELECT 
                            v.cod_repres, 
                            v.cod_item, 
                            v.cod_tabela_preco, 
                            v.item_quantidade 
                            FROM 
                            (
                                SELECT 
                                TRIM(TAB.COD_REPRES) AS cod_repres, 
                                TRIM(TAB.CODIGO_ITEM) AS cod_item, 
                                TRIM(TAB.COD_TABELAPRECO) AS cod_tabela_preco, 
                                TAB.QTDE AS item_quantidade, 
                                TAB.VALOR_TOTAL AS item_valor_total 
                                FROM 
                                (
                                    SELECT 
                                    CASE WHEN COALESCE(
                                        TRIM(ITEM.APLICACAO), 
                                        ''
                                    ) = 'X' THEN 'IMPORT' WHEN COALESCE(
                                        TRIM(GRUPO_ESTOQUE.AUXILIAR_STRING1), 
                                        ''
                                    ) = 'FP' THEN 'PERSIANAS' ELSE 'DECOR' END AS EMPRESA, 
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
                                    (
                                        ITEM_NOTAFISCAL.QUANTIDADE * ITEM_NOTAFISCAL.VALOR_UNITARIO
                                    ) AS VALOR_MERCADORIA, 
                                    ITEM_NOTAFISCAL.PERC_DESCTO AS PERC_DESCONTO, 
                                    ITEM_NOTAFISCAL.VALOR_DESCTO AS VALOR_DESCTO, 
                                    (
                                        ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE + ITEM_NOTAFISCAL.VALOR_DESPESAS
                                    ) AS VALOR_TOTAL, 
                                    ITEM_NOTAFISCAL.COD_TABELAPRECO AS COD_TABELAPRECO 
                                    FROM 
                                    NOTA_FISCAL 
                                    INNER JOIN ITEM_NOTAFISCAL ON (
                                        ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA 
                                        AND ITEM_NOTAFISCAL.SERIE = NOTA_FISCAL.SERIE 
                                        AND ITEM_NOTAFISCAL.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR 
                                        AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL
                                    ) 
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
                                    LEFT JOIN SUBGRUPO_ESTOQUE ON (
                                        SUBGRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE 
                                        AND SUBGRUPO_ESTOQUE.COD_SUBGRUPOESTOQUE = ITEM.COD_SUBGRUPOESTOQUE
                                    ) 
                                    LEFT JOIN FAMILIA_INDUSTRIAL ON FAMILIA_INDUSTRIAL.COD_FMINDUSTRIAL = ITEM.COD_FMINDUSTRIAL 
                                    WHERE 
                                    NOTA_FISCAL.COD_EMPRESA = '101' 
                                    AND NOTA_FISCAL.SITUACAO <> 'C' 
                                    AND NOTA_FISCAL.DT_EMISSAO >= '{$dtInicial}' 
                                    AND NOTA_FISCAL.DT_EMISSAO <= '{$dtFinal}' 
                                    AND NOTA_FISCAL.ENTRADA_SAIDA = 'S' 
                                    AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '008' 
                                    AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001', '99') 
                                    UNION ALL 
                                    SELECT 
                                    CASE WHEN COALESCE(
                                        TRIM(ITEM.APLICACAO), 
                                        ''
                                    ) = 'X' THEN 'IMPORT' WHEN COALESCE(
                                        TRIM(GRUPO_ESTOQUE.AUXILIAR_STRING1), 
                                        ''
                                    ) = 'FP' THEN 'PERSIANAS' ELSE 'DECOR' END AS EMPRESA, 
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
                                    (ITEM_NOTAFISCAL.QUANTIDADE * -1) AS QTDE, 
                                    ITEM_NOTAFISCAL.VALOR_UNITARIO AS VLR_UNITARIO, 
                                    (
                                        ITEM_NOTAFISCAL.QUANTIDADE * ITEM_NOTAFISCAL.VALOR_UNITARIO 
                                    ) AS VALOR_MERCADORIA, 
                                    ITEM_NOTAFISCAL.PERC_DESCTO AS PERC_DESCONTO, 
                                    (ITEM_NOTAFISCAL.VALOR_DESCTO) AS VALOR_DESCTO, 
                                    (
                                        (
                                        ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE + ITEM_NOTAFISCAL.VALOR_DESPESAS
                                        ) * -1
                                    ) AS VALOR_TOTAL, 
                                    ITEM_NOTAFISCAL.COD_TABELAPRECO AS COD_TABELAPRECO 
                                    FROM 
                                    NOTA_FISCAL 
                                    INNER JOIN ITEM_NOTAFISCAL ON (
                                        ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA 
                                        AND ITEM_NOTAFISCAL.SERIE = NOTA_FISCAL.SERIE 
                                        AND ITEM_NOTAFISCAL.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR 
                                        AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL
                                    ) 
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
                                    LEFT JOIN SUBGRUPO_ESTOQUE ON (
                                        SUBGRUPO_ESTOQUE.COD_GRUPOESTOQUE = ITEM.COD_GRUPOESTOQUE 
                                        AND SUBGRUPO_ESTOQUE.COD_SUBGRUPOESTOQUE = ITEM.COD_SUBGRUPOESTOQUE
                                    ) 
                                    LEFT JOIN FAMILIA_INDUSTRIAL ON FAMILIA_INDUSTRIAL.COD_FMINDUSTRIAL = ITEM.COD_FMINDUSTRIAL 
                                    WHERE 
                                    NOTA_FISCAL.COD_EMPRESA = '101' 
                                    AND NOTA_FISCAL.SITUACAO <> 'C' 
                                    AND NOTA_FISCAL.DT_EMISSAO >= '{$dtInicial}' 
                                    AND NOTA_FISCAL.DT_EMISSAO <= '{$dtFinal}' 
                                    AND NOTA_FISCAL.ENTRADA_SAIDA = 'E' 
                                    AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '012' 
                                    AND NOTA_FISCAL.COD_REPRES NOT IN ('0000001', '99')
                                ) TAB
                            ) v
                        ) y 
                        GROUP BY 
                        y.cod_repres, 
                        y.cod_item";

            $vendas = $conn->query($sql)->fetchAll(PDO::FETCH_CLASS, "stdClass");

            TTransaction::close();

            /* ===================== 3) Carregar itens vinculados à meta ===================== */
            TTransaction::open(self::$dbAp);
            $itensVinculados = MetaImportItem::where('meta_import_id', '=', $meta_import_id)
                ->getIndexedArray('id', 'cod_item');
            TTransaction::close();

            /* ===================== 4) Agrupar vendas por representante, batendo com os itens ===================== */
            $vendasPorRepres = [];
            foreach ($vendas as $venda) {
                $repres = trim($venda->cod_repres);
                $codItem = trim($venda->cod_item);

                // só acumula se o item estiver na meta
                if (in_array($codItem, $itensVinculados)) {
                    if (!isset($vendasPorRepres[$repres])) {
                        $vendasPorRepres[$repres] = 0;
                    }
                    $vendasPorRepres[$repres] += floatval($venda->quantidade);
                }
            }

            /* ===================== 4) Processar reps vinculados e salvar fechamento ===================== */
            TTransaction::open(self::$dbAp);

            foreach ($repsVinculados as $mir) {
                $repres_id   = $mir->ap_representante_id;
                $cod_repres  = $mir->ap_representante->cod_repres; // relacionamento
                $quantidadeVendida = $vendasPorRepres[$cod_repres] ?? 0.0;

                // Verifica se já existe fechamento
                $fechamento = MetaImportFechamento::where('meta_import_id', '=', $meta_import_id)
                    ->where('ap_representante_id', '=', $repres_id)
                    ->first();

                if (!$fechamento) {
                    $fechamento = new MetaImportFechamento;
                    $fechamento->meta_import_id       = $meta_import_id;
                    $fechamento->ap_representante_id  = $repres_id;
                    $fechamento->meta_import_repres_id = $mir->id; // vínculo direto
                }

                // alvo cadastrado na meta_import_repres (qtd planejada do consultor)
                $alvoQtd = (float) ($mir->qtd ?? 0);

                // calcula o percentual alcançado sobre a meta de quantidade
                // regra: se alvoQtd > 0 => (vendida / alvo) * 100; se alvoQtd <= 0 => 0
                $percentualQtd = ($alvoQtd > 0)
                    ? round(($quantidadeVendida / $alvoQtd) * 100, 2)
                    : 0.0;

                // grava campos no fechamento
                $fechamento->qtd_total       = $quantidadeVendida;
                $fechamento->percentual_qtd  = $percentualQtd;

                $fechamento->store();
            }

            TTransaction::close();

            return true;

        } catch (Exception $e) {
            return $e->getMessage(). "<br/>".__METHOD__." Line: ".$e->getLine()." Arquivo: " . $e->getFile();
        }
    }

    public static function fechamentoAutomaticoCrontab()
    {
    try
    {
        $sessao = TSession::getValue('userunitid') ?? 1;
        $erros  = [];

        /*
         * PASSO 1 - Fechamento padrão do mês
         * (obterValorVendido, verificarBonusComissao, verificaPremioProspeccaoReativacao)
         */
        self::fechar();

        /*
         * PASSO 2 - Obter meta aberta novamente, para saber qual meta é a "atual"
         * e validar se a data_final já passou.
         */
        $metaAberta = self::get_meta_aberta();

        if (!$metaAberta) {
            LogCrontab::registrarLog(
                "Fechamento automático do mês",
                __METHOD__,
                0,
                "Nenhuma meta aberta encontrada . Nada a processar.",
                "Arquivo: " . __CLASS__ . ".<br/>Linha: " . __LINE__ . ".",
                $sessao
            );
            return;
        }

        $agora      = new DateTime();
        $data_final = new DateTime($metaAberta->data_final);

        // Se a meta ainda não venceu, não roda fechamento automático
        if ($data_final > $agora) {
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

        /*
         * PASSO 3 - Processar todos os ST (MiniMeta) abertos da meta atual
         *   - status = 1
         *   - meta_id = meta atual
         * Se não tiver nada cadastrado, não faz nada.
         */
        TTransaction::open(self::$dbAp);
        $miniMetasAbertas = MiniMeta::where('meta_id', '=', $metaId)
                                    ->where('status', '=', 1)
                                    ->load();
        TTransaction::close();

        if ($miniMetasAbertas) {
            foreach ($miniMetasAbertas as $miniMeta) {

                // Chama o obterST para esse ST específico
                $retSt = self::obterST($miniMeta->id);

                //  se der erro ele retorna string, se der certo não retorna nada (null)
                if (is_string($retSt) && trim($retSt) !== '') {
                    $erros[] = "Erro ao processar ST (MiniMeta {$miniMeta->id}): {$retSt}";
                    continue;
                }

                // Se chegou aqui, considera ST processado com sucesso e fecha o ST (status = 2)
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
            * PASSO 4 - Processar MetaImport vinculadas à meta atual
            * Mesma ideia da MiniMeta:
            *   - status = 1
            *   - meta_id = meta atual
            * Se o campo de vínculo for outro (ex: meta_id_tadecor), é só trocar aqui.
            */
            TTransaction::open(self::$dbAp);
            $metasImportAbertas = MetaImport::where('meta_id', '=', $metaId)
                                            ->where('status', '=', 1)
                                            ->load();
            TTransaction::close();

            if ($metasImportAbertas) {
                foreach ($metasImportAbertas as $mi) {

                    $retImport = MetaImportService::obterMetaImport($mi->id);

                    // No MetaImportService::obterMetaImport você retorna true se deu certo, string se deu erro
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
        catch (Exception $e)
        {
            LogCrontab::registrarLog(
                "Fechamento automático do mês",
                __METHOD__,
                1,
                $e->getMessage(),
                "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>"
            );
        }
    }

}


