<?php

class TrimestreClienteInicial extends TRecord
{
    const TABLENAME  = 'trimestre_cliente_inicial';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private MetaTrimestral $meta_trimestral;
    private ApGrupoCliente $grupo;
    private ApRepresentante $repres;
    private ApCidade $cidade;
    private ApEstado $estado;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('meta_trimestral_id');
        parent::addAttribute('data_insercao');
        parent::addAttribute('cod_clifor');
        parent::addAttribute('razao_social');
        parent::addAttribute('dt_cadastro');
        parent::addAttribute('grupo_id');
        parent::addAttribute('repres_id');
        parent::addAttribute('ativo');
        parent::addAttribute('reativacao');
        parent::addAttribute('tipo_pessoa');
        parent::addAttribute('filial');
        parent::addAttribute('cod_principal');
        parent::addAttribute('rota');
        parent::addAttribute('cidade_id');
        parent::addAttribute('estado_id');
            
    }

    /**
     * Method set_meta_trimestral
     * Sample of usage: $var->meta_trimestral = $object;
     * @param $object Instance of MetaTrimestral
     */
    public function set_meta_trimestral(MetaTrimestral $object)
    {
        $this->meta_trimestral = $object;
        $this->meta_trimestral_id = $object->id;
    }

    /**
     * Method get_meta_trimestral
     * Sample of usage: $var->meta_trimestral->attribute;
     * @returns MetaTrimestral instance
     */
    public function get_meta_trimestral()
    {
    
        // loads the associated object
        if (empty($this->meta_trimestral))
            $this->meta_trimestral = new MetaTrimestral($this->meta_trimestral_id);
    
        // returns the associated object
        return $this->meta_trimestral;
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
     * Method set_ap_cidade
     * Sample of usage: $var->ap_cidade = $object;
     * @param $object Instance of ApCidade
     */
    public function set_cidade(ApCidade $object)
    {
        $this->cidade = $object;
        $this->cidade_id = $object->id;
    }

    /**
     * Method get_cidade
     * Sample of usage: $var->cidade->attribute;
     * @returns ApCidade instance
     */
    public function get_cidade()
    {
    
        // loads the associated object
        if (empty($this->cidade))
            $this->cidade = new ApCidade($this->cidade_id);
    
        // returns the associated object
        return $this->cidade;
    }
    /**
     * Method set_ap_estado
     * Sample of usage: $var->ap_estado = $object;
     * @param $object Instance of ApEstado
     */
    public function set_estado(ApEstado $object)
    {
        $this->estado = $object;
        $this->estado_id = $object->id;
    }

    /**
     * Method get_estado
     * Sample of usage: $var->estado->attribute;
     * @returns ApEstado instance
     */
    public function get_estado()
    {
    
        // loads the associated object
        if (empty($this->estado))
            $this->estado = new ApEstado($this->estado_id);
    
        // returns the associated object
        return $this->estado;
    }

    
}

