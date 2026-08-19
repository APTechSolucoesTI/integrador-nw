<?php

class FamiliaComercial extends TRecord
{
    const TABLENAME  = 'familia_comercial';
    const PRIMARYKEY = 'cod_fmcomercial';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('dt_change');
        parent::addAttribute('permite_natconf');
        parent::addAttribute('tipo_detalhenfe');
        parent::addAttribute('codtp_grupofamilia');
            
    }

    
}

