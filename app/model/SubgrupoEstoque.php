<?php

class SubgrupoEstoque extends TRecord
{
    const TABLENAME  = 'subgrupo_estoque';
    const PRIMARYKEY = 'cod_subgrupoestoque';
    const IDPOLICY   =  'serial'; // {max, serial}

    private GrupoEstoque $fk_cod_grupoestoque;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_grupoestoque');
        parent::addAttribute('descricao');
        parent::addAttribute('ativo');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_atualizacao');
        parent::addAttribute('dt_bloqueio');
        parent::addAttribute('motivo_bloqueio');
        parent::addAttribute('seq_exibicao');
        parent::addAttribute('dt_change');
            
    }

    /**
     * Method set_grupo_estoque
     * Sample of usage: $var->grupo_estoque = $object;
     * @param $object Instance of GrupoEstoque
     */
    public function set_fk_cod_grupoestoque(GrupoEstoque $object)
    {
        $this->fk_cod_grupoestoque = $object;
        $this->cod_grupoestoque = $object->cod_grupoestoque;
    }

    /**
     * Method get_fk_cod_grupoestoque
     * Sample of usage: $var->fk_cod_grupoestoque->attribute;
     * @returns GrupoEstoque instance
     */
    public function get_fk_cod_grupoestoque()
    {
    
        // loads the associated object
        if (empty($this->fk_cod_grupoestoque))
            $this->fk_cod_grupoestoque = new GrupoEstoque($this->cod_grupoestoque);
    
        // returns the associated object
        return $this->fk_cod_grupoestoque;
    }

    
}

