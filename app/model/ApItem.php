<?php

class ApItem extends TRecord
{
    const TABLENAME  = 'ap_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApSubgrupoEstoque $subgrupo_estoque;
    private ApGrupoEstoque $grupo_estoque;
    private ApFamiliaComercial $familia_comercial;
    private ApFamiliaIndustrial $familia_industrial;
    private SystemUnit $system_unit;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_unit_id');
        parent::addAttribute('cod_item');
        parent::addAttribute('cod_clifor');
        parent::addAttribute('subgrupo_estoque_id');
        parent::addAttribute('grupo_estoque_id');
        parent::addAttribute('familia_comercial_id');
        parent::addAttribute('familia_industrial_id');
        parent::addAttribute('codigo');
        parent::addAttribute('descricao');
        parent::addAttribute('cod_unidade');
        parent::addAttribute('ativo');
        parent::addAttribute('ap_fm_industrial_id_teste');
            
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
     * Method getAguardoProdutos
     */
    public function getAguardoProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_item_id', '=', $this->id));
        return AguardoProduto::getObjects( $criteria );
    }

    public function set_aguardo_produto_system_unit_to_string($aguardo_produto_system_unit_to_string)
    {
        if(is_array($aguardo_produto_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $aguardo_produto_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->aguardo_produto_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->aguardo_produto_system_unit_to_string = $aguardo_produto_system_unit_to_string;
        }

        $this->vdata['aguardo_produto_system_unit_to_string'] = $this->aguardo_produto_system_unit_to_string;
    }

    public function get_aguardo_produto_system_unit_to_string()
    {
        if(!empty($this->aguardo_produto_system_unit_to_string))
        {
            return $this->aguardo_produto_system_unit_to_string;
        }
    
        $values = AguardoProduto::where('ap_item_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_aguardo_produto_system_users_to_string($aguardo_produto_system_users_to_string)
    {
        if(is_array($aguardo_produto_system_users_to_string))
        {
            $values = SystemUsers::where('id', 'in', $aguardo_produto_system_users_to_string)->getIndexedArray('name', 'name');
            $this->aguardo_produto_system_users_to_string = implode(', ', $values);
        }
        else
        {
            $this->aguardo_produto_system_users_to_string = $aguardo_produto_system_users_to_string;
        }

        $this->vdata['aguardo_produto_system_users_to_string'] = $this->aguardo_produto_system_users_to_string;
    }

    public function get_aguardo_produto_system_users_to_string()
    {
        if(!empty($this->aguardo_produto_system_users_to_string))
        {
            return $this->aguardo_produto_system_users_to_string;
        }
    
        $values = AguardoProduto::where('ap_item_id', '=', $this->id)->getIndexedArray('system_users_id','{system_users->name}');
        return implode(', ', $values);
    }

    public function set_aguardo_produto_repres_to_string($aguardo_produto_repres_to_string)
    {
        if(is_array($aguardo_produto_repres_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $aguardo_produto_repres_to_string)->getIndexedArray('id', 'id');
            $this->aguardo_produto_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->aguardo_produto_repres_to_string = $aguardo_produto_repres_to_string;
        }

        $this->vdata['aguardo_produto_repres_to_string'] = $this->aguardo_produto_repres_to_string;
    }

    public function get_aguardo_produto_repres_to_string()
    {
        if(!empty($this->aguardo_produto_repres_to_string))
        {
            return $this->aguardo_produto_repres_to_string;
        }
    
        $values = AguardoProduto::where('ap_item_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
        return implode(', ', $values);
    }

    public function set_aguardo_produto_ap_item_to_string($aguardo_produto_ap_item_to_string)
    {
        if(is_array($aguardo_produto_ap_item_to_string))
        {
            $values = ApItem::where('id', 'in', $aguardo_produto_ap_item_to_string)->getIndexedArray('id', 'id');
            $this->aguardo_produto_ap_item_to_string = implode(', ', $values);
        }
        else
        {
            $this->aguardo_produto_ap_item_to_string = $aguardo_produto_ap_item_to_string;
        }

        $this->vdata['aguardo_produto_ap_item_to_string'] = $this->aguardo_produto_ap_item_to_string;
    }

    public function get_aguardo_produto_ap_item_to_string()
    {
        if(!empty($this->aguardo_produto_ap_item_to_string))
        {
            return $this->aguardo_produto_ap_item_to_string;
        }
    
        $values = AguardoProduto::where('ap_item_id', '=', $this->id)->getIndexedArray('ap_item_id','{ap_item->id}');
        return implode(', ', $values);
    }

    
}

