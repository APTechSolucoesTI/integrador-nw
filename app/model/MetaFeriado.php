<?php

class MetaFeriado extends TRecord
{
    const TABLENAME  = 'meta_feriado';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Meta $meta;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('meta_id');
        parent::addAttribute('data_feriado');
        parent::addAttribute('descricao');
            
    }

    /**
     * Method set_meta
     * Sample of usage: $var->meta = $object;
     * @param $object Instance of Meta
     */
    public function set_meta(Meta $object)
    {
        $this->meta = $object;
        $this->meta_id = $object->id;
    }

    /**
     * Method get_meta
     * Sample of usage: $var->meta->attribute;
     * @returns Meta instance
     */
    public function get_meta()
    {
    
        // loads the associated object
        if (empty($this->meta))
            $this->meta = new Meta($this->meta_id);
    
        // returns the associated object
        return $this->meta;
    }

    
}

