<?php

class MiniMetaTipo extends TRecord
{
    const TABLENAME  = 'mini_meta_tipo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const QTDE = '1';
    const VALOR = '2';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
            
    }

    /**
     * Method getMiniMetas
     */
    public function getMiniMetas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('mini_meta_tipo_id', '=', $this->id));
        return MiniMeta::getObjects( $criteria );
    }

    public function set_mini_meta_mini_meta_tipo_to_string($mini_meta_mini_meta_tipo_to_string)
    {
        if(is_array($mini_meta_mini_meta_tipo_to_string))
        {
            $values = MiniMetaTipo::where('id', 'in', $mini_meta_mini_meta_tipo_to_string)->getIndexedArray('nome', 'nome');
            $this->mini_meta_mini_meta_tipo_to_string = implode(', ', $values);
        }
        else
        {
            $this->mini_meta_mini_meta_tipo_to_string = $mini_meta_mini_meta_tipo_to_string;
        }

        $this->vdata['mini_meta_mini_meta_tipo_to_string'] = $this->mini_meta_mini_meta_tipo_to_string;
    }

    public function get_mini_meta_mini_meta_tipo_to_string()
    {
        if(!empty($this->mini_meta_mini_meta_tipo_to_string))
        {
            return $this->mini_meta_mini_meta_tipo_to_string;
        }
    
        $values = MiniMeta::where('mini_meta_tipo_id', '=', $this->id)->getIndexedArray('mini_meta_tipo_id','{mini_meta_tipo->nome}');
        return implode(', ', $values);
    }

    public function set_mini_meta_ap_tabela_preco_to_string($mini_meta_ap_tabela_preco_to_string)
    {
        if(is_array($mini_meta_ap_tabela_preco_to_string))
        {
            $values = ApTabelaPreco::where('id', 'in', $mini_meta_ap_tabela_preco_to_string)->getIndexedArray('id', 'id');
            $this->mini_meta_ap_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->mini_meta_ap_tabela_preco_to_string = $mini_meta_ap_tabela_preco_to_string;
        }

        $this->vdata['mini_meta_ap_tabela_preco_to_string'] = $this->mini_meta_ap_tabela_preco_to_string;
    }

    public function get_mini_meta_ap_tabela_preco_to_string()
    {
        if(!empty($this->mini_meta_ap_tabela_preco_to_string))
        {
            return $this->mini_meta_ap_tabela_preco_to_string;
        }
    
        $values = MiniMeta::where('mini_meta_tipo_id', '=', $this->id)->getIndexedArray('ap_tabela_preco_id','{ap_tabela_preco->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(MiniMeta::where('mini_meta_tipo_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

