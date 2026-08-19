<?php

class MetaImportFechamento extends TRecord
{
    const TABLENAME  = 'meta_import_fechamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private MetaImportRepres $meta_import_repres;
    private MetaImport $meta_import;
    private ApRepresentante $ap_representante;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('meta_import_repres_id');
        parent::addAttribute('qtd_total');
        parent::addAttribute('meta_import_id');
        parent::addAttribute('ap_representante_id');
        parent::addAttribute('percentual_qtd');
    
    }

    /**
     * Method set_meta_import_repres
     * Sample of usage: $var->meta_import_repres = $object;
     * @param $object Instance of MetaImportRepres
     */
    public function set_meta_import_repres(MetaImportRepres $object)
    {
        $this->meta_import_repres = $object;
        $this->meta_import_repres_id = $object->id;
    }

    /**
     * Method get_meta_import_repres
     * Sample of usage: $var->meta_import_repres->attribute;
     * @returns MetaImportRepres instance
     */
    public function get_meta_import_repres()
    {
    
        // loads the associated object
        if (empty($this->meta_import_repres))
            $this->meta_import_repres = new MetaImportRepres($this->meta_import_repres_id);
    
        // returns the associated object
        return $this->meta_import_repres;
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

}

