<?php

class CepCoordenadas extends TRecord
{
    const TABLENAME  = 'cep_coordenadas';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cep');
        parent::addAttribute('latitude');
        parent::addAttribute('longitude');
            
    }

    
}

