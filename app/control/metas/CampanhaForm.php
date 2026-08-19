<?php

class CampanhaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'Campanha';
    private static $primaryKey = 'id';
    private static $formName = 'form_CampanhaForm';

    use BuilderMasterDetailTrait;
    use BuilderMasterDetailFieldListTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de campanha");

        $criteria_campanha_tipo_id = new TCriteria();
        $criteria_campanha_item_campanha_cod_item = new TCriteria();
        $criteria_campanha_premio_campanha_comparador_id = new TCriteria();

        $id = new TEntry('id');
        $system_unit_id = new THidden('system_unit_id');
        $campanha_tipo_id = new TDBCombo('campanha_tipo_id', 'integrador', 'CampanhaTipo', 'id', '{nome}','nome asc' , $criteria_campanha_tipo_id );
        $status = new TCombo('status');
        $painel = new TSpinner('painel');
        $descricao = new TEntry('descricao');
        $data_inicial = new TDateTime('data_inicial');
        $data_final = new TDateTime('data_final');
        $min = new TNumeric('min', '2', ',', '.' );
        $qtde = new TNumeric('qtde', '2', ',', '.' );
        $valor_unitario_min = new TNumeric('valor_unitario_min', '2', ',', '.' );
        $campanha_item_campanha_id = new THidden('campanha_item_campanha_id[]');
        $campanha_item_campanha___row__id = new THidden('campanha_item_campanha___row__id[]');
        $campanha_item_campanha___row__data = new THidden('campanha_item_campanha___row__data[]');
        $campanha_item_campanha_cod_item = new TDBUniqueSearch('campanha_item_campanha_cod_item[]', 'integrador', 'ApItem', 'codigo', 'descricao','id asc' , $criteria_campanha_item_campanha_cod_item );
        $this->fieldList_688902fd49d48 = new TFieldList();
        $campanha_premio_campanha_comparador_id = new TDBCombo('campanha_premio_campanha_comparador_id', 'integrador', 'Comparador', 'id', '{descricao}','id asc' , $criteria_campanha_premio_campanha_comparador_id );
        $campanha_premio_campanha_id = new THidden('campanha_premio_campanha_id');
        $campanha_premio_campanha_dado_0 = new TNumeric('campanha_premio_campanha_dado_0', '2', ',', '.' );
        $campanha_premio_campanha_dado_1 = new TNumeric('campanha_premio_campanha_dado_1', '2', ',', '.' );
        $campanha_premio_campanha_tipo_valor = new TCombo('campanha_premio_campanha_tipo_valor');
        $campanha_premio_campanha_premio = new TNumeric('campanha_premio_campanha_premio', '2', ',', '.' );
        $button_adicionar_campanha_premio_campanha = new TButton('button_adicionar_campanha_premio_campanha');
        $ap_tabela_preco_id = new BPageContainer();
        $campanhaFechamento = new BPageContainer();

        $this->fieldList_688902fd49d48->addField(null, $campanha_item_campanha_id, []);
        $this->fieldList_688902fd49d48->addField(null, $campanha_item_campanha___row__id, ['uniqid' => true]);
        $this->fieldList_688902fd49d48->addField(null, $campanha_item_campanha___row__data, []);
        $this->fieldList_688902fd49d48->addField(new TLabel("Item", null, '14px', null), $campanha_item_campanha_cod_item, ['width' => '100%']);

        $this->fieldList_688902fd49d48->width = '100%';
        $this->fieldList_688902fd49d48->setFieldPrefix('campanha_item_campanha');
        $this->fieldList_688902fd49d48->name = 'fieldList_688902fd49d48';

        $this->criteria_fieldList_688902fd49d48 = new TCriteria();
        $this->default_item_fieldList_688902fd49d48 = new stdClass();

        $this->form->addField($campanha_item_campanha_id);
        $this->form->addField($campanha_item_campanha___row__id);
        $this->form->addField($campanha_item_campanha___row__data);
        $this->form->addField($campanha_item_campanha_cod_item);

        $this->fieldList_688902fd49d48->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $campanha_tipo_id->setChangeAction(new TAction([$this,'onChange']));
        $campanha_premio_campanha_comparador_id->setChangeAction(new TAction([$this,'onSelectOperador']));

        $campanha_tipo_id->addValidation("Tipo", new TRequiredValidator()); 
        $descricao->addValidation("Descrição", new TRequiredValidator()); 
        $valor_unitario_min->addValidation("Valor Unitário Mínimo", new TRequiredValidator()); 

        $id->setEditable(false);
        $painel->setRange(1, 2000, 1);
        $campanha_item_campanha_cod_item->setMinLength(2);
        $campanha_item_campanha_cod_item->setFilterColumns(["codigo","descricao"]);
        $button_adicionar_campanha_premio_campanha->addStyleClass('btn-default');
        $button_adicionar_campanha_premio_campanha->setImage('fas:plus #2ecc71');
        $status->addItems(["0"=>"Aguardando","1"=>"Aberto","2"=>"Fechado"]);
        $campanha_premio_campanha_tipo_valor->addItems(["1"=>" Percentual","2"=>" Valor Monetário"]);

        $descricao->setMaxLength(255);
        $campanha_premio_campanha_premio->setMaxLength(17);

        $data_final->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_inicial->setDatabaseMask('yyyy-mm-dd hh:ii');

        $ap_tabela_preco_id->setId('b689390dc2da38');
        $campanhaFechamento->setId('b689c7b52513cd');

        $ap_tabela_preco_id->hide();
        $campanhaFechamento->hide();

        $status->setValue('1');
        $campanha_premio_campanha_tipo_valor->setValue('1');
        $system_unit_id->setValue(TSession::getValue("userunitid"));

        $data_final->setMask('dd/mm/yyyy hh:ii');
        $data_inicial->setMask('dd/mm/yyyy hh:ii');
        $campanha_item_campanha_cod_item->setMask('{codigo} - {descricao}');

        $campanhaFechamento->setAction(new TAction(['CampanhaFechamentoSimpleList', 'onShow']));
        $ap_tabela_preco_id->setAction(new TAction(['ApTabelaPrecoSimpleList', 'onShow'], $param));
        $button_adicionar_campanha_premio_campanha->setAction(new TAction([$this, 'onAddDetailCampanhaPremioCampanha'],['static' => 1]), "Adicionar");

        $status->enableSearch();
        $campanha_tipo_id->enableSearch();
        $campanha_premio_campanha_tipo_valor->enableSearch();
        $campanha_premio_campanha_comparador_id->enableSearch();

        $id->setSize(100);
        $painel->setSize(210);
        $min->setSize('100%');
        $qtde->setSize('100%');
        $status->setSize('100%');
        $descricao->setSize('100%');
        $data_final->setSize('100%');
        $system_unit_id->setSize(200);
        $data_inicial->setSize('100%');
        $campanha_tipo_id->setSize('100%');
        $valor_unitario_min->setSize('100%');
        $ap_tabela_preco_id->setSize('100%');
        $campanhaFechamento->setSize('100%');
        $campanha_premio_campanha_id->setSize(200);
        $campanha_item_campanha_cod_item->setSize('100%');
        $campanha_premio_campanha_dado_0->setSize('100%');
        $campanha_premio_campanha_dado_1->setSize('100%');
        $campanha_premio_campanha_premio->setSize('100%');
        $campanha_premio_campanha_tipo_valor->setSize('100%');
        $campanha_premio_campanha_comparador_id->setSize('100%');

        $button_adicionar_campanha_premio_campanha->id = '688903b1c61ae';

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $campanhaFechamento->add($loadingContainer);

        $this->ap_tabela_preco_id = $ap_tabela_preco_id;
        $this->campanhaFechamento = $campanhaFechamento;

        $this->form->appendPage("Dados");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id,$system_unit_id],[new TLabel("Tipo:", '#ff0000', '14px', null, '100%'),$campanha_tipo_id],[new TLabel("Status:", '#FF0000', '14px', null, '100%'),$status],[new TLabel("Painel:", '#FF0000', '14px', null),$painel]);
        $row1->layout = [' col-sm-2',' col-sm-4',' col-sm-3',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Descrição:", '#F44336', '14px', null, '100%'),$descricao]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Data inicial:", null, '14px', null, '100%'),$data_inicial],[new TLabel("Data final:", null, '14px', null, '100%'),$data_final]);
        $row3->layout = ['col-sm-4','col-sm-4'];

        $row4 = $this->form->addFields([new TLabel("Mínimo:", null, '14px', null),$min],[new TLabel("Valor Prêmio:", null, '14px', null),$qtde],[new TLabel("Valor Unitário  Mínimo:", '#FF0000', '14px', null, '100%'),$valor_unitario_min]);
        $row4->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $this->form->appendPage("Itens");
        $row5 = $this->form->addFields([$this->fieldList_688902fd49d48]);
        $row5->layout = [' col-sm-12'];

        $this->form->appendPage("Premio");

        $this->detailFormCampanhaPremioCampanha = new BootstrapFormBuilder('detailFormCampanhaPremioCampanha');
        $this->detailFormCampanhaPremioCampanha->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormCampanhaPremioCampanha->setProperty('class', 'form-horizontal builder-detail-form');

        $row6 = $this->detailFormCampanhaPremioCampanha->addFields([new TLabel("Operador:", '#ff0000', '14px', null, '100%'),$campanha_premio_campanha_comparador_id,$campanha_premio_campanha_id],[new TLabel("Inicio:", '#ff0000', '14px', null, '100%'),$campanha_premio_campanha_dado_0],[new TLabel("Fim:", null, '14px', null, '100%'),$campanha_premio_campanha_dado_1]);
        $row6->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row7 = $this->detailFormCampanhaPremioCampanha->addFields([new TLabel("Tipo de premiação:", '#ff0000', '14px', null, '100%'),$campanha_premio_campanha_tipo_valor],[new TLabel("Premiação:", '#ff0000', '14px', null, '100%'),$campanha_premio_campanha_premio]);
        $row7->layout = ['col-sm-4','col-sm-4'];

        $row8 = $this->detailFormCampanhaPremioCampanha->addFields([$button_adicionar_campanha_premio_campanha]);
        $row8->layout = [' col-sm-12'];

        $row9 = $this->detailFormCampanhaPremioCampanha->addFields([new THidden('campanha_premio_campanha__row__id')]);
        $this->campanha_premio_campanha_criteria = new TCriteria();

        $this->campanha_premio_campanha_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->campanha_premio_campanha_list->generateHiddenFields();
        $this->campanha_premio_campanha_list->setId('campanha_premio_campanha_list');

        $this->campanha_premio_campanha_list->style = 'width:100%';
        $this->campanha_premio_campanha_list->class .= ' table-bordered';

        $column_campanha_premio_campanha_comparador_descricao = new TDataGridColumn('comparador->descricao', "Operador", 'left');
        $column_campanha_premio_campanha_dado_0 = new TDataGridColumn('dado_0', "Percentual", 'left');
        $column_campanha_premio_campanha_dado_1 = new TDataGridColumn('dado_1', "Percentual Extra", 'left');
        $column_campanha_premio_campanha_premio_transformed = new TDataGridColumn('premio', "Premiação", 'left');

        $column_campanha_premio_campanha__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_campanha_premio_campanha__row__data->setVisibility(false);

        $action_onEditDetailCampanhaPremio = new TDataGridAction(array('CampanhaForm', 'onEditDetailCampanhaPremio'));
        $action_onEditDetailCampanhaPremio->setUseButton(false);
        $action_onEditDetailCampanhaPremio->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailCampanhaPremio->setLabel("Editar");
        $action_onEditDetailCampanhaPremio->setImage('far:edit #478fca');
        $action_onEditDetailCampanhaPremio->setFields(['__row__id', '__row__data']);

        $this->campanha_premio_campanha_list->addAction($action_onEditDetailCampanhaPremio);
        $action_onDeleteDetailCampanhaPremio = new TDataGridAction(array('CampanhaForm', 'onDeleteDetailCampanhaPremio'));
        $action_onDeleteDetailCampanhaPremio->setUseButton(false);
        $action_onDeleteDetailCampanhaPremio->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailCampanhaPremio->setLabel("Excluir");
        $action_onDeleteDetailCampanhaPremio->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailCampanhaPremio->setFields(['__row__id', '__row__data']);

        $this->campanha_premio_campanha_list->addAction($action_onDeleteDetailCampanhaPremio);

        $this->campanha_premio_campanha_list->addColumn($column_campanha_premio_campanha_comparador_descricao);
        $this->campanha_premio_campanha_list->addColumn($column_campanha_premio_campanha_dado_0);
        $this->campanha_premio_campanha_list->addColumn($column_campanha_premio_campanha_dado_1);
        $this->campanha_premio_campanha_list->addColumn($column_campanha_premio_campanha_premio_transformed);

        $this->campanha_premio_campanha_list->addColumn($column_campanha_premio_campanha__row__data);

        $this->campanha_premio_campanha_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->campanha_premio_campanha_list);
        $this->detailFormCampanhaPremioCampanha->addContent([$tableResponsiveDiv]);

        $column_campanha_premio_campanha_premio_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $valor = number_format($object->premio ?? 0, 2, ",", ".");
                if($object->tipo_valor == 1){
                    return $valor.'%';
                }
                return 'R$ '.$valor;

        });        $row10 = $this->form->addFields([$this->detailFormCampanhaPremioCampanha]);
        $row10->layout = [' col-sm-12'];

        $this->form->appendPage("Tabela de Preço");
        $row11 = $this->form->addFields([$ap_tabela_preco_id]);
        $row11->layout = [' col-sm-12'];

        $this->form->appendPage("Fechamento");
        $row12 = $this->form->addFields([$campanhaFechamento]);
        $row12->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['CampanhaList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=CampanhaForm]');
        $style->width = '60% !important';   
        $style->show(true);

    }

    public static function onChange($param = null) 
    {
        try {

            $formName = 'form_CampanhaForm';
            $tipo_id = $param['campanha_tipo_id'] ?? null;

            if ($tipo_id == 1) { //Se a opção for Ranking desativa Min e QTD

                // Desativa campos específicos
                TNumeric::disableField($formName, 'min');
                TNumeric::disableField($formName, 'qtde');

                // REATIVA todos os campos da aba prêmio
                TNumeric::enableField($formName, 'campanha_premio_campanha_dado_0');
                TNumeric::enableField($formName, 'campanha_premio_campanha_dado_1');
                TNumeric::enableField($formName, 'campanha_premio_campanha_premio');
                TCombo::enableField($formName, 'campanha_premio_campanha_tipo_valor');
                TDBCombo::enableField($formName, 'campanha_premio_campanha_comparador_id');
            }
            elseif ($tipo_id == 2) { //Se a opção for Quantidade desativa tudo do Prêmio
                // Desativa campos da aba prêmio
                TNumeric::disableField($formName, 'campanha_premio_campanha_dado_0');
                TNumeric::disableField($formName, 'campanha_premio_campanha_dado_1');
                TNumeric::disableField($formName, 'campanha_premio_campanha_premio');
                TCombo::disableField($formName, 'campanha_premio_campanha_tipo_valor');
                TDBCombo::disableField($formName, 'campanha_premio_campanha_comparador_id');

                // REATIVA campos FORA DO PRÊMIO
                TNumeric::enableField($formName, 'min');
                TNumeric::enableField($formName, 'qtde');
            }
            else {
                // Libera tudo por padrão
                TNumeric::enableField($formName, 'min');
                TNumeric::enableField($formName, 'qtde');
                TNumeric::enableField($formName, 'campanha_premio_campanha_dado_0');
                TNumeric::enableField($formName, 'campanha_premio_campanha_dado_1');
                TNumeric::enableField($formName, 'campanha_premio_campanha_premio');
                TCombo::enableField($formName, 'campanha_premio_campanha_tipo_valor');
                TDBCombo::enableField($formName, 'campanha_premio_campanha_comparador_id');
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onSelectOperador($param = null) 
    {

        try{

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailCampanhaPremioCampanha($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields[] = ['label'=>"Operador", 'name'=>"campanha_premio_campanha_comparador_id", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Inicio", 'name'=>"campanha_premio_campanha_dado_0", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Tipo de aplicação", 'name'=>"campanha_premio_campanha_tipo_valor", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Premiação", 'name'=>"campanha_premio_campanha_premio", 'class'=>'TRequiredValidator', 'value'=>[]];
            foreach($requiredFields as $requiredField)
            {
                try
                {
                    (new $requiredField['class'])->validate($requiredField['label'], $data->{$requiredField['name']}, $requiredField['value']);
                }
                catch(Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
             }
             if(count($errors) > 0)
             {
                 throw new Exception(implode('<br>', $errors));
             }

            $__row__id = !empty($data->campanha_premio_campanha__row__id) ? $data->campanha_premio_campanha__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new CampanhaPremio();
            $grid_data->__row__id = $__row__id;
            $grid_data->comparador_id = $data->campanha_premio_campanha_comparador_id;
            $grid_data->id = $data->campanha_premio_campanha_id;
            $grid_data->dado_0 = $data->campanha_premio_campanha_dado_0;
            $grid_data->dado_1 = $data->campanha_premio_campanha_dado_1;
            $grid_data->tipo_valor = $data->campanha_premio_campanha_tipo_valor;
            $grid_data->premio = $data->campanha_premio_campanha_premio;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['comparador_id'] =  $param['campanha_premio_campanha_comparador_id'] ?? null;
            $__row__data['__display__']['id'] =  $param['campanha_premio_campanha_id'] ?? null;
            $__row__data['__display__']['dado_0'] =  $param['campanha_premio_campanha_dado_0'] ?? null;
            $__row__data['__display__']['dado_1'] =  $param['campanha_premio_campanha_dado_1'] ?? null;
            $__row__data['__display__']['tipo_valor'] =  $param['campanha_premio_campanha_tipo_valor'] ?? null;
            $__row__data['__display__']['premio'] =  $param['campanha_premio_campanha_premio'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->campanha_premio_campanha_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('campanha_premio_campanha_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->campanha_premio_campanha_comparador_id = '';
            $data->campanha_premio_campanha_id = '';
            $data->campanha_premio_campanha_dado_0 = '';
            $data->campanha_premio_campanha_dado_1 = '';
            $data->campanha_premio_campanha_tipo_valor = '1';
            $data->campanha_premio_campanha_premio = '';
            $data->campanha_premio_campanha__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#688903b1c61ae');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public static function onEditDetailCampanhaPremio($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;
            $fireEvents = true;
            $aggregate = false;

            $data = new stdClass;
            $data->campanha_premio_campanha_comparador_id = $__row__data->__display__->comparador_id ?? null;
            $data->campanha_premio_campanha_id = $__row__data->__display__->id ?? null;
            $data->campanha_premio_campanha_dado_0 = $__row__data->__display__->dado_0 ?? null;
            $data->campanha_premio_campanha_dado_1 = $__row__data->__display__->dado_1 ?? null;
            $data->campanha_premio_campanha_tipo_valor = $__row__data->__display__->tipo_valor ?? null;
            $data->campanha_premio_campanha_premio = $__row__data->__display__->premio ?? null;
            $data->campanha_premio_campanha__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data, $aggregate, $fireEvents);
            TScript::create("
               var element = $('#688903b1c61ae');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='far fa-edit' style='color:#478fca;padding-right:4px;'></i>Editar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailCampanhaPremio($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->campanha_premio_campanha_comparador_id = '';
            $data->campanha_premio_campanha_id = '';
            $data->campanha_premio_campanha_dado_0 = '';
            $data->campanha_premio_campanha_dado_1 = '';
            $data->campanha_premio_campanha_tipo_valor = '';
            $data->campanha_premio_campanha_premio = '';
            $data->campanha_premio_campanha__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('campanha_premio_campanha_list', $__row__data->__row__id);
            TScript::create("
               var element = $('#688903b1c61ae');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Campanha(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            $campanha_premio_campanha_items = $this->storeMasterDetailItems('CampanhaPremio', 'campanha_id', 'campanha_premio_campanha', $object, $param['campanha_premio_campanha_list___row__data'] ?? [], $this->form, $this->campanha_premio_campanha_list, function($masterObject, $detailObject){ 

                //code here

            }, $this->campanha_premio_campanha_criteria); 

            $campanha_item_campanha_items = $this->storeItems('CampanhaItem', 'campanha_id', $object, $this->fieldList_688902fd49d48, function($masterObject, $detailObject){ 

                //code here

            }, $this->criteria_fieldList_688902fd49d48); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('CampanhaList', 'onShow', $loadPageParam); 

                        TScript::create("Template.closeRightPanel();");
            TForm::sendData(self::$formName, (object)['id' => $object->id]);

        }
        catch (Exception $e) // in case of exception
        {

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
                if (isset($param['key']))
                {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Campanha($key); // instantiates the Active Record 

                                $this->ap_tabela_preco_id->unhide();
                $this->ap_tabela_preco_id->setParameter('campanha_id', $object->id);
                $this->campanhaFechamento->unhide();
                $this->campanhaFechamento->setParameter('campanha_id', $object->id);

                $campanha_premio_campanha_items = $this->loadMasterDetailItems('CampanhaPremio', 'campanha_id', 'campanha_premio_campanha', $object, $this->form, $this->campanha_premio_campanha_list, $this->campanha_premio_campanha_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->fieldList_688902fd49d48_items = $this->loadItems('CampanhaItem', 'campanha_id', $object, $this->fieldList_688902fd49d48, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_fieldList_688902fd49d48); 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

        $this->fieldList_688902fd49d48->addHeader();
        $this->fieldList_688902fd49d48->addDetail($this->default_item_fieldList_688902fd49d48);

        $this->fieldList_688902fd49d48->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    }

    public function onShow($param = null)
    {
        $this->fieldList_688902fd49d48->addHeader();
        $this->fieldList_688902fd49d48->addDetail($this->default_item_fieldList_688902fd49d48);

        $this->fieldList_688902fd49d48->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

