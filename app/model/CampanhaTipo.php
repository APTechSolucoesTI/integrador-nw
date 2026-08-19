<?php

class CampanhaTipo extends TRecord
{
    const TABLENAME  = 'campanha_tipo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const QTDE = '1';
    const RANKING = '2';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
            
    }

    /**
     * Method getCampanhas
     */
    public function getCampanhas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('campanha_tipo_id', '=', $this->id));
        return Campanha::getObjects( $criteria );
    }

    public function set_campanha_system_unit_to_string($campanha_system_unit_to_string)
    {
        if(is_array($campanha_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $campanha_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->campanha_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_system_unit_to_string = $campanha_system_unit_to_string;
        }

        $this->vdata['campanha_system_unit_to_string'] = $this->campanha_system_unit_to_string;
    }

    public function get_campanha_system_unit_to_string()
    {
        if(!empty($this->campanha_system_unit_to_string))
        {
            return $this->campanha_system_unit_to_string;
        }
    
        $values = Campanha::where('campanha_tipo_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_campanha_ap_tabela_preco_to_string($campanha_ap_tabela_preco_to_string)
    {
        if(is_array($campanha_ap_tabela_preco_to_string))
        {
            $values = ApTabelaPreco::where('id', 'in', $campanha_ap_tabela_preco_to_string)->getIndexedArray('id', 'id');
            $this->campanha_ap_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_ap_tabela_preco_to_string = $campanha_ap_tabela_preco_to_string;
        }

        $this->vdata['campanha_ap_tabela_preco_to_string'] = $this->campanha_ap_tabela_preco_to_string;
    }

    public function get_campanha_ap_tabela_preco_to_string()
    {
        if(!empty($this->campanha_ap_tabela_preco_to_string))
        {
            return $this->campanha_ap_tabela_preco_to_string;
        }
    
        $values = Campanha::where('campanha_tipo_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
        return implode(', ', $values);
    }

    public function set_campanha_campanha_tipo_to_string($campanha_campanha_tipo_to_string)
    {
        if(is_array($campanha_campanha_tipo_to_string))
        {
            $values = CampanhaTipo::where('id', 'in', $campanha_campanha_tipo_to_string)->getIndexedArray('id', 'id');
            $this->campanha_campanha_tipo_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_campanha_tipo_to_string = $campanha_campanha_tipo_to_string;
        }

        $this->vdata['campanha_campanha_tipo_to_string'] = $this->campanha_campanha_tipo_to_string;
    }

    public function get_campanha_campanha_tipo_to_string()
    {
        if(!empty($this->campanha_campanha_tipo_to_string))
        {
            return $this->campanha_campanha_tipo_to_string;
        }
    
        $values = Campanha::where('campanha_tipo_id', '=', $this->id)->getIndexedArray('campanha_tipo_id','{campanha_tipo->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(Campanha::where('campanha_tipo_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

