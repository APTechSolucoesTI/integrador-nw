<?php

class HistoricoVendaFormView extends TWindow
{
    protected $form; // form
    private static $database = 'integrador';
    private static $activeRecord = 'HistoricoVenda';
    private static $primaryKey = 'id';
    private static $formName = 'formView_HistoricoVenda';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        parent::setSize(0.8, null);
        parent::setTitle("Consulta de Venda");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        TTransaction::open(self::$database);
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setTagName('div');

        $historico_venda = new HistoricoVenda($param['key']);
        // define the form title
        $this->form->setFormTitle("Consulta de Venda");

        $label1 = new TLabel("Meta:", '', '12px', '', '100%');
        $text1 = new TTextDisplay($historico_venda->meta->mes_ano, '', '12px', '');
        $label2 = new TLabel("Unidade:", '', '12px', '', '100%');
        $text2 = new TTextDisplay($historico_venda->system_unit->name, '', '12px', '');
        $label3 = new TLabel("Id:", '', '12px', '', '100%');
        $text3 = new TTextDisplay($historico_venda->id, '', '12px', '');
        $label4 = new TLabel("Data de Emissão:", '', '12px', '', '100%');
        $datetext4 = new TTextDisplay(TDate::convertToMask($historico_venda->data_emissao, 'yyyy-mm-dd', 'dd/mm/yyyy'), '', '12px', '');
        $label5 = new TLabel("Data de Cadastro:", '', '12px', '', '100%');
        $datetext2 = new TTextDisplay(TDate::convertToMask($historico_venda->data_cadastro, 'yyyy-mm-dd', 'dd/mm/yyyy'), '', '12px', '');
        $label6 = new TLabel("Número", '', '12px', '', '100%');
        $text6 = new TTextDisplay($historico_venda->nro, '', '12px', '');
        $label7 = new TLabel("Código do Cliente:", '', '12px', '', '100%');
        $text7 = new TTextDisplay($historico_venda->cod_clifor, '', '12px', '');
        $label8 = new TLabel("Razão Social:", '', '12px', '', '100%');
        $text8 = new TTextDisplay($historico_venda->razao, '', '12px', '');
        $label9 = new TLabel("Reativado:", '', '12px', '', '100%');
        $text9 = new TTextDisplay($historico_venda->agente, '', '12px', '');
        $label10 = new TLabel("Cidade:", '', '12px', '', '100%');
        $text10 = new TTextDisplay($historico_venda->cidade->nome, '', '12px', '');
        $label11 = new TLabel("Grupo do Cliente:", '', '12px', '', '100%');
        $text11 = new TTextDisplay($historico_venda->grupo_cliente->descricao, '', '12px', '');
        $label12 = new TLabel("Consultor(a):", '', '12px', '', '100%');
        $text12 = new TTextDisplay($historico_venda->repres->fantasia, '', '12px', '');



        $this->form->appendPage("Dados");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([$label1,$text1],[$label2,$text2]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([$label3,$text3],[$label4,$datetext4]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([$label5,$datetext2],[$label6,$text6]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([$label7,$text7],[$label8,$text8]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([$label9,$text9],[$label10,$text10]);
        $row5->layout = ['col-sm-6','col-sm-6'];

        $row6 = $this->form->addFields([$label11,$text11],[$label12,$text12]);
        $row6->layout = ['col-sm-6','col-sm-6'];

        $this->form->appendPage("Itens");

        $this->historico_venda_item_venda_id_list = new TQuickGrid;
        $this->historico_venda_item_venda_id_list->style = 'width:100%';
        $this->historico_venda_item_venda_id_list->disableDefaultClick();

        $column_cod_item = $this->historico_venda_item_venda_id_list->addQuickColumn("Código", 'cod_item', 'left');
        $column_descricao = $this->historico_venda_item_venda_id_list->addQuickColumn("Descrição", 'descricao', 'left');
        $column_grupo_estoque_descricao = $this->historico_venda_item_venda_id_list->addQuickColumn("Grupo de Estoque", 'grupo_estoque->descricao', 'left');
        $column_familia_industrial_descricao = $this->historico_venda_item_venda_id_list->addQuickColumn("Família Industrial", 'familia_industrial->descricao', 'left');
        $column_quantidade_transformed = $this->historico_venda_item_venda_id_list->addQuickColumn("Quantidade", 'quantidade', 'left');
        $column_valor_unitario_transformed = $this->historico_venda_item_venda_id_list->addQuickColumn("Valor Unitário", 'valor_unitario', 'left');
        $column_perc_desconto_transformed = $this->historico_venda_item_venda_id_list->addQuickColumn("Desconto (%)", 'perc_desconto', 'left');
        $column_valor_desconto_transformed = $this->historico_venda_item_venda_id_list->addQuickColumn("Desconto (R$)", 'valor_desconto', 'left');
        $column_valor_total_transformed = $this->historico_venda_item_venda_id_list->addQuickColumn("Valor Total", 'valor_total', 'left');

        $column_quantidade_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }

        });

        $column_valor_unitario_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_perc_desconto_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(empty($value)) $value = 0;
            return number_format($value, 2, ',', '')."%";

        });

        $column_valor_desconto_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_valor_total_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $this->historico_venda_item_venda_id_list->createModel();

        $criteria_historico_venda_item_venda_id = new TCriteria();
        $criteria_historico_venda_item_venda_id->add(new TFilter('venda_id', '=', $historico_venda->id));

        $criteria_historico_venda_item_venda_id->setProperty('order', 'id desc');

        $historico_venda_item_venda_id_items = HistoricoVendaItem::getObjects($criteria_historico_venda_item_venda_id);

        $this->historico_venda_item_venda_id_list->addItems($historico_venda_item_venda_id_items);

        $panel = new TElement('div');
        $panel->class = 'formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->historico_venda_item_venda_id_list));

        $this->form->addContent([$panel]);

        if(!empty($param['current_tab']))
        {
            $this->form->setCurrentPage($param['current_tab']);
        }


        TTransaction::close();
        parent::add($this->form);

    }

    public function onShow($param = null)
    {     

    }

}

