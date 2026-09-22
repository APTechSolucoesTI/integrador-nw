<?php

class PersianaAgrupamentoGrupo extends TRecord
{
    const TABLENAME  = 'persiana_agrupamento_grupo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApGrupoEstoque $ap_grupo_estoque;
    private PersianaAgrupamento $persiana_agrupamento;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('persiana_agrupamento_id');
        parent::addAttribute('ap_grupo_estoque_id');
            
    }

    /**
     * Method set_ap_grupo_estoque
     * Sample of usage: $var->ap_grupo_estoque = $object;
     * @param $object Instance of ApGrupoEstoque
     */
    public function set_ap_grupo_estoque(ApGrupoEstoque $object)
    {
        $this->ap_grupo_estoque = $object;
        $this->ap_grupo_estoque_id = $object->id;
    }

    /**
     * Method get_ap_grupo_estoque
     * Sample of usage: $var->ap_grupo_estoque->attribute;
     * @returns ApGrupoEstoque instance
     */
    public function get_ap_grupo_estoque()
    {
    
        // loads the associated object
        if (empty($this->ap_grupo_estoque))
            $this->ap_grupo_estoque = new ApGrupoEstoque($this->ap_grupo_estoque_id);
    
        // returns the associated object
        return $this->ap_grupo_estoque;
    }
    /**
     * Method set_persiana_agrupamento
     * Sample of usage: $var->persiana_agrupamento = $object;
     * @param $object Instance of PersianaAgrupamento
     */
    public function set_persiana_agrupamento(PersianaAgrupamento $object)
    {
        $this->persiana_agrupamento = $object;
        $this->persiana_agrupamento_id = $object->id;
    }

    /**
     * Method get_persiana_agrupamento
     * Sample of usage: $var->persiana_agrupamento->attribute;
     * @returns PersianaAgrupamento instance
     */
    public function get_persiana_agrupamento()
    {
    
        // loads the associated object
        if (empty($this->persiana_agrupamento))
            $this->persiana_agrupamento = new PersianaAgrupamento($this->persiana_agrupamento_id);
    
        // returns the associated object
        return $this->persiana_agrupamento;
    }

    
}

