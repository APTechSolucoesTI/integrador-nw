<?php

class HistoricoVendaItem extends TRecord
{
    const TABLENAME  = 'historico_venda_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private HistoricoVenda $venda;
    private ApGrupoEstoque $grupo_estoque;
    private ApSubgrupoEstoque $subgrupo_estoque;
    private ApFamiliaIndustrial $familia_industrial;
    private ApFamiliaComercial $familia_comercial;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('empresa');
        parent::addAttribute('venda_id');
        parent::addAttribute('cod_item');
        parent::addAttribute('descricao');
        parent::addAttribute('grupo_estoque_id');
        parent::addAttribute('subgrupo_estoque_id');
        parent::addAttribute('familia_comercial_id');
        parent::addAttribute('familia_industrial_id');
        parent::addAttribute('quantidade');
        parent::addAttribute('valor_unitario');
        parent::addAttribute('valor_mercadoria');
        parent::addAttribute('perc_desconto');
        parent::addAttribute('valor_desconto');
        parent::addAttribute('valor_total');
        parent::addAttribute('sequencia');
            
    }

    /**
     * Method set_historico_venda
     * Sample of usage: $var->historico_venda = $object;
     * @param $object Instance of HistoricoVenda
     */
    public function set_venda(HistoricoVenda $object)
    {
        $this->venda = $object;
        $this->venda_id = $object->id;
    }

    /**
     * Method get_venda
     * Sample of usage: $var->venda->attribute;
     * @returns HistoricoVenda instance
     */
    public function get_venda()
    {
    
        // loads the associated object
        if (empty($this->venda))
            $this->venda = new HistoricoVenda($this->venda_id);
    
        // returns the associated object
        return $this->venda;
    }
    /**
     * Method set_ap_grupo_estoque
     * Sample of usage: $var->ap_grupo_estoque = $object;
     * @param $object Instance of ApGrupoEstoque
     */
    public function set_grupo_estoque(ApGrupoEstoque $object)
    {
        $this->grupo_estoque = $object;
        $this->grupo_estoque_id = $object->id;
    }

    /**
     * Method get_grupo_estoque
     * Sample of usage: $var->grupo_estoque->attribute;
     * @returns ApGrupoEstoque instance
     */
    public function get_grupo_estoque()
    {
    
        // loads the associated object
        if (empty($this->grupo_estoque))
            $this->grupo_estoque = new ApGrupoEstoque($this->grupo_estoque_id);
    
        // returns the associated object
        return $this->grupo_estoque;
    }
    /**
     * Method set_ap_subgrupo_estoque
     * Sample of usage: $var->ap_subgrupo_estoque = $object;
     * @param $object Instance of ApSubgrupoEstoque
     */
    public function set_subgrupo_estoque(ApSubgrupoEstoque $object)
    {
        $this->subgrupo_estoque = $object;
        $this->subgrupo_estoque_id = $object->id;
    }

    /**
     * Method get_subgrupo_estoque
     * Sample of usage: $var->subgrupo_estoque->attribute;
     * @returns ApSubgrupoEstoque instance
     */
    public function get_subgrupo_estoque()
    {
    
        // loads the associated object
        if (empty($this->subgrupo_estoque))
            $this->subgrupo_estoque = new ApSubgrupoEstoque($this->subgrupo_estoque_id);
    
        // returns the associated object
        return $this->subgrupo_estoque;
    }
    /**
     * Method set_ap_familia_industrial
     * Sample of usage: $var->ap_familia_industrial = $object;
     * @param $object Instance of ApFamiliaIndustrial
     */
    public function set_familia_industrial(ApFamiliaIndustrial $object)
    {
        $this->familia_industrial = $object;
        $this->familia_industrial_id = $object->id;
    }

    /**
     * Method get_familia_industrial
     * Sample of usage: $var->familia_industrial->attribute;
     * @returns ApFamiliaIndustrial instance
     */
    public function get_familia_industrial()
    {
    
        // loads the associated object
        if (empty($this->familia_industrial))
            $this->familia_industrial = new ApFamiliaIndustrial($this->familia_industrial_id);
    
        // returns the associated object
        return $this->familia_industrial;
    }
    /**
     * Method set_ap_familia_comercial
     * Sample of usage: $var->ap_familia_comercial = $object;
     * @param $object Instance of ApFamiliaComercial
     */
    public function set_familia_comercial(ApFamiliaComercial $object)
    {
        $this->familia_comercial = $object;
        $this->familia_comercial_id = $object->id;
    }

    /**
     * Method get_familia_comercial
     * Sample of usage: $var->familia_comercial->attribute;
     * @returns ApFamiliaComercial instance
     */
    public function get_familia_comercial()
    {
    
        // loads the associated object
        if (empty($this->familia_comercial))
            $this->familia_comercial = new ApFamiliaComercial($this->familia_comercial_id);
    
        // returns the associated object
        return $this->familia_comercial;
    }

    
}

