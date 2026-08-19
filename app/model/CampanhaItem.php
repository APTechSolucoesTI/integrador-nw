<?php

class CampanhaItem extends TRecord
{
    const TABLENAME  = 'campanha_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Campanha $campanha;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('campanha_id');
        parent::addAttribute('cod_item');
            
    }

    /**
     * Method set_campanha
     * Sample of usage: $var->campanha = $object;
     * @param $object Instance of Campanha
     */
    public function set_campanha(Campanha $object)
    {
        $this->campanha = $object;
        $this->campanha_id = $object->id;
    }

    /**
     * Method get_campanha
     * Sample of usage: $var->campanha->attribute;
     * @returns Campanha instance
     */
    public function get_campanha()
    {
    
        // loads the associated object
        if (empty($this->campanha))
            $this->campanha = new Campanha($this->campanha_id);
    
        // returns the associated object
        return $this->campanha;
    }

    
}

