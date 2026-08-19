<?php

class CreditoClienteCadastro extends TRecord
{
    const TABLENAME  = 'credito_cliente_cadastro';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('periodo_inicio');
        parent::addAttribute('periodo_fim');
        parent::addAttribute('data_criacao');
        parent::addAttribute('status');
            
    }

    /**
     * Method getCreditoClientes
     */
    public function getCreditoClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('credito_cliente_cadastro_id', '=', $this->id));
        return CreditoCliente::getObjects( $criteria );
    }

    public function set_credito_cliente_historico_cli_to_string($credito_cliente_historico_cli_to_string)
    {
        if(is_array($credito_cliente_historico_cli_to_string))
        {
            $values = HistoricoCliRepres::where('id', 'in', $credito_cliente_historico_cli_to_string)->getIndexedArray('id', 'id');
            $this->credito_cliente_historico_cli_to_string = implode(', ', $values);
        }
        else
        {
            $this->credito_cliente_historico_cli_to_string = $credito_cliente_historico_cli_to_string;
        }

        $this->vdata['credito_cliente_historico_cli_to_string'] = $this->credito_cliente_historico_cli_to_string;
    }

    public function get_credito_cliente_historico_cli_to_string()
    {
        if(!empty($this->credito_cliente_historico_cli_to_string))
        {
            return $this->credito_cliente_historico_cli_to_string;
        }
    
        $values = CreditoCliente::where('credito_cliente_cadastro_id', '=', $this->id)->getIndexedArray('historico_cli_id','{historico_cli->id}');
        return implode(', ', $values);
    }

    public function set_credito_cliente_credito_cliente_cadastro_to_string($credito_cliente_credito_cliente_cadastro_to_string)
    {
        if(is_array($credito_cliente_credito_cliente_cadastro_to_string))
        {
            $values = CreditoClienteCadastro::where('id', 'in', $credito_cliente_credito_cliente_cadastro_to_string)->getIndexedArray('id', 'id');
            $this->credito_cliente_credito_cliente_cadastro_to_string = implode(', ', $values);
        }
        else
        {
            $this->credito_cliente_credito_cliente_cadastro_to_string = $credito_cliente_credito_cliente_cadastro_to_string;
        }

        $this->vdata['credito_cliente_credito_cliente_cadastro_to_string'] = $this->credito_cliente_credito_cliente_cadastro_to_string;
    }

    public function get_credito_cliente_credito_cliente_cadastro_to_string()
    {
        if(!empty($this->credito_cliente_credito_cliente_cadastro_to_string))
        {
            return $this->credito_cliente_credito_cliente_cadastro_to_string;
        }
    
        $values = CreditoCliente::where('credito_cliente_cadastro_id', '=', $this->id)->getIndexedArray('credito_cliente_cadastro_id','{credito_cliente_cadastro->id}');
        return implode(', ', $values);
    }

    
}

