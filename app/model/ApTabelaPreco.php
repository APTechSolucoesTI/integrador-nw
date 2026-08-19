<?php

class ApTabelaPreco extends TRecord
{
    const TABLENAME  = 'ap_tabela_preco';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private SystemUnit $system_unit;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_tabelapreco');
        parent::addAttribute('descricao');
        parent::addAttribute('ativo');
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
     * Method getCampanhas
     */
    public function getCampanhas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_tabela_preco_id', '=', $this->id));
        return Campanha::getObjects( $criteria );
    }
    /**
     * Method getCampanhaTabelaPrecos
     */
    public function getCampanhaTabelaPrecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_tabela_preco_id', '=', $this->id));
        return CampanhaTabelaPreco::getObjects( $criteria );
    }
    /**
     * Method getMiniMetas
     */
    public function getMiniMetas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_tabela_preco_id', '=', $this->id));
        return MiniMeta::getObjects( $criteria );
    }
    /**
     * Method getMinimetaTabelaPrecos
     */
    public function getMinimetaTabelaPrecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_tabela_preco_id', '=', $this->id));
        return MinimetaTabelaPreco::getObjects( $criteria );
    }
    /**
     * Method getMetaImportTabelaPrecos
     */
    public function getMetaImportTabelaPrecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_tabela_preco_id', '=', $this->id));
        return MetaImportTabelaPreco::getObjects( $criteria );
    }
    /**
     * Method getMetaImports
     */
    public function getMetaImports()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ap_tabela_preco_id', '=', $this->id));
        return MetaImport::getObjects( $criteria );
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
    
        $values = Campanha::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = Campanha::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
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
    
        $values = Campanha::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('campanha_tipo_id','{campanha_tipo->id}');
        return implode(', ', $values);
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
    
        $values = CampanhaTabelaPreco::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
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
    
        $values = CampanhaTabelaPreco::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('campanha_id','{campanha->descricao}');
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
    
        $values = MiniMeta::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('mini_meta_tipo_id','{mini_meta_tipo->nome}');
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
    
        $values = MiniMeta::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
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
    
        $values = MinimetaTabelaPreco::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
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
    
        $values = MinimetaTabelaPreco::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('mini_meta_id','{mini_meta->descricao}');
        return implode(', ', $values);
    }

    public function set_meta_import_tabela_preco_ap_tabela_preco_to_string($meta_import_tabela_preco_ap_tabela_preco_to_string)
    {
        if(is_array($meta_import_tabela_preco_ap_tabela_preco_to_string))
        {
            $values = ApTabelaPreco::where('id', 'in', $meta_import_tabela_preco_ap_tabela_preco_to_string)->getIndexedArray('id', 'id');
            $this->meta_import_tabela_preco_ap_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_import_tabela_preco_ap_tabela_preco_to_string = $meta_import_tabela_preco_ap_tabela_preco_to_string;
        }

        $this->vdata['meta_import_tabela_preco_ap_tabela_preco_to_string'] = $this->meta_import_tabela_preco_ap_tabela_preco_to_string;
    }

    public function get_meta_import_tabela_preco_ap_tabela_preco_to_string()
    {
        if(!empty($this->meta_import_tabela_preco_ap_tabela_preco_to_string))
        {
            return $this->meta_import_tabela_preco_ap_tabela_preco_to_string;
        }
    
        $values = MetaImportTabelaPreco::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
        return implode(', ', $values);
    }

    public function set_meta_import_tabela_preco_meta_import_to_string($meta_import_tabela_preco_meta_import_to_string)
    {
        if(is_array($meta_import_tabela_preco_meta_import_to_string))
        {
            $values = MetaImport::where('id', 'in', $meta_import_tabela_preco_meta_import_to_string)->getIndexedArray('id', 'id');
            $this->meta_import_tabela_preco_meta_import_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_import_tabela_preco_meta_import_to_string = $meta_import_tabela_preco_meta_import_to_string;
        }

        $this->vdata['meta_import_tabela_preco_meta_import_to_string'] = $this->meta_import_tabela_preco_meta_import_to_string;
    }

    public function get_meta_import_tabela_preco_meta_import_to_string()
    {
        if(!empty($this->meta_import_tabela_preco_meta_import_to_string))
        {
            return $this->meta_import_tabela_preco_meta_import_to_string;
        }
    
        $values = MetaImportTabelaPreco::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('meta_import_id','{meta_import->id}');
        return implode(', ', $values);
    }

    public function set_meta_import_ap_tabela_preco_to_string($meta_import_ap_tabela_preco_to_string)
    {
        if(is_array($meta_import_ap_tabela_preco_to_string))
        {
            $values = ApTabelaPreco::where('id', 'in', $meta_import_ap_tabela_preco_to_string)->getIndexedArray('id', 'id');
            $this->meta_import_ap_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_import_ap_tabela_preco_to_string = $meta_import_ap_tabela_preco_to_string;
        }

        $this->vdata['meta_import_ap_tabela_preco_to_string'] = $this->meta_import_ap_tabela_preco_to_string;
    }

    public function get_meta_import_ap_tabela_preco_to_string()
    {
        if(!empty($this->meta_import_ap_tabela_preco_to_string))
        {
            return $this->meta_import_ap_tabela_preco_to_string;
        }
    
        $values = MetaImport::where('ap_tabela_preco_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(Campanha::where('ap_tabela_preco_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(CampanhaTabelaPreco::where('ap_tabela_preco_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(MiniMeta::where('ap_tabela_preco_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(MinimetaTabelaPreco::where('ap_tabela_preco_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(MetaImportTabelaPreco::where('ap_tabela_preco_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(MetaImport::where('ap_tabela_preco_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

