<?php

class HistoricoVenda extends TRecord
{
    const TABLENAME  = 'historico_venda';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private SystemUnit $system_unit;
    private ApCidade $cidade;
    private ApGrupoCliente $grupo_cliente;
    private ApRepresentante $repres;
    private Meta $meta;
    private ApEstado $estado;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('meta_id');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('data_emissao');
        parent::addAttribute('data_cadastro');
        parent::addAttribute('nro');
        parent::addAttribute('tipo_pessoa');
        parent::addAttribute('cod_clifor');
        parent::addAttribute('razao');
        parent::addAttribute('agente');
        parent::addAttribute('repres_id');
        parent::addAttribute('grupo_cliente_id');
        parent::addAttribute('cidade_id');
        parent::addAttribute('estado_id');
        parent::addAttribute('rota');
    
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
     * Method set_ap_grupo_cliente
     * Sample of usage: $var->ap_grupo_cliente = $object;
     * @param $object Instance of ApGrupoCliente
     */
    public function set_grupo_cliente(ApGrupoCliente $object)
    {
        $this->grupo_cliente = $object;
        $this->grupo_cliente_id = $object->id;
    }

    /**
     * Method get_grupo_cliente
     * Sample of usage: $var->grupo_cliente->attribute;
     * @returns ApGrupoCliente instance
     */
    public function get_grupo_cliente()
    {
    
        // loads the associated object
        if (empty($this->grupo_cliente))
            $this->grupo_cliente = new ApGrupoCliente($this->grupo_cliente_id);
    
        // returns the associated object
        return $this->grupo_cliente;
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
     * Method set_meta
     * Sample of usage: $var->meta = $object;
     * @param $object Instance of Meta
     */
    public function set_meta(Meta $object)
    {
        $this->meta = $object;
        $this->meta_id = $object->id;
    }

    /**
     * Method get_meta
     * Sample of usage: $var->meta->attribute;
     * @returns Meta instance
     */
    public function get_meta()
    {
    
        // loads the associated object
        if (empty($this->meta))
            $this->meta = new Meta($this->meta_id);
    
        // returns the associated object
        return $this->meta;
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

    /**
     * Method getHistoricoVendaItems
     */
    public function getHistoricoVendaItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('venda_id', '=', $this->id));
        return HistoricoVendaItem::getObjects( $criteria );
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
    
        $values = HistoricoVendaItem::where('venda_id', '=', $this->id)->getIndexedArray('venda_id','{venda->id}');
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
    
        $values = HistoricoVendaItem::where('venda_id', '=', $this->id)->getIndexedArray('grupo_estoque_id','{grupo_estoque->descricao}');
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
    
        $values = HistoricoVendaItem::where('venda_id', '=', $this->id)->getIndexedArray('subgrupo_estoque_id','{subgrupo_estoque->descricao}');
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
    
        $values = HistoricoVendaItem::where('venda_id', '=', $this->id)->getIndexedArray('familia_comercial_id','{familia_comercial->descricao}');
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
    
        $values = HistoricoVendaItem::where('venda_id', '=', $this->id)->getIndexedArray('familia_industrial_id','{familia_industrial->descricao}');
        return implode(', ', $values);
    }

    public function get_valor_total()
    {
        if(!empty($this->valor_total))
        {
            return $this->valor_total;
        }
    
        $itens = HistoricoVendaItem::where('venda_id', '=', $this->id)->load();
    
        $value = 0;
    
        foreach($itens as $item){
            $value += $item->valor_total;
        }
    
        return $value;
    }

}

