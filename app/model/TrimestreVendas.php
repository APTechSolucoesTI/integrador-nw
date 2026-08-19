<?php

class TrimestreVendas extends TRecord
{
    const TABLENAME  = 'trimestre_vendas.trimestre_vendas';
    const PRIMARYKEY = 'nro_nf';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_emissao');
        parent::addAttribute('clifor_data_cadastro');
        parent::addAttribute('cod_clifor');
        parent::addAttribute('clifor_razao');
        parent::addAttribute('clifor_agente');
        parent::addAttribute('clifor_cidade');
        parent::addAttribute('clifor_estado');
        parent::addAttribute('clifor_uf');
        parent::addAttribute('clifor_grp');
        parent::addAttribute('cod_item');
        parent::addAttribute('item_descricao');
        parent::addAttribute('cod_repres');
        parent::addAttribute('seq_item');
        parent::addAttribute('repres_razao');
        parent::addAttribute('repres_fantasia');
        parent::addAttribute('repres_email');
        parent::addAttribute('item_quantidade');
        parent::addAttribute('item_valor_total');
        parent::addAttribute('item_valor_mostruario');
            
    }

    
}

