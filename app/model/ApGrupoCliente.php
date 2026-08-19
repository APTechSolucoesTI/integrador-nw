<?php

class ApGrupoCliente extends TRecord
{
    const TABLENAME  = 'ap_grupo_cliente';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    private SystemUnit $system_unit;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_grpcliente');
        parent::addAttribute('descricao');
        parent::addAttribute('pontuacao');
        parent::addAttribute('valor_inicial');
        parent::addAttribute('valor_final');
        parent::addAttribute('system_unit_id');
            
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
     * Method getHistoricoCliRepress
     */
    public function getHistoricoCliRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('grupo_id', '=', $this->id));
        return HistoricoCliRepres::getObjects( $criteria );
    }
    /**
     * Method getHistoricoVendas
     */
    public function getHistoricoVendas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('grupo_cliente_id', '=', $this->id));
        return HistoricoVenda::getObjects( $criteria );
    }
    /**
     * Method getTrimestreClienteInicials
     */
    public function getTrimestreClienteInicials()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('grupo_id', '=', $this->id));
        return TrimestreClienteInicial::getObjects( $criteria );
    }
    /**
     * Method getTrimestreClienteAtuals
     */
    public function getTrimestreClienteAtuals()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('grupo_id', '=', $this->id));
        return TrimestreClienteAtual::getObjects( $criteria );
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
    
        $values = HistoricoCliRepres::where('grupo_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = HistoricoCliRepres::where('grupo_id', '=', $this->id)->getIndexedArray('ap_cidade_id','{ap_cidade->id}');
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
    
        $values = HistoricoCliRepres::where('grupo_id', '=', $this->id)->getIndexedArray('grupo_id','{grupo->id}');
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
    
        $values = HistoricoCliRepres::where('grupo_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = HistoricoVenda::where('grupo_cliente_id', '=', $this->id)->getIndexedArray('meta_id','{meta->id}');
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
    
        $values = HistoricoVenda::where('grupo_cliente_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = HistoricoVenda::where('grupo_cliente_id', '=', $this->id)->getIndexedArray('grupo_cliente_id','{grupo_cliente->id}');
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
    
        $values = HistoricoVenda::where('grupo_cliente_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
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
    
        $values = HistoricoVenda::where('grupo_cliente_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
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
    
        $values = TrimestreClienteInicial::where('grupo_id', '=', $this->id)->getIndexedArray('meta_trimestral_id','{meta_trimestral->descricao}');
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
    
        $values = TrimestreClienteInicial::where('grupo_id', '=', $this->id)->getIndexedArray('grupo_id','{grupo->id}');
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
    
        $values = TrimestreClienteInicial::where('grupo_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = TrimestreClienteInicial::where('grupo_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
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
    
        $values = TrimestreClienteInicial::where('grupo_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
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
    
        $values = TrimestreClienteAtual::where('grupo_id', '=', $this->id)->getIndexedArray('meta_trimestral_id','{meta_trimestral->descricao}');
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
    
        $values = TrimestreClienteAtual::where('grupo_id', '=', $this->id)->getIndexedArray('grupo_id','{grupo->id}');
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
    
        $values = TrimestreClienteAtual::where('grupo_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = TrimestreClienteAtual::where('grupo_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
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
    
        $values = TrimestreClienteAtual::where('grupo_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(HistoricoCliRepres::where('grupo_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(HistoricoVenda::where('grupo_cliente_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(TrimestreClienteInicial::where('grupo_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(TrimestreClienteAtual::where('grupo_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

