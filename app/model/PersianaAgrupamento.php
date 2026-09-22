<?php

class PersianaAgrupamento extends TRecord
{
    const TABLENAME  = 'persiana_agrupamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('qtd');
        parent::addAttribute('data_inicio');
        parent::addAttribute('data_fim');
        parent::addAttribute('ativo');
        parent::addAttribute('eficiencia_operacional');
            
    }

    /**
     * Method getPersianaAgrupamentoDiass
     */
    public function getPersianaAgrupamentoDiass()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('persiana_agrupamento_id', '=', $this->id));
        return PersianaAgrupamentoDias::getObjects( $criteria );
    }
    /**
     * Method getPersianaAgrupamentoExcecaos
     */
    public function getPersianaAgrupamentoExcecaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('persiana_agrupamento_id', '=', $this->id));
        return PersianaAgrupamentoExcecao::getObjects( $criteria );
    }
    /**
     * Method getPersianaAgrupamentoGrupos
     */
    public function getPersianaAgrupamentoGrupos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('persiana_agrupamento_id', '=', $this->id));
        return PersianaAgrupamentoGrupo::getObjects( $criteria );
    }

    public function set_persiana_agrupamento_dias_persiana_agrupamento_to_string($persiana_agrupamento_dias_persiana_agrupamento_to_string)
    {
        if(is_array($persiana_agrupamento_dias_persiana_agrupamento_to_string))
        {
            $values = PersianaAgrupamento::where('id', 'in', $persiana_agrupamento_dias_persiana_agrupamento_to_string)->getIndexedArray('id', 'id');
            $this->persiana_agrupamento_dias_persiana_agrupamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->persiana_agrupamento_dias_persiana_agrupamento_to_string = $persiana_agrupamento_dias_persiana_agrupamento_to_string;
        }

        $this->vdata['persiana_agrupamento_dias_persiana_agrupamento_to_string'] = $this->persiana_agrupamento_dias_persiana_agrupamento_to_string;
    }

    public function get_persiana_agrupamento_dias_persiana_agrupamento_to_string()
    {
        if(!empty($this->persiana_agrupamento_dias_persiana_agrupamento_to_string))
        {
            return $this->persiana_agrupamento_dias_persiana_agrupamento_to_string;
        }
    
        $values = PersianaAgrupamentoDias::where('persiana_agrupamento_id', '=', $this->id)->getIndexedArray('persiana_agrupamento_id','{persiana_agrupamento->id}');
        return implode(', ', $values);
    }

    public function set_persiana_agrupamento_excecao_persiana_agrupamento_to_string($persiana_agrupamento_excecao_persiana_agrupamento_to_string)
    {
        if(is_array($persiana_agrupamento_excecao_persiana_agrupamento_to_string))
        {
            $values = PersianaAgrupamento::where('id', 'in', $persiana_agrupamento_excecao_persiana_agrupamento_to_string)->getIndexedArray('id', 'id');
            $this->persiana_agrupamento_excecao_persiana_agrupamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->persiana_agrupamento_excecao_persiana_agrupamento_to_string = $persiana_agrupamento_excecao_persiana_agrupamento_to_string;
        }

        $this->vdata['persiana_agrupamento_excecao_persiana_agrupamento_to_string'] = $this->persiana_agrupamento_excecao_persiana_agrupamento_to_string;
    }

    public function get_persiana_agrupamento_excecao_persiana_agrupamento_to_string()
    {
        if(!empty($this->persiana_agrupamento_excecao_persiana_agrupamento_to_string))
        {
            return $this->persiana_agrupamento_excecao_persiana_agrupamento_to_string;
        }
    
        $values = PersianaAgrupamentoExcecao::where('persiana_agrupamento_id', '=', $this->id)->getIndexedArray('persiana_agrupamento_id','{persiana_agrupamento->id}');
        return implode(', ', $values);
    }

    public function set_persiana_agrupamento_grupo_persiana_agrupamento_to_string($persiana_agrupamento_grupo_persiana_agrupamento_to_string)
    {
        if(is_array($persiana_agrupamento_grupo_persiana_agrupamento_to_string))
        {
            $values = PersianaAgrupamento::where('id', 'in', $persiana_agrupamento_grupo_persiana_agrupamento_to_string)->getIndexedArray('id', 'id');
            $this->persiana_agrupamento_grupo_persiana_agrupamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->persiana_agrupamento_grupo_persiana_agrupamento_to_string = $persiana_agrupamento_grupo_persiana_agrupamento_to_string;
        }

        $this->vdata['persiana_agrupamento_grupo_persiana_agrupamento_to_string'] = $this->persiana_agrupamento_grupo_persiana_agrupamento_to_string;
    }

    public function get_persiana_agrupamento_grupo_persiana_agrupamento_to_string()
    {
        if(!empty($this->persiana_agrupamento_grupo_persiana_agrupamento_to_string))
        {
            return $this->persiana_agrupamento_grupo_persiana_agrupamento_to_string;
        }
    
        $values = PersianaAgrupamentoGrupo::where('persiana_agrupamento_id', '=', $this->id)->getIndexedArray('persiana_agrupamento_id','{persiana_agrupamento->id}');
        return implode(', ', $values);
    }

    public function set_persiana_agrupamento_grupo_ap_grupo_estoque_to_string($persiana_agrupamento_grupo_ap_grupo_estoque_to_string)
    {
        if(is_array($persiana_agrupamento_grupo_ap_grupo_estoque_to_string))
        {
            $values = ApGrupoEstoque::where('id', 'in', $persiana_agrupamento_grupo_ap_grupo_estoque_to_string)->getIndexedArray('descricao', 'descricao');
            $this->persiana_agrupamento_grupo_ap_grupo_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->persiana_agrupamento_grupo_ap_grupo_estoque_to_string = $persiana_agrupamento_grupo_ap_grupo_estoque_to_string;
        }

        $this->vdata['persiana_agrupamento_grupo_ap_grupo_estoque_to_string'] = $this->persiana_agrupamento_grupo_ap_grupo_estoque_to_string;
    }

    public function get_persiana_agrupamento_grupo_ap_grupo_estoque_to_string()
    {
        if(!empty($this->persiana_agrupamento_grupo_ap_grupo_estoque_to_string))
        {
            return $this->persiana_agrupamento_grupo_ap_grupo_estoque_to_string;
        }
    
        $values = PersianaAgrupamentoGrupo::where('persiana_agrupamento_id', '=', $this->id)->getIndexedArray('ap_grupo_estoque_id','{ap_grupo_estoque->descricao}');
        return implode(', ', $values);
    }

    
}

