<?php

class CreditoCliente extends TRecord
{
    const TABLENAME  = 'credito_cliente';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private HistoricoCliRepres $historico_cli;
    private CreditoClienteCadastro $credito_cliente_cadastro;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_clifor');
        parent::addAttribute('valor_total');
        parent::addAttribute('media_por_pedido');
        parent::addAttribute('razao_clifor');
        parent::addAttribute('periodo_inicio');
        parent::addAttribute('periodo_fim');
        parent::addAttribute('quantidade_notas');
        parent::addAttribute('data_processamento');
        parent::addAttribute('historico_cli_id');
        parent::addAttribute('credito_cliente_cadastro_id');
            
    }

    /**
     * Method set_historico_cli_repres
     * Sample of usage: $var->historico_cli_repres = $object;
     * @param $object Instance of HistoricoCliRepres
     */
    public function set_historico_cli(HistoricoCliRepres $object)
    {
        $this->historico_cli = $object;
        $this->historico_cli_id = $object->id;
    }

    /**
     * Method get_historico_cli
     * Sample of usage: $var->historico_cli->attribute;
     * @returns HistoricoCliRepres instance
     */
    public function get_historico_cli()
    {
    
        // loads the associated object
        if (empty($this->historico_cli))
            $this->historico_cli = new HistoricoCliRepres($this->historico_cli_id);
    
        // returns the associated object
        return $this->historico_cli;
    }
    /**
     * Method set_credito_cliente_cadastro
     * Sample of usage: $var->credito_cliente_cadastro = $object;
     * @param $object Instance of CreditoClienteCadastro
     */
    public function set_credito_cliente_cadastro(CreditoClienteCadastro $object)
    {
        $this->credito_cliente_cadastro = $object;
        $this->credito_cliente_cadastro_id = $object->id;
    }

    /**
     * Method get_credito_cliente_cadastro
     * Sample of usage: $var->credito_cliente_cadastro->attribute;
     * @returns CreditoClienteCadastro instance
     */
    public function get_credito_cliente_cadastro()
    {
    
        // loads the associated object
        if (empty($this->credito_cliente_cadastro))
            $this->credito_cliente_cadastro = new CreditoClienteCadastro($this->credito_cliente_cadastro_id);
    
        // returns the associated object
        return $this->credito_cliente_cadastro;
    }

    
}

