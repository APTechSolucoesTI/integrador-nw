<?php

class PersianaAgrupamentoExcecao extends TRecord
{
    const TABLENAME  = 'persiana_agrupamento_excecao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private PersianaAgrupamento $persiana_agrupamento;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('persiana_agrupamento_id');
        parent::addAttribute('data');
        parent::addAttribute('qtd');
            
    }

    /**
     * Method set_persiana_agrupamento
     * Sample of usage: $var->persiana_agrupamento = $object;
     * @param $object Instance of PersianaAgrupamento
     */
    public function set_persiana_agrupamento(PersianaAgrupamento $object)
    {
        $this->persiana_agrupamento = $object;
        $this->persiana_agrupamento_id = $object->id;
    }

    /**
     * Method get_persiana_agrupamento
     * Sample of usage: $var->persiana_agrupamento->attribute;
     * @returns PersianaAgrupamento instance
     */
    public function get_persiana_agrupamento()
    {
    
        // loads the associated object
        if (empty($this->persiana_agrupamento))
            $this->persiana_agrupamento = new PersianaAgrupamento($this->persiana_agrupamento_id);
    
        // returns the associated object
        return $this->persiana_agrupamento;
    }

    
}

