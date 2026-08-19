<?php

class MetaImportItem extends TRecord
{
    const TABLENAME  = 'meta_import_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private MetaImport $meta_import;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('meta_import_id');
        parent::addAttribute('cod_item');
    
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

}

