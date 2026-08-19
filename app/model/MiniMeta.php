<?php

class MiniMeta extends TRecord
{
    const TABLENAME  = 'mini_meta';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private MiniMetaTipo $mini_meta_tipo;
    private SystemUnit $system_unit;
    private ApTabelaPreco $ap_tabela_preco;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_unit_id');
        parent::addAttribute('mini_meta_tipo_id');
        parent::addAttribute('ap_tabela_preco_id');
        parent::addAttribute('painel');
        parent::addAttribute('descricao');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('data_inicial');
        parent::addAttribute('data_final');
        parent::addAttribute('status');
        parent::addAttribute('min');
        parent::addAttribute('qtde');
        parent::addAttribute('mes_ano');
        parent::addAttribute('valor_unitario_min');
            
    }

    /**
     * Method set_mini_meta_tipo
     * Sample of usage: $var->mini_meta_tipo = $object;
     * @param $object Instance of MiniMetaTipo
     */
    public function set_mini_meta_tipo(MiniMetaTipo $object)
    {
        $this->mini_meta_tipo = $object;
        $this->mini_meta_tipo_id = $object->id;
    }

    /**
     * Method get_mini_meta_tipo
     * Sample of usage: $var->mini_meta_tipo->attribute;
     * @returns MiniMetaTipo instance
     */
    public function get_mini_meta_tipo()
    {
    
        // loads the associated object
        if (empty($this->mini_meta_tipo))
            $this->mini_meta_tipo = new MiniMetaTipo($this->mini_meta_tipo_id);
    
        // returns the associated object
        return $this->mini_meta_tipo;
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
     * Method getMiniMetaFechamentos
     */
    public function getMiniMetaFechamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('mini_meta_id', '=', $this->id));
        return MiniMetaFechamento::getObjects( $criteria );
    }
    /**
     * Method getMiniMetaItems
     */
    public function getMiniMetaItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('mini_meta_id', '=', $this->id));
        return MiniMetaItem::getObjects( $criteria );
    }
    /**
     * Method getMinimetaTabelaPrecos
     */
    public function getMinimetaTabelaPrecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('mini_meta_id', '=', $this->id));
        return MinimetaTabelaPreco::getObjects( $criteria );
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
    
        $values = MiniMetaFechamento::where('mini_meta_id', '=', $this->id)->getIndexedArray('mini_meta_id','{mini_meta->descricao}');
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
    
        $values = MiniMetaFechamento::where('mini_meta_id', '=', $this->id)->getIndexedArray('ap_representante_id','{ap_representante->id}');
        return implode(', ', $values);
    }

    public function set_mini_meta_item_mini_meta_to_string($mini_meta_item_mini_meta_to_string)
    {
        if(is_array($mini_meta_item_mini_meta_to_string))
        {
            $values = MiniMeta::where('id', 'in', $mini_meta_item_mini_meta_to_string)->getIndexedArray('descricao', 'descricao');
            $this->mini_meta_item_mini_meta_to_string = implode(', ', $values);
        }
        else
        {
            $this->mini_meta_item_mini_meta_to_string = $mini_meta_item_mini_meta_to_string;
        }

        $this->vdata['mini_meta_item_mini_meta_to_string'] = $this->mini_meta_item_mini_meta_to_string;
    }

    public function get_mini_meta_item_mini_meta_to_string()
    {
        if(!empty($this->mini_meta_item_mini_meta_to_string))
        {
            return $this->mini_meta_item_mini_meta_to_string;
        }
    
        $values = MiniMetaItem::where('mini_meta_id', '=', $this->id)->getIndexedArray('mini_meta_id','{mini_meta->descricao}');
        return implode(', ', $values);
    }

    public function set_minimeta_tabela_preco_ap_tabela_preco_to_string($minimeta_tabela_preco_ap_tabela_preco_to_string)
    {
        if(is_array($minimeta_tabela_preco_ap_tabela_preco_to_string))
        {
            $values = ApTabelaPreco::where('id', 'in', $minimeta_tabela_preco_ap_tabela_preco_to_string)->getIndexedArray('id', 'id');
            $this->minimeta_tabela_preco_ap_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->minimeta_tabela_preco_ap_tabela_preco_to_string = $minimeta_tabela_preco_ap_tabela_preco_to_string;
        }

        $this->vdata['minimeta_tabela_preco_ap_tabela_preco_to_string'] = $this->minimeta_tabela_preco_ap_tabela_preco_to_string;
    }

    public function get_minimeta_tabela_preco_ap_tabela_preco_to_string()
    {
        if(!empty($this->minimeta_tabela_preco_ap_tabela_preco_to_string))
        {
            return $this->minimeta_tabela_preco_ap_tabela_preco_to_string;
        }
    
        $values = MinimetaTabelaPreco::where('mini_meta_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
        return implode(', ', $values);
    }

    public function set_minimeta_tabela_preco_mini_meta_to_string($minimeta_tabela_preco_mini_meta_to_string)
    {
        if(is_array($minimeta_tabela_preco_mini_meta_to_string))
        {
            $values = MiniMeta::where('id', 'in', $minimeta_tabela_preco_mini_meta_to_string)->getIndexedArray('descricao', 'descricao');
            $this->minimeta_tabela_preco_mini_meta_to_string = implode(', ', $values);
        }
        else
        {
            $this->minimeta_tabela_preco_mini_meta_to_string = $minimeta_tabela_preco_mini_meta_to_string;
        }

        $this->vdata['minimeta_tabela_preco_mini_meta_to_string'] = $this->minimeta_tabela_preco_mini_meta_to_string;
    }

    public function get_minimeta_tabela_preco_mini_meta_to_string()
    {
        if(!empty($this->minimeta_tabela_preco_mini_meta_to_string))
        {
            return $this->minimeta_tabela_preco_mini_meta_to_string;
        }
    
        $values = MinimetaTabelaPreco::where('mini_meta_id', '=', $this->id)->getIndexedArray('mini_meta_id','{mini_meta->descricao}');
        return implode(', ', $values);
    }

    
}

