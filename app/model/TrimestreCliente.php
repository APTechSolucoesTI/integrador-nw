<?php

class TrimestreCliente extends TRecord
{
    const TABLENAME  = 'trimestre_cliente.trimestre_cliente';
    const PRIMARYKEY = 'cod_clifor';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('representante');
        parent::addAttribute('cod_repres');
        parent::addAttribute('repres_email');
        parent::addAttribute('grupo_empresarial');
        parent::addAttribute('cod_principal');
        parent::addAttribute('razao_principal');
        parent::addAttribute('razao_social');
        parent::addAttribute('grupo_cliente');
        parent::addAttribute('dt_cadastro');
        parent::addAttribute('ativo');
            
    }

    
}

