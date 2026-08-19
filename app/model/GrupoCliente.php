<?php

class GrupoCliente extends TRecord
{
    const TABLENAME  = 'grupo_cliente';
    const PRIMARYKEY = 'cod_grpcliente';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_tabelaprazo');
        parent::addAttribute('cod_tabelapreco');
        parent::addAttribute('cod_portador');
        parent::addAttribute('cod_repres');
        parent::addAttribute('cod_condpagamento');
        parent::addAttribute('cod_transp');
        parent::addAttribute('descricao');
        parent::addAttribute('perc_descto');
        parent::addAttribute('limite_credito');
        parent::addAttribute('inf_tabelaprazo');
        parent::addAttribute('inf_tabelapreco');
        parent::addAttribute('inf_portador');
        parent::addAttribute('inf_repres');
        parent::addAttribute('inf_condpagamento');
        parent::addAttribute('inf_transp');
        parent::addAttribute('inf_percdescto');
        parent::addAttribute('inf_limitecredito');
        parent::addAttribute('dt_change');
        parent::addAttribute('considerar_taxaboleto');
        parent::addAttribute('inf_taxaboleto');
        parent::addAttribute('auxiliar_string1');
            
    }

    
}

