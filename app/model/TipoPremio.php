<?php

class TipoPremio extends TRecord
{
    const TABLENAME  = 'tipo_premio';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('coluna');
        parent::addAttribute('seq');
            
    }

    /**
     * Method getPremios
     */
    public function getPremios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipo_premio_id', '=', $this->id));
        return Premio::getObjects( $criteria );
    }

    public function set_premio_tipo_premio_to_string($premio_tipo_premio_to_string)
    {
        if(is_array($premio_tipo_premio_to_string))
        {
            $values = TipoPremio::where('id', 'in', $premio_tipo_premio_to_string)->getIndexedArray('id', 'id');
            $this->premio_tipo_premio_to_string = implode(', ', $values);
        }
        else
        {
            $this->premio_tipo_premio_to_string = $premio_tipo_premio_to_string;
        }

        $this->vdata['premio_tipo_premio_to_string'] = $this->premio_tipo_premio_to_string;
    }

    public function get_premio_tipo_premio_to_string()
    {
        if(!empty($this->premio_tipo_premio_to_string))
        {
            return $this->premio_tipo_premio_to_string;
        }
    
        $values = Premio::where('tipo_premio_id', '=', $this->id)->getIndexedArray('tipo_premio_id','{tipo_premio->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(Premio::where('tipo_premio_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

