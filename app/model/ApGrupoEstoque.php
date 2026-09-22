<?php

class ApGrupoEstoque extends TRecord
{
    const TABLENAME  = 'ap_grupo_estoque';
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
        parent::addAttribute('cod_grupoestoque');
        parent::addAttribute('descricao');
        parent::addAttribute('sequencia_exibicao');
            
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
        $criteria->add(new TFilter('grupo_estoque_id', '=', $this->id));
        return HistoricoVendaItem::getObjects( $criteria );
    }
    /**
     * Method getApSubgrupoEstoques
     */
    public function getApSubgrupoEstoques()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('grupo_estoque_id', '=', $this->id));
        return ApSubgrupoEstoque::getObjects( $criteria );
    }
    /**
     * Method getApItems
     */
    public function getApItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('grupo_estoque_id', '=', $this->id));
        return ApItem::getObjects( $criteria );
    }
    /**
     * Method getRestricaoPremios
     */
    public function getRestricaoPremios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_grupo_estoque_id', '=', $this->id));
        return RestricaoPremio::getObjects( $criteria );
    }
    /**
     * Method getPersianaAgrupamentoGrupos
     */
    public function getPersianaAgrupamentoGrupos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_grupo_estoque_id', '=', $this->id));
        return PersianaAgrupamentoGrupo::getObjects( $criteria );
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
    
        $values = HistoricoVendaItem::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('venda_id','{venda->id}');
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
    
        $values = HistoricoVendaItem::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('grupo_estoque_id','{grupo_estoque->descricao}');
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
    
        $values = HistoricoVendaItem::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('subgrupo_estoque_id','{subgrupo_estoque->descricao}');
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
    
        $values = HistoricoVendaItem::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('familia_comercial_id','{familia_comercial->descricao}');
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
    
        $values = HistoricoVendaItem::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('familia_industrial_id','{familia_industrial->descricao}');
        return implode(', ', $values);
    }

    public function set_ap_subgrupo_estoque_system_unit_to_string($ap_subgrupo_estoque_system_unit_to_string)
    {
        if(is_array($ap_subgrupo_estoque_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $ap_subgrupo_estoque_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->ap_subgrupo_estoque_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_subgrupo_estoque_system_unit_to_string = $ap_subgrupo_estoque_system_unit_to_string;
        }

        $this->vdata['ap_subgrupo_estoque_system_unit_to_string'] = $this->ap_subgrupo_estoque_system_unit_to_string;
    }

    public function get_ap_subgrupo_estoque_system_unit_to_string()
    {
        if(!empty($this->ap_subgrupo_estoque_system_unit_to_string))
        {
            return $this->ap_subgrupo_estoque_system_unit_to_string;
        }
    
        $values = ApSubgrupoEstoque::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_ap_subgrupo_estoque_grupo_estoque_to_string($ap_subgrupo_estoque_grupo_estoque_to_string)
    {
        if(is_array($ap_subgrupo_estoque_grupo_estoque_to_string))
        {
            $values = ApGrupoEstoque::where('id', 'in', $ap_subgrupo_estoque_grupo_estoque_to_string)->getIndexedArray('descricao', 'descricao');
            $this->ap_subgrupo_estoque_grupo_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_subgrupo_estoque_grupo_estoque_to_string = $ap_subgrupo_estoque_grupo_estoque_to_string;
        }

        $this->vdata['ap_subgrupo_estoque_grupo_estoque_to_string'] = $this->ap_subgrupo_estoque_grupo_estoque_to_string;
    }

    public function get_ap_subgrupo_estoque_grupo_estoque_to_string()
    {
        if(!empty($this->ap_subgrupo_estoque_grupo_estoque_to_string))
        {
            return $this->ap_subgrupo_estoque_grupo_estoque_to_string;
        }
    
        $values = ApSubgrupoEstoque::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('grupo_estoque_id','{grupo_estoque->descricao}');
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
    
        $values = ApItem::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = ApItem::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('subgrupo_estoque_id','{subgrupo_estoque->descricao}');
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
    
        $values = ApItem::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('grupo_estoque_id','{grupo_estoque->descricao}');
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
    
        $values = ApItem::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('familia_comercial_id','{familia_comercial->descricao}');
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
    
        $values = ApItem::where('grupo_estoque_id', '=', $this->id)->getIndexedArray('familia_industrial_id','{familia_industrial->descricao}');
        return implode(', ', $values);
    }

    public function set_restricao_premio_premio_to_string($restricao_premio_premio_to_string)
    {
        if(is_array($restricao_premio_premio_to_string))
        {
            $values = Premio::where('id', 'in', $restricao_premio_premio_to_string)->getIndexedArray('id', 'id');
            $this->restricao_premio_premio_to_string = implode(', ', $values);
        }
        else
        {
            $this->restricao_premio_premio_to_string = $restricao_premio_premio_to_string;
        }

        $this->vdata['restricao_premio_premio_to_string'] = $this->restricao_premio_premio_to_string;
    }

    public function get_restricao_premio_premio_to_string()
    {
        if(!empty($this->restricao_premio_premio_to_string))
        {
            return $this->restricao_premio_premio_to_string;
        }
    
        $values = RestricaoPremio::where('ap_grupo_estoque_id', '=', $this->id)->getIndexedArray('premio_id','{premio->id}');
        return implode(', ', $values);
    }

    public function set_restricao_premio_ap_grupo_estoque_to_string($restricao_premio_ap_grupo_estoque_to_string)
    {
        if(is_array($restricao_premio_ap_grupo_estoque_to_string))
        {
            $values = ApGrupoEstoque::where('id', 'in', $restricao_premio_ap_grupo_estoque_to_string)->getIndexedArray('descricao', 'descricao');
            $this->restricao_premio_ap_grupo_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->restricao_premio_ap_grupo_estoque_to_string = $restricao_premio_ap_grupo_estoque_to_string;
        }

        $this->vdata['restricao_premio_ap_grupo_estoque_to_string'] = $this->restricao_premio_ap_grupo_estoque_to_string;
    }

    public function get_restricao_premio_ap_grupo_estoque_to_string()
    {
        if(!empty($this->restricao_premio_ap_grupo_estoque_to_string))
        {
            return $this->restricao_premio_ap_grupo_estoque_to_string;
        }
    
        $values = RestricaoPremio::where('ap_grupo_estoque_id', '=', $this->id)->getIndexedArray('ap_grupo_estoque_id','{ap_grupo_estoque->descricao}');
        return implode(', ', $values);
    }

    public function set_restricao_premio_ap_subgrupo_estoque_to_string($restricao_premio_ap_subgrupo_estoque_to_string)
    {
        if(is_array($restricao_premio_ap_subgrupo_estoque_to_string))
        {
            $values = ApSubgrupoEstoque::where('id', 'in', $restricao_premio_ap_subgrupo_estoque_to_string)->getIndexedArray('descricao', 'descricao');
            $this->restricao_premio_ap_subgrupo_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->restricao_premio_ap_subgrupo_estoque_to_string = $restricao_premio_ap_subgrupo_estoque_to_string;
        }

        $this->vdata['restricao_premio_ap_subgrupo_estoque_to_string'] = $this->restricao_premio_ap_subgrupo_estoque_to_string;
    }

    public function get_restricao_premio_ap_subgrupo_estoque_to_string()
    {
        if(!empty($this->restricao_premio_ap_subgrupo_estoque_to_string))
        {
            return $this->restricao_premio_ap_subgrupo_estoque_to_string;
        }
    
        $values = RestricaoPremio::where('ap_grupo_estoque_id', '=', $this->id)->getIndexedArray('ap_subgrupo_estoque_id','{ap_subgrupo_estoque->descricao}');
        return implode(', ', $values);
    }

    public function set_persiana_agrupamento_grupo_persiana_agrupamento_to_string($persiana_agrupamento_grupo_persiana_agrupamento_to_string)
    {
        if(is_array($persiana_agrupamento_grupo_persiana_agrupamento_to_string))
        {
            $values = PersianaAgrupamento::where('id', 'in', $persiana_agrupamento_grupo_persiana_agrupamento_to_string)->getIndexedArray('id', 'id');
            $this->persiana_agrupamento_grupo_persiana_agrupamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->persiana_agrupamento_grupo_persiana_agrupamento_to_string = $persiana_agrupamento_grupo_persiana_agrupamento_to_string;
        }

        $this->vdata['persiana_agrupamento_grupo_persiana_agrupamento_to_string'] = $this->persiana_agrupamento_grupo_persiana_agrupamento_to_string;
    }

    public function get_persiana_agrupamento_grupo_persiana_agrupamento_to_string()
    {
        if(!empty($this->persiana_agrupamento_grupo_persiana_agrupamento_to_string))
        {
            return $this->persiana_agrupamento_grupo_persiana_agrupamento_to_string;
        }
    
        $values = PersianaAgrupamentoGrupo::where('ap_grupo_estoque_id', '=', $this->id)->getIndexedArray('persiana_agrupamento_id','{persiana_agrupamento->id}');
        return implode(', ', $values);
    }

    public function set_persiana_agrupamento_grupo_ap_grupo_estoque_to_string($persiana_agrupamento_grupo_ap_grupo_estoque_to_string)
    {
        if(is_array($persiana_agrupamento_grupo_ap_grupo_estoque_to_string))
        {
            $values = ApGrupoEstoque::where('id', 'in', $persiana_agrupamento_grupo_ap_grupo_estoque_to_string)->getIndexedArray('descricao', 'descricao');
            $this->persiana_agrupamento_grupo_ap_grupo_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->persiana_agrupamento_grupo_ap_grupo_estoque_to_string = $persiana_agrupamento_grupo_ap_grupo_estoque_to_string;
        }

        $this->vdata['persiana_agrupamento_grupo_ap_grupo_estoque_to_string'] = $this->persiana_agrupamento_grupo_ap_grupo_estoque_to_string;
    }

    public function get_persiana_agrupamento_grupo_ap_grupo_estoque_to_string()
    {
        if(!empty($this->persiana_agrupamento_grupo_ap_grupo_estoque_to_string))
        {
            return $this->persiana_agrupamento_grupo_ap_grupo_estoque_to_string;
        }
    
        $values = PersianaAgrupamentoGrupo::where('ap_grupo_estoque_id', '=', $this->id)->getIndexedArray('ap_grupo_estoque_id','{ap_grupo_estoque->descricao}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(HistoricoVendaItem::where('grupo_estoque_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(ApSubgrupoEstoque::where('grupo_estoque_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(ApItem::where('grupo_estoque_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(RestricaoPremio::where('ap_grupo_estoque_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(PersianaAgrupamentoGrupo::where('ap_grupo_estoque_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

