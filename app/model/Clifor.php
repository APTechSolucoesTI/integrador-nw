<?php

class Clifor extends TRecord
{
    const TABLENAME  = 'clifor';
    const PRIMARYKEY = 'cod_clifor';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('razao');
        parent::addAttribute('cod_situacaovenda');
        parent::addAttribute('cod_moeda');
        parent::addAttribute('cod_idioma');
        parent::addAttribute('cod_natrecdespfor');
        parent::addAttribute('cod_natrecdespcli');
        parent::addAttribute('cod_grpcliente');
        parent::addAttribute('cod_bairro');
        parent::addAttribute('cod_grpfornec');
        parent::addAttribute('cod_redespacho');
        parent::addAttribute('cod_cidade');
        parent::addAttribute('cod_transp');
        parent::addAttribute('cod_regiao');
        parent::addAttribute('cod_repres');
        parent::addAttribute('cod_estado');
        parent::addAttribute('fantasia');
        parent::addAttribute('cod_pais');
        parent::addAttribute('cod_condpagamento');
        parent::addAttribute('cod_portadorcr');
        parent::addAttribute('cod_portadorcp');
        parent::addAttribute('cod_atividade');
        parent::addAttribute('endereco');
        parent::addAttribute('cod_tabelapreco');
        parent::addAttribute('cod_tabelaprazo');
        parent::addAttribute('cod_natoperacaosai');
        parent::addAttribute('cod_natoperacaoent');
        parent::addAttribute('cliente');
        parent::addAttribute('fornec');
        parent::addAttribute('cep');
        parent::addAttribute('pessoa');
        parent::addAttribute('cgc_cpf');
        parent::addAttribute('ie_rg');
        parent::addAttribute('insmunicipal');
        parent::addAttribute('telefone1');
        parent::addAttribute('telefone2');
        parent::addAttribute('celular');
        parent::addAttribute('fax');
        parent::addAttribute('cxpostal');
        parent::addAttribute('web');
        parent::addAttribute('email');
        parent::addAttribute('dt_cadastro');
        parent::addAttribute('dt_atualizacao');
        parent::addAttribute('dt_nascto');
        parent::addAttribute('obs');
        parent::addAttribute('suframa');
        parent::addAttribute('ativo');
        parent::addAttribute('subst_tributaria_iss');
        parent::addAttribute('aliquota_iss');
        parent::addAttribute('limite_credito');
        parent::addAttribute('dias_atraso');
        parent::addAttribute('dt_validadecredito');
        parent::addAttribute('avaliacao_credito');
        parent::addAttribute('cod_banco');
        parent::addAttribute('nro_agencia');
        parent::addAttribute('conta_corrente');
        parent::addAttribute('considerar_pedidocomp');
        parent::addAttribute('motivo_bloqueio');
        parent::addAttribute('considerar_pedentfut');
        parent::addAttribute('perc_descto');
        parent::addAttribute('agrupar_conhecimento');
        parent::addAttribute('parametro_truck');
        parent::addAttribute('cod_natopedentro');
        parent::addAttribute('cod_tabfrete');
        parent::addAttribute('parada_acertocontas');
        parent::addAttribute('cod_natopefora');
        parent::addAttribute('trk_conhecnfanf');
        parent::addAttribute('trk_tiporateio');
        parent::addAttribute('cod_endcobranca');
        parent::addAttribute('cod_endentrega');
        parent::addAttribute('exige_certificadoqualidade');
        parent::addAttribute('contato');
        parent::addAttribute('cgccpf_digito');
        parent::addAttribute('gera_manga');
        parent::addAttribute('tipo_descto');
        parent::addAttribute('pis_retidoclass');
        parent::addAttribute('cofins_retidoclass');
        parent::addAttribute('produtor_rural');
        parent::addAttribute('imp_boleto');
        parent::addAttribute('codigo_palm');
        parent::addAttribute('qld_qstrespondeu');
        parent::addAttribute('qld_qstdtenvio');
        parent::addAttribute('qld_qstdtresposta');
        parent::addAttribute('qld_qstresponsavel');
        parent::addAttribute('qld_iqf');
        parent::addAttribute('qld_dtiqf');
        parent::addAttribute('comp_situacao');
        parent::addAttribute('comp_dtbloqueio');
        parent::addAttribute('comp_motivo');
        parent::addAttribute('dt_validadeconsulta');
        parent::addAttribute('ponto_referencia');
        parent::addAttribute('cod_atividademunicipal');
        parent::addAttribute('venda_situacao');
        parent::addAttribute('venda_dtbloqueio');
        parent::addAttribute('venda_motivo');
        parent::addAttribute('dt_change');
        parent::addAttribute('considerar_taxaboleto');
        parent::addAttribute('trk_tiposeguro');
        parent::addAttribute('auxiliar_string1');
        parent::addAttribute('auxiliar_string2');
        parent::addAttribute('numero');
        parent::addAttribute('end_complemento');
        parent::addAttribute('end_numero');
        parent::addAttribute('cod_motchegouempresa');
        parent::addAttribute('dt_motchegouempresa');
        parent::addAttribute('cod_tipologradouro');
        parent::addAttribute('codigo_folhamatic');
        parent::addAttribute('tipo_controleboletoemail');
        parent::addAttribute('cod_itinerario');
        parent::addAttribute('cod_tipofrete');
        parent::addAttribute('perc_desctopv');
        parent::addAttribute('prazoaceite_validade');
        parent::addAttribute('iqf_notaquestionario');
        parent::addAttribute('iqf_indiceatendimento');
        parent::addAttribute('codigo_sisterceiro');
        parent::addAttribute('permite_alterartabprecopv');
        parent::addAttribute('aviso_faturamento');
        parent::addAttribute('dt_validadeliberado');
        parent::addAttribute('energia_telecomunicacao');
        parent::addAttribute('codtp_ccta');
        parent::addAttribute('codtp_tltu');
        parent::addAttribute('codtp_grupotensao');
        parent::addAttribute('energia_telecom');
        parent::addAttribute('prospect');
        parent::addAttribute('auxiliar_float1');
        parent::addAttribute('auxiliar_float2');
        parent::addAttribute('perc_desctocr');
        parent::addAttribute('analise_gradetransp');
        parent::addAttribute('nfse_controle');
        parent::addAttribute('inscricao_produtorrural');
        parent::addAttribute('auxiliar_string3');
        parent::addAttribute('auxiliar_string4');
        parent::addAttribute('emitir_cartacobranca');
        parent::addAttribute('analisa_impostosretidosserv');
        parent::addAttribute('auxiliar_string5');
        parent::addAttribute('ie_pessoafisica');
        parent::addAttribute('identificacao_estrangeiro');
        parent::addAttribute('identificacao_iedest');
        parent::addAttribute('auxiliar_string6');
        parent::addAttribute('cod_tipopedvenda');
        parent::addAttribute('dt_valsuframa');
        parent::addAttribute('tipo_crt');
        parent::addAttribute('hora_atualizacao');
        parent::addAttribute('exp_situacao');
        parent::addAttribute('exp_data');
        parent::addAttribute('exp_hora');
        parent::addAttribute('imp_somentecxpostal');
        parent::addAttribute('agente_regularanp');
        parent::addAttribute('dispensado_coleta');
        parent::addAttribute('cod_formaspagto');
        parent::addAttribute('doc_ficticio');
            
    }

    /**
     * Method getHistoricoCliRepress
     */
    public function getHistoricoCliRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cod_clifor', '=', $this->cod_clifor));
        return HistoricoCliRepres::getObjects( $criteria );
    }

    public function set_historico_cli_repres_system_unit_to_string($historico_cli_repres_system_unit_to_string)
    {
        if(is_array($historico_cli_repres_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $historico_cli_repres_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->historico_cli_repres_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_cli_repres_system_unit_to_string = $historico_cli_repres_system_unit_to_string;
        }

        $this->vdata['historico_cli_repres_system_unit_to_string'] = $this->historico_cli_repres_system_unit_to_string;
    }

    public function get_historico_cli_repres_system_unit_to_string()
    {
        if(!empty($this->historico_cli_repres_system_unit_to_string))
        {
            return $this->historico_cli_repres_system_unit_to_string;
        }
    
        $values = HistoricoCliRepres::where('cod_clifor', '=', $this->cod_clifor)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_historico_cli_repres_ap_cidade_to_string($historico_cli_repres_ap_cidade_to_string)
    {
        if(is_array($historico_cli_repres_ap_cidade_to_string))
        {
            $values = ApCidade::where('id', 'in', $historico_cli_repres_ap_cidade_to_string)->getIndexedArray('id', 'id');
            $this->historico_cli_repres_ap_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_cli_repres_ap_cidade_to_string = $historico_cli_repres_ap_cidade_to_string;
        }

        $this->vdata['historico_cli_repres_ap_cidade_to_string'] = $this->historico_cli_repres_ap_cidade_to_string;
    }

    public function get_historico_cli_repres_ap_cidade_to_string()
    {
        if(!empty($this->historico_cli_repres_ap_cidade_to_string))
        {
            return $this->historico_cli_repres_ap_cidade_to_string;
        }
    
        $values = HistoricoCliRepres::where('cod_clifor', '=', $this->cod_clifor)->getIndexedArray('ap_cidade_id','{ap_cidade->id}');
        return implode(', ', $values);
    }

    public function set_historico_cli_repres_grupo_to_string($historico_cli_repres_grupo_to_string)
    {
        if(is_array($historico_cli_repres_grupo_to_string))
        {
            $values = ApGrupoCliente::where('id', 'in', $historico_cli_repres_grupo_to_string)->getIndexedArray('id', 'id');
            $this->historico_cli_repres_grupo_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_cli_repres_grupo_to_string = $historico_cli_repres_grupo_to_string;
        }

        $this->vdata['historico_cli_repres_grupo_to_string'] = $this->historico_cli_repres_grupo_to_string;
    }

    public function get_historico_cli_repres_grupo_to_string()
    {
        if(!empty($this->historico_cli_repres_grupo_to_string))
        {
            return $this->historico_cli_repres_grupo_to_string;
        }
    
        $values = HistoricoCliRepres::where('cod_clifor', '=', $this->cod_clifor)->getIndexedArray('grupo_id','{grupo->id}');
        return implode(', ', $values);
    }

    public function set_historico_cli_repres_repres_to_string($historico_cli_repres_repres_to_string)
    {
        if(is_array($historico_cli_repres_repres_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $historico_cli_repres_repres_to_string)->getIndexedArray('id', 'id');
            $this->historico_cli_repres_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_cli_repres_repres_to_string = $historico_cli_repres_repres_to_string;
        }

        $this->vdata['historico_cli_repres_repres_to_string'] = $this->historico_cli_repres_repres_to_string;
    }

    public function get_historico_cli_repres_repres_to_string()
    {
        if(!empty($this->historico_cli_repres_repres_to_string))
        {
            return $this->historico_cli_repres_repres_to_string;
        }
    
        $values = HistoricoCliRepres::where('cod_clifor', '=', $this->cod_clifor)->getIndexedArray('repres_id','{repres->id}');
        return implode(', ', $values);
    }

    
}

