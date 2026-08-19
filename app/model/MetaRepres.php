<?php

class MetaRepres extends TRecord
{
    const TABLENAME  = 'meta_repres';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApRepresentante $repres;
    private Meta $meta;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('meta_id');
        parent::addAttribute('repres_id');
        parent::addAttribute('fantasia');
        parent::addAttribute('valor_meta');
        parent::addAttribute('valor_super_meta');
        parent::addAttribute('valor_moc');
        parent::addAttribute('valor_cortina');
        parent::addAttribute('valor_mostruario');
        parent::addAttribute('valor_prospeccao');
        parent::addAttribute('valor_reativacao');
        parent::addAttribute('valor_prosp_reat');
        parent::addAttribute('perc_site');
        parent::addAttribute('perc_aprov_carteira');
        parent::addAttribute('perc_cliente_abaixo');
        parent::addAttribute('perc_fora_estado');
        parent::addAttribute('perc_import');
        parent::addAttribute('perc_persianas');
        parent::addAttribute('valor_import');
        parent::addAttribute('valor_persianas');
    
    }

    /**
     * Method set_ap_representante
     * Sample of usage: $var->ap_representante = $object;
     * @param $object Instance of ApRepresentante
     */
    public function set_repres(ApRepresentante $object)
    {
        $this->repres = $object;
        $this->repres_id = $object->id;
    }

    /**
     * Method get_repres
     * Sample of usage: $var->repres->attribute;
     * @returns ApRepresentante instance
     */
    public function get_repres()
    {
    
        // loads the associated object
        if (empty($this->repres))
            $this->repres = new ApRepresentante($this->repres_id);
    
        // returns the associated object
        return $this->repres;
    }
    /**
     * Method set_meta
     * Sample of usage: $var->meta = $object;
     * @param $object Instance of Meta
     */
    public function set_meta(Meta $object)
    {
        $this->meta = $object;
        $this->meta_id = $object->id;
    }

    /**
     * Method get_meta
     * Sample of usage: $var->meta->attribute;
     * @returns Meta instance
     */
    public function get_meta()
    {
    
        // loads the associated object
        if (empty($this->meta))
            $this->meta = new Meta($this->meta_id);
    
        // returns the associated object
        return $this->meta;
    }

    /**
     * Method getMetaFechamentos
     */
    public function getMetaFechamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_repres_id', '=', $this->id));
        return MetaFechamento::getObjects( $criteria );
    }

    public function set_meta_fechamento_meta_to_string($meta_fechamento_meta_to_string)
    {
        if(is_array($meta_fechamento_meta_to_string))
        {
            $values = Meta::where('id', 'in', $meta_fechamento_meta_to_string)->getIndexedArray('id', 'id');
            $this->meta_fechamento_meta_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_fechamento_meta_to_string = $meta_fechamento_meta_to_string;
        }

        $this->vdata['meta_fechamento_meta_to_string'] = $this->meta_fechamento_meta_to_string;
    }

    public function get_meta_fechamento_meta_to_string()
    {
        if(!empty($this->meta_fechamento_meta_to_string))
        {
            return $this->meta_fechamento_meta_to_string;
        }
    
        $values = MetaFechamento::where('meta_repres_id', '=', $this->id)->getIndexedArray('meta_id','{meta->id}');
        return implode(', ', $values);
    }

    public function set_meta_fechamento_meta_repres_to_string($meta_fechamento_meta_repres_to_string)
    {
        if(is_array($meta_fechamento_meta_repres_to_string))
        {
            $values = MetaRepres::where('id', 'in', $meta_fechamento_meta_repres_to_string)->getIndexedArray('id', 'id');
            $this->meta_fechamento_meta_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_fechamento_meta_repres_to_string = $meta_fechamento_meta_repres_to_string;
        }

        $this->vdata['meta_fechamento_meta_repres_to_string'] = $this->meta_fechamento_meta_repres_to_string;
    }

    public function get_meta_fechamento_meta_repres_to_string()
    {
        if(!empty($this->meta_fechamento_meta_repres_to_string))
        {
            return $this->meta_fechamento_meta_repres_to_string;
        }
    
        $values = MetaFechamento::where('meta_repres_id', '=', $this->id)->getIndexedArray('meta_repres_id','{meta_repres->id}');
        return implode(', ', $values);
    }

    public function set_meta_fechamento_repres_to_string($meta_fechamento_repres_to_string)
    {
        if(is_array($meta_fechamento_repres_to_string))
        {
            $values = ApRepresentante::where('id', 'in', $meta_fechamento_repres_to_string)->getIndexedArray('id', 'id');
            $this->meta_fechamento_repres_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_fechamento_repres_to_string = $meta_fechamento_repres_to_string;
        }

        $this->vdata['meta_fechamento_repres_to_string'] = $this->meta_fechamento_repres_to_string;
    }

    public function get_meta_fechamento_repres_to_string()
    {
        if(!empty($this->meta_fechamento_repres_to_string))
        {
            return $this->meta_fechamento_repres_to_string;
        }
    
        $values = MetaFechamento::where('meta_repres_id', '=', $this->id)->getIndexedArray('repres_id','{repres->id}');
        return implode(', ', $values);
    }

}

