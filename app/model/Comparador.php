<?php

class Comparador extends TRecord
{
    const TABLENAME  = 'comparador';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('operador');
            
    }

    /**
     * Method getCampanhaPremios
     */
    public function getCampanhaPremios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('comparador_id', '=', $this->id));
        return CampanhaPremio::getObjects( $criteria );
    }
    /**
     * Method getPremioRegras
     */
    public function getPremioRegras()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('comparador_id', '=', $this->id));
        return PremioRegra::getObjects( $criteria );
    }

    public function set_campanha_premio_campanha_to_string($campanha_premio_campanha_to_string)
    {
        if(is_array($campanha_premio_campanha_to_string))
        {
            $values = Campanha::where('id', 'in', $campanha_premio_campanha_to_string)->getIndexedArray('descricao', 'descricao');
            $this->campanha_premio_campanha_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_premio_campanha_to_string = $campanha_premio_campanha_to_string;
        }

        $this->vdata['campanha_premio_campanha_to_string'] = $this->campanha_premio_campanha_to_string;
    }

    public function get_campanha_premio_campanha_to_string()
    {
        if(!empty($this->campanha_premio_campanha_to_string))
        {
            return $this->campanha_premio_campanha_to_string;
        }
    
        $values = CampanhaPremio::where('comparador_id', '=', $this->id)->getIndexedArray('campanha_id','{campanha->descricao}');
        return implode(', ', $values);
    }

    public function set_campanha_premio_comparador_to_string($campanha_premio_comparador_to_string)
    {
        if(is_array($campanha_premio_comparador_to_string))
        {
            $values = Comparador::where('id', 'in', $campanha_premio_comparador_to_string)->getIndexedArray('id', 'id');
            $this->campanha_premio_comparador_to_string = implode(', ', $values);
        }
        else
        {
            $this->campanha_premio_comparador_to_string = $campanha_premio_comparador_to_string;
        }

        $this->vdata['campanha_premio_comparador_to_string'] = $this->campanha_premio_comparador_to_string;
    }

    public function get_campanha_premio_comparador_to_string()
    {
        if(!empty($this->campanha_premio_comparador_to_string))
        {
            return $this->campanha_premio_comparador_to_string;
        }
    
        $values = CampanhaPremio::where('comparador_id', '=', $this->id)->getIndexedArray('comparador_id','{comparador->id}');
        return implode(', ', $values);
    }

    public function set_premio_regra_premio_to_string($premio_regra_premio_to_string)
    {
        if(is_array($premio_regra_premio_to_string))
        {
            $values = Premio::where('id', 'in', $premio_regra_premio_to_string)->getIndexedArray('id', 'id');
            $this->premio_regra_premio_to_string = implode(', ', $values);
        }
        else
        {
            $this->premio_regra_premio_to_string = $premio_regra_premio_to_string;
        }

        $this->vdata['premio_regra_premio_to_string'] = $this->premio_regra_premio_to_string;
    }

    public function get_premio_regra_premio_to_string()
    {
        if(!empty($this->premio_regra_premio_to_string))
        {
            return $this->premio_regra_premio_to_string;
        }
    
        $values = PremioRegra::where('comparador_id', '=', $this->id)->getIndexedArray('premio_id','{premio->id}');
        return implode(', ', $values);
    }

    public function set_premio_regra_comparador_to_string($premio_regra_comparador_to_string)
    {
        if(is_array($premio_regra_comparador_to_string))
        {
            $values = Comparador::where('id', 'in', $premio_regra_comparador_to_string)->getIndexedArray('id', 'id');
            $this->premio_regra_comparador_to_string = implode(', ', $values);
        }
        else
        {
            $this->premio_regra_comparador_to_string = $premio_regra_comparador_to_string;
        }

        $this->vdata['premio_regra_comparador_to_string'] = $this->premio_regra_comparador_to_string;
    }

    public function get_premio_regra_comparador_to_string()
    {
        if(!empty($this->premio_regra_comparador_to_string))
        {
            return $this->premio_regra_comparador_to_string;
        }
    
        $values = PremioRegra::where('comparador_id', '=', $this->id)->getIndexedArray('comparador_id','{comparador->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(CampanhaPremio::where('comparador_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(PremioRegra::where('comparador_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

