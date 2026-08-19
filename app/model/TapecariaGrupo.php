<?php

class TapecariaGrupo extends TRecord
{
    const TABLENAME  = 'tapecaria_grupo';
    const PRIMARYKEY = 'cod_grupoestoque';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
            
    }

    
}

