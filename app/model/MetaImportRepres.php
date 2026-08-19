<?php

class MetaImportRepres extends TRecord
{
    const TABLENAME  = 'meta_import_repres';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApRepresentante $ap_representante;
    private MetaImport $meta_import;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('qtd');
        parent::addAttribute('fantasia');
        parent::addAttribute('ap_representante_id');
        parent::addAttribute('meta_import_id');
            
    }

    /**
     * Method set_ap_representante
     * Sample of usage: $var->ap_representante = $object;
     * @param $object Instance of ApRepresentante
     */
    public function set_ap_representante(ApRepresentante $object)
    {
        $this->ap_representante = $object;
        $this->ap_representante_id = $object->id;
    }

    /**
     * Method get_ap_representante
     * Sample of usage: $var->ap_representante->attribute;
     * @returns ApRepresentante instance
     */
    public function get_ap_representante()
    {
    
        // loads the associated object
        if (empty($this->ap_representante))
            $this->ap_representante = new ApRepresentante($this->ap_representante_id);
    
        // returns the associated object
        return $this->ap_representante;
    }
    /**
     * Method set_meta_import
     * Sample of usage: $var->meta_import = $object;
     * @param $object Instance of MetaImport
     */
    public function set_meta_import(MetaImport $object)
    {
        $this->meta_import = $object;
        $this->meta_import_id = $object->id;
    }

    /**
     * Method get_meta_import
     * Sample of usage: $var->meta_import->attribute;
     * @returns MetaImport instance
     */
    public function get_meta_import()
    {
    
        // loads the associated object
        if (empty($this->meta_import))
            $this->meta_import = new MetaImport($this->meta_import_id);
    
        // returns the associated object
        return $this->meta_import;
    }

    /**
     * Method getMetaImportFechamentos
     */
    public function getMetaImportFechamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_import_repres_id', '=', $this->id));
        return MetaImportFechamento::getObjects( $criteria );
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
    
        $values = MetaImportFechamento::where('meta_import_repres_id', '=', $this->id)->getIndexedArray('meta_import_repres_id','{meta_import_repres->id}');
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
    
        $values = MetaImportFechamento::where('meta_import_repres_id', '=', $this->id)->getIndexedArray('meta_import_id','{meta_import->id}');
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
    
        $values = MetaImportFechamento::where('meta_import_repres_id', '=', $this->id)->getIndexedArray('ap_representante_id','{ap_representante->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(MetaImportFechamento::where('meta_import_repres_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

