<?php

class TrimestreService
{
    private static $dbNw = 'nw';
    private static $dbAp = 'integrador';
    
    public static function inicial(){
        //calcular pontuação inicial do representante (não rodar nunca, apenas quando importar no inicio do trimestre)
    }
    
    public static function calculo(){
        TrimestreService::obterVendas();
        TrimestreService::popularPrincipais();
        TrimestreService::popularDadosFiliais();
        TrimestreService::calcularPontuacaoAtual();
    }
    
    public static function getTrimestreAtivo(){
        try{
            TTransaction::open(self::$dbAp);
            
            $metaTrimestral = MetaTrimestral::where('status','=',1)->first();
            $metaTrimestral = MetaTrimestral::find(14);
            if(!$metaTrimestral){
                TTransaction::close();
                throw new Exception("Sem meta trimestral aberta.");
            }
            TTransaction::close();
            return $metaTrimestral;
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }

    public static function obterVendas(){
        try{
            $hora0 = new DateTime('now');
            $count = 0;
            
            $metaTrimestral = self::getTrimestreAtivo();
            $agora      = new DateTime();
            $data_inicial = new DateTime($metaTrimestral->data_inicial);
            $data_final = new DateTime($metaTrimestral->data_final);

            $filtro_mes = ($data_final > $agora) ? date('n') : (int) ($data_final->format('n'));
            $filtro_ano = ($data_final > $agora) ? date('Y') : (int) ($data_final->format('Y'));

            $meses = array();
            $meses_anos = array();

            $data = clone $data_inicial;
            while ($data <= $data_final) {
                $meses[] = (int)$data->format('n'); // pega o número do mês
                $meses_anos[] = "'".(string) ((int)$data->format('n')."/".(int)$data->format('Y'))."'";
                $data->modify('+1 month');
            }

            TTransaction::open(self::$dbAp);
            $conn = TTransaction::get();
            $sql = "
                SELECT
                    c.cod_clifor as cod_clifor,
                    c.repres_id as repres_id,
                    v.agente as agente,
                    CASE
                        WHEN c.dt_cadastro >= '$metaTrimestral->data_inicial' THEN EXTRACT(MONTH FROM c.dt_cadastro)
                        WHEN v.agente in (".implode(",",$meses_anos).") THEN split_part(v.agente, '/', 1)::int
                        ELSE EXTRACT(MONTH FROM '$metaTrimestral->data_inicial'::date)
                    END as mes,
                    v.grupo_cliente_id as grupo_id,
                    v.data_cadastro as data_cadastro,
                    v.tipo_pessoa as tipo_pessoa,
                    v.razao as razao,
	                COALESCE(SUM(i.valor_total),0) as valor_total,
                    SUM(CASE WHEN i.sequencia = 12 THEN i.valor_total ELSE 0 END) AS valor_mostruario
                FROM
                    historico_cli_repres c
                    LEFT JOIN historico_venda v 
                        ON v.cod_clifor = c.cod_clifor
                        AND v.data_emissao >= '$metaTrimestral->data_inicial'
                        AND v.data_emissao <= '$metaTrimestral->data_final'
                    LEFT JOIN historico_venda_item i 
                        ON i.venda_id = v.id
                WHERE
                    c.mes = $filtro_mes
                    AND c.ano = $filtro_ano
                GROUP BY
                    1,2,3,4,5,6,7,8
                ORDER BY
                    5
                ";

            $result = $conn->query($sql);
            
            $vendas = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            
            $inicialNaoCadastrado = array();
            foreach($vendas as $venda){
                $inicial = TrimestreClienteInicial::where('meta_trimestral_id','=',$metaTrimestral->id)
                                                    ->where('cod_clifor','=',$venda->cod_clifor)
                                                    ->first();

                if(!$inicial){
                    $historico = HistoricoCliRepres::where('cod_clifor','=',$venda->cod_clifor)->orderby('ano, mes')->last();
                    if($historico){
                        $inicial = new TrimestreClienteInicial();
                        $inicial->meta_trimestral_id = $metaTrimestral->id;
                        $inicial->cod_clifor = $venda->cod_clifor;
                        $inicial->tipo = $venda->tipo;
                        $inicial->data_insercao = date('Y-m-d H:i:s');
                        $inicial->dt_cadastro = $venda->data_cadastro;
                        $inicial->tipo_pessoa = $venda->tipo_pessoa;
                        $inicial->razao_social = $venda->razao;
                        $inicial->reativacao = $venda->agente;
                        $inicial->filial = $historico->filial;
                        $inicial->cod_principal = $historico->cod_principal;
                        $inicial->cidade = $historico->ap_cidade->nome;
                        $inicial->uf = $historico->cod_estado;
                        $inicial->store();
                    }else{
                        $inicialNaoCadastrado[] = $venda->cod_clifor;
                    }
                }
                
                if($inicial){
                    $atual = TrimestreClienteAtual::where('meta_trimestral_id','=',$metaTrimestral->id)
                                                    ->where('cliente_inicial_id','=',$inicial->id)
                                                    ->first()
                            ?? new TrimestreClienteAtual();
                    $atual->meta_trimestral_id = $metaTrimestral->id;
                    $atual->cliente_inicial_id = $inicial->id;
                    $atual->repres_id  = $venda->repres_id;
                    $atual->reativacao = $venda->agente;

                    $mes_final = (int) ($data_final->format('n'));
                    $mes_inicial = (int) $venda->mes;
                    $meses_passados = $mes_final - $mes_inicial+1;
                    $meses_passados = max($meses_passados, 1);

                    $media_mensal = $venda->valor_total / $meses_passados;

                    $grupo_busca = ApGrupoCliente::where('valor_inicial', '<=', $media_mensal)
                                                ->where('valor_final', '>=', $media_mensal)
                                                ->first();

                    $atual->grupo_atingido_id = $grupo_busca ? $grupo_busca->id : null;
                    
                    $atual->valor_total = $venda->valor_total ?? 0;
                    $atual->valor_mostruario = $venda->valor_mostruario ?? 0;
                    $atual->mes = $mes_inicial;
                    $atual->store();
                    $count++;
                }
            }
            TTransaction::close();
            $hora1 = new DateTime('now');
            $intervalo = $hora1->diff($hora0);
            $segundosTotais = ($intervalo->days * 24 * 60 * 60) + ($intervalo->h * 60 * 60) + ($intervalo->i * 60) + $intervalo->s;
            
            if(count($inicialNaoCadastrado)>0){
                //Registro de log de execução
                LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 0, "$count Atuais cadastrados com Vendas, em $segundosTotais segundos.<br/>Clientes sem cadastro por falta de HistoricoCliFor: ".implode(', ',$inicialNaoCadastrado).".", "Arquivo: TrimestreService.<br/>Linha: ".__LINE__.".");
            }else{
                //Registro de log de execução
                LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 0, "$count Atuais cadastrados com Vendas em $segundosTotais segundos.", "Arquivo: TrimestreService.<br/>Linha: ".__LINE__.".");
            }
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }

