<?php

class RestricaoPremio extends TRecord
{
    const TABLENAME  = 'restricao_premio';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Premio $premio;
    private ApGrupoEstoque $ap_grupo_estoque;
    private ApSubgrupoEstoque $ap_subgrupo_estoque;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('premio_id');
        parent::addAttribute('ap_grupo_estoque_id');
        parent::addAttribute('ap_subgrupo_estoque_id');
            
    }

    /**
     * Method set_premio
     * Sample of usage: $var->premio = $object;
     * @param $object Instance of Premio
     */
    public function set_premio(Premio $object)
    {
        $this->premio = $object;
        $this->premio_id = $object->id;
    }

    /**
     * Method get_premio
     * Sample of usage: $var->premio->attribute;
     * @returns Premio instance
     */
    public function get_premio()
    {
    
        // loads the associated object
        if (empty($this->premio))
            $this->premio = new Premio($this->premio_id);
    
        // returns the associated object
        return $this->premio;
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
     * Method set_ap_subgrupo_estoque
     * Sample of usage: $var->ap_subgrupo_estoque = $object;
     * @param $object Instance of ApSubgrupoEstoque
     */
    public function set_ap_subgrupo_estoque(ApSubgrupoEstoque $object)
    {
        $this->ap_subgrupo_estoque = $object;
        $this->ap_subgrupo_estoque_id = $object->id;
    }

    /**
     * Method get_ap_subgrupo_estoque
     * Sample of usage: $var->ap_subgrupo_estoque->attribute;
     * @returns ApSubgrupoEstoque instance
     */
    public function get_ap_subgrupo_estoque()
    {
    
        // loads the associated object
        if (empty($this->ap_subgrupo_estoque))
            $this->ap_subgrupo_estoque = new ApSubgrupoEstoque($this->ap_subgrupo_estoque_id);
    
        // returns the associated object
        return $this->ap_subgrupo_estoque;
    }

    
}

