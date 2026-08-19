<?php

class PedidoVenda extends TRecord
{
    const TABLENAME  = 'pedido_venda';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_pedidovenda');
        parent::addAttribute('cod_clifor');
        parent::addAttribute('cod_portador2');
        parent::addAttribute('cod_tipopedvenda');
        parent::addAttribute('cod_gerente');
        parent::addAttribute('cod_portador');
        parent::addAttribute('cod_orcamentista');
        parent::addAttribute('cod_empresa');
        parent::addAttribute('cod_prioridade');
        parent::addAttribute('cod_tabelapreco');
        parent::addAttribute('cod_repres');
        parent::addAttribute('cod_transp');
        parent::addAttribute('cod_condpagamento');
        parent::addAttribute('cod_moeda');
        parent::addAttribute('contato');
        parent::addAttribute('cod_endcobranca');
        parent::addAttribute('cod_usuario');
        parent::addAttribute('cod_condpagamento2');
        parent::addAttribute('redespacho');
        parent::addAttribute('nro_pedcliente');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('dt_solicitada_apos');
        parent::addAttribute('dt_solicitada_limite');
        parent::addAttribute('situacao');
        parent::addAttribute('dt_validade');
        parent::addAttribute('pedido_orcamento');
        parent::addAttribute('aceita_ftparcial');
        parent::addAttribute('dt_previsaofechamento');
        parent::addAttribute('tipo_frete');
        parent::addAttribute('val_frete');
        parent::addAttribute('val_seguro');
        parent::addAttribute('dt_aprovacao');
        parent::addAttribute('quem_aprovou');
        parent::addAttribute('valor_despesas');
        parent::addAttribute('cod_endentrega');
        parent::addAttribute('mensagem');
        parent::addAttribute('mensagem_nf');
        parent::addAttribute('tipo_mensagemnf');
        parent::addAttribute('inf_comissao');
        parent::addAttribute('paga_comissao');
        parent::addAttribute('liberado');
        parent::addAttribute('perc_comissaofatura');
        parent::addAttribute('dt_cancelou');
        parent::addAttribute('perc_comissaorecebe');
        parent::addAttribute('usuario_cancelou');
        parent::addAttribute('cod_motcancelamento');
        parent::addAttribute('motivo_cancelou');
        parent::addAttribute('perc_descto');
        parent::addAttribute('impresso');
        parent::addAttribute('tipo_descto');
        parent::addAttribute('pros_nome');
        parent::addAttribute('pros_contato');
        parent::addAttribute('pros_telefone');
        parent::addAttribute('pros_celular');
        parent::addAttribute('pros_uf');
        parent::addAttribute('pros_email');
        parent::addAttribute('pros_obs');
        parent::addAttribute('tipo_taxamoeda');
        parent::addAttribute('valor_taxamoeda');
        parent::addAttribute('referencia');
        parent::addAttribute('arq_descproposta');
        parent::addAttribute('arq_condgeral');
        parent::addAttribute('valor_bcalc_repasseicms');
        parent::addAttribute('valor_repasseicms');
        parent::addAttribute('valor_bcalc_zonafranca');
        parent::addAttribute('valor_zonafranca');
        parent::addAttribute('valor_bcalc_iss');
        parent::addAttribute('valor_iss');
        parent::addAttribute('exp_situacao');
        parent::addAttribute('exp_data');
        parent::addAttribute('exp_hora');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('cliente_geramanga');
        parent::addAttribute('imp_boleto');
        parent::addAttribute('codigo_palm');
        parent::addAttribute('sep_status');
        parent::addAttribute('sep_usuario');
        parent::addAttribute('sep_dtinicio');
        parent::addAttribute('sep_hsinicio');
        parent::addAttribute('cod_orcamento');
        parent::addAttribute('obs_proposta');
        parent::addAttribute('prazo_entrega');
        parent::addAttribute('dt_proximocontato');
        parent::addAttribute('anotacao_followup');
        parent::addAttribute('dt_envio');
        parent::addAttribute('dt_change');
        parent::addAttribute('status_web');
        parent::addAttribute('origem');
        parent::addAttribute('dt_finalizadoweb');
        parent::addAttribute('dt_baixadoweb');
        parent::addAttribute('recalculo_web');
        parent::addAttribute('perc_comissaopedido');
        parent::addAttribute('auxiliar_string1');
        parent::addAttribute('auxiliar_string2');
        parent::addAttribute('cod_tipofrete');
        parent::addAttribute('cod_taxasucesso');
        parent::addAttribute('cod_ordemseparacao');
        parent::addAttribute('nro_pedidovendaint');
        parent::addAttribute('email_boleto');
        parent::addAttribute('dt_concluiudigitacao');
        parent::addAttribute('cod_ficha');
        parent::addAttribute('valor_fretepeso');
        parent::addAttribute('em_analise');
        parent::addAttribute('cod_usuarioanalise');
        parent::addAttribute('cod_cliforoptring');
        parent::addAttribute('pjt_codempresa');
        parent::addAttribute('pjt_codprojeto');
        parent::addAttribute('dt_solicitacao');
        parent::addAttribute('dt_gerandodav');
        parent::addAttribute('qtd_caixas');
        parent::addAttribute('especie_emb');
        parent::addAttribute('peso_bruto');
        parent::addAttribute('peso_liquido');
        parent::addAttribute('cod_empresapvenda');
        parent::addAttribute('cod_pontovendapvenda');
        parent::addAttribute('dt_pvenda');
        parent::addAttribute('hr_pvenda');
        parent::addAttribute('nome_ficha');
        parent::addAttribute('cod_formaspagto');
        parent::addAttribute('nro_pedidovendaint2');
        parent::addAttribute('liberado_old');
        parent::addAttribute('exec_libbloq');
        parent::addAttribute('obs_libbloq');
        parent::addAttribute('tipo_ecommerce');
        parent::addAttribute('usuario_alteracao');
        parent::addAttribute('tipo_integracao');
        parent::addAttribute('id_transporteint');
        parent::addAttribute('recalc_dup');
        parent::addAttribute('recalc_pagto');
            
    }

    
}