    public static function popularPrincipais(){
        try{
            $hora0 = new DateTime('now');
            $count = 0;
            
            $metaTrimestral = self::getTrimestreAtivo();
            $agora      = new DateTime();
            $data_inicial = new DateTime($metaTrimestral->data_inicial);
            $data_final = new DateTime($metaTrimestral->data_final);
            
            $meses = array();
            $meses_anos = array();

            $data = clone $data_inicial;
            while ($data <= $data_final) {
                $meses[] = (int)$data->format('n'); // pega o número do mês
                $meses_anos[] = "'".(string) ((int)$data->format('n')."/".(int)$data->format('Y'))."'";
                $data->modify('+1 month');
            }

            TTransaction::open(self::$dbAp);
            $conn = TTransaction::get();
            
            TrimestreFilial::where('meta_trimestral_id','=',$metaTrimestral->id)->delete();
            TrimestrePrincipal::where('meta_trimestral_id','=',$metaTrimestral->id)->delete();

            $result = $conn->query("
                SELECT
                    TAB.cod_principal as cod_principal,
                    SUM(TAB.valor_total) as valor_total,
                    SUM(TAB.valor_mostruario) as valor_mostruario
                FROM(
                    SELECT
                        CASE WHEN i.cod_principal is not null
                                THEN i.cod_principal
                                ELSE i.cod_clifor
                        END as cod_principal,
                        i.filial as filial,
                        i.id as inicial_id,
                        a.id as atual_id,
                        a.mes as mes_inicial,
                        sum(a.valor_total) as valor_total,
                        sum(a.valor_mostruario) as valor_mostruario
                    FROM
                        trimestre_cliente_inicial i
                        LEFT JOIN trimestre_cliente_atual a
                            ON i.id = a.cliente_inicial_id
                    WHERE
                        i.meta_trimestral_id = $metaTrimestral->id 
                    GROUP BY
                        1,2,3,4
                )TAB
                GROUP BY
                    1
                ");
            $dados = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");

            
            
            foreach($dados as $dado){

                $inicial = TrimestreClienteInicial::where('meta_trimestral_id','=',$metaTrimestral->id)
                                                    ->where('cod_clifor','=',$dado->cod_principal)
                                                    ->first();
                if(!$inicial){
                    echo "Inicial: ".$dado->cod_principal . "<br/>";
                }

                $atual = TrimestreClienteAtual::where('meta_trimestral_id','=',$metaTrimestral->id)
                                                    ->where('cliente_inicial_id','=',$inicial->id)
                                                    ->first();

                if(!$atual && $inicial){
                    echo "'$dado->cod_principal',";
                    /*
                    $atual = new TrimestreClienteAtual();
                            
                    $atual->meta_trimestral_id = $metaTrimestral->id;
                    $atual->cliente_inicial_id = $inicial->id;
                    $atual->reativacao = $inicial->reativacao; 
                    $atual->valor_total = 0;
                    $atual->valor_mostruario = 0;

                    $cadastro     = new DateTime($inicial->dt_cadastro);
                    if($cadastro >= $data_inicial){
                        $mes = $cadastro->format('n');
                    }elseif(!empty($atual->reativacao) && in_array($atual->reativacao, $meses_anos)){
                        $mes = (explode('/',$atual->reativacao))[0];
                    }else{
                        $mes = $data_inicial->format('n');
                    }
                    
                    $atual->mes = $mes;
                    $atual->store();
                    $count++;
                    */
                }
                
                $principal = new TrimestrePrincipal();
                $principal->meta_trimestral_id = $metaTrimestral->id;
                $principal->inicial_id = $inicial->id;
                $principal->atual_id = $atual->id;
                $principal->cod_clifor = $dado->cod_principal;
                $principal->valor_total = $dado->valor_total;
                $principal->valor_mostruario = $dado->valor_mostruario;
                $principal->filial = $inicial->filial;
                
                
                $mes_final = $data_final->format('n');
                $mes_inicial = (int) $atual->mes;
                $meses_passados = $mes_final - $mes_inicial+1;
                $meses_passados = max($meses_passados, 1);
            
                $media_mensal = $principal->valor_total/$meses_passados;
            
                $grupo_busca = ApGrupoCliente::where('valor_inicial', '<=', $media_mensal)
                                             ->where('valor_final', '>=', $media_mensal)
                                             ->first();
            
                $principal->grupo_atingido_id = $grupo_busca ? $grupo_busca->id : null;
                $principal->valor_media = $media_mensal;
                
                $principal->store();
                $count++;
                
            }
            
            TrimestrePrincipal::where('meta_trimestral_id','=',$metaTrimestral->id)->where('valor_total','is',null)->set('valor_total',0)->update();
            
            TTransaction::close();
            
            $hora1 = new DateTime('now');
            $intervalo = $hora1->diff($hora0);
            $segundosTotais = ($intervalo->days * 24 * 60 * 60) + ($intervalo->h * 60 * 60) + ($intervalo->i * 60) + $intervalo->s;
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 0, "$count Principais calculados em $segundosTotais segundos.", "Arquivo: TrimestreService.<br/>Linha: ".__LINE__.".");
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }

    public static function popularDadosFiliais(){
        try{
            $hora0 = new DateTime('now');
            $count = 0;
            
            $metaTrimestral = self::getTrimestreAtivo();
            $agora          = new DateTime();
            $data_inicial   = new DateTime($metaTrimestral->data_inicial);
            $data_final     = new DateTime($metaTrimestral->data_final);

            $filtro_mes = ($data_final > $agora) ? date('n') : (int) ($data_final->format('n'));
            $filtro_ano = ($data_final > $agora) ? date('Y') : (int) ($data_final->format('Y'));

            TTransaction::open(self::$dbAp);
            $conn = TTransaction::get();

            $array_erro = array();
            $array_erro['principal'] = "<br/> Principal: ";
            $array_erro['inicial'] = "Inicial: ";
            $array_erro['atual'] = "Atual: ";
            $iniciais = TrimestreClienteInicial::where('meta_trimestral_id','=',$metaTrimestral->id)
                                                ->where('cod_principal','is not',null)
                                                ->load();

            TrimestreFilial::where('meta_trimestral_id','=',$metaTrimestral->id)->delete();

            foreach($iniciais as $inicial){
                
                if($inicial->cod_principal && !empty($inicial->cod_principal) && $inicial->cod_principal!="" && $inicial->cod_principal!=null){
                    $iniPrinc = TrimestreClienteInicial::where('meta_trimestral_id','=',$metaTrimestral->id)
                                                        ->where('cod_clifor','like',$inicial->cod_principal)
                                                        ->first();
                    
                    if(!$iniPrinc){
                        $historico = HistoricoCliRepres::where('cod_clifor','=',$inicial->cod_principal)
                                                        ->where('mes','=',$filtro_mes)
                                                        ->where('ano','=',$filtro_ano)
                                                        ->last();
                        if($historico){
                            $iniPrinc = new TrimestreClienteInicial();
                            $iniPrinc->meta_trimestral_id = $metaTrimestral->id;
                            $iniPrinc->cod_clifor = $historico->cod_clifor;
                            $iniPrinc->data_insercao = date('Y-m-d H:i:s');
                            $iniPrinc->dt_cadastro = $historico->dt_cadastro;
                            $iniPrinc->tipo_pessoa = $historico->tipo_pessoa;
                            $iniPrinc->razao_social = $historico->razao_clifor;
                            $iniPrinc->reativacao = $historico->agente_regular_anp;
                            $iniPrinc->filial = $historico->filial;
                            $iniPrinc->cod_principal = $historico->cod_principal;
                            $iniPrinc->cidade = $historico->ap_cidade->nome;
                            $iniPrinc->uf = $historico->cod_estado;
                            $iniPrinc->store();
                        }else{
                            $array_erro['inicial'] .= "$inicial->cod_clifor principal: $inicial->cod_principal |"; 
                        }
                    }
                    if($iniPrinc){
                    
                        $principal = TrimestrePrincipal::where('meta_trimestral_id','=',$metaTrimestral->id)
                                                        ->where('inicial_id','=',$iniPrinc->id)
                                                        ->first();
                        
                        if(!$principal){
                            $array_erro['principal'].= $inicial->cod_principal. ' | ';
                        }else{
                            $atual = TrimestreClienteAtual::where('meta_trimestral_id','=',$metaTrimestral->id)
                                                            ->where('cliente_inicial_id','=',$inicial->id)
                                                            ->first();
                            
                            if(!$atual){
                                $array_erro['atual'] .= $inicial->id. ' | '; 
                            }else{
                            
                                $filial = new TrimestreFilial();
                                
                                $filial->meta_trimestral_id = $metaTrimestral->id;
                                $filial->inicial_id = $inicial->id;
                                $filial->atual_id = $atual->id ?? null;
                                $filial->principal_id = $principal->id;                                
                                $filial->grupo_atingido_id = $principal->grupo_atingido_id;
                                $filial->cod_principal = $inicial->cod_principal;
                                $filial->valor_total = $atual->valor_total ?? 0;
                                $filial->valor_mostruario = $atual->valor_mostruario ?? 0;
                                //
                                $filial->store();
                                $count++;
                            }
                        }
                    }
                }
                
            }
            TTransaction::close();

            $erros = '';
            if($array_erro['principal'] != "<br/> Principal: "){
                $erros .= $array_erro['principal'];
            }
            if($array_erro['inicial'] != "Inicial: "){
                $erros .= $array_erro['inicial'];
            }
            if($array_erro['atual'] != "Atual: "){
                $erros .= $array_erro['atual'];
            }

            $hora1 = new DateTime('now');
            $intervalo = $hora1->diff($hora0);
            $segundosTotais = ($intervalo->days * 24 * 60 * 60) + ($intervalo->h * 60 * 60) + ($intervalo->i * 60) + $intervalo->s;
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 0, "$count Filiais registrados em $segundosTotais segundos.".$erros, "Arquivo: TrimestreService.<br/>Linha: ".__LINE__.".");
            
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }

    public static function calcularPontuacaoAtual(){
        try{
            $hora0 = new DateTime('now');
            $count = 0;
            
            $metaTrimestral = self::getTrimestreAtivo();
            
            TTransaction::open(self::$dbAp);            
            $conn = TTransaction::get();
            
            $result = $conn->query("
                SELECT 
                    r.id as repres_id,
                    SUM(g.pontuacao) as pontuacao
                FROM 
                    trimestre_cliente_atual a
                    INNER JOIN trimestre_principal p
                        ON p.inicial_id = a.cliente_inicial_id
                    LEFT JOIN ap_grupo_cliente g
                        ON p.grupo_atingido_id = g.id
                    LEFT JOIN ap_representante r
                        ON a.repres_id = r.id
                WHERE
                    a.meta_trimestral_id = $metaTrimestral->id
                GROUP BY 
                    r.id
                ORDER BY 
                    2 ASC");
            
            $pontos = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
            foreach($pontos as $ponto){
                $meta = MetaTrimestralRepres::where('meta_trimestral_id','=',$metaTrimestral->id)->where('ap_representante_id','=',$ponto->repres_id)->first();
                if($meta){
                    $meta->pontuacao_atual = $ponto->pontuacao;
                    $meta->store();
                    $count++;
                }
            }
            
            TTransaction::close();
            
            $hora1 = new DateTime('now');
            $intervalo = $hora1->diff($hora0);
            $segundosTotais = ($intervalo->days * 24 * 60 * 60) + ($intervalo->h * 60 * 60) + ($intervalo->i * 60) + $intervalo->s;
            
            //Registro de log de execução
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 0, "Pontuação atual de $count Consultores(as) calculada em $segundosTotais segundos.", "Arquivo: TrimestreService.<br/>Linha: ".__LINE__.".");
        } catch (Exception $e) {
            LogCrontab::registrarLog("Atualização do Trimestre", __METHOD__, 1, $e->getMessage(), "Arquivo: " . $e->getFile() . "<br/>Linha: " . $e->getLine() . "<br/>");
        }
    }
}