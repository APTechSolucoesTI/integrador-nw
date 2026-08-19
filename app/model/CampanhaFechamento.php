<?php

class CampanhaFechamento extends TRecord
{
    const TABLENAME  = 'campanha_fechamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Campanha $campanha;
    private ApRepresentante $ap_representante;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('premio');
        parent::addAttribute('campanha_id');
        parent::addAttribute('ap_representante_id');
        parent::addAttribute('alcancou_quantidade');
        parent::addAttribute('alcancou_ranking');
        parent::addAttribute('quantidade_total');
            
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

