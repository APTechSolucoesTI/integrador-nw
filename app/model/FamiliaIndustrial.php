<?php

class FamiliaIndustrial extends TRecord
{
    const TABLENAME  = 'familia_industrial';
    const PRIMARYKEY = 'cod_fmindustrial';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('dt_change');
            
    }

    
}

