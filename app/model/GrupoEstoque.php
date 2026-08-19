<?php

class GrupoEstoque extends TRecord
{
    const TABLENAME  = 'grupo_estoque';
    const PRIMARYKEY = 'cod_grupoestoque';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('qtd_niveil');
        parent::addAttribute('item_venda');
        parent::addAttribute('item_producao');
        parent::addAttribute('item_compraprodutivo');
        parent::addAttribute('item_compranaoprodutivo');
        parent::addAttribute('margem_lucro');
        parent::addAttribute('seq_exibicao');
        parent::addAttribute('dt_change');
        parent::addAttribute('codigo_sisterceiro');
        parent::addAttribute('exibir_itemfilhograde');
        parent::addAttribute('tipo_detalhenfe');
        parent::addAttribute('auxiliar_string1');
        parent::addAttribute('auxiliar_string2');
        parent::addAttribute('codtp_tipoproduto');
        parent::addAttribute('exibiritens_coltransfpro');
        parent::addAttribute('sigla_codautomatico');
        parent::addAttribute('cod_tipoos');
        parent::addAttribute('auxiliar_float1');
        parent::addAttribute('dispensado_coleta');
        parent::addAttribute('perc_descto');
            
    }

    /**
     * Method getSubgrupoEstoques
     */
    public function getSubgrupoEstoques()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cod_grupoestoque', '=', $this->cod_grupoestoque));
        return SubgrupoEstoque::getObjects( $criteria );
    }

    public function set_subgrupo_estoque_fk_cod_grupoestoque_to_string($subgrupo_estoque_fk_cod_grupoestoque_to_string)
    {
        if(is_array($subgrupo_estoque_fk_cod_grupoestoque_to_string))
        {
            $values = GrupoEstoque::where('cod_grupoestoque', 'in', $subgrupo_estoque_fk_cod_grupoestoque_to_string)->getIndexedArray('cod_grupoestoque', 'cod_grupoestoque');
            $this->subgrupo_estoque_fk_cod_grupoestoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->subgrupo_estoque_fk_cod_grupoestoque_to_string = $subgrupo_estoque_fk_cod_grupoestoque_to_string;
        }

        $this->vdata['subgrupo_estoque_fk_cod_grupoestoque_to_string'] = $this->subgrupo_estoque_fk_cod_grupoestoque_to_string;
    }

    public function get_subgrupo_estoque_fk_cod_grupoestoque_to_string()
    {
        if(!empty($this->subgrupo_estoque_fk_cod_grupoestoque_to_string))
        {
            return $this->subgrupo_estoque_fk_cod_grupoestoque_to_string;
        }
    
        $values = SubgrupoEstoque::where('cod_grupoestoque', '=', $this->cod_grupoestoque)->getIndexedArray('cod_grupoestoque','{fk_cod_grupoestoque->cod_grupoestoque}');
        return implode(', ', $values);
    }

    
}

