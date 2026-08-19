<?php

class CampanhaRepres extends TRecord
{
    const TABLENAME  = 'campanha_repres';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Campanha $campanha;
    private ApRepresentante $repres;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('campanha_id');
        parent::addAttribute('repres_id');
        parent::addAttribute('quantidade');
        parent::addAttribute('valor');
        parent::addAttribute('premio');
            
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
    /**
     * Method set_ap_representante
     * Sample of usage: $var->ap_representante = $object;
     * @param $object Instance of ApRepresentante
     */
    public function set_repres(ApRepresentante $object)
    {
        $this->repres = $object;
        $this->repres_id = $object->id;
    }

    /**
     * Method get_repres
     * Sample of usage: $var->repres->attribute;
     * @returns ApRepresentante instance
     */
    public function get_repres()
    {
    
        // loads the associated object
        if (empty($this->repres))
            $this->repres = new ApRepresentante($this->repres_id);
    
        // returns the associated object
        return $this->repres;
    }

    
}

