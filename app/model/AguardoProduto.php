<?php

class AguardoProduto extends TRecord
{
    const TABLENAME  = 'aguardo_produto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApItem $ap_item;
    private SystemUsers $system_users;
    private ApRepresentante $repres;
    private SystemUnit $system_unit;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_unit_id');
        parent::addAttribute('system_users_id');
        parent::addAttribute('repres_id');
        parent::addAttribute('ap_item_id');
        parent::addAttribute('quantidade');
        parent::addAttribute('status');
        parent::addAttribute('cod_clifor');
    
    }

    /**
     * Method set_ap_item
     * Sample of usage: $var->ap_item = $object;
     * @param $object Instance of ApItem
     */
    public function set_ap_item(ApItem $object)
    {
        $this->ap_item = $object;
        $this->ap_item_id = $object->id;
    }

    /**
     * Method get_ap_item
     * Sample of usage: $var->ap_item->attribute;
     * @returns ApItem instance
     */
    public function get_ap_item()
    {
    
        // loads the associated object
        if (empty($this->ap_item))
            $this->ap_item = new ApItem($this->ap_item_id);
    
        // returns the associated object
        return $this->ap_item;
    }
    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_system_users(SystemUsers $object)
    {
        $this->system_users = $object;
        $this->system_users_id = $object->id;
    }

    /**
     * Method get_system_users
     * Sample of usage: $var->system_users->attribute;
     * @returns SystemUsers instance
     */
    public function get_system_users()
    {
    
        // loads the associated object
        if (empty($this->system_users))
            $this->system_users = new SystemUsers($this->system_users_id);
    
        // returns the associated object
        return $this->system_users;
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
     * Method set_system_unit
     * Sample of usage: $var->system_unit = $object;
     * @param $object Instance of SystemUnit
     */
    public function set_system_unit(SystemUnit $object)
    {
        $this->system_unit = $object;
        $this->system_unit_id = $object->id;
    }

    /**
     * Method get_system_unit
     * Sample of usage: $var->system_unit->attribute;
     * @returns SystemUnit instance
     */
    public function get_system_unit()
    {
    
        // loads the associated object
        if (empty($this->system_unit))
            $this->system_unit = new SystemUnit($this->system_unit_id);
    
        // returns the associated object
        return $this->system_unit;
    }

}

