<?php

class PlanejamentoImport extends TRecord
{
    const TABLENAME  = 'planejamento_import';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('qtd');
            
    }

    /**
     * Method getPlanejamentoImportExcecaos
     */
    public function getPlanejamentoImportExcecaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('planejamento_import_id', '=', $this->id));
        return PlanejamentoImportExcecao::getObjects( $criteria );
    }
    /**
     * Method getPlanejamentoImportDiass
     */
    public function getPlanejamentoImportDiass()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('planejamento_import_id', '=', $this->id));
        return PlanejamentoImportDias::getObjects( $criteria );
    }

    public function set_planejamento_import_excecao_planejamento_import_to_string($planejamento_import_excecao_planejamento_import_to_string)
    {
        if(is_array($planejamento_import_excecao_planejamento_import_to_string))
        {
            $values = PlanejamentoImport::where('id', 'in', $planejamento_import_excecao_planejamento_import_to_string)->getIndexedArray('id', 'id');
            $this->planejamento_import_excecao_planejamento_import_to_string = implode(', ', $values);
        }
        else
        {
            $this->planejamento_import_excecao_planejamento_import_to_string = $planejamento_import_excecao_planejamento_import_to_string;
        }

        $this->vdata['planejamento_import_excecao_planejamento_import_to_string'] = $this->planejamento_import_excecao_planejamento_import_to_string;
    }

    public function get_planejamento_import_excecao_planejamento_import_to_string()
    {
        if(!empty($this->planejamento_import_excecao_planejamento_import_to_string))
        {
            return $this->planejamento_import_excecao_planejamento_import_to_string;
        }
    
        $values = PlanejamentoImportExcecao::where('planejamento_import_id', '=', $this->id)->getIndexedArray('planejamento_import_id','{planejamento_import->id}');
        return implode(', ', $values);
    }

    public function set_planejamento_import_dias_planejamento_import_to_string($planejamento_import_dias_planejamento_import_to_string)
    {
        if(is_array($planejamento_import_dias_planejamento_import_to_string))
        {
            $values = PlanejamentoImport::where('id', 'in', $planejamento_import_dias_planejamento_import_to_string)->getIndexedArray('id', 'id');
            $this->planejamento_import_dias_planejamento_import_to_string = implode(', ', $values);
        }
        else
        {
            $this->planejamento_import_dias_planejamento_import_to_string = $planejamento_import_dias_planejamento_import_to_string;
        }

        $this->vdata['planejamento_import_dias_planejamento_import_to_string'] = $this->planejamento_import_dias_planejamento_import_to_string;
    }

    public function get_planejamento_import_dias_planejamento_import_to_string()
    {
        if(!empty($this->planejamento_import_dias_planejamento_import_to_string))
        {
            return $this->planejamento_import_dias_planejamento_import_to_string;
        }
    
        $values = PlanejamentoImportDias::where('planejamento_import_id', '=', $this->id)->getIndexedArray('planejamento_import_id','{planejamento_import->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(PlanejamentoImportExcecao::where('planejamento_import_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(PlanejamentoImportDias::where('planejamento_import_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

