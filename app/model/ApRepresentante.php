<?php

class ApRepresentante extends TRecord
{
    const TABLENAME  = 'ap_representante';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private SystemUsers $system_users;
    private SystemUnit $system_unit;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_unit_id');
        parent::addAttribute('cod_repres');
        parent::addAttribute('razao');
        parent::addAttribute('fantasia');
        parent::addAttribute('ativo');
        parent::addAttribute('system_users_id');
        parent::addAttribute('email');
    
    }

    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_system_users(SystemUsers $object)
    {
        $this->system_users = $object;
        $this->system_users_id = $object->id;
    }

    /**
     * Method get_system_users
     * Sample of usage: $var->system_users->attribute;
     * @returns SystemUsers instance
     */
    public function get_system_users()
    {
    
        // loads the associated object
        if (empty($this->system_users))
            $this->system_users = new SystemUsers($this->system_users_id);
    
        // returns the associated object
        return $this->system_users;
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
     * Method getMetaFechamentos
     */
    public function getMetaFechamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('repres_id', '=', $this->id));
        return MetaFechamento::getObjects( $criteria );
    }
    /**
     * Method getMetaRepress
     */
    public function getMetaRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('repres_id', '=', $this->id));
        return MetaRepres::getObjects( $criteria );
    }
    /**
     * Method getMiniMetaFechamentos
     */
    public function getMiniMetaFechamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_representante_id', '=', $this->id));
        return MiniMetaFechamento::getObjects( $criteria );
    }
    /**
     * Method getAguardoProdutos
     */
    public function getAguardoProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('repres_id', '=', $this->id));
        return AguardoProduto::getObjects( $criteria );
    }
    /**
     * Method getHistoricoCliRepress
     */
    public function getHistoricoCliRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('repres_id', '=', $this->id));
        return HistoricoCliRepres::getObjects( $criteria );
    }
    /**
     * Method getHistoricoVendas
     */
    public function getHistoricoVendas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('repres_id', '=', $this->id));
        return HistoricoVenda::getObjects( $criteria );
    }
    /**
     * Method getMetaImportRepress
     */
    public function getMetaImportRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_representante_id', '=', $this->id));
        return MetaImportRepres::getObjects( $criteria );
    }
    /**
     * Method getTrimestreClienteInicials
     */
    public function getTrimestreClienteInicials()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('repres_id', '=', $this->id));
        return TrimestreClienteInicial::getObjects( $criteria );
    }
    /**
     * Method getMetaTrimestralRepress
     */
    public function getMetaTrimestralRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_representante_id', '=', $this->id));
        return MetaTrimestralRepres::getObjects( $criteria );
    }
    /**
     * Method getMetaImportFechamentos
     */
    public function getMetaImportFechamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_representante_id', '=', $this->id));
        return MetaImportFechamento::getObjects( $criteria );
    }
    /**
     * Method getCampanhaRepress
     */
    public function getCampanhaRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('repres_id', '=', $this->id));
        return CampanhaRepres::getObjects( $criteria );
    }
    /**
     * Method getCampanhaFechamentos
     */
    public function getCampanhaFechamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_representante_id', '=', $this->id));
        return CampanhaFechamento::getObjects( $criteria );
    }
    /**
     * Method getTrimestreClienteAtuals
     */
    public function getTrimestreClienteAtuals()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('repres_id', '=', $this->id));
        return TrimestreClienteAtual::getObjects( $criteria );
    }

    public function set_meta_fechamento_meta_to_string($meta_fechamento_meta_to_string)
    {
        if(is_array($meta_fechamento_meta_to_string))
        {
            $values = Meta::where('id', 'in', $meta_fechamento_meta_to_string)->getIndexedArray('id', 'id');
            $this->meta_fechamento_meta_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_fechamento_meta_to_string = $meta_fechamento_meta_to_string;
        }

        $this->vdata['meta_fechamento_meta_to_string'] = $this->meta_fechamento_meta_to_string;
    }

    public function get_meta_fechamento_meta_to_string()
    {
        if(!empty($this->meta_fechamento_meta_to_string))
        {
            return $this->meta_fechamento_meta_to_string;
        }
    
        $values = MetaFechamento::where('repres_id', '=', $this->id)->getIndexedArray('meta_id','{meta->id}');
        return implode(', ', $values);
    }

    public function set_meta_fechamento_meta_repres_to_string($meta_fechamento_meta_repres_to_string)
    {
        if(is_array($meta_fechamento_meta_repres_to_string))
        {
            $values = MetaRepres::where('id', 'in', $meta_fechamento_meta_repres_to_string)->getIndexedArray('id', 'id');
            $this->meta_fechamento_meta_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_fechamento_meta_repres_to_string = $meta_fechamento_meta_repres_to_string;
        }

        $this->vdata['meta_fechamento_meta_repres_to_string'] = $this->meta_fechamento_meta_repres_to_string;
    }

    public function get_meta_fechamento_meta_repres_to_string()
    {
        if(!empty($this->meta_fechamento_meta_repres_to_string))
        {
            return $this->meta_fechamento_meta_repres_to_string;
        }
    
        $values = MetaFechamento::where('repres_id', '=', $this->id)->getIndexedArray('meta_repres_id','{meta_repres->id}');
        return implode(', ', $values);
    }

    public function set_meta_fechamento_repres_to_string($meta_fechamento_repres_to_string)
    {
        if(is_array($meta_fechamento_repres_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $meta_fechamento_repres_to_string)->getIndexedArray('id', 'id');
            $this->meta_fechamento_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_fechamento_repres_to_string = $meta_fechamento_repres_to_string;
        }

        $this->vdata['meta_fechamento_repres_to_string'] = $this->meta_fechamento_repres_to_string;
    }

    public function get_meta_fechamento_repres_to_string()
    {
        if(!empty($this->meta_fechamento_repres_to_string))
        {
            return $this->meta_fechamento_repres_to_string;
        }
    
        $values = MetaFechamento::where('repres_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
        return implode(', ', $values);
    }

    public function set_meta_repres_meta_to_string($meta_repres_meta_to_string)
    {
        if(is_array($meta_repres_meta_to_string))
        {
            $values = Meta::where('id', 'in', $meta_repres_meta_to_string)->getIndexedArray('id', 'id');
            $this->meta_repres_meta_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_repres_meta_to_string = $meta_repres_meta_to_string;
        }

        $this->vdata['meta_repres_meta_to_string'] = $this->meta_repres_meta_to_string;
    }

    public function get_meta_repres_meta_to_string()
    {
        if(!empty($this->meta_repres_meta_to_string))
        {
            return $this->meta_repres_meta_to_string;
        }
    
        $values = MetaRepres::where('repres_id', '=', $this->id)->getIndexedArray('meta_id','{meta->id}');
        return implode(', ', $values);
    }

    public function set_meta_repres_repres_to_string($meta_repres_repres_to_string)
    {
        if(is_array($meta_repres_repres_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $meta_repres_repres_to_string)->getIndexedArray('id', 'id');
            $this->meta_repres_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_repres_repres_to_string = $meta_repres_repres_to_string;
        }

        $this->vdata['meta_repres_repres_to_string'] = $this->meta_repres_repres_to_string;
    }

    public function get_meta_repres_repres_to_string()
    {
        if(!empty($this->meta_repres_repres_to_string))
        {
            return $this->meta_repres_repres_to_string;
        }
    
        $values = MetaRepres::where('repres_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
        return implode(', ', $values);
    }

    public function set_mini_meta_fechamento_mini_meta_to_string($mini_meta_fechamento_mini_meta_to_string)
    {
        if(is_array($mini_meta_fechamento_mini_meta_to_string))
        {
            $values = MiniMeta::where('id', 'in', $mini_meta_fechamento_mini_meta_to_string)->getIndexedArray('descricao', 'descricao');
            $this->mini_meta_fechamento_mini_meta_to_string = implode(', ', $values);
        }
        else
        {
            $this->mini_meta_fechamento_mini_meta_to_string = $mini_meta_fechamento_mini_meta_to_string;
        }

        $this->vdata['mini_meta_fechamento_mini_meta_to_string'] = $this->mini_meta_fechamento_mini_meta_to_string;
    }

    public function get_mini_meta_fechamento_mini_meta_to_string()
    {
        if(!empty($this->mini_meta_fechamento_mini_meta_to_string))
        {
            return $this->mini_meta_fechamento_mini_meta_to_string;
        }
    
        $values = MiniMetaFechamento::where('ap_representante_id', '=', $this->id)->getIndexedArray('mini_meta_id','{mini_meta->descricao}');
        return implode(', ', $values);
    }

    public function set_mini_meta_fechamento_ap_representante_to_string($mini_meta_fechamento_ap_representante_to_string)
    {
        if(is_array($mini_meta_fechamento_ap_representante_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $mini_meta_fechamento_ap_representante_to_string)->getIndexedArray('id', 'id');
            $this->mini_meta_fechamento_ap_representante_to_string = implode(', ', $values);
        }
        else
        {
            $this->mini_meta_fechamento_ap_representante_to_string = $mini_meta_fechamento_ap_representante_to_string;
        }

        $this->vdata['mini_meta_fechamento_ap_representante_to_string'] = $this->mini_meta_fechamento_ap_representante_to_string;
    }

    public function get_mini_meta_fechamento_ap_representante_to_string()
    {
        if(!empty($this->mini_meta_fechamento_ap_representante_to_string))
        {
            return $this->mini_meta_fechamento_ap_representante_to_string;
        }
    
        $values = MiniMetaFechamento::where('ap_representante_id', '=', $this->id)->getIndexedArray('ap_representante_id','{ap_representante->id}');
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
    
        $values = AguardoProduto::where('repres_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = AguardoProduto::where('repres_id', '=', $this->id)->getIndexedArray('system_users_id','{system_users->name}');
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
    
        $values = AguardoProduto::where('repres_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = AguardoProduto::where('repres_id', '=', $this->id)->getIndexedArray('ap_item_id','{ap_item->id}');
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
    
        $values = HistoricoCliRepres::where('repres_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = HistoricoCliRepres::where('repres_id', '=', $this->id)->getIndexedArray('ap_cidade_id','{ap_cidade->id}');
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
    
        $values = HistoricoCliRepres::where('repres_id', '=', $this->id)->getIndexedArray('grupo_id','{grupo->id}');
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
    
        $values = HistoricoCliRepres::where('repres_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = HistoricoVenda::where('repres_id', '=', $this->id)->getIndexedArray('meta_id','{meta->id}');
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
    
        $values = HistoricoVenda::where('repres_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = HistoricoVenda::where('repres_id', '=', $this->id)->getIndexedArray('grupo_cliente_id','{grupo_cliente->id}');
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
    
        $values = HistoricoVenda::where('repres_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
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
    
        $values = HistoricoVenda::where('repres_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
        return implode(', ', $values);
    }

    public function set_meta_import_repres_ap_representante_to_string($meta_import_repres_ap_representante_to_string)
    {
        if(is_array($meta_import_repres_ap_representante_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $meta_import_repres_ap_representante_to_string)->getIndexedArray('id', 'id');
            $this->meta_import_repres_ap_representante_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_import_repres_ap_representante_to_string = $meta_import_repres_ap_representante_to_string;
        }

        $this->vdata['meta_import_repres_ap_representante_to_string'] = $this->meta_import_repres_ap_representante_to_string;
    }

    public function get_meta_import_repres_ap_representante_to_string()
    {
        if(!empty($this->meta_import_repres_ap_representante_to_string))
        {
            return $this->meta_import_repres_ap_representante_to_string;
        }
    
        $values = MetaImportRepres::where('ap_representante_id', '=', $this->id)->getIndexedArray('ap_representante_id','{ap_representante->id}');
        return implode(', ', $values);
    }

    public function set_meta_import_repres_meta_import_to_string($meta_import_repres_meta_import_to_string)
    {
        if(is_array($meta_import_repres_meta_import_to_string))
        {
            $values = MetaImport::where('id', 'in', $meta_import_repres_meta_import_to_string)->getIndexedArray('id', 'id');
            $this->meta_import_repres_meta_import_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_import_repres_meta_import_to_string = $meta_import_repres_meta_import_to_string;
        }

        $this->vdata['meta_import_repres_meta_import_to_string'] = $this->meta_import_repres_meta_import_to_string;
    }

    public function get_meta_import_repres_meta_import_to_string()
    {
        if(!empty($this->meta_import_repres_meta_import_to_string))
        {
            return $this->meta_import_repres_meta_import_to_string;
        }
    
        $values = MetaImportRepres::where('ap_representante_id', '=', $this->id)->getIndexedArray('meta_import_id','{meta_import->id}');
        return implode(', ', $values);
    }

    public function set_trimestre_cliente_inicial_meta_trimestral_to_string($trimestre_cliente_inicial_meta_trimestral_to_string)
    {
        if(is_array($trimestre_cliente_inicial_meta_trimestral_to_string))
        {
            $values = MetaTrimestral::where('id', 'in', $trimestre_cliente_inicial_meta_trimestral_to_string)->getIndexedArray('descricao', 'descricao');
            $this->trimestre_cliente_inicial_meta_trimestral_to_string = implode(', ', $values);
        }
        else
        {
            $this->trimestre_cliente_inicial_meta_trimestral_to_string = $trimestre_cliente_inicial_meta_trimestral_to_string;
        }

        $this->vdata['trimestre_cliente_inicial_meta_trimestral_to_string'] = $this->trimestre_cliente_inicial_meta_trimestral_to_string;
    }

    public function get_trimestre_cliente_inicial_meta_trimestral_to_string()
    {
        if(!empty($this->trimestre_cliente_inicial_meta_trimestral_to_string))
        {
            return $this->trimestre_cliente_inicial_meta_trimestral_to_string;
        }
    
        $values = TrimestreClienteInicial::where('repres_id', '=', $this->id)->getIndexedArray('meta_trimestral_id','{meta_trimestral->descricao}');
        return implode(', ', $values);
    }

    public function set_trimestre_cliente_inicial_grupo_to_string($trimestre_cliente_inicial_grupo_to_string)
    {
        if(is_array($trimestre_cliente_inicial_grupo_to_string))
        {
            $values = ApGrupoCliente::where('id', 'in', $trimestre_cliente_inicial_grupo_to_string)->getIndexedArray('id', 'id');
            $this->trimestre_cliente_inicial_grupo_to_string = implode(', ', $values);
        }
        else
        {
            $this->trimestre_cliente_inicial_grupo_to_string = $trimestre_cliente_inicial_grupo_to_string;
        }

        $this->vdata['trimestre_cliente_inicial_grupo_to_string'] = $this->trimestre_cliente_inicial_grupo_to_string;
    }

    public function get_trimestre_cliente_inicial_grupo_to_string()
    {
        if(!empty($this->trimestre_cliente_inicial_grupo_to_string))
        {
            return $this->trimestre_cliente_inicial_grupo_to_string;
        }
    
        $values = TrimestreClienteInicial::where('repres_id', '=', $this->id)->getIndexedArray('grupo_id','{grupo->id}');
        return implode(', ', $values);
    }

    public function set_trimestre_cliente_inicial_repres_to_string($trimestre_cliente_inicial_repres_to_string)
    {
        if(is_array($trimestre_cliente_inicial_repres_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $trimestre_cliente_inicial_repres_to_string)->getIndexedArray('id', 'id');
            $this->trimestre_cliente_inicial_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->trimestre_cliente_inicial_repres_to_string = $trimestre_cliente_inicial_repres_to_string;
        }

        $this->vdata['trimestre_cliente_inicial_repres_to_string'] = $this->trimestre_cliente_inicial_repres_to_string;
    }

    public function get_trimestre_cliente_inicial_repres_to_string()
    {
        if(!empty($this->trimestre_cliente_inicial_repres_to_string))
        {
            return $this->trimestre_cliente_inicial_repres_to_string;
        }
    
        $values = TrimestreClienteInicial::where('repres_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
        return implode(', ', $values);
    }

    public function set_trimestre_cliente_inicial_cidade_to_string($trimestre_cliente_inicial_cidade_to_string)
    {
        if(is_array($trimestre_cliente_inicial_cidade_to_string))
        {
            $values = ApCidade::where('id', 'in', $trimestre_cliente_inicial_cidade_to_string)->getIndexedArray('id', 'id');
            $this->trimestre_cliente_inicial_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->trimestre_cliente_inicial_cidade_to_string = $trimestre_cliente_inicial_cidade_to_string;
        }

        $this->vdata['trimestre_cliente_inicial_cidade_to_string'] = $this->trimestre_cliente_inicial_cidade_to_string;
    }

    public function get_trimestre_cliente_inicial_cidade_to_string()
    {
        if(!empty($this->trimestre_cliente_inicial_cidade_to_string))
        {
            return $this->trimestre_cliente_inicial_cidade_to_string;
        }
    
        $values = TrimestreClienteInicial::where('repres_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
        return implode(', ', $values);
    }

    public function set_trimestre_cliente_inicial_estado_to_string($trimestre_cliente_inicial_estado_to_string)
    {
        if(is_array($trimestre_cliente_inicial_estado_to_string))
        {
            $values = ApEstado::where('id', 'in', $trimestre_cliente_inicial_estado_to_string)->getIndexedArray('id', 'id');
            $this->trimestre_cliente_inicial_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->trimestre_cliente_inicial_estado_to_string = $trimestre_cliente_inicial_estado_to_string;
        }

        $this->vdata['trimestre_cliente_inicial_estado_to_string'] = $this->trimestre_cliente_inicial_estado_to_string;
    }

    public function get_trimestre_cliente_inicial_estado_to_string()
    {
        if(!empty($this->trimestre_cliente_inicial_estado_to_string))
        {
            return $this->trimestre_cliente_inicial_estado_to_string;
        }
    
        $values = TrimestreClienteInicial::where('repres_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
        return implode(', ', $values);
    }

    public function set_meta_trimestral_repres_meta_trimestral_to_string($meta_trimestral_repres_meta_trimestral_to_string)
    {
        if(is_array($meta_trimestral_repres_meta_trimestral_to_string))
        {
            $values = MetaTrimestral::where('id', 'in', $meta_trimestral_repres_meta_trimestral_to_string)->getIndexedArray('descricao', 'descricao');
            $this->meta_trimestral_repres_meta_trimestral_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_trimestral_repres_meta_trimestral_to_string = $meta_trimestral_repres_meta_trimestral_to_string;
        }

        $this->vdata['meta_trimestral_repres_meta_trimestral_to_string'] = $this->meta_trimestral_repres_meta_trimestral_to_string;
    }

    public function get_meta_trimestral_repres_meta_trimestral_to_string()
    {
        if(!empty($this->meta_trimestral_repres_meta_trimestral_to_string))
        {
            return $this->meta_trimestral_repres_meta_trimestral_to_string;
        }
    
        $values = MetaTrimestralRepres::where('ap_representante_id', '=', $this->id)->getIndexedArray('meta_trimestral_id','{meta_trimestral->descricao}');
        return implode(', ', $values);
    }

    public function set_meta_trimestral_repres_ap_representante_to_string($meta_trimestral_repres_ap_representante_to_string)
    {
        if(is_array($meta_trimestral_repres_ap_representante_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $meta_trimestral_repres_ap_representante_to_string)->getIndexedArray('id', 'id');
            $this->meta_trimestral_repres_ap_representante_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_trimestral_repres_ap_representante_to_string = $meta_trimestral_repres_ap_representante_to_string;
        }

        $this->vdata['meta_trimestral_repres_ap_representante_to_string'] = $this->meta_trimestral_repres_ap_representante_to_string;
    }

    public function get_meta_trimestral_repres_ap_representante_to_string()
    {
        if(!empty($this->meta_trimestral_repres_ap_representante_to_string))
        {
            return $this->meta_trimestral_repres_ap_representante_to_string;
        }
    
        $values = MetaTrimestralRepres::where('ap_representante_id', '=', $this->id)->getIndexedArray('ap_representante_id','{ap_representante->id}');
        return implode(', ', $values);
    }

    public function set_meta_import_fechamento_meta_import_repres_to_string($meta_import_fechamento_meta_import_repres_to_string)
    {
        if(is_array($meta_import_fechamento_meta_import_repres_to_string))
        {
            $values = MetaImportRepres::where('id', 'in', $meta_import_fechamento_meta_import_repres_to_string)->getIndexedArray('id', 'id');
            $this->meta_import_fechamento_meta_import_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_import_fechamento_meta_import_repres_to_string = $meta_import_fechamento_meta_import_repres_to_string;
        }

        $this->vdata['meta_import_fechamento_meta_import_repres_to_string'] = $this->meta_import_fechamento_meta_import_repres_to_string;
    }

    public function get_meta_import_fechamento_meta_import_repres_to_string()
    {
        if(!empty($this->meta_import_fechamento_meta_import_repres_to_string))
        {
            return $this->meta_import_fechamento_meta_import_repres_to_string;
        }
    
        $values = MetaImportFechamento::where('ap_representante_id', '=', $this->id)->getIndexedArray('meta_import_repres_id','{meta_import_repres->id}');
        return implode(', ', $values);
    }

    public function set_meta_import_fechamento_meta_import_to_string($meta_import_fechamento_meta_import_to_string)
    {
        if(is_array($meta_import_fechamento_meta_import_to_string))
        {
            $values = MetaImport::where('id', 'in', $meta_import_fechamento_meta_import_to_string)->getIndexedArray('id', 'id');
            $this->meta_import_fechamento_meta_import_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_import_fechamento_meta_import_to_string = $meta_import_fechamento_meta_import_to_string;
        }

        $this->vdata['meta_import_fechamento_meta_import_to_string'] = $this->meta_import_fechamento_meta_import_to_string;
    }

    public function get_meta_import_fechamento_meta_import_to_string()
    {
        if(!empty($this->meta_import_fechamento_meta_import_to_string))
        {
            return $this->meta_import_fechamento_meta_import_to_string;
        }
    
        $values = MetaImportFechamento::where('ap_representante_id', '=', $this->id)->getIndexedArray('meta_import_id','{meta_import->id}');
        return implode(', ', $values);
    }

    public function set_meta_import_fechamento_ap_representante_to_string($meta_import_fechamento_ap_representante_to_string)
    {
        if(is_array($meta_import_fechamento_ap_representante_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $meta_import_fechamento_ap_representante_to_string)->getIndexedArray('id', 'id');
            $this->meta_import_fechamento_ap_representante_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_import_fechamento_ap_representante_to_string = $meta_import_fechamento_ap_representante_to_string;
        }

        $this->vdata['meta_import_fechamento_ap_representante_to_string'] = $this->meta_import_fechamento_ap_representante_to_string;
    }

    public function get_meta_import_fechamento_ap_representante_to_string()
    {
        if(!empty($this->meta_import_fechamento_ap_representante_to_string))
        {
            return $this->meta_import_fechamento_ap_representante_to_string;
        }
    
        $values = MetaImportFechamento::where('ap_representante_id', '=', $this->id)->getIndexedArray('ap_representante_id','{ap_representante->id}');
        return implode(', ', $values);
    }

    public function set_campanha_repres_campanha_to_string($campanha_repres_campanha_to_string)
    {
        if(is_array($campanha_repres_campanha_to_string))
        {
            $values = Campanha::where('id', 'in', $campanha_repres_campanha_to_string)->getIndexedArray('descricao', 'descricao');
            $this->campanha_repres_campanha_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_repres_campanha_to_string = $campanha_repres_campanha_to_string;
        }

        $this->vdata['campanha_repres_campanha_to_string'] = $this->campanha_repres_campanha_to_string;
    }

    public function get_campanha_repres_campanha_to_string()
    {
        if(!empty($this->campanha_repres_campanha_to_string))
        {
            return $this->campanha_repres_campanha_to_string;
        }
    
        $values = CampanhaRepres::where('repres_id', '=', $this->id)->getIndexedArray('campanha_id','{campanha->descricao}');
        return implode(', ', $values);
    }

    public function set_campanha_repres_repres_to_string($campanha_repres_repres_to_string)
    {
        if(is_array($campanha_repres_repres_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $campanha_repres_repres_to_string)->getIndexedArray('id', 'id');
            $this->campanha_repres_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_repres_repres_to_string = $campanha_repres_repres_to_string;
        }

        $this->vdata['campanha_repres_repres_to_string'] = $this->campanha_repres_repres_to_string;
    }

    public function get_campanha_repres_repres_to_string()
    {
        if(!empty($this->campanha_repres_repres_to_string))
        {
            return $this->campanha_repres_repres_to_string;
        }
    
        $values = CampanhaRepres::where('repres_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
        return implode(', ', $values);
    }

    public function set_campanha_fechamento_campanha_to_string($campanha_fechamento_campanha_to_string)
    {
        if(is_array($campanha_fechamento_campanha_to_string))
        {
            $values = Campanha::where('id', 'in', $campanha_fechamento_campanha_to_string)->getIndexedArray('descricao', 'descricao');
            $this->campanha_fechamento_campanha_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_fechamento_campanha_to_string = $campanha_fechamento_campanha_to_string;
        }

        $this->vdata['campanha_fechamento_campanha_to_string'] = $this->campanha_fechamento_campanha_to_string;
    }

    public function get_campanha_fechamento_campanha_to_string()
    {
        if(!empty($this->campanha_fechamento_campanha_to_string))
        {
            return $this->campanha_fechamento_campanha_to_string;
        }
    
        $values = CampanhaFechamento::where('ap_representante_id', '=', $this->id)->getIndexedArray('campanha_id','{campanha->descricao}');
        return implode(', ', $values);
    }

    public function set_campanha_fechamento_ap_representante_to_string($campanha_fechamento_ap_representante_to_string)
    {
        if(is_array($campanha_fechamento_ap_representante_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $campanha_fechamento_ap_representante_to_string)->getIndexedArray('id', 'id');
            $this->campanha_fechamento_ap_representante_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_fechamento_ap_representante_to_string = $campanha_fechamento_ap_representante_to_string;
        }

        $this->vdata['campanha_fechamento_ap_representante_to_string'] = $this->campanha_fechamento_ap_representante_to_string;
    }

    public function get_campanha_fechamento_ap_representante_to_string()
    {
        if(!empty($this->campanha_fechamento_ap_representante_to_string))
        {
            return $this->campanha_fechamento_ap_representante_to_string;
        }
    
        $values = CampanhaFechamento::where('ap_representante_id', '=', $this->id)->getIndexedArray('ap_representante_id','{ap_representante->id}');
        return implode(', ', $values);
    }

    public function set_trimestre_cliente_atual_meta_trimestral_to_string($trimestre_cliente_atual_meta_trimestral_to_string)
    {
        if(is_array($trimestre_cliente_atual_meta_trimestral_to_string))
        {
            $values = MetaTrimestral::where('id', 'in', $trimestre_cliente_atual_meta_trimestral_to_string)->getIndexedArray('descricao', 'descricao');
            $this->trimestre_cliente_atual_meta_trimestral_to_string = implode(', ', $values);
        }
        else
        {
            $this->trimestre_cliente_atual_meta_trimestral_to_string = $trimestre_cliente_atual_meta_trimestral_to_string;
        }

        $this->vdata['trimestre_cliente_atual_meta_trimestral_to_string'] = $this->trimestre_cliente_atual_meta_trimestral_to_string;
    }

    public function get_trimestre_cliente_atual_meta_trimestral_to_string()
    {
        if(!empty($this->trimestre_cliente_atual_meta_trimestral_to_string))
        {
            return $this->trimestre_cliente_atual_meta_trimestral_to_string;
        }
    
        $values = TrimestreClienteAtual::where('repres_id', '=', $this->id)->getIndexedArray('meta_trimestral_id','{meta_trimestral->descricao}');
        return implode(', ', $values);
    }

    public function set_trimestre_cliente_atual_grupo_to_string($trimestre_cliente_atual_grupo_to_string)
    {
        if(is_array($trimestre_cliente_atual_grupo_to_string))
        {
            $values = ApGrupoCliente::where('id', 'in', $trimestre_cliente_atual_grupo_to_string)->getIndexedArray('id', 'id');
            $this->trimestre_cliente_atual_grupo_to_string = implode(', ', $values);
        }
        else
        {
            $this->trimestre_cliente_atual_grupo_to_string = $trimestre_cliente_atual_grupo_to_string;
        }

        $this->vdata['trimestre_cliente_atual_grupo_to_string'] = $this->trimestre_cliente_atual_grupo_to_string;
    }

    public function get_trimestre_cliente_atual_grupo_to_string()
    {
        if(!empty($this->trimestre_cliente_atual_grupo_to_string))
        {
            return $this->trimestre_cliente_atual_grupo_to_string;
        }
    
        $values = TrimestreClienteAtual::where('repres_id', '=', $this->id)->getIndexedArray('grupo_id','{grupo->id}');
        return implode(', ', $values);
    }

    public function set_trimestre_cliente_atual_repres_to_string($trimestre_cliente_atual_repres_to_string)
    {
        if(is_array($trimestre_cliente_atual_repres_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $trimestre_cliente_atual_repres_to_string)->getIndexedArray('id', 'id');
            $this->trimestre_cliente_atual_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->trimestre_cliente_atual_repres_to_string = $trimestre_cliente_atual_repres_to_string;
        }

        $this->vdata['trimestre_cliente_atual_repres_to_string'] = $this->trimestre_cliente_atual_repres_to_string;
    }

    public function get_trimestre_cliente_atual_repres_to_string()
    {
        if(!empty($this->trimestre_cliente_atual_repres_to_string))
        {
            return $this->trimestre_cliente_atual_repres_to_string;
        }
    
        $values = TrimestreClienteAtual::where('repres_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
        return implode(', ', $values);
    }

    public function set_trimestre_cliente_atual_cidade_to_string($trimestre_cliente_atual_cidade_to_string)
    {
        if(is_array($trimestre_cliente_atual_cidade_to_string))
        {
            $values = ApCidade::where('id', 'in', $trimestre_cliente_atual_cidade_to_string)->getIndexedArray('id', 'id');
            $this->trimestre_cliente_atual_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->trimestre_cliente_atual_cidade_to_string = $trimestre_cliente_atual_cidade_to_string;
        }

        $this->vdata['trimestre_cliente_atual_cidade_to_string'] = $this->trimestre_cliente_atual_cidade_to_string;
    }

    public function get_trimestre_cliente_atual_cidade_to_string()
    {
        if(!empty($this->trimestre_cliente_atual_cidade_to_string))
        {
            return $this->trimestre_cliente_atual_cidade_to_string;
        }
    
        $values = TrimestreClienteAtual::where('repres_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
        return implode(', ', $values);
    }

    public function set_trimestre_cliente_atual_estado_to_string($trimestre_cliente_atual_estado_to_string)
    {
        if(is_array($trimestre_cliente_atual_estado_to_string))
        {
            $values = ApEstado::where('id', 'in', $trimestre_cliente_atual_estado_to_string)->getIndexedArray('id', 'id');
            $this->trimestre_cliente_atual_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->trimestre_cliente_atual_estado_to_string = $trimestre_cliente_atual_estado_to_string;
        }

        $this->vdata['trimestre_cliente_atual_estado_to_string'] = $this->trimestre_cliente_atual_estado_to_string;
    }

    public function get_trimestre_cliente_atual_estado_to_string()
    {
        if(!empty($this->trimestre_cliente_atual_estado_to_string))
        {
            return $this->trimestre_cliente_atual_estado_to_string;
        }
    
        $values = TrimestreClienteAtual::where('repres_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
    

        if(MetaFechamento::where('repres_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(MetaRepres::where('repres_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(MiniMetaFechamento::where('ap_representante_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(AguardoProduto::where('repres_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(HistoricoCliRepres::where('repres_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(HistoricoVenda::where('repres_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(MetaImportRepres::where('ap_representante_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(TrimestreClienteInicial::where('repres_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(MetaTrimestralRepres::where('ap_representante_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(MetaImportFechamento::where('ap_representante_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(CampanhaRepres::where('repres_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(CampanhaFechamento::where('ap_representante_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(TrimestreClienteAtual::where('repres_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    public static function onSincronizarRepres(){
        TTransaction::open('nw');
        $representantes = Representante::where('ativo','=','S')->load();
        TTransaction::close();
        foreach($representantes as $representante){
            TTransaction::open('integrador');
            $repres = (ApRepresentante::where('cod_repres','=',$representante->cod_repres)->where('system_unit_id','=',TSession::getValue('userunitid')))->first();
            if(!$repres){
                $repres = new ApRepresentante();
            }
            $repres->system_unit_id = TSession::getValue('userunitid');
            $repres->cod_repres = $representante->cod_repres;
            $repres->ativo = $representante->ativo;
            $repres->email = $representante->email;
            $repres->razao = $representante->razao;
            $repres->fantasia = $representante->fantasia;
            $repres->store();
            TTransaction::close();
        }
    }
            
}

