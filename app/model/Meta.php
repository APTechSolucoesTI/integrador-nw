<?php

class Meta extends TRecord
{
    const TABLENAME  = 'meta';
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
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('data_inicial');
        parent::addAttribute('data_final');
        parent::addAttribute('data_abertura');
        parent::addAttribute('data_entrega');
        parent::addAttribute('data_me');
        parent::addAttribute('dias_uteis');
        parent::addAttribute('feriados');
        parent::addAttribute('dias_disponiveis');
        parent::addAttribute('status');
        parent::addAttribute('mes_ano');
        parent::addAttribute('limite_import');
    
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
     * Method getMetaFeriados
     */
    public function getMetaFeriados()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_id', '=', $this->id));
        return MetaFeriado::getObjects( $criteria );
    }
    /**
     * Method getMetaRepress
     */
    public function getMetaRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_id', '=', $this->id));
        return MetaRepres::getObjects( $criteria );
    }
    /**
     * Method getHistoricoVendas
     */
    public function getHistoricoVendas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_id', '=', $this->id));
        return HistoricoVenda::getObjects( $criteria );
    }
    /**
     * Method getMetaFechamentos
     */
    public function getMetaFechamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_id', '=', $this->id));
        return MetaFechamento::getObjects( $criteria );
    }

    public function set_meta_feriado_meta_to_string($meta_feriado_meta_to_string)
    {
        if(is_array($meta_feriado_meta_to_string))
        {
            $values = Meta::where('id', 'in', $meta_feriado_meta_to_string)->getIndexedArray('id', 'id');
            $this->meta_feriado_meta_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_feriado_meta_to_string = $meta_feriado_meta_to_string;
        }

        $this->vdata['meta_feriado_meta_to_string'] = $this->meta_feriado_meta_to_string;
    }

    public function get_meta_feriado_meta_to_string()
    {
        if(!empty($this->meta_feriado_meta_to_string))
        {
            return $this->meta_feriado_meta_to_string;
        }
    
        $values = MetaFeriado::where('meta_id', '=', $this->id)->getIndexedArray('meta_id','{meta->id}');
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
    
        $values = MetaRepres::where('meta_id', '=', $this->id)->getIndexedArray('meta_id','{meta->id}');
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
    
        $values = MetaRepres::where('meta_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = HistoricoVenda::where('meta_id', '=', $this->id)->getIndexedArray('meta_id','{meta->id}');
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
    
        $values = HistoricoVenda::where('meta_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = HistoricoVenda::where('meta_id', '=', $this->id)->getIndexedArray('grupo_cliente_id','{grupo_cliente->id}');
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
    
        $values = HistoricoVenda::where('meta_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
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
    
        $values = HistoricoVenda::where('meta_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
        return implode(', ', $values);
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
    
        $values = MetaFechamento::where('meta_id', '=', $this->id)->getIndexedArray('meta_id','{meta->id}');
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
    
        $values = MetaFechamento::where('meta_id', '=', $this->id)->getIndexedArray('meta_repres_id','{meta_repres->id}');
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
    
        $values = MetaFechamento::where('meta_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
        return implode(', ', $values);
    }

    public function calculaDiasUteis(){
        $data_inicial = new DateTime($this->data_inicial);
        $data_final = new DateTime($this->data_final);
    
        $numero_dias = $fim_semana = 0;
    
        while ($data_inicial <= $data_final) {
           $numero_dias++;
           $dia_semana = $data_inicial->format('N');
           if ($dia_semana > 5) { // 6 e 7 são sábado e domingo
               $fim_semana++;
           };
           //Adiciona um dia
           $data_inicial = date_add($data_inicial,new DateInterval( "P1D"));
        }
    
        return($numero_dias-$fim_semana);
    }
    public function get_mes_extenso(){
        switch($this->mes){
            case 1:
                return "Janeiro";
            case 2:
                return "Fevereiro";
            case 3:
                return "Março";
            case 4:
                return "Abril";
            case 5:
                return "Maio";
            case 6:
                return "Junho";
            case 7:
                return "Julho";
            case 8:
                return "Agosto";
            case 9:
                return "Setembro";
            case 10:
                return "Outubro";
            case 11:
                return "Novembro";
            case 12:
                return "Dezembro";
        }
    }
        
}

