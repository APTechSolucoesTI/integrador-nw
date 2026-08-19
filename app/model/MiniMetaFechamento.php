<?php

class MiniMetaFechamento extends TRecord
{
    const TABLENAME  = 'mini_meta_fechamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private MiniMeta $mini_meta;
    private ApRepresentante $ap_representante;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('mini_meta_id');
        parent::addAttribute('ap_representante_id');
        parent::addAttribute('alcancou_st');
        parent::addAttribute('quantidade_st');
        parent::addAttribute('premio_st');
            
    }

    /**
     * Method set_mini_meta
     * Sample of usage: $var->mini_meta = $object;
     * @param $object Instance of MiniMeta
     */
    public function set_mini_meta(MiniMeta $object)
    {
        $this->mini_meta = $object;
        $this->mini_meta_id = $object->id;
    }

    /**
     * Method get_mini_meta
     * Sample of usage: $var->mini_meta->attribute;
     * @returns MiniMeta instance
     */
    public function get_mini_meta()
    {
    
        // loads the associated object
        if (empty($this->mini_meta))
            $this->mini_meta = new MiniMeta($this->mini_meta_id);
    
        // returns the associated object
        return $this->mini_meta;
    }
    /**
     * Method set_ap_representante
     * Sample of usage: $var->ap_representante = $object;
     * @param $object Instance of ApRepresentante
     */
    public function set_ap_representante(ApRepresentante $object)
    {
        $this->ap_representante = $object;
        $this->ap_representante_id = $object->id;
    }

    /**
     * Method get_ap_representante
     * Sample of usage: $var->ap_representante->attribute;
     * @returns ApRepresentante instance
     */
    public function get_ap_representante()
    {
    
        // loads the associated object
        if (empty($this->ap_representante))
            $this->ap_representante = new ApRepresentante($this->ap_representante_id);
    
        // returns the associated object
        return $this->ap_representante;
    }

    
}

