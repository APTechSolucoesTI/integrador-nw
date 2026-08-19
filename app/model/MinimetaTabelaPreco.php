<?php

class MinimetaTabelaPreco extends TRecord
{
    const TABLENAME  = 'minimeta_tabela_preco';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApTabelaPreco $ap_tabela_preco;
    private MiniMeta $mini_meta;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('ap_tabela_preco_id');
        parent::addAttribute('mini_meta_id');
        parent::addAttribute('fazparte');
    
    }

    /**
     * Method set_ap_tabela_preco
     * Sample of usage: $var->ap_tabela_preco = $object;
     * @param $object Instance of ApTabelaPreco
     */
    public function set_ap_tabela_preco(ApTabelaPreco $object)
    {
        $this->ap_tabela_preco = $object;
        $this->ap_tabela_preco_id = $object->id;
    }

    /**
     * Method get_ap_tabela_preco
     * Sample of usage: $var->ap_tabela_preco->attribute;
     * @returns ApTabelaPreco instance
     */
    public function get_ap_tabela_preco()
    {
    
        // loads the associated object
        if (empty($this->ap_tabela_preco))
            $this->ap_tabela_preco = new ApTabelaPreco($this->ap_tabela_preco_id);
    
        // returns the associated object
        return $this->ap_tabela_preco;
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

}

