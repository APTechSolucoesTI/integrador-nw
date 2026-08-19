<?php

class PlanejamentoImportDias extends TRecord
{
    const TABLENAME  = 'planejamento_import_dias';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private PlanejamentoImport $planejamento_import;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('planejamento_import_id');
        parent::addAttribute('dia');
        parent::addAttribute('valido');
            
    }

    /**
     * Method set_planejamento_import
     * Sample of usage: $var->planejamento_import = $object;
     * @param $object Instance of PlanejamentoImport
     */
    public function set_planejamento_import(PlanejamentoImport $object)
    {
        $this->planejamento_import = $object;
        $this->planejamento_import_id = $object->id;
    }

    /**
     * Method get_planejamento_import
     * Sample of usage: $var->planejamento_import->attribute;
     * @returns PlanejamentoImport instance
     */
    public function get_planejamento_import()
    {
    
        // loads the associated object
        if (empty($this->planejamento_import))
            $this->planejamento_import = new PlanejamentoImport($this->planejamento_import_id);
    
        // returns the associated object
        return $this->planejamento_import;
    }

    
}

