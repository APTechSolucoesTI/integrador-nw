<?php

class Cidade extends TRecord
{
    const TABLENAME  = 'cidade';
    const PRIMARYKEY = 'cod_cidade';
    const IDPOLICY   =  'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('cep');
        parent::addAttribute('dipam_municipio');
        parent::addAttribute('zfm_municipio');
        parent::addAttribute('dfc_municipio');
        parent::addAttribute('cod_ibge');
        parent::addAttribute('dt_change');
        parent::addAttribute('auxiliar_float1');
        parent::addAttribute('auxiliar_float2');
        parent::addAttribute('descricao_softwareemissor');
        parent::addAttribute('cod_siafi');
        parent::addAttribute('auxiliar_string1');
        parent::addAttribute('auxiliar_string2');
        parent::addAttribute('cod_anp');
    
    }

    public static function onSincronizarCidades(){
        TTransaction::open('nw');
        $conn = TTransaction::get();
        $result = $conn->query('SELECT cod_cidade, descricao FROM cidade ORDER BY cod_cidade');
        $objects = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
        TTransaction::close();
        if($objects)
        {
            foreach($objects as $object) 
            { 
                TTransaction::open('integrador');
        	    $cidade = ApCidade::where('cod_cidade','=', $object->cod_cidade)->where('system_unit_id','=',TSession::getValue('userunitid'))->first();
        	    if(!$cidade){
        	        $newCidade = new ApCidade();
                    $newCidade->cod_cidade = $object->cod_cidade;
                    $newCidade->nome       = $object->descricao;
                    $newCidade->system_unit_id = TSession::getValue('userunitid');
                    $newCidade->store();
        	    }
            	TTransaction::close();
            }
        }
    }

    public static function nomeCidade($cod_cidade){
        TTransaction::open('nw');
        $conn = TTransaction::get();
        $result = $conn->query("SELECT cod_cidade, descricao FROM cidade WHERE cod_cidade like '%$cod_cidade%' ORDER BY cod_cidade LIMIT 1");
        $objects = $result->fetchAll(PDO::FETCH_CLASS, "stdClass");
        TTransaction::close();
        if($objects)
        {
            foreach($objects as $object) 
            { 
                return $object->descricao;
                break;
        	}
        }
    }

}

