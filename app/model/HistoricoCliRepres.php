<?php

class HistoricoCliRepres extends TRecord
{
    const TABLENAME  = 'historico_cli_repres';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApGrupoCliente $grupo;
    private ApRepresentante $repres;
    private Clifor $fk_cod_clifor;
    private SystemUnit $system_unit;
    private ApCidade $ap_cidade;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_unit_id');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('cod_clifor');
        parent::addAttribute('razao_clifor');
        parent::addAttribute('dt_cadastro');
        parent::addAttribute('ap_cidade_id');
        parent::addAttribute('grupo_id');
        parent::addAttribute('repres_id');
        parent::addAttribute('ativo');
        parent::addAttribute('agente_regular_anp');
        parent::addAttribute('tipo');
        parent::addAttribute('prospeccao');
        parent::addAttribute('cod_estado');
        parent::addAttribute('filial');
        parent::addAttribute('cod_principal');
        parent::addAttribute('tipo_pessoa');
        parent::addAttribute('rota');
        parent::addAttribute('grupo_empresarial');
        parent::addAttribute('latitude');
        parent::addAttribute('longitude');
        parent::addAttribute('cep');
        parent::addAttribute('dt_atualizacao');
        parent::addAttribute('dt_change');
            
    }

    /**
     * Method set_ap_grupo_cliente
     * Sample of usage: $var->ap_grupo_cliente = $object;
     * @param $object Instance of ApGrupoCliente
     */
    public function set_grupo(ApGrupoCliente $object)
    {
        $this->grupo = $object;
        $this->grupo_id = $object->id;
    }

    /**
     * Method get_grupo
     * Sample of usage: $var->grupo->attribute;
     * @returns ApGrupoCliente instance
     */
    public function get_grupo()
    {
    
        // loads the associated object
        if (empty($this->grupo))
            $this->grupo = new ApGrupoCliente($this->grupo_id);
    
        // returns the associated object
        return $this->grupo;
    }
    /**
     * Method set_ap_representante
     * Sample of usage: $var->ap_representante = $object;
     * @param $object Instance of ApRepresentante
     */
    public function set_repres(ApRepresentante $object)
    {
        $this->repres = $object;
        $this->repres_id = $object->id;
    }

    /**
     * Method get_repres
     * Sample of usage: $var->repres->attribute;
     * @returns ApRepresentante instance
     */
    public function get_repres()
    {
    
        // loads the associated object
        if (empty($this->repres))
            $this->repres = new ApRepresentante($this->repres_id);
    
        // returns the associated object
        return $this->repres;
    }
    /**
     * Method set_clifor
     * Sample of usage: $var->clifor = $object;
     * @param $object Instance of Clifor
     */
    public function set_fk_cod_clifor(Clifor $object)
    {
        $this->fk_cod_clifor = $object;
        $this->cod_clifor = $object->cod_clifor;
    }

    /**
     * Method get_fk_cod_clifor
     * Sample of usage: $var->fk_cod_clifor->attribute;
     * @returns Clifor instance
     */
    public function get_fk_cod_clifor()
    {
        try{
        TTransaction::openFake('nw');
        // loads the associated object
        if (empty($this->fk_cod_clifor))
            $this->fk_cod_clifor = new Clifor($this->cod_clifor);
        TTransaction::close();
        }catch(Exception $e){
            TTransaction::close();
        }
        // returns the associated object
        return $this->fk_cod_clifor;
    }
    /**
     * Method set_system_unit
     * Sample of usage: $var->system_unit = $object;
     * @param $object Instance of SystemUnit
     */
    public function set_system_unit(SystemUnit $object)
    {
        $this->system_unit = $object;
        $this->system_unit_id = $object->id;
    }

    /**
     * Method get_system_unit
     * Sample of usage: $var->system_unit->attribute;
     * @returns SystemUnit instance
     */
    public function get_system_unit()
    {
    
        // loads the associated object
        if (empty($this->system_unit))
            $this->system_unit = new SystemUnit($this->system_unit_id);
    
        // returns the associated object
        return $this->system_unit;
    }
    /**
     * Method set_ap_cidade
     * Sample of usage: $var->ap_cidade = $object;
     * @param $object Instance of ApCidade
     */
    public function set_ap_cidade(ApCidade $object)
    {
        $this->ap_cidade = $object;
        $this->ap_cidade_id = $object->id;
    }

    /**
     * Method get_ap_cidade
     * Sample of usage: $var->ap_cidade->attribute;
     * @returns ApCidade instance
     */
    public function get_ap_cidade()
    {
    
        // loads the associated object
        if (empty($this->ap_cidade))
            $this->ap_cidade = new ApCidade($this->ap_cidade_id);
    
        // returns the associated object
        return $this->ap_cidade;
    }

    /**
     * Method getCreditoClientes
     */
    public function getCreditoClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('historico_cli_id', '=', $this->id));
        return CreditoCliente::getObjects( $criteria );
    }

    public function set_credito_cliente_historico_cli_to_string($credito_cliente_historico_cli_to_string)
    {
        if(is_array($credito_cliente_historico_cli_to_string))
        {
            $values = HistoricoCliRepres::where('id', 'in', $credito_cliente_historico_cli_to_string)->getIndexedArray('id', 'id');
            $this->credito_cliente_historico_cli_to_string = implode(', ', $values);
        }
        else
        {
            $this->credito_cliente_historico_cli_to_string = $credito_cliente_historico_cli_to_string;
        }

        $this->vdata['credito_cliente_historico_cli_to_string'] = $this->credito_cliente_historico_cli_to_string;
    }

    public function get_credito_cliente_historico_cli_to_string()
    {
        if(!empty($this->credito_cliente_historico_cli_to_string))
        {
            return $this->credito_cliente_historico_cli_to_string;
        }
    
        $values = CreditoCliente::where('historico_cli_id', '=', $this->id)->getIndexedArray('historico_cli_id','{historico_cli->id}');
        return implode(', ', $values);
    }

    public function set_credito_cliente_credito_cliente_cadastro_to_string($credito_cliente_credito_cliente_cadastro_to_string)
    {
        if(is_array($credito_cliente_credito_cliente_cadastro_to_string))
        {
            $values = CreditoClienteCadastro::where('id', 'in', $credito_cliente_credito_cliente_cadastro_to_string)->getIndexedArray('id', 'id');
            $this->credito_cliente_credito_cliente_cadastro_to_string = implode(', ', $values);
        }
        else
        {
            $this->credito_cliente_credito_cliente_cadastro_to_string = $credito_cliente_credito_cliente_cadastro_to_string;
        }

        $this->vdata['credito_cliente_credito_cliente_cadastro_to_string'] = $this->credito_cliente_credito_cliente_cadastro_to_string;
    }

    public function get_credito_cliente_credito_cliente_cadastro_to_string()
    {
        if(!empty($this->credito_cliente_credito_cliente_cadastro_to_string))
        {
            return $this->credito_cliente_credito_cliente_cadastro_to_string;
        }
    
        $values = CreditoCliente::where('historico_cli_id', '=', $this->id)->getIndexedArray('credito_cliente_cadastro_id','{credito_cliente_cadastro->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(CreditoCliente::where('historico_cli_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

