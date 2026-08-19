<?php

class AptechNotaFiscal extends TRecord
{
    const TABLENAME  = 'aptech_nota_fiscal';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nro_nfiscal');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('cod_clifor');
        parent::addAttribute('razao_clifor');
        parent::addAttribute('cod_cidade_clifor');
        parent::addAttribute('cod_grupo_clifor');
        parent::addAttribute('dt_change');
        parent::addAttribute('cod_repres');
        parent::addAttribute('vendedor_interno');
        parent::addAttribute('vendedor_externo');
            
    }

    /**
     * Method getAptechNotaFiscalItems
     */
    public function getAptechNotaFiscalItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('aptech_nota_fiscal_id', '=', $this->id));
        return AptechNotaFiscalItem::getObjects( $criteria );
    }

    public function set_aptech_nota_fiscal_item_aptech_nota_fiscal_to_string($aptech_nota_fiscal_item_aptech_nota_fiscal_to_string)
    {
        if(is_array($aptech_nota_fiscal_item_aptech_nota_fiscal_to_string))
        {
            $values = AptechNotaFiscal::where('id', 'in', $aptech_nota_fiscal_item_aptech_nota_fiscal_to_string)->getIndexedArray('id', 'id');
            $this->aptech_nota_fiscal_item_aptech_nota_fiscal_to_string = implode(', ', $values);
        }
        else
        {
            $this->aptech_nota_fiscal_item_aptech_nota_fiscal_to_string = $aptech_nota_fiscal_item_aptech_nota_fiscal_to_string;
        }

        $this->vdata['aptech_nota_fiscal_item_aptech_nota_fiscal_to_string'] = $this->aptech_nota_fiscal_item_aptech_nota_fiscal_to_string;
    }

    public function get_aptech_nota_fiscal_item_aptech_nota_fiscal_to_string()
    {
        if(!empty($this->aptech_nota_fiscal_item_aptech_nota_fiscal_to_string))
        {
            return $this->aptech_nota_fiscal_item_aptech_nota_fiscal_to_string;
        }
    
        $values = AptechNotaFiscalItem::where('aptech_nota_fiscal_id', '=', $this->id)->getIndexedArray('aptech_nota_fiscal_id','{aptech_nota_fiscal->id}');
        return implode(', ', $values);
    }

    
}

