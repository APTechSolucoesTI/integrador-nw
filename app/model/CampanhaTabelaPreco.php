<?php

class CampanhaTabelaPreco extends TRecord
{
    const TABLENAME  = 'campanha_tabela_preco';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ApTabelaPreco $ap_tabela_preco;
    private Campanha $campanha;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('ap_tabela_preco_id');
        parent::addAttribute('campanha_id');
        parent::addAttribute('fazparte');
    
    }

    /**
     * Method set_ap_tabela_preco
     * Sample of usage: $var->ap_tabela_preco = $object;
     * @param $object Instance of ApTabelaPreco
     */
    public function set_ap_tabela_preco(ApTabelaPreco $object)
    {
        $this->ap_tabela_preco = $object;
        $this->ap_tabela_preco_id = $object->id;
    }

    /**
     * Method get_ap_tabela_preco
     * Sample of usage: $var->ap_tabela_preco->attribute;
     * @returns ApTabelaPreco instance
     */
    public function get_ap_tabela_preco()
    {
    
        // loads the associated object
        if (empty($this->ap_tabela_preco))
            $this->ap_tabela_preco = new ApTabelaPreco($this->ap_tabela_preco_id);
    
        // returns the associated object
        return $this->ap_tabela_preco;
    }
    /**
     * Method set_campanha
     * Sample of usage: $var->campanha = $object;
     * @param $object Instance of Campanha
     */
    public function set_campanha(Campanha $object)
    {
        $this->campanha = $object;
        $this->campanha_id = $object->id;
    }

    /**
     * Method get_campanha
     * Sample of usage: $var->campanha->attribute;
     * @returns Campanha instance
     */
    public function get_campanha()
    {
    
        // loads the associated object
        if (empty($this->campanha))
            $this->campanha = new Campanha($this->campanha_id);
    
        // returns the associated object
        return $this->campanha;
    }

  public static function onToggleTabelaPreco($param)
{
    try {
        TTransaction::open('integrador');

        $campanha_id = TSession::getValue('campanha_id');
        $tabela_preco_id = $param['tabela_preco_id'];
        $value = $param['value'];

        if (!$campanha_id || !$tabela_preco_id) {
            throw new Exception("Parâmetros inválidos");
        }

        $registro = CampanhaTabelaPreco::where('campanha_id', '=', $campanha_id)
                    ->where('ap_tabela_preco_id', '=', $tabela_preco_id)
                    ->first();

        if ($value == '1') {
            if (!$registro) {
                $registro = new CampanhaTabelaPreco;
                $registro->campanha_id = $campanha_id;
                $registro->ap_tabela_preco_id = $tabela_preco_id;
            }
            $registro->fazparte = 'S';
            $registro->store();
        } else {
            if ($registro) {
                $registro->fazparte = 'N';
                $registro->store();
            }
        }

        TTransaction::close();
    } catch (Exception $e) {
        TTransaction::rollback();
        new TMessage('error', 'Erro ao salvar: ' . $e->getMessage());
    }
}

}

