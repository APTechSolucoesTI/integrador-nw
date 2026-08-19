<?php

class Representante extends TRecord
{
    const TABLENAME  = 'representante';
    const PRIMARYKEY = 'cod_repres';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('razao');
        parent::addAttribute('cod_bairro');
        parent::addAttribute('cod_cidade');
        parent::addAttribute('cod_regiao');
        parent::addAttribute('cod_banco');
        parent::addAttribute('cod_pais');
        parent::addAttribute('fantasia');
        parent::addAttribute('cod_estado');
        parent::addAttribute('endereco');
        parent::addAttribute('cep');
        parent::addAttribute('pessoa');
        parent::addAttribute('cgc_cpf');
        parent::addAttribute('ie_rg');
        parent::addAttribute('telefone1');
        parent::addAttribute('insmunicipal');
        parent::addAttribute('telefone2');
        parent::addAttribute('fax');
        parent::addAttribute('celular');
        parent::addAttribute('cxpostal');
        parent::addAttribute('web');
        parent::addAttribute('email');
        parent::addAttribute('dt_cadastro');
        parent::addAttribute('dt_atualizacao');
        parent::addAttribute('contato');
        parent::addAttribute('obs');
        parent::addAttribute('nro_depend');
        parent::addAttribute('cod_contabilcr');
        parent::addAttribute('cod_contabildb');
        parent::addAttribute('agencia');
        parent::addAttribute('nro_conta');
        parent::addAttribute('comissao_padrao');
        parent::addAttribute('ativo');
        parent::addAttribute('imp_rendafonte');
        parent::addAttribute('motivo_bloqueio');
        parent::addAttribute('pode_desconto');
        parent::addAttribute('descto_maximo');
        parent::addAttribute('comissao_tipobase');
        parent::addAttribute('calcular_icms');
        parent::addAttribute('calcular_ipi');
        parent::addAttribute('calcular_pis');
        parent::addAttribute('calcular_cofins');
        parent::addAttribute('calcular_juros');
        parent::addAttribute('calcular_descto');
        parent::addAttribute('paga_comissao');
        parent::addAttribute('perc_comissaofatura');
        parent::addAttribute('perc_comissaorecebe');
        parent::addAttribute('email_pedven');
        parent::addAttribute('email_emisnf');
        parent::addAttribute('percentual_manga');
        parent::addAttribute('dt_change');
        parent::addAttribute('auxiliar_string1');
        parent::addAttribute('auxiliar_string2');
        parent::addAttribute('gerar_titulocomissaocp');
        parent::addAttribute('perc_comissaopedido');
        parent::addAttribute('auxiliar_float1');
        parent::addAttribute('auxiliar_float2');
        parent::addAttribute('calcular_icmssub');
        parent::addAttribute('calcular_icmsst');
            
    }

    
}

