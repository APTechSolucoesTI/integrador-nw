<?php

class CampanhaPremio extends TRecord
{
    const TABLENAME  = 'campanha_premio';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Comparador $comparador;
    private Campanha $campanha;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('campanha_id');
        parent::addAttribute('comparador_id');
        parent::addAttribute('dado_0');
        parent::addAttribute('dado_1');
        parent::addAttribute('premio');
        parent::addAttribute('tipo_valor');
        parent::addAttribute('alvo_valor');
            
    }

    /**
     * Method set_comparador
     * Sample of usage: $var->comparador = $object;
     * @param $object Instance of Comparador
     */
    public function set_comparador(Comparador $object)
    {
        $this->comparador = $object;
        $this->comparador_id = $object->id;
    }

    /**
     * Method get_comparador
     * Sample of usage: $var->comparador->attribute;
     * @returns Comparador instance
     */
    public function get_comparador()
    {
    
        // loads the associated object
        if (empty($this->comparador))
            $this->comparador = new Comparador($this->comparador_id);
    
        // returns the associated object
        return $this->comparador;
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

