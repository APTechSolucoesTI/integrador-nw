<?php

class PremioRegra extends TRecord
{
    const TABLENAME  = 'premio_regra';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Premio $premio;
    private Comparador $comparador;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('premio_id');
        parent::addAttribute('comparador_id');
        parent::addAttribute('dado_0');
        parent::addAttribute('dado_1');
        parent::addAttribute('bonus');
        parent::addAttribute('tipo_valor');
        parent::addAttribute('alvo_valor');
    
    }

    /**
     * Method set_premio
     * Sample of usage: $var->premio = $object;
     * @param $object Instance of Premio
     */
    public function set_premio(Premio $object)
    {
        $this->premio = $object;
        $this->premio_id = $object->id;
    }

    /**
     * Method get_premio
     * Sample of usage: $var->premio->attribute;
     * @returns Premio instance
     */
    public function get_premio()
    {
    
        // loads the associated object
        if (empty($this->premio))
            $this->premio = new Premio($this->premio_id);
    
        // returns the associated object
        return $this->premio;
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

}

