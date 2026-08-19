<?php

class Premio extends TRecord
{
    const TABLENAME  = 'premio';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private TipoPremio $tipo_premio;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('tipo_premio_id');
        parent::addAttribute('obs');
        parent::addAttribute('restricao');
    
    }

    /**
     * Method set_tipo_premio
     * Sample of usage: $var->tipo_premio = $object;
     * @param $object Instance of TipoPremio
     */
    public function set_tipo_premio(TipoPremio $object)
    {
        $this->tipo_premio = $object;
        $this->tipo_premio_id = $object->id;
    }

    /**
     * Method get_tipo_premio
     * Sample of usage: $var->tipo_premio->attribute;
     * @returns TipoPremio instance
     */
    public function get_tipo_premio()
    {
    
        // loads the associated object
        if (empty($this->tipo_premio))
            $this->tipo_premio = new TipoPremio($this->tipo_premio_id);
    
        // returns the associated object
        return $this->tipo_premio;
    }

    /**
     * Method getPremioRegras
     */
    public function getPremioRegras()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('premio_id', '=', $this->id));
        return PremioRegra::getObjects( $criteria );
    }
    /**
     * Method getRestricaoPremios
     */
    public function getRestricaoPremios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('premio_id', '=', $this->id));
        return RestricaoPremio::getObjects( $criteria );
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
    
        $values = PremioRegra::where('premio_id', '=', $this->id)->getIndexedArray('premio_id','{premio->id}');
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
    
        $values = PremioRegra::where('premio_id', '=', $this->id)->getIndexedArray('comparador_id','{comparador->id}');
        return implode(', ', $values);
    }

    public function set_restricao_premio_premio_to_string($restricao_premio_premio_to_string)
    {
        if(is_array($restricao_premio_premio_to_string))
        {
            $values = Premio::where('id', 'in', $restricao_premio_premio_to_string)->getIndexedArray('id', 'id');
            $this->restricao_premio_premio_to_string = implode(', ', $values);
        }
        else
        {
            $this->restricao_premio_premio_to_string = $restricao_premio_premio_to_string;
        }

        $this->vdata['restricao_premio_premio_to_string'] = $this->restricao_premio_premio_to_string;
    }

    public function get_restricao_premio_premio_to_string()
    {
        if(!empty($this->restricao_premio_premio_to_string))
        {
            return $this->restricao_premio_premio_to_string;
        }
    
        $values = RestricaoPremio::where('premio_id', '=', $this->id)->getIndexedArray('premio_id','{premio->id}');
        return implode(', ', $values);
    }

    public function set_restricao_premio_ap_grupo_estoque_to_string($restricao_premio_ap_grupo_estoque_to_string)
    {
        if(is_array($restricao_premio_ap_grupo_estoque_to_string))
        {
            $values = ApGrupoEstoque::where('id', 'in', $restricao_premio_ap_grupo_estoque_to_string)->getIndexedArray('descricao', 'descricao');
            $this->restricao_premio_ap_grupo_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->restricao_premio_ap_grupo_estoque_to_string = $restricao_premio_ap_grupo_estoque_to_string;
        }

        $this->vdata['restricao_premio_ap_grupo_estoque_to_string'] = $this->restricao_premio_ap_grupo_estoque_to_string;
    }

    public function get_restricao_premio_ap_grupo_estoque_to_string()
    {
        if(!empty($this->restricao_premio_ap_grupo_estoque_to_string))
        {
            return $this->restricao_premio_ap_grupo_estoque_to_string;
        }
    
        $values = RestricaoPremio::where('premio_id', '=', $this->id)->getIndexedArray('ap_grupo_estoque_id','{ap_grupo_estoque->descricao}');
        return implode(', ', $values);
    }

    public function set_restricao_premio_ap_subgrupo_estoque_to_string($restricao_premio_ap_subgrupo_estoque_to_string)
    {
        if(is_array($restricao_premio_ap_subgrupo_estoque_to_string))
        {
            $values = ApSubgrupoEstoque::where('id', 'in', $restricao_premio_ap_subgrupo_estoque_to_string)->getIndexedArray('descricao', 'descricao');
            $this->restricao_premio_ap_subgrupo_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->restricao_premio_ap_subgrupo_estoque_to_string = $restricao_premio_ap_subgrupo_estoque_to_string;
        }

        $this->vdata['restricao_premio_ap_subgrupo_estoque_to_string'] = $this->restricao_premio_ap_subgrupo_estoque_to_string;
    }

    public function get_restricao_premio_ap_subgrupo_estoque_to_string()
    {
        if(!empty($this->restricao_premio_ap_subgrupo_estoque_to_string))
        {
            return $this->restricao_premio_ap_subgrupo_estoque_to_string;
        }
    
        $values = RestricaoPremio::where('premio_id', '=', $this->id)->getIndexedArray('ap_subgrupo_estoque_id','{ap_subgrupo_estoque->descricao}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
    

        if(PremioRegra::where('premio_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(RestricaoPremio::where('premio_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    public function get_mes_ano(){
        switch($this->mes){
            case 1:
                return "Janeiro/".$this->ano;
            case 2:
                return "Fevereiro/".$this->ano;
            case 3:
                return "Março/".$this->ano;
            case 4:
                return "Abril/".$this->ano;
            case 5:
                return "Maio/".$this->ano;
            case 6:
                return "Junho/".$this->ano;
            case 7:
                return "Julho/".$this->ano;
            case 8:
                return "Agosto/".$this->ano;
            case 9:
                return "Setembro/".$this->ano;
            case 10:
                return "Outubro/".$this->ano;
            case 11:
                return "Novembro/".$this->ano;
            case 12:
                return "Dezembro/".$this->ano;
            default:
                return null;
        }
    }
    
}

