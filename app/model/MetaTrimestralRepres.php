<?php

class MetaTrimestralRepres extends TRecord
{
    const TABLENAME  = 'meta_trimestral_repres';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private MetaTrimestral $meta_trimestral;
    private ApRepresentante $ap_representante;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('meta_trimestral_id');
        parent::addAttribute('ap_representante_id');
        parent::addAttribute('valor');
        parent::addAttribute('pontuacao_inicial');
        parent::addAttribute('pontuacao_alvo');
        parent::addAttribute('pontuacao_atual');
        parent::addAttribute('fantasia');
            
    }

    /**
     * Method set_meta_trimestral
     * Sample of usage: $var->meta_trimestral = $object;
     * @param $object Instance of MetaTrimestral
     */
    public function set_meta_trimestral(MetaTrimestral $object)
    {
        $this->meta_trimestral = $object;
        $this->meta_trimestral_id = $object->id;
    }

    /**
     * Method get_meta_trimestral
     * Sample of usage: $var->meta_trimestral->attribute;
     * @returns MetaTrimestral instance
     */
    public function get_meta_trimestral()
    {
    
        // loads the associated object
        if (empty($this->meta_trimestral))
            $this->meta_trimestral = new MetaTrimestral($this->meta_trimestral_id);
    
        // returns the associated object
        return $this->meta_trimestral;
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

