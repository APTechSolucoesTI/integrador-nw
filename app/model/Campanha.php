<?php

class Campanha extends TRecord
{
    const TABLENAME  = 'campanha';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApTabelaPreco $ap_tabela_preco;
    private CampanhaTipo $campanha_tipo;
    private SystemUnit $system_unit;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_unit_id');
        parent::addAttribute('ap_tabela_preco_id');
        parent::addAttribute('campanha_tipo_id');
        parent::addAttribute('descricao');
        parent::addAttribute('data_inicial');
        parent::addAttribute('data_final');
        parent::addAttribute('status');
        parent::addAttribute('valor_unitario_min');
        parent::addAttribute('min');
        parent::addAttribute('qtde');
        parent::addAttribute('painel');
            
    }

    /**
     * Method set_ap_tabela_preco
     * Sample of usage: $var->ap_tabela_preco = $object;
     * @param $object Instance of ApTabelaPreco
     */
    public function set_ap_tabela_preco(ApTabelaPreco $object)
    {
        $this->ap_tabela_preco = $object;
        $this->ap_tabela_preco_id = $object->id;
    }

    /**
     * Method get_ap_tabela_preco
     * Sample of usage: $var->ap_tabela_preco->attribute;
     * @returns ApTabelaPreco instance
     */
    public function get_ap_tabela_preco()
    {
    
        // loads the associated object
        if (empty($this->ap_tabela_preco))
            $this->ap_tabela_preco = new ApTabelaPreco($this->ap_tabela_preco_id);
    
        // returns the associated object
        return $this->ap_tabela_preco;
    }
    /**
     * Method set_campanha_tipo
     * Sample of usage: $var->campanha_tipo = $object;
     * @param $object Instance of CampanhaTipo
     */
    public function set_campanha_tipo(CampanhaTipo $object)
    {
        $this->campanha_tipo = $object;
        $this->campanha_tipo_id = $object->id;
    }

    /**
     * Method get_campanha_tipo
     * Sample of usage: $var->campanha_tipo->attribute;
     * @returns CampanhaTipo instance
     */
    public function get_campanha_tipo()
    {
    
        // loads the associated object
        if (empty($this->campanha_tipo))
            $this->campanha_tipo = new CampanhaTipo($this->campanha_tipo_id);
    
        // returns the associated object
        return $this->campanha_tipo;
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
     * Method getCampanhaTabelaPrecos
     */
    public function getCampanhaTabelaPrecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('campanha_id', '=', $this->id));
        return CampanhaTabelaPreco::getObjects( $criteria );
    }
    /**
     * Method getCampanhaItems
     */
    public function getCampanhaItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('campanha_id', '=', $this->id));
        return CampanhaItem::getObjects( $criteria );
    }
    /**
     * Method getCampanhaPremios
     */
    public function getCampanhaPremios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('campanha_id', '=', $this->id));
        return CampanhaPremio::getObjects( $criteria );
    }
    /**
     * Method getCampanhaRepress
     */
    public function getCampanhaRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('campanha_id', '=', $this->id));
        return CampanhaRepres::getObjects( $criteria );
    }
    /**
     * Method getCampanhaFechamentos
     */
    public function getCampanhaFechamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('campanha_id', '=', $this->id));
        return CampanhaFechamento::getObjects( $criteria );
    }

    public function set_campanha_tabela_preco_ap_tabela_preco_to_string($campanha_tabela_preco_ap_tabela_preco_to_string)
    {
        if(is_array($campanha_tabela_preco_ap_tabela_preco_to_string))
        {
            $values = ApTabelaPreco::where('id', 'in', $campanha_tabela_preco_ap_tabela_preco_to_string)->getIndexedArray('id', 'id');
            $this->campanha_tabela_preco_ap_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_tabela_preco_ap_tabela_preco_to_string = $campanha_tabela_preco_ap_tabela_preco_to_string;
        }

        $this->vdata['campanha_tabela_preco_ap_tabela_preco_to_string'] = $this->campanha_tabela_preco_ap_tabela_preco_to_string;
    }

    public function get_campanha_tabela_preco_ap_tabela_preco_to_string()
    {
        if(!empty($this->campanha_tabela_preco_ap_tabela_preco_to_string))
        {
            return $this->campanha_tabela_preco_ap_tabela_preco_to_string;
        }
    
        $values = CampanhaTabelaPreco::where('campanha_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
        return implode(', ', $values);
    }

    public function set_campanha_tabela_preco_campanha_to_string($campanha_tabela_preco_campanha_to_string)
    {
        if(is_array($campanha_tabela_preco_campanha_to_string))
        {
            $values = Campanha::where('id', 'in', $campanha_tabela_preco_campanha_to_string)->getIndexedArray('descricao', 'descricao');
            $this->campanha_tabela_preco_campanha_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_tabela_preco_campanha_to_string = $campanha_tabela_preco_campanha_to_string;
        }

        $this->vdata['campanha_tabela_preco_campanha_to_string'] = $this->campanha_tabela_preco_campanha_to_string;
    }

    public function get_campanha_tabela_preco_campanha_to_string()
    {
        if(!empty($this->campanha_tabela_preco_campanha_to_string))
        {
            return $this->campanha_tabela_preco_campanha_to_string;
        }
    
        $values = CampanhaTabelaPreco::where('campanha_id', '=', $this->id)->getIndexedArray('campanha_id','{campanha->descricao}');
        return implode(', ', $values);
    }

    public function set_campanha_item_campanha_to_string($campanha_item_campanha_to_string)
    {
        if(is_array($campanha_item_campanha_to_string))
        {
            $values = Campanha::where('id', 'in', $campanha_item_campanha_to_string)->getIndexedArray('descricao', 'descricao');
            $this->campanha_item_campanha_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_item_campanha_to_string = $campanha_item_campanha_to_string;
        }

        $this->vdata['campanha_item_campanha_to_string'] = $this->campanha_item_campanha_to_string;
    }

    public function get_campanha_item_campanha_to_string()
    {
        if(!empty($this->campanha_item_campanha_to_string))
        {
            return $this->campanha_item_campanha_to_string;
        }
    
        $values = CampanhaItem::where('campanha_id', '=', $this->id)->getIndexedArray('campanha_id','{campanha->descricao}');
        return implode(', ', $values);
    }

    public function set_campanha_premio_campanha_to_string($campanha_premio_campanha_to_string)
    {
        if(is_array($campanha_premio_campanha_to_string))
        {
            $values = Campanha::where('id', 'in', $campanha_premio_campanha_to_string)->getIndexedArray('descricao', 'descricao');
            $this->campanha_premio_campanha_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_premio_campanha_to_string = $campanha_premio_campanha_to_string;
        }

        $this->vdata['campanha_premio_campanha_to_string'] = $this->campanha_premio_campanha_to_string;
    }

    public function get_campanha_premio_campanha_to_string()
    {
        if(!empty($this->campanha_premio_campanha_to_string))
        {
            return $this->campanha_premio_campanha_to_string;
        }
    
        $values = CampanhaPremio::where('campanha_id', '=', $this->id)->getIndexedArray('campanha_id','{campanha->descricao}');
        return implode(', ', $values);
    }

    public function set_campanha_premio_comparador_to_string($campanha_premio_comparador_to_string)
    {
        if(is_array($campanha_premio_comparador_to_string))
        {
            $values = Comparador::where('id', 'in', $campanha_premio_comparador_to_string)->getIndexedArray('id', 'id');
            $this->campanha_premio_comparador_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_premio_comparador_to_string = $campanha_premio_comparador_to_string;
        }

        $this->vdata['campanha_premio_comparador_to_string'] = $this->campanha_premio_comparador_to_string;
    }

    public function get_campanha_premio_comparador_to_string()
    {
        if(!empty($this->campanha_premio_comparador_to_string))
        {
            return $this->campanha_premio_comparador_to_string;
        }
    
        $values = CampanhaPremio::where('campanha_id', '=', $this->id)->getIndexedArray('comparador_id','{comparador->id}');
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
    
        $values = CampanhaRepres::where('campanha_id', '=', $this->id)->getIndexedArray('campanha_id','{campanha->descricao}');
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
    
        $values = CampanhaRepres::where('campanha_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
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
    
        $values = CampanhaFechamento::where('campanha_id', '=', $this->id)->getIndexedArray('campanha_id','{campanha->descricao}');
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
    
        $values = CampanhaFechamento::where('campanha_id', '=', $this->id)->getIndexedArray('ap_representante_id','{ap_representante->id}');
        return implode(', ', $values);
    }

    
}

