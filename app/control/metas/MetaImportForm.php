<?php

class MetaImportForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'MetaImport';
    private static $primaryKey = 'id';
    private static $formName = 'form_MetaImportForm';

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
        $this->form->setFormTitle("Cadastro de Meta Import");

        $criteria_meta_import_item_meta_import_cod_item = new TCriteria();
        $criteria_meta_import_repres_meta_import_ap_representante_id = new TCriteria();

        $filterVar = "S";
        $criteria_meta_import_repres_meta_import_ap_representante_id->add(new TFilter('ativo', '=', $filterVar)); 

        $id = new TEntry('id');
        $system_unit_id = new THidden('system_unit_id');
        $descricao = new TEntry('descricao');
        $status = new TCombo('status');
        $painel = new TSpinner('painel');
        $ano = new TSpinner('ano');
        $mes = new TCombo('mes');
        $data_inicial = new TDateTime('data_inicial');
        $data_final = new TDateTime('data_final');
        $meta_import_item_meta_import_id = new THidden('meta_import_item_meta_import_id[]');
        $meta_import_item_meta_import___row__id = new THidden('meta_import_item_meta_import___row__id[]');
        $meta_import_item_meta_import___row__data = new THidden('meta_import_item_meta_import___row__data[]');
        $meta_import_item_meta_import_cod_item = new TDBUniqueSearch('meta_import_item_meta_import_cod_item[]', 'integrador', 'ApItem', 'codigo', 'descricao','id asc' , $criteria_meta_import_item_meta_import_cod_item );
        $this->fieldList_68b7287789739 = new TFieldList();
        $meta_import_repres_meta_import_ap_representante_id = new TDBUniqueSearch('meta_import_repres_meta_import_ap_representante_id', 'integrador', 'ApRepresentante', 'id', 'fantasia','fantasia asc' , $criteria_meta_import_repres_meta_import_ap_representante_id );
        $meta_import_repres_meta_import_id = new THidden('meta_import_repres_meta_import_id');
        $meta_import_repres_meta_import_qtd = new TNumeric('meta_import_repres_meta_import_qtd', '2', ',', '.' );
        $button_adicionar_meta_import_repres_meta_import = new TButton('button_adicionar_meta_import_repres_meta_import');
        $tbpreco = new BPageContainer();

        $this->fieldList_68b7287789739->addField(null, $meta_import_item_meta_import_id, []);
        $this->fieldList_68b7287789739->addField(null, $meta_import_item_meta_import___row__id, ['uniqid' => true]);
        $this->fieldList_68b7287789739->addField(null, $meta_import_item_meta_import___row__data, []);
        $this->fieldList_68b7287789739->addField(new TLabel("Item:", null, '14px', null), $meta_import_item_meta_import_cod_item, ['width' => '100%']);

        $this->fieldList_68b7287789739->width = '100%';
        $this->fieldList_68b7287789739->setFieldPrefix('meta_import_item_meta_import');
        $this->fieldList_68b7287789739->name = 'fieldList_68b7287789739';

        $this->criteria_fieldList_68b7287789739 = new TCriteria();
        $this->default_item_fieldList_68b7287789739 = new stdClass();

        $this->form->addField($meta_import_item_meta_import_id);
        $this->form->addField($meta_import_item_meta_import___row__id);
        $this->form->addField($meta_import_item_meta_import___row__data);
        $this->form->addField($meta_import_item_meta_import_cod_item);

        $this->fieldList_68b7287789739->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $descricao->addValidation("Descrição", new TRequiredValidator()); 
        $painel->addValidation("Painel", new TRequiredValidator()); 
        $ano->addValidation("Ano", new TRequiredValidator()); 
        $mes->addValidation("Mês", new TRequiredValidator()); 

        $id->setEditable(false);
        $button_adicionar_meta_import_repres_meta_import->addStyleClass('btn-default');
        $button_adicionar_meta_import_repres_meta_import->setImage('fas:plus #2ecc71');
        $tbpreco->setId('b68c41da589113');
        $tbpreco->hide();
        $descricao->setMaxLength(255);
        $meta_import_repres_meta_import_qtd->setMaxLength(17);

        $status->addItems(["0"=>"Aguardando","1"=>"Aberto","2"=>"Fechado","3"=>"Cancelado"]);
        $mes->addItems(["01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro"]);

        $mes->enableSearch();
        $status->enableSearch();

        $painel->setRange(1, 2000, 1);
        $ano->setRange(2024, 3024, 1);

        $data_final->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_inicial->setDatabaseMask('yyyy-mm-dd hh:ii');

        $meta_import_item_meta_import_cod_item->setMinLength(2);
        $meta_import_repres_meta_import_ap_representante_id->setMinLength(2);

        $meta_import_item_meta_import_cod_item->setFilterColumns(["codigo","descricao"]);
        $meta_import_repres_meta_import_ap_representante_id->setFilterColumns(["cod_repres","fantasia"]);

        $tbpreco->setAction(new TAction(['MetaImportVisualizarTabelaPreco', 'onShow']));
        $button_adicionar_meta_import_repres_meta_import->setAction(new TAction([$this, 'onAddDetailMetaImportRepresMetaImport'],['static' => 1]), "Adicionar");

        $status->setValue('1');
        $ano->setValue(date('Y'));
        $mes->setValue(date('m'));
        $system_unit_id->setValue(TSession::getValue("userunitid"));

        $data_final->setMask('dd/mm/yyyy hh:ii');
        $data_inicial->setMask('dd/mm/yyyy hh:ii');
        $meta_import_item_meta_import_cod_item->setMask('{codigo} - {descricao}');
        $meta_import_repres_meta_import_ap_representante_id->setMask('{fantasia}');

        $id->setSize(80);
        $ano->setSize(430);
        $mes->setSize('88%');
        $painel->setSize('46%');
        $status->setSize('100%');
        $data_final->setSize(200);
        $tbpreco->setSize('100%');
        $descricao->setSize('97%');
        $data_inicial->setSize(200);
        $system_unit_id->setSize(200);
        $meta_import_repres_meta_import_id->setSize(200);
        $meta_import_repres_meta_import_qtd->setSize('100%');
        $meta_import_item_meta_import_cod_item->setSize('100%');
        $meta_import_repres_meta_import_ap_representante_id->setSize('100%');

        $button_adicionar_meta_import_repres_meta_import->id = '68b729c889742';

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $tbpreco->add($loadingContainer);

        $this->tbpreco = $tbpreco;

        $this->form->appendPage("Meta Import");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id,$system_unit_id]);
        $row1->layout = ['col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Descrição:", '#FF0000', '14px', null, '100%'),$descricao],[new TLabel("Status:", '#FF0000', '14px', null),$status],[new TLabel("Painel:", '#FF0000', '14px', null, '100%'),$painel]);
        $row2->layout = [' col-sm-4','col-sm-3',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Ano:", '#FF0000', '14px', null, '100%'),$ano],[new TLabel("Mês:", '#FF0000', '14px', null, '100%'),$mes]);
        $row3->layout = [' col-sm-4',' col-sm-5'];

        $row4 = $this->form->addFields([new TLabel("Data inicial:", null, '14px', null, '100%'),$data_inicial],[new TLabel("Data final:", null, '14px', null, '100%'),$data_final],[]);
        $row4->layout = [' col-sm-2','col-sm-3','col-sm-6'];

        $this->form->appendPage("Item");
        $row5 = $this->form->addFields([$this->fieldList_68b7287789739]);
        $row5->layout = [' col-sm-12'];

        $this->form->appendPage("Consultor(a)");

        $this->detailFormMetaImportRepresMetaImport = new BootstrapFormBuilder('detailFormMetaImportRepresMetaImport');
        $this->detailFormMetaImportRepresMetaImport->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormMetaImportRepresMetaImport->setProperty('class', 'form-horizontal builder-detail-form');

        $row6 = $this->detailFormMetaImportRepresMetaImport->addFields([new TLabel("Consultor(a):", null, '14px', null, '100%'),$meta_import_repres_meta_import_ap_representante_id,$meta_import_repres_meta_import_id]);
        $row6->layout = [' col-sm-12'];

        $row7 = $this->detailFormMetaImportRepresMetaImport->addFields([new TLabel("Quantidade:", null, '14px', null, '100%'),$meta_import_repres_meta_import_qtd]);
        $row7->layout = ['col-sm-12'];

        $row8 = $this->detailFormMetaImportRepresMetaImport->addFields([$button_adicionar_meta_import_repres_meta_import]);
        $row8->layout = [' col-sm-12'];

        $row9 = $this->detailFormMetaImportRepresMetaImport->addFields([new THidden('meta_import_repres_meta_import__row__id')]);
        $this->meta_import_repres_meta_import_criteria = new TCriteria();

        $this->meta_import_repres_meta_import_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->meta_import_repres_meta_import_list->generateHiddenFields();
        $this->meta_import_repres_meta_import_list->setId('meta_import_repres_meta_import_list');

        $this->meta_import_repres_meta_import_list->style = 'width:100%';
        $this->meta_import_repres_meta_import_list->class .= ' table-bordered';

        $column_meta_import_repres_meta_import_ap_representante_fantasia = new TDataGridColumn('ap_representante->fantasia', "Consultor(a)", 'center');
        $column_meta_import_repres_meta_import_qtd_transformed = new TDataGridColumn('qtd', "Quantidade", 'left');

        $column_meta_import_repres_meta_import__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_meta_import_repres_meta_import__row__data->setVisibility(false);

        $action_onEditDetailMetaImportRepres = new TDataGridAction(array('MetaImportForm', 'onEditDetailMetaImportRepres'));
        $action_onEditDetailMetaImportRepres->setUseButton(false);
        $action_onEditDetailMetaImportRepres->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailMetaImportRepres->setLabel("Editar");
        $action_onEditDetailMetaImportRepres->setImage('far:edit #478fca');
        $action_onEditDetailMetaImportRepres->setFields(['__row__id', '__row__data']);

        $this->meta_import_repres_meta_import_list->addAction($action_onEditDetailMetaImportRepres);
        $action_onDeleteDetailMetaImportRepres = new TDataGridAction(array('MetaImportForm', 'onDeleteDetailMetaImportRepres'));
        $action_onDeleteDetailMetaImportRepres->setUseButton(false);
        $action_onDeleteDetailMetaImportRepres->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailMetaImportRepres->setLabel("Excluir");
        $action_onDeleteDetailMetaImportRepres->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailMetaImportRepres->setFields(['__row__id', '__row__data']);

        $this->meta_import_repres_meta_import_list->addAction($action_onDeleteDetailMetaImportRepres);

        $this->meta_import_repres_meta_import_list->addColumn($column_meta_import_repres_meta_import_ap_representante_fantasia);
        $this->meta_import_repres_meta_import_list->addColumn($column_meta_import_repres_meta_import_qtd_transformed);

        $this->meta_import_repres_meta_import_list->addColumn($column_meta_import_repres_meta_import__row__data);

        $this->meta_import_repres_meta_import_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->meta_import_repres_meta_import_list);
        $this->detailFormMetaImportRepresMetaImport->addContent([$tableResponsiveDiv]);

        $column_meta_import_repres_meta_import_qtd_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        });        $row10 = $this->form->addFields([$this->detailFormMetaImportRepresMetaImport]);
        $row10->layout = [' col-sm-12'];

        $this->form->appendPage("Tabela de Preço");
        $row11 = $this->form->addFields([$tbpreco]);
        $row11->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['MetaImportHeaderList', 'onShow']), 'fas:arrow-left #000000');
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

        $style = new TStyle('right-panel > .container-part[page-name=MetaImportForm]');
        $style->width = '70% !important';   
        $style->show(true);

    }

    public  function onAddDetailMetaImportRepresMetaImport($param = null) 
    {
        try
        {
            $data = $this->form->getData();

                $__row__id = !empty($data->meta_import_repres_meta_import__row__id) ? $data->meta_import_repres_meta_import__row__id : 'b'.uniqid();

                TTransaction::open(self::$database);

                $grid_data = new MetaImportRepres();
                $grid_data->__row__id = $__row__id;
                $grid_data->ap_representante_id = $data->meta_import_repres_meta_import_ap_representante_id;
                $grid_data->id = $data->meta_import_repres_meta_import_id;
                $grid_data->qtd = $data->meta_import_repres_meta_import_qtd;

                $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
                $__row__data['__row__id'] = $__row__id;
                $__row__data['__display__']['ap_representante_id'] =  $param['meta_import_repres_meta_import_ap_representante_id'] ?? null;
                $__row__data['__display__']['id'] =  $param['meta_import_repres_meta_import_id'] ?? null;
                $__row__data['__display__']['qtd'] =  $param['meta_import_repres_meta_import_qtd'] ?? null;

                $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
                $row = $this->meta_import_repres_meta_import_list->addItem($grid_data);
                $row->id = $grid_data->__row__id;

                TDataGrid::replaceRowById('meta_import_repres_meta_import_list', $grid_data->__row__id, $row);

                TTransaction::close();

                $data = new stdClass;
                $data->meta_import_repres_meta_import_ap_representante_id = '';
                $data->meta_import_repres_meta_import_id = '';
                $data->meta_import_repres_meta_import_qtd = '';
                $data->meta_import_repres_meta_import__row__id = '';

                TForm::sendData(self::$formName, $data);
                TScript::create("
                   var element = $('#68b729c889742');
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

    public static function onEditDetailMetaImportRepres($param = null) 
    {
        try
        {

                $__row__data = unserialize(base64_decode($param['__row__data']));
                $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;
                $fireEvents = true;
                $aggregate = false;

                $data = new stdClass;
                $data->meta_import_repres_meta_import_ap_representante_id = $__row__data->__display__->ap_representante_id ?? null;
                $data->meta_import_repres_meta_import_id = $__row__data->__display__->id ?? null;
                $data->meta_import_repres_meta_import_qtd = $__row__data->__display__->qtd ?? null;
                $data->meta_import_repres_meta_import__row__id = $__row__data->__row__id;

                TForm::sendData(self::$formName, $data, $aggregate, $fireEvents);
                TScript::create("
                   var element = $('#68b729c889742');
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

    public static function onDeleteDetailMetaImportRepres($param = null) 
    {
        try
        {

                $__row__data = unserialize(base64_decode($param['__row__data']));

                $data = new stdClass;
                $data->meta_import_repres_meta_import_ap_representante_id = '';
                $data->meta_import_repres_meta_import_id = '';
                $data->meta_import_repres_meta_import_qtd = '';
                $data->meta_import_repres_meta_import__row__id = '';

                TForm::sendData(self::$formName, $data);

                TDataGrid::removeRowById('meta_import_repres_meta_import_list', $__row__data->__row__id);
                TScript::create("
                   var element = $('#68b729c889742');
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

            $object = new MetaImport(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

//<generatedAutoCode>
            $this->meta_import_repres_meta_import_criteria->setProperty('order', 'fantasia asc');
//</generatedAutoCode>
            $meta_import_repres_meta_import_items = $this->storeMasterDetailItems('MetaImportRepres', 'meta_import_id', 'meta_import_repres_meta_import', $object, $param['meta_import_repres_meta_import_list___row__data'] ?? [], $this->form, $this->meta_import_repres_meta_import_list, function($masterObject, $detailObject){ 

                //code here

            }, $this->meta_import_repres_meta_import_criteria); 

            $meta_import_item_meta_import_items = $this->storeItems('MetaImportItem', 'meta_import_id', $object, $this->fieldList_68b7287789739, function($masterObject, $detailObject){ 

                //code here

            }, $this->criteria_fieldList_68b7287789739); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('MetaImportHeaderList', 'onShow', $loadPageParam); 

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

                $object = new MetaImport($key); // instantiates the Active Record 

                                $this->tbpreco->unhide();
                $this->tbpreco->setParameter('meta_import_id', $object->id);

//<generatedAutoCode>
                $this->meta_import_repres_meta_import_criteria->setProperty('order', 'fantasia asc');
//</generatedAutoCode>
                $meta_import_repres_meta_import_items = $this->loadMasterDetailItems('MetaImportRepres', 'meta_import_id', 'meta_import_repres_meta_import', $object, $this->form, $this->meta_import_repres_meta_import_list, $this->meta_import_repres_meta_import_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->fieldList_68b7287789739_items = $this->loadItems('MetaImportItem', 'meta_import_id', $object, $this->fieldList_68b7287789739, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_fieldList_68b7287789739); 

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

        $this->fieldList_68b7287789739->addHeader();
        $this->fieldList_68b7287789739->addDetail($this->default_item_fieldList_68b7287789739);

        $this->fieldList_68b7287789739->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    }

    public function onShow($param = null)
    {
        $this->fieldList_68b7287789739->addHeader();
        $this->fieldList_68b7287789739->addDetail($this->default_item_fieldList_68b7287789739);

        $this->fieldList_68b7287789739->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

