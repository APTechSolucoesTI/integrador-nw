<?php

class ApFamiliaComercial extends TRecord
{
    const TABLENAME  = 'ap_familia_comercial';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private SystemUnit $system_unit;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_unit_id');
        parent::addAttribute('descricao');
        parent::addAttribute('cod_fmcomercial');
            
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
     * Method getHistoricoVendaItems
     */
    public function getHistoricoVendaItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('familia_comercial_id', '=', $this->id));
        return HistoricoVendaItem::getObjects( $criteria );
    }
    /**
     * Method getApItems
     */
    public function getApItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('familia_comercial_id', '=', $this->id));
        return ApItem::getObjects( $criteria );
    }

    public function set_historico_venda_item_venda_to_string($historico_venda_item_venda_to_string)
    {
        if(is_array($historico_venda_item_venda_to_string))
        {
            $values = HistoricoVenda::where('id', 'in', $historico_venda_item_venda_to_string)->getIndexedArray('id', 'id');
            $this->historico_venda_item_venda_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_venda_item_venda_to_string = $historico_venda_item_venda_to_string;
        }

        $this->vdata['historico_venda_item_venda_to_string'] = $this->historico_venda_item_venda_to_string;
    }

    public function get_historico_venda_item_venda_to_string()
    {
        if(!empty($this->historico_venda_item_venda_to_string))
        {
            return $this->historico_venda_item_venda_to_string;
        }
    
        $values = HistoricoVendaItem::where('familia_comercial_id', '=', $this->id)->getIndexedArray('venda_id','{venda->id}');
        return implode(', ', $values);
    }

    public function set_historico_venda_item_grupo_estoque_to_string($historico_venda_item_grupo_estoque_to_string)
    {
        if(is_array($historico_venda_item_grupo_estoque_to_string))
        {
            $values = ApGrupoEstoque::where('id', 'in', $historico_venda_item_grupo_estoque_to_string)->getIndexedArray('descricao', 'descricao');
            $this->historico_venda_item_grupo_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_venda_item_grupo_estoque_to_string = $historico_venda_item_grupo_estoque_to_string;
        }

        $this->vdata['historico_venda_item_grupo_estoque_to_string'] = $this->historico_venda_item_grupo_estoque_to_string;
    }

    public function get_historico_venda_item_grupo_estoque_to_string()
    {
        if(!empty($this->historico_venda_item_grupo_estoque_to_string))
        {
            return $this->historico_venda_item_grupo_estoque_to_string;
        }
    
        $values = HistoricoVendaItem::where('familia_comercial_id', '=', $this->id)->getIndexedArray('grupo_estoque_id','{grupo_estoque->descricao}');
        return implode(', ', $values);
    }

    public function set_historico_venda_item_subgrupo_estoque_to_string($historico_venda_item_subgrupo_estoque_to_string)
    {
        if(is_array($historico_venda_item_subgrupo_estoque_to_string))
        {
            $values = ApSubgrupoEstoque::where('id', 'in', $historico_venda_item_subgrupo_estoque_to_string)->getIndexedArray('descricao', 'descricao');
            $this->historico_venda_item_subgrupo_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_venda_item_subgrupo_estoque_to_string = $historico_venda_item_subgrupo_estoque_to_string;
        }

        $this->vdata['historico_venda_item_subgrupo_estoque_to_string'] = $this->historico_venda_item_subgrupo_estoque_to_string;
    }

    public function get_historico_venda_item_subgrupo_estoque_to_string()
    {
        if(!empty($this->historico_venda_item_subgrupo_estoque_to_string))
        {
            return $this->historico_venda_item_subgrupo_estoque_to_string;
        }
    
        $values = HistoricoVendaItem::where('familia_comercial_id', '=', $this->id)->getIndexedArray('subgrupo_estoque_id','{subgrupo_estoque->descricao}');
        return implode(', ', $values);
    }

    public function set_historico_venda_item_familia_comercial_to_string($historico_venda_item_familia_comercial_to_string)
    {
        if(is_array($historico_venda_item_familia_comercial_to_string))
        {
            $values = ApFamiliaComercial::where('id', 'in', $historico_venda_item_familia_comercial_to_string)->getIndexedArray('descricao', 'descricao');
            $this->historico_venda_item_familia_comercial_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_venda_item_familia_comercial_to_string = $historico_venda_item_familia_comercial_to_string;
        }

        $this->vdata['historico_venda_item_familia_comercial_to_string'] = $this->historico_venda_item_familia_comercial_to_string;
    }

    public function get_historico_venda_item_familia_comercial_to_string()
    {
        if(!empty($this->historico_venda_item_familia_comercial_to_string))
        {
            return $this->historico_venda_item_familia_comercial_to_string;
        }
    
        $values = HistoricoVendaItem::where('familia_comercial_id', '=', $this->id)->getIndexedArray('familia_comercial_id','{familia_comercial->descricao}');
        return implode(', ', $values);
    }

    public function set_historico_venda_item_familia_industrial_to_string($historico_venda_item_familia_industrial_to_string)
    {
        if(is_array($historico_venda_item_familia_industrial_to_string))
        {
            $values = ApFamiliaIndustrial::where('id', 'in', $historico_venda_item_familia_industrial_to_string)->getIndexedArray('descricao', 'descricao');
            $this->historico_venda_item_familia_industrial_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_venda_item_familia_industrial_to_string = $historico_venda_item_familia_industrial_to_string;
        }

        $this->vdata['historico_venda_item_familia_industrial_to_string'] = $this->historico_venda_item_familia_industrial_to_string;
    }

    public function get_historico_venda_item_familia_industrial_to_string()
    {
        if(!empty($this->historico_venda_item_familia_industrial_to_string))
        {
            return $this->historico_venda_item_familia_industrial_to_string;
        }
    
        $values = HistoricoVendaItem::where('familia_comercial_id', '=', $this->id)->getIndexedArray('familia_industrial_id','{familia_industrial->descricao}');
        return implode(', ', $values);
    }

    public function set_ap_item_system_unit_to_string($ap_item_system_unit_to_string)
    {
        if(is_array($ap_item_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $ap_item_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->ap_item_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_item_system_unit_to_string = $ap_item_system_unit_to_string;
        }

        $this->vdata['ap_item_system_unit_to_string'] = $this->ap_item_system_unit_to_string;
    }

    public function get_ap_item_system_unit_to_string()
    {
        if(!empty($this->ap_item_system_unit_to_string))
        {
            return $this->ap_item_system_unit_to_string;
        }
    
        $values = ApItem::where('familia_comercial_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_ap_item_subgrupo_estoque_to_string($ap_item_subgrupo_estoque_to_string)
    {
        if(is_array($ap_item_subgrupo_estoque_to_string))
        {
            $values = ApSubgrupoEstoque::where('id', 'in', $ap_item_subgrupo_estoque_to_string)->getIndexedArray('descricao', 'descricao');
            $this->ap_item_subgrupo_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_item_subgrupo_estoque_to_string = $ap_item_subgrupo_estoque_to_string;
        }

        $this->vdata['ap_item_subgrupo_estoque_to_string'] = $this->ap_item_subgrupo_estoque_to_string;
    }

    public function get_ap_item_subgrupo_estoque_to_string()
    {
        if(!empty($this->ap_item_subgrupo_estoque_to_string))
        {
            return $this->ap_item_subgrupo_estoque_to_string;
        }
    
        $values = ApItem::where('familia_comercial_id', '=', $this->id)->getIndexedArray('subgrupo_estoque_id','{subgrupo_estoque->descricao}');
        return implode(', ', $values);
    }

    public function set_ap_item_grupo_estoque_to_string($ap_item_grupo_estoque_to_string)
    {
        if(is_array($ap_item_grupo_estoque_to_string))
        {
            $values = ApGrupoEstoque::where('id', 'in', $ap_item_grupo_estoque_to_string)->getIndexedArray('descricao', 'descricao');
            $this->ap_item_grupo_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_item_grupo_estoque_to_string = $ap_item_grupo_estoque_to_string;
        }

        $this->vdata['ap_item_grupo_estoque_to_string'] = $this->ap_item_grupo_estoque_to_string;
    }

    public function get_ap_item_grupo_estoque_to_string()
    {
        if(!empty($this->ap_item_grupo_estoque_to_string))
        {
            return $this->ap_item_grupo_estoque_to_string;
        }
    
        $values = ApItem::where('familia_comercial_id', '=', $this->id)->getIndexedArray('grupo_estoque_id','{grupo_estoque->descricao}');
        return implode(', ', $values);
    }

    public function set_ap_item_familia_comercial_to_string($ap_item_familia_comercial_to_string)
    {
        if(is_array($ap_item_familia_comercial_to_string))
        {
            $values = ApFamiliaComercial::where('id', 'in', $ap_item_familia_comercial_to_string)->getIndexedArray('descricao', 'descricao');
            $this->ap_item_familia_comercial_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_item_familia_comercial_to_string = $ap_item_familia_comercial_to_string;
        }

        $this->vdata['ap_item_familia_comercial_to_string'] = $this->ap_item_familia_comercial_to_string;
    }

    public function get_ap_item_familia_comercial_to_string()
    {
        if(!empty($this->ap_item_familia_comercial_to_string))
        {
            return $this->ap_item_familia_comercial_to_string;
        }
    
        $values = ApItem::where('familia_comercial_id', '=', $this->id)->getIndexedArray('familia_comercial_id','{familia_comercial->descricao}');
        return implode(', ', $values);
    }

    public function set_ap_item_familia_industrial_to_string($ap_item_familia_industrial_to_string)
    {
        if(is_array($ap_item_familia_industrial_to_string))
        {
            $values = ApFamiliaIndustrial::where('id', 'in', $ap_item_familia_industrial_to_string)->getIndexedArray('descricao', 'descricao');
            $this->ap_item_familia_industrial_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_item_familia_industrial_to_string = $ap_item_familia_industrial_to_string;
        }

        $this->vdata['ap_item_familia_industrial_to_string'] = $this->ap_item_familia_industrial_to_string;
    }

    public function get_ap_item_familia_industrial_to_string()
    {
        if(!empty($this->ap_item_familia_industrial_to_string))
        {
            return $this->ap_item_familia_industrial_to_string;
        }
    
        $values = ApItem::where('familia_comercial_id', '=', $this->id)->getIndexedArray('familia_industrial_id','{familia_industrial->descricao}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(HistoricoVendaItem::where('familia_comercial_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(ApItem::where('familia_comercial_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

