<?php

class SystemUnit extends TRecord
{
    const TABLENAME  = 'system_unit';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name');
        parent::addAttribute('connection_name');
            
    }

    /**
     * Method getApTabelaPrecos
     */
    public function getApTabelaPrecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return ApTabelaPreco::getObjects( $criteria );
    }
    /**
     * Method getGrupos
     */
    public function getGrupos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return Grupo::getObjects( $criteria );
    }
    /**
     * Method getRegraProspeccaos
     */
    public function getRegraProspeccaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return RegraProspeccao::getObjects( $criteria );
    }
    /**
     * Method getMetas
     */
    public function getMetas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return Meta::getObjects( $criteria );
    }
    /**
     * Method getHistoricoVendas
     */
    public function getHistoricoVendas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return HistoricoVenda::getObjects( $criteria );
    }
    /**
     * Method getApGrupoEstoques
     */
    public function getApGrupoEstoques()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return ApGrupoEstoque::getObjects( $criteria );
    }
    /**
     * Method getAguardoProdutos
     */
    public function getAguardoProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return AguardoProduto::getObjects( $criteria );
    }
    /**
     * Method getApItems
     */
    public function getApItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return ApItem::getObjects( $criteria );
    }
    /**
     * Method getApFamiliaComercials
     */
    public function getApFamiliaComercials()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return ApFamiliaComercial::getObjects( $criteria );
    }
    /**
     * Method getApFamiliaIndustrials
     */
    public function getApFamiliaIndustrials()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return ApFamiliaIndustrial::getObjects( $criteria );
    }
    /**
     * Method getApSubgrupoEstoques
     */
    public function getApSubgrupoEstoques()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return ApSubgrupoEstoque::getObjects( $criteria );
    }
    /**
     * Method getApRepresentantes
     */
    public function getApRepresentantes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return ApRepresentante::getObjects( $criteria );
    }
    /**
     * Method getHistoricoCliRepress
     */
    public function getHistoricoCliRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return HistoricoCliRepres::getObjects( $criteria );
    }
    /**
     * Method getApGrupoClientes
     */
    public function getApGrupoClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return ApGrupoCliente::getObjects( $criteria );
    }
    /**
     * Method getMiniMetas
     */
    public function getMiniMetas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return MiniMeta::getObjects( $criteria );
    }
    /**
     * Method getLogCrontabs
     */
    public function getLogCrontabs()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return LogCrontab::getObjects( $criteria );
    }
    /**
     * Method getCampanhas
     */
    public function getCampanhas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return Campanha::getObjects( $criteria );
    }

    public function set_ap_tabela_preco_system_unit_to_string($ap_tabela_preco_system_unit_to_string)
    {
        if(is_array($ap_tabela_preco_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $ap_tabela_preco_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->ap_tabela_preco_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_tabela_preco_system_unit_to_string = $ap_tabela_preco_system_unit_to_string;
        }

        $this->vdata['ap_tabela_preco_system_unit_to_string'] = $this->ap_tabela_preco_system_unit_to_string;
    }

    public function get_ap_tabela_preco_system_unit_to_string()
    {
        if(!empty($this->ap_tabela_preco_system_unit_to_string))
        {
            return $this->ap_tabela_preco_system_unit_to_string;
        }
    
        $values = ApTabelaPreco::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_grupo_system_unit_to_string($grupo_system_unit_to_string)
    {
        if(is_array($grupo_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $grupo_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->grupo_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->grupo_system_unit_to_string = $grupo_system_unit_to_string;
        }

        $this->vdata['grupo_system_unit_to_string'] = $this->grupo_system_unit_to_string;
    }

    public function get_grupo_system_unit_to_string()
    {
        if(!empty($this->grupo_system_unit_to_string))
        {
            return $this->grupo_system_unit_to_string;
        }
    
        $values = Grupo::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_historico_venda_meta_to_string($historico_venda_meta_to_string)
    {
        if(is_array($historico_venda_meta_to_string))
        {
            $values = Meta::where('id', 'in', $historico_venda_meta_to_string)->getIndexedArray('id', 'id');
            $this->historico_venda_meta_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_venda_meta_to_string = $historico_venda_meta_to_string;
        }

        $this->vdata['historico_venda_meta_to_string'] = $this->historico_venda_meta_to_string;
    }

    public function get_historico_venda_meta_to_string()
    {
        if(!empty($this->historico_venda_meta_to_string))
        {
            return $this->historico_venda_meta_to_string;
        }
    
        $values = HistoricoVenda::where('system_unit_id', '=', $this->id)->getIndexedArray('meta_id','{meta->id}');
        return implode(', ', $values);
    }

    public function set_historico_venda_repres_to_string($historico_venda_repres_to_string)
    {
        if(is_array($historico_venda_repres_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $historico_venda_repres_to_string)->getIndexedArray('id', 'id');
            $this->historico_venda_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_venda_repres_to_string = $historico_venda_repres_to_string;
        }

        $this->vdata['historico_venda_repres_to_string'] = $this->historico_venda_repres_to_string;
    }

    public function get_historico_venda_repres_to_string()
    {
        if(!empty($this->historico_venda_repres_to_string))
        {
            return $this->historico_venda_repres_to_string;
        }
    
        $values = HistoricoVenda::where('system_unit_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
        return implode(', ', $values);
    }

    public function set_historico_venda_grupo_cliente_to_string($historico_venda_grupo_cliente_to_string)
    {
        if(is_array($historico_venda_grupo_cliente_to_string))
        {
            $values = ApGrupoCliente::where('id', 'in', $historico_venda_grupo_cliente_to_string)->getIndexedArray('id', 'id');
            $this->historico_venda_grupo_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_venda_grupo_cliente_to_string = $historico_venda_grupo_cliente_to_string;
        }

        $this->vdata['historico_venda_grupo_cliente_to_string'] = $this->historico_venda_grupo_cliente_to_string;
    }

    public function get_historico_venda_grupo_cliente_to_string()
    {
        if(!empty($this->historico_venda_grupo_cliente_to_string))
        {
            return $this->historico_venda_grupo_cliente_to_string;
        }
    
        $values = HistoricoVenda::where('system_unit_id', '=', $this->id)->getIndexedArray('grupo_cliente_id','{grupo_cliente->id}');
        return implode(', ', $values);
    }

    public function set_historico_venda_cidade_to_string($historico_venda_cidade_to_string)
    {
        if(is_array($historico_venda_cidade_to_string))
        {
            $values = ApCidade::where('id', 'in', $historico_venda_cidade_to_string)->getIndexedArray('id', 'id');
            $this->historico_venda_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_venda_cidade_to_string = $historico_venda_cidade_to_string;
        }

        $this->vdata['historico_venda_cidade_to_string'] = $this->historico_venda_cidade_to_string;
    }

    public function get_historico_venda_cidade_to_string()
    {
        if(!empty($this->historico_venda_cidade_to_string))
        {
            return $this->historico_venda_cidade_to_string;
        }
    
        $values = HistoricoVenda::where('system_unit_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
        return implode(', ', $values);
    }

    public function set_historico_venda_estado_to_string($historico_venda_estado_to_string)
    {
        if(is_array($historico_venda_estado_to_string))
        {
            $values = ApEstado::where('id', 'in', $historico_venda_estado_to_string)->getIndexedArray('id', 'id');
            $this->historico_venda_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_venda_estado_to_string = $historico_venda_estado_to_string;
        }

        $this->vdata['historico_venda_estado_to_string'] = $this->historico_venda_estado_to_string;
    }

    public function get_historico_venda_estado_to_string()
    {
        if(!empty($this->historico_venda_estado_to_string))
        {
            return $this->historico_venda_estado_to_string;
        }
    
        $values = HistoricoVenda::where('system_unit_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
        return implode(', ', $values);
    }

    public function set_ap_grupo_estoque_system_unit_to_string($ap_grupo_estoque_system_unit_to_string)
    {
        if(is_array($ap_grupo_estoque_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $ap_grupo_estoque_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->ap_grupo_estoque_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_grupo_estoque_system_unit_to_string = $ap_grupo_estoque_system_unit_to_string;
        }

        $this->vdata['ap_grupo_estoque_system_unit_to_string'] = $this->ap_grupo_estoque_system_unit_to_string;
    }

    public function get_ap_grupo_estoque_system_unit_to_string()
    {
        if(!empty($this->ap_grupo_estoque_system_unit_to_string))
        {
            return $this->ap_grupo_estoque_system_unit_to_string;
        }
    
        $values = ApGrupoEstoque::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
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
    
        $values = AguardoProduto::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = AguardoProduto::where('system_unit_id', '=', $this->id)->getIndexedArray('system_users_id','{system_users->name}');
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
    
        $values = AguardoProduto::where('system_unit_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = AguardoProduto::where('system_unit_id', '=', $this->id)->getIndexedArray('ap_item_id','{ap_item->id}');
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
    
        $values = ApItem::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = ApItem::where('system_unit_id', '=', $this->id)->getIndexedArray('subgrupo_estoque_id','{subgrupo_estoque->descricao}');
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
    
        $values = ApItem::where('system_unit_id', '=', $this->id)->getIndexedArray('grupo_estoque_id','{grupo_estoque->descricao}');
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
    
        $values = ApItem::where('system_unit_id', '=', $this->id)->getIndexedArray('familia_comercial_id','{familia_comercial->descricao}');
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
    
        $values = ApItem::where('system_unit_id', '=', $this->id)->getIndexedArray('familia_industrial_id','{familia_industrial->descricao}');
        return implode(', ', $values);
    }

    public function set_ap_familia_comercial_system_unit_to_string($ap_familia_comercial_system_unit_to_string)
    {
        if(is_array($ap_familia_comercial_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $ap_familia_comercial_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->ap_familia_comercial_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_familia_comercial_system_unit_to_string = $ap_familia_comercial_system_unit_to_string;
        }

        $this->vdata['ap_familia_comercial_system_unit_to_string'] = $this->ap_familia_comercial_system_unit_to_string;
    }

    public function get_ap_familia_comercial_system_unit_to_string()
    {
        if(!empty($this->ap_familia_comercial_system_unit_to_string))
        {
            return $this->ap_familia_comercial_system_unit_to_string;
        }
    
        $values = ApFamiliaComercial::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_ap_familia_industrial_system_unit_to_string($ap_familia_industrial_system_unit_to_string)
    {
        if(is_array($ap_familia_industrial_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $ap_familia_industrial_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->ap_familia_industrial_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_familia_industrial_system_unit_to_string = $ap_familia_industrial_system_unit_to_string;
        }

        $this->vdata['ap_familia_industrial_system_unit_to_string'] = $this->ap_familia_industrial_system_unit_to_string;
    }

    public function get_ap_familia_industrial_system_unit_to_string()
    {
        if(!empty($this->ap_familia_industrial_system_unit_to_string))
        {
            return $this->ap_familia_industrial_system_unit_to_string;
        }
    
        $values = ApFamiliaIndustrial::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = ApSubgrupoEstoque::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = ApSubgrupoEstoque::where('system_unit_id', '=', $this->id)->getIndexedArray('grupo_estoque_id','{grupo_estoque->descricao}');
        return implode(', ', $values);
    }

    public function set_ap_representante_system_unit_to_string($ap_representante_system_unit_to_string)
    {
        if(is_array($ap_representante_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $ap_representante_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->ap_representante_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_representante_system_unit_to_string = $ap_representante_system_unit_to_string;
        }

        $this->vdata['ap_representante_system_unit_to_string'] = $this->ap_representante_system_unit_to_string;
    }

    public function get_ap_representante_system_unit_to_string()
    {
        if(!empty($this->ap_representante_system_unit_to_string))
        {
            return $this->ap_representante_system_unit_to_string;
        }
    
        $values = ApRepresentante::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_ap_representante_system_users_to_string($ap_representante_system_users_to_string)
    {
        if(is_array($ap_representante_system_users_to_string))
        {
            $values = SystemUsers::where('id', 'in', $ap_representante_system_users_to_string)->getIndexedArray('name', 'name');
            $this->ap_representante_system_users_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_representante_system_users_to_string = $ap_representante_system_users_to_string;
        }

        $this->vdata['ap_representante_system_users_to_string'] = $this->ap_representante_system_users_to_string;
    }

    public function get_ap_representante_system_users_to_string()
    {
        if(!empty($this->ap_representante_system_users_to_string))
        {
            return $this->ap_representante_system_users_to_string;
        }
    
        $values = ApRepresentante::where('system_unit_id', '=', $this->id)->getIndexedArray('system_users_id','{system_users->name}');
        return implode(', ', $values);
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
    
        $values = HistoricoCliRepres::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = HistoricoCliRepres::where('system_unit_id', '=', $this->id)->getIndexedArray('ap_cidade_id','{ap_cidade->id}');
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
    
        $values = HistoricoCliRepres::where('system_unit_id', '=', $this->id)->getIndexedArray('grupo_id','{grupo->id}');
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
    
        $values = HistoricoCliRepres::where('system_unit_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
        return implode(', ', $values);
    }

    public function set_ap_grupo_cliente_system_unit_to_string($ap_grupo_cliente_system_unit_to_string)
    {
        if(is_array($ap_grupo_cliente_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $ap_grupo_cliente_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->ap_grupo_cliente_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->ap_grupo_cliente_system_unit_to_string = $ap_grupo_cliente_system_unit_to_string;
        }

        $this->vdata['ap_grupo_cliente_system_unit_to_string'] = $this->ap_grupo_cliente_system_unit_to_string;
    }

    public function get_ap_grupo_cliente_system_unit_to_string()
    {
        if(!empty($this->ap_grupo_cliente_system_unit_to_string))
        {
            return $this->ap_grupo_cliente_system_unit_to_string;
        }
    
        $values = ApGrupoCliente::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_mini_meta_mini_meta_tipo_to_string($mini_meta_mini_meta_tipo_to_string)
    {
        if(is_array($mini_meta_mini_meta_tipo_to_string))
        {
            $values = MiniMetaTipo::where('id', 'in', $mini_meta_mini_meta_tipo_to_string)->getIndexedArray('nome', 'nome');
            $this->mini_meta_mini_meta_tipo_to_string = implode(', ', $values);
        }
        else
        {
            $this->mini_meta_mini_meta_tipo_to_string = $mini_meta_mini_meta_tipo_to_string;
        }

        $this->vdata['mini_meta_mini_meta_tipo_to_string'] = $this->mini_meta_mini_meta_tipo_to_string;
    }

    public function get_mini_meta_mini_meta_tipo_to_string()
    {
        if(!empty($this->mini_meta_mini_meta_tipo_to_string))
        {
            return $this->mini_meta_mini_meta_tipo_to_string;
        }
    
        $values = MiniMeta::where('system_unit_id', '=', $this->id)->getIndexedArray('mini_meta_tipo_id','{mini_meta_tipo->nome}');
        return implode(', ', $values);
    }

    public function set_mini_meta_ap_tabela_preco_to_string($mini_meta_ap_tabela_preco_to_string)
    {
        if(is_array($mini_meta_ap_tabela_preco_to_string))
        {
            $values = ApTabelaPreco::where('id', 'in', $mini_meta_ap_tabela_preco_to_string)->getIndexedArray('id', 'id');
            $this->mini_meta_ap_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->mini_meta_ap_tabela_preco_to_string = $mini_meta_ap_tabela_preco_to_string;
        }

        $this->vdata['mini_meta_ap_tabela_preco_to_string'] = $this->mini_meta_ap_tabela_preco_to_string;
    }

    public function get_mini_meta_ap_tabela_preco_to_string()
    {
        if(!empty($this->mini_meta_ap_tabela_preco_to_string))
        {
            return $this->mini_meta_ap_tabela_preco_to_string;
        }
    
        $values = MiniMeta::where('system_unit_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
        return implode(', ', $values);
    }

    public function set_log_crontab_system_unit_to_string($log_crontab_system_unit_to_string)
    {
        if(is_array($log_crontab_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $log_crontab_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->log_crontab_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->log_crontab_system_unit_to_string = $log_crontab_system_unit_to_string;
        }

        $this->vdata['log_crontab_system_unit_to_string'] = $this->log_crontab_system_unit_to_string;
    }

    public function get_log_crontab_system_unit_to_string()
    {
        if(!empty($this->log_crontab_system_unit_to_string))
        {
            return $this->log_crontab_system_unit_to_string;
        }
    
        $values = LogCrontab::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_campanha_system_unit_to_string($campanha_system_unit_to_string)
    {
        if(is_array($campanha_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $campanha_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->campanha_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_system_unit_to_string = $campanha_system_unit_to_string;
        }

        $this->vdata['campanha_system_unit_to_string'] = $this->campanha_system_unit_to_string;
    }

    public function get_campanha_system_unit_to_string()
    {
        if(!empty($this->campanha_system_unit_to_string))
        {
            return $this->campanha_system_unit_to_string;
        }
    
        $values = Campanha::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_campanha_ap_tabela_preco_to_string($campanha_ap_tabela_preco_to_string)
    {
        if(is_array($campanha_ap_tabela_preco_to_string))
        {
            $values = ApTabelaPreco::where('id', 'in', $campanha_ap_tabela_preco_to_string)->getIndexedArray('id', 'id');
            $this->campanha_ap_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_ap_tabela_preco_to_string = $campanha_ap_tabela_preco_to_string;
        }

        $this->vdata['campanha_ap_tabela_preco_to_string'] = $this->campanha_ap_tabela_preco_to_string;
    }

    public function get_campanha_ap_tabela_preco_to_string()
    {
        if(!empty($this->campanha_ap_tabela_preco_to_string))
        {
            return $this->campanha_ap_tabela_preco_to_string;
        }
    
        $values = Campanha::where('system_unit_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
        return implode(', ', $values);
    }

    public function set_campanha_campanha_tipo_to_string($campanha_campanha_tipo_to_string)
    {
        if(is_array($campanha_campanha_tipo_to_string))
        {
            $values = CampanhaTipo::where('id', 'in', $campanha_campanha_tipo_to_string)->getIndexedArray('id', 'id');
            $this->campanha_campanha_tipo_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_campanha_tipo_to_string = $campanha_campanha_tipo_to_string;
        }

        $this->vdata['campanha_campanha_tipo_to_string'] = $this->campanha_campanha_tipo_to_string;
    }

    public function get_campanha_campanha_tipo_to_string()
    {
        if(!empty($this->campanha_campanha_tipo_to_string))
        {
            return $this->campanha_campanha_tipo_to_string;
        }
    
        $values = Campanha::where('system_unit_id', '=', $this->id)->getIndexedArray('campanha_tipo_id','{campanha_tipo->id}');
        return implode(', ', $values);
    }

    
}

