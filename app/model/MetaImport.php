<?php

class MetaImport extends TRecord
{
    const TABLENAME  = 'meta_import';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApTabelaPreco $ap_tabela_preco;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_unit_id');
        parent::addAttribute('descricao');
        parent::addAttribute('data_inicial');
        parent::addAttribute('data_final');
        parent::addAttribute('status');
        parent::addAttribute('painel');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('ap_tabela_preco_id');
            
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
     * Method getMetaImportRepress
     */
    public function getMetaImportRepress()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_import_id', '=', $this->id));
        return MetaImportRepres::getObjects( $criteria );
    }
    /**
     * Method getMetaImportItems
     */
    public function getMetaImportItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_import_id', '=', $this->id));
        return MetaImportItem::getObjects( $criteria );
    }
    /**
     * Method getMetaImportFechamentos
     */
    public function getMetaImportFechamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_import_id', '=', $this->id));
        return MetaImportFechamento::getObjects( $criteria );
    }
    /**
     * Method getMetaImportTabelaPrecos
     */
    public function getMetaImportTabelaPrecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_import_id', '=', $this->id));
        return MetaImportTabelaPreco::getObjects( $criteria );
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
    
        $values = MetaImportRepres::where('meta_import_id', '=', $this->id)->getIndexedArray('ap_representante_id','{ap_representante->id}');
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
    
        $values = MetaImportRepres::where('meta_import_id', '=', $this->id)->getIndexedArray('meta_import_id','{meta_import->id}');
        return implode(', ', $values);
    }

    public function set_meta_import_item_meta_import_to_string($meta_import_item_meta_import_to_string)
    {
        if(is_array($meta_import_item_meta_import_to_string))
        {
            $values = MetaImport::where('id', 'in', $meta_import_item_meta_import_to_string)->getIndexedArray('id', 'id');
            $this->meta_import_item_meta_import_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_import_item_meta_import_to_string = $meta_import_item_meta_import_to_string;
        }

        $this->vdata['meta_import_item_meta_import_to_string'] = $this->meta_import_item_meta_import_to_string;
    }

    public function get_meta_import_item_meta_import_to_string()
    {
        if(!empty($this->meta_import_item_meta_import_to_string))
        {
            return $this->meta_import_item_meta_import_to_string;
        }
    
        $values = MetaImportItem::where('meta_import_id', '=', $this->id)->getIndexedArray('meta_import_id','{meta_import->id}');
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
    
        $values = MetaImportFechamento::where('meta_import_id', '=', $this->id)->getIndexedArray('meta_import_repres_id','{meta_import_repres->id}');
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
    
        $values = MetaImportFechamento::where('meta_import_id', '=', $this->id)->getIndexedArray('meta_import_id','{meta_import->id}');
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
    
        $values = MetaImportFechamento::where('meta_import_id', '=', $this->id)->getIndexedArray('ap_representante_id','{ap_representante->id}');
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
    
        $values = MetaImportTabelaPreco::where('meta_import_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
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
    
        $values = MetaImportTabelaPreco::where('meta_import_id', '=', $this->id)->getIndexedArray('meta_import_id','{meta_import->id}');
        return implode(', ', $values);
    }

    
}

