<?php

class Estado extends TRecord
{
    const TABLENAME  = 'estado';
    const PRIMARYKEY = 'cod_estado';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('cod_mensagem');
        parent::addAttribute('icms_entrada');
        parent::addAttribute('icms_saida');
        parent::addAttribute('icms_interno');
        parent::addAttribute('aliq_icms_subst');
        parent::addAttribute('perc_icms_subst');
        parent::addAttribute('dt_change');
        parent::addAttribute('trk_percseguro');
        parent::addAttribute('prazo_cancelamentonfe');
        parent::addAttribute('perc_fcp');
        parent::addAttribute('perc_fcpicmsop');
        parent::addAttribute('perc_anp');
            
    }

    
}

