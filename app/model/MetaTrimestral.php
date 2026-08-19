<?php

class MetaTrimestral extends TRecord
{
    const TABLENAME  = 'meta_trimestral';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('data_inicial');
        parent::addAttribute('data_final');
        parent::addAttribute('status');
            
    }

    /**
     * Method getTrimestreClienteInicials
     */
    public function getTrimestreClienteInicials()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_trimestral_id', '=', $this->id));
        return TrimestreClienteInicial::getObjects( $criteria );
    }
    /**
     * Method getMetaTrimestralRepress
     */
    public function getMetaTrimestralRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_trimestral_id', '=', $this->id));
        return MetaTrimestralRepres::getObjects( $criteria );
    }
    /**
     * Method getTrimestreClienteAtuals
     */
    public function getTrimestreClienteAtuals()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_trimestral_id', '=', $this->id));
        return TrimestreClienteAtual::getObjects( $criteria );
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
    
        $values = TrimestreClienteInicial::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('meta_trimestral_id','{meta_trimestral->descricao}');
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
    
        $values = TrimestreClienteInicial::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('grupo_id','{grupo->id}');
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
    
        $values = TrimestreClienteInicial::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = TrimestreClienteInicial::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
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
    
        $values = TrimestreClienteInicial::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
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
    
        $values = MetaTrimestralRepres::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('meta_trimestral_id','{meta_trimestral->descricao}');
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
    
        $values = MetaTrimestralRepres::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('ap_representante_id','{ap_representante->id}');
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
    
        $values = TrimestreClienteAtual::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('meta_trimestral_id','{meta_trimestral->descricao}');
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
    
        $values = TrimestreClienteAtual::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('grupo_id','{grupo->id}');
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
    
        $values = TrimestreClienteAtual::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = TrimestreClienteAtual::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
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
    
        $values = TrimestreClienteAtual::where('meta_trimestral_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(TrimestreClienteInicial::where('meta_trimestral_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(MetaTrimestralRepres::where('meta_trimestral_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(TrimestreClienteAtual::where('meta_trimestral_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

