<?php

class MetaFechamento extends TRecord
{
    const TABLENAME  = 'meta_fechamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApRepresentante $repres;
    private Meta $meta;
    private MetaRepres $meta_repres;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('meta_id');
        parent::addAttribute('meta_repres_id');
        parent::addAttribute('repres_id');
        parent::addAttribute('faturamento_total');
        parent::addAttribute('faturamento_cortina');
        parent::addAttribute('faturamento_mostruario');
        parent::addAttribute('faturamento_prospeccao');
        parent::addAttribute('faturamento_reativacao');
        parent::addAttribute('faturamento_prosp_reat');
        parent::addAttribute('percentual_import');
        parent::addAttribute('percentual_persianas');
        parent::addAttribute('alcancou_meta');
        parent::addAttribute('alcancou_super_meta');
        parent::addAttribute('alcancou_import');
        parent::addAttribute('alcancou_persianas');
        parent::addAttribute('alcancou_cortina_pronta');
        parent::addAttribute('alcancou_mostruario');
        parent::addAttribute('alcancou_prospeccao');
        parent::addAttribute('alcancou_reativacao');
        parent::addAttribute('alcancou_prosp_reat');
        parent::addAttribute('alcancou_site');
        parent::addAttribute('premio_cortina');
        parent::addAttribute('premio_mostruario');
        parent::addAttribute('premio_prospeccao');
        parent::addAttribute('premio_reativacao');
        parent::addAttribute('premio_site');
        parent::addAttribute('premio_prosp_reat');
        parent::addAttribute('comissao');
        parent::addAttribute('bonus_meta');
        parent::addAttribute('bonus_mini');
        parent::addAttribute('bonus_mini_pers');
        parent::addAttribute('bonus_mini_import');
        parent::addAttribute('st');
        parent::addAttribute('premio_estrategico');
        parent::addAttribute('site_porcentagem');
            
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
     * Method set_meta_repres
     * Sample of usage: $var->meta_repres = $object;
     * @param $object Instance of MetaRepres
     */
    public function set_meta_repres(MetaRepres $object)
    {
        $this->meta_repres = $object;
        $this->meta_repres_id = $object->id;
    }

    /**
     * Method get_meta_repres
     * Sample of usage: $var->meta_repres->attribute;
     * @returns MetaRepres instance
     */
    public function get_meta_repres()
    {
    
        // loads the associated object
        if (empty($this->meta_repres))
            $this->meta_repres = new MetaRepres($this->meta_repres_id);
    
        // returns the associated object
        return $this->meta_repres;
    }

    
}

