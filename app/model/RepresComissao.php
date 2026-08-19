<?php

class RepresComissao extends TRecord
{
    const TABLENAME  = 'repres_comissao';
    const PRIMARYKEY = 'cod_represcomissao';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_repres');
        parent::addAttribute('cod_empresa');
        parent::addAttribute('cod_clifor');
        parent::addAttribute('cod_item');
        parent::addAttribute('perc_comissao');
        parent::addAttribute('cod_grupoestoque');
        parent::addAttribute('cod_condpagamento');
        parent::addAttribute('desconto_inicial');
        parent::addAttribute('participacao');
        parent::addAttribute('limite_partic');
        parent::addAttribute('desconto_final');
        parent::addAttribute('perc_partic');
        parent::addAttribute('dt_change');
        parent::addAttribute('cod_estado');
        parent::addAttribute('cod_regiao');
        parent::addAttribute('cod_grpcliente');
        parent::addAttribute('cod_tipofrete');
        parent::addAttribute('tipo_descto');
        parent::addAttribute('tipo_perccomissao');
        parent::addAttribute('comissao_objetivo');
        parent::addAttribute('indice_mes');
        parent::addAttribute('prazo_mediopadrao');
        parent::addAttribute('cod_fmcomercial');
        parent::addAttribute('cod_fabricante');
        parent::addAttribute('cod_subgrupoestoque');
            
    }

    
}

