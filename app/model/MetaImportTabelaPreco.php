<?php

class MetaImportTabelaPreco extends TRecord
{
    const TABLENAME  = 'meta_import_tabela_preco';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApTabelaPreco $ap_tabela_preco;
    private MetaImport $meta_import;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('fazparte');
        parent::addAttribute('ap_tabela_preco_id');
        parent::addAttribute('meta_import_id');
            
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

