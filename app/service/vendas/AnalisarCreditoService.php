<?php

class AnalisarCreditoService


{

    private static $dbNw = 'nw';
    private static $dbAp = 'integrador';
    private static $dbSite = 'site';

    public static function analisarCreditoCliente($cadastro_id)
    {
        try {
            //---------------------------
            // 0) Buscar cadastro (datas e info)
            //---------------------------
            TTransaction::open(self::$dbAp);
            $cadastro = CreditoClienteCadastro::find($cadastro_id);
            TTransaction::close();

            if (!$cadastro) {
                throw new Exception("Cadastro de crédito não encontrado (ID: {$cadastro_id})");
            }

            $dataInicio = $cadastro->periodo_inicio;
            $dataFim    = $cadastro->periodo_fim;

            //---------------------------
            // 1) Buscar na NW (filtra por DT_EMISSAO com BETWEEN)
            //---------------------------
            TTransaction::open(self::$dbNw);
            $conn = TTransaction::get();

            $sql = " 
               SELECT
                    TRIM(TAB.CODIGO_CLIENTE) AS cod_clifor,
                    TRIM(TAB.RAZAO_SOCIAL)   AS razao_clifor,
                    TAB.NRO_NOTA_FISCAL      AS nro_nota_fiscal,
                    -- Alias de período apenas para exibição, não para filtro:
                    (LPAD(TAB.MES::text, 2, '0') || '/' || TAB.ANO::text) AS periodo,
                    SUM(TAB.VALOR_TOTAL)     AS item_valor_total
                FROM (
                    SELECT
                        DATE_PART('year', NOTA_FISCAL.DT_EMISSAO)  AS ANO,
                        DATE_PART('month', NOTA_FISCAL.DT_EMISSAO) AS MES,
                        CLIFOR.DT_CADASTRO        AS DATA_CADASTRO,
                        NOTA_FISCAL.NRO_NFISCAL   AS NRO_NOTA_FISCAL,
                        NOTA_FISCAL.COD_CLIFOR    AS CODIGO_CLIENTE,
                        CLIFOR.RAZAO              AS RAZAO_SOCIAL,
                        (ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE) AS VALOR_TOTAL
                    FROM
                        NOTA_FISCAL
                        INNER JOIN ITEM_NOTAFISCAL
                            ON ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA
                        AND ITEM_NOTAFISCAL.SERIE       = NOTA_FISCAL.SERIE
                        AND ITEM_NOTAFISCAL.COD_CLIFOR  = NOTA_FISCAL.COD_CLIFOR
                        AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL
                        INNER JOIN CLIFOR ON CLIFOR.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                    WHERE
                        NOTA_FISCAL.COD_EMPRESA     = '101'
                        AND NOTA_FISCAL.SITUACAO   <> 'C'
                        AND NOTA_FISCAL.ENTRADA_SAIDA = 'S'
                        AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '008'
                        AND NOTA_FISCAL.DT_EMISSAO BETWEEN :data_inicio AND :data_fim

                    UNION ALL

                    SELECT
                        DATE_PART('year', NOTA_FISCAL.DT_EMISSAO)  AS ANO,
                        DATE_PART('month', NOTA_FISCAL.DT_EMISSAO) AS MES,
                        CLIFOR.DT_CADASTRO        AS DATA_CADASTRO,
                        NOTA_FISCAL.NRO_NFISCAL   AS NRO_NOTA_FISCAL,
                        NOTA_FISCAL.COD_CLIFOR    AS CODIGO_CLIENTE,
                        CLIFOR.RAZAO              AS RAZAO_SOCIAL,
                        ((ITEM_NOTAFISCAL.VALOR_TOTAL + ITEM_NOTAFISCAL.VALOR_FRETE) * -1) AS VALOR_TOTAL
                    FROM
                        NOTA_FISCAL
                        INNER JOIN ITEM_NOTAFISCAL
                            ON ITEM_NOTAFISCAL.COD_EMPRESA = NOTA_FISCAL.COD_EMPRESA
                        AND ITEM_NOTAFISCAL.SERIE       = NOTA_FISCAL.SERIE
                        AND ITEM_NOTAFISCAL.COD_CLIFOR  = NOTA_FISCAL.COD_CLIFOR
                        AND ITEM_NOTAFISCAL.NRO_NFISCAL = NOTA_FISCAL.NRO_NFISCAL
                        INNER JOIN CLIFOR ON CLIFOR.COD_CLIFOR = NOTA_FISCAL.COD_CLIFOR
                    WHERE
                        NOTA_FISCAL.COD_EMPRESA     = '101'
                        AND NOTA_FISCAL.SITUACAO   <> 'C'
                        AND NOTA_FISCAL.ENTRADA_SAIDA = 'E'
                        AND ITEM_NOTAFISCAL.COD_TIPONATUREZA = '012'
                        AND NOTA_FISCAL.DT_EMISSAO BETWEEN :data_inicio AND :data_fim
                ) TAB
                GROUP BY
                    TAB.CODIGO_CLIENTE,
                    TAB.RAZAO_SOCIAL,
                    TAB.NRO_NOTA_FISCAL,
                    TAB.MES,
                    TAB.ANO
                ORDER BY
                    periodo
        ";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':data_inicio' => $dataInicio,
                ':data_fim'    => $dataFim
            ]);

            $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            TTransaction::close(); // fecha dbNw

            //---------------------------
            // 2) Consolidar por cliente (total, qtd notas, média)
            //---------------------------
            $porCliente = [];
            foreach ($linhas as $r) {
                $cod = $r['cod_clifor'];
                if (!isset($porCliente[$cod])) {
                    $porCliente[$cod] = [
                        'cod_clifor'    => $cod,
                        'razao_clifor'  => $r['razao_clifor'],
                        'valor_total'   => 0.0,
                        'notas'         => [], // DISTINCT
                    ];
                }
                $porCliente[$cod]['valor_total'] += (float)$r['item_valor_total'];
                $porCliente[$cod]['notas'][$r['nro_nota_fiscal']] = true;
            }

            //---------------------------
            // 3) Gravar no integrador.credito_cliente 
            //---------------------------

            TTransaction::open(self::$dbAp);
            foreach ($porCliente as $c) {
                $valor_total       = (float)$c['valor_total'];
                $quantidade_notas  = count($c['notas']);
                $media_por_pedido  = $quantidade_notas > 0 ? ($valor_total / $quantidade_notas) : 0.0;

                // se precisar, busca historico_cli_id
             //$historicoId = self::buscarHistoricoCliId($c['cod_clifor'], $dataInicio, $dataFim);

                $credito = new CreditoCliente; 
                $credito->credito_cliente_cadastro_id = $cadastro_id; // vínculo obrigatório
                $credito->cod_clifor         = $c['cod_clifor'];
                $credito->razao_clifor       = $c['razao_clifor'];
                $credito->valor_total        = $valor_total;
                $credito->quantidade_notas   = $quantidade_notas;
                $credito->media_por_pedido   = $media_por_pedido;
                $credito->periodo_inicio     = $dataInicio;
                $credito->periodo_fim        = $dataFim;
                //$credito->historico_cli_id   = $historicoId; 
                $credito->store();
            }

            // Atualiza cadastro como fechado (status = 2, por ex.)
            $cadastro->status = 2;
            $cadastro->store();

            TTransaction::close(); // fecha dbAp

            return true;

        } catch (Exception $e) {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
            return false;
        }
    }

}
