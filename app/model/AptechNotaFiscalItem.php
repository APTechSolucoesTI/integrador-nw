<?php

class AptechNotaFiscalItem extends TRecord
{
    const TABLENAME  = 'aptech_nota_fiscal_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private AptechNotaFiscal $aptech_nota_fiscal;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('aptech_nota_fiscal_id');
        parent::addAttribute('nro_nfiscal');
        parent::addAttribute('cod_item');
        parent::addAttribute('descricao');
        parent::addAttribute('quantidade');
        parent::addAttribute('valor_unitario');
        parent::addAttribute('perc_descto');
        parent::addAttribute('valor_descto');
        parent::addAttribute('valor_frete');
        parent::addAttribute('cod_grupoestoque');
        parent::addAttribute('cod_subgrupoestoque');
        parent::addAttribute('familia_comercial');
        parent::addAttribute('familia_industrial');
        parent::addAttribute('valor_total');
            
    }

    /**
     * Method set_aptech_nota_fiscal
     * Sample of usage: $var->aptech_nota_fiscal = $object;
     * @param $object Instance of AptechNotaFiscal
     */
    public function set_aptech_nota_fiscal(AptechNotaFiscal $object)
    {
        $this->aptech_nota_fiscal = $object;
        $this->aptech_nota_fiscal_id = $object->id;
    }

    /**
     * Method get_aptech_nota_fiscal
     * Sample of usage: $var->aptech_nota_fiscal->attribute;
     * @returns AptechNotaFiscal instance
     */
    public function get_aptech_nota_fiscal()
    {
    
        // loads the associated object
        if (empty($this->aptech_nota_fiscal))
            $this->aptech_nota_fiscal = new AptechNotaFiscal($this->aptech_nota_fiscal_id);
    
        // returns the associated object
        return $this->aptech_nota_fiscal;
    }

    
}

