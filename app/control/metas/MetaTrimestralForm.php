<?php

class MetaTrimestralForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'MetaTrimestral';
    private static $primaryKey = 'id';
    private static $formName = 'form_MetaTrimestralForm';

    use BuilderMasterDetailTrait;

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
        $this->form->setFormTitle("Cadastro de Meta Trimestral");

        $criteria_meta_trimestral_repres_meta_trimestral_ap_representante_id = new TCriteria();

        $filterVar = "S";
        $criteria_meta_trimestral_repres_meta_trimestral_ap_representante_id->add(new TFilter('ativo', '=', $filterVar)); 

        $id = new TEntry('id');
        $status = new TCombo('status');
        $descricao = new TEntry('descricao');
        $data_inicial = new TDate('data_inicial');
        $data_final = new TDate('data_final');
        $meta_trimestral_repres_meta_trimestral_ap_representante_id = new TDBCombo('meta_trimestral_repres_meta_trimestral_ap_representante_id', 'integrador', 'ApRepresentante', 'id', '{fantasia}','fantasia asc' , $criteria_meta_trimestral_repres_meta_trimestral_ap_representante_id );
        $meta_trimestral_repres_meta_trimestral_id = new THidden('meta_trimestral_repres_meta_trimestral_id');
        $meta_trimestral_repres_meta_trimestral_valor = new TNumeric('meta_trimestral_repres_meta_trimestral_valor', '2', ',', '.' );
        $meta_trimestral_repres_meta_trimestral_pontuacao_inicial = new THidden('meta_trimestral_repres_meta_trimestral_pontuacao_inicial');
        $meta_trimestral_repres_meta_trimestral_pontuacao_alvo = new THidden('meta_trimestral_repres_meta_trimestral_pontuacao_alvo');
        $meta_trimestral_repres_meta_trimestral_pontuacao_atual = new THidden('meta_trimestral_repres_meta_trimestral_pontuacao_atual');
        $button_adicionar_meta_trimestral_repres_meta_trimestral = new TButton('button_adicionar_meta_trimestral_repres_meta_trimestral');

        $status->addValidation("Status", new TRequiredValidator()); 
        $descricao->addValidation("Descrição", new TRequiredValidator()); 
        $data_inicial->addValidation("Data inicial", new TRequiredValidator()); 

        $status->addItems(["0"=>"Aguardando","1"=>"Aberto","2"=>"Fechado","3"=>"Cancelado"]);
        $button_adicionar_meta_trimestral_repres_meta_trimestral->setAction(new TAction([$this, 'onAddDetailMetaTrimestralRepresMetaTrimestral'],['static' => 1]), "Adicionar");
        $button_adicionar_meta_trimestral_repres_meta_trimestral->addStyleClass('btn-default');
        $button_adicionar_meta_trimestral_repres_meta_trimestral->setImage('fas:plus #2ecc71');
        $id->setEditable(false);
        $status->setEditable(false);

        $status->enableSearch();
        $meta_trimestral_repres_meta_trimestral_ap_representante_id->enableSearch();

        $data_final->setMask('dd/mm/yyyy');
        $data_inicial->setMask('dd/mm/yyyy');

        $data_final->setDatabaseMask('yyyy-mm-dd');
        $data_inicial->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(100);
        $status->setSize('100%');
        $descricao->setSize('100%');
        $data_final->setSize('100%');
        $data_inicial->setSize('100%');
        $meta_trimestral_repres_meta_trimestral_id->setSize(200);
        $meta_trimestral_repres_meta_trimestral_valor->setSize('100%');
        $meta_trimestral_repres_meta_trimestral_pontuacao_alvo->setSize(200);
        $meta_trimestral_repres_meta_trimestral_pontuacao_atual->setSize(200);
        $meta_trimestral_repres_meta_trimestral_pontuacao_inicial->setSize(200);
        $meta_trimestral_repres_meta_trimestral_ap_representante_id->setSize('100%');

        $button_adicionar_meta_trimestral_repres_meta_trimestral->id = '66c5f001826ec';

        if(TSession::getValue("userid") == 1){
            $status->setEditable(true);
        }

        $tab_66c5efae826e7 = new BootstrapFormBuilder('tab_66c5efae826e7');
        $this->tab_66c5efae826e7 = $tab_66c5efae826e7;
        $tab_66c5efae826e7->setProperty('style', 'border:none; box-shadow:none;');

        $tab_66c5efae826e7->appendPage("Meta");

        $tab_66c5efae826e7->addFields([new THidden('current_tab_tab_66c5efae826e7')]);
        $tab_66c5efae826e7->setTabFunction("$('[name=current_tab_tab_66c5efae826e7]').val($(this).attr('data-current_page'));");

        $row1 = $tab_66c5efae826e7->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Status:", '#ff0000', '14px', null, '100%'),$status]);
        $row1->layout = [' col-sm-6',' col-sm-6'];

        $row2 = $tab_66c5efae826e7->addFields([new TLabel("Descrição:", '#FF0000', '14px', null, '100%'),$descricao]);
        $row2->layout = [' col-sm-12'];

        $row3 = $tab_66c5efae826e7->addFields([new TLabel("Data Inicial:", '#ff0000', '14px', null, '100%'),$data_inicial],[new TLabel("Data Final:", '#FF0000', '14px', null, '100%'),$data_final]);
        $row3->layout = [' col-sm-6',' col-sm-6'];

        $tab_66c5efae826e7->appendPage("Consultores(as)");

        $this->detailFormMetaTrimestralRepresMetaTrimestral = new BootstrapFormBuilder('detailFormMetaTrimestralRepresMetaTrimestral');
        $this->detailFormMetaTrimestralRepresMetaTrimestral->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormMetaTrimestralRepresMetaTrimestral->setProperty('class', 'form-horizontal builder-detail-form');

        $row4 = $this->detailFormMetaTrimestralRepresMetaTrimestral->addFields([new TLabel("Consultor(a):", '#ff0000', '14px', null, '100%'),$meta_trimestral_repres_meta_trimestral_ap_representante_id,$meta_trimestral_repres_meta_trimestral_id],[new TLabel("Valor:", '#ff0000', '14px', null, '100%'),$meta_trimestral_repres_meta_trimestral_valor,$meta_trimestral_repres_meta_trimestral_pontuacao_inicial,$meta_trimestral_repres_meta_trimestral_pontuacao_alvo,$meta_trimestral_repres_meta_trimestral_pontuacao_atual]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->detailFormMetaTrimestralRepresMetaTrimestral->addFields([$button_adicionar_meta_trimestral_repres_meta_trimestral]);
        $row5->layout = [' col-sm-12'];

        $row6 = $this->detailFormMetaTrimestralRepresMetaTrimestral->addFields([new THidden('meta_trimestral_repres_meta_trimestral__row__id')]);
        $this->meta_trimestral_repres_meta_trimestral_criteria = new TCriteria();

        $this->meta_trimestral_repres_meta_trimestral_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->meta_trimestral_repres_meta_trimestral_list->generateHiddenFields();
        $this->meta_trimestral_repres_meta_trimestral_list->setId('meta_trimestral_repres_meta_trimestral_list');

        $this->meta_trimestral_repres_meta_trimestral_list->style = 'width:100%';
        $this->meta_trimestral_repres_meta_trimestral_list->class .= ' table-bordered';

        $column_meta_trimestral_repres_meta_trimestral_ap_representante_fantasia = new TDataGridColumn('ap_representante->fantasia', "Consultor(a)", 'left');
        $column_meta_trimestral_repres_meta_trimestral_valor = new TDataGridColumn('{valor} %', "Meta", 'left');
        $column_meta_trimestral_repres_meta_trimestral_pontuacao_inicial_transformed = new TDataGridColumn('pontuacao_inicial', "Inicial", 'left');
        $column_meta_trimestral_repres_meta_trimestral_pontuacao_alvo_transformed = new TDataGridColumn('pontuacao_alvo', "Meta", 'left');
        $column_meta_trimestral_repres_meta_trimestral_ap_representante_id = new TDataGridColumn('ap_representante_id', "Pontuação", 'left');

        $column_meta_trimestral_repres_meta_trimestral__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_meta_trimestral_repres_meta_trimestral__row__data->setVisibility(false);

        $action_onEditDetailMetaTrimestralRepres = new TDataGridAction(array('MetaTrimestralForm', 'onEditDetailMetaTrimestralRepres'));
        $action_onEditDetailMetaTrimestralRepres->setUseButton(false);
        $action_onEditDetailMetaTrimestralRepres->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailMetaTrimestralRepres->setLabel("Editar");
        $action_onEditDetailMetaTrimestralRepres->setImage('far:edit #478fca');
        $action_onEditDetailMetaTrimestralRepres->setFields(['__row__id', '__row__data']);

        $this->meta_trimestral_repres_meta_trimestral_list->addAction($action_onEditDetailMetaTrimestralRepres);
        $action_onDeleteDetailMetaTrimestralRepres = new TDataGridAction(array('MetaTrimestralForm', 'onDeleteDetailMetaTrimestralRepres'));
        $action_onDeleteDetailMetaTrimestralRepres->setUseButton(false);
        $action_onDeleteDetailMetaTrimestralRepres->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailMetaTrimestralRepres->setLabel("Excluir");
        $action_onDeleteDetailMetaTrimestralRepres->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailMetaTrimestralRepres->setFields(['__row__id', '__row__data']);

        $this->meta_trimestral_repres_meta_trimestral_list->addAction($action_onDeleteDetailMetaTrimestralRepres);

        $this->meta_trimestral_repres_meta_trimestral_list->addColumn($column_meta_trimestral_repres_meta_trimestral_ap_representante_fantasia);
        $this->meta_trimestral_repres_meta_trimestral_list->addColumn($column_meta_trimestral_repres_meta_trimestral_valor);
        $this->meta_trimestral_repres_meta_trimestral_list->addColumn($column_meta_trimestral_repres_meta_trimestral_pontuacao_inicial_transformed);
        $this->meta_trimestral_repres_meta_trimestral_list->addColumn($column_meta_trimestral_repres_meta_trimestral_pontuacao_alvo_transformed);
        $this->meta_trimestral_repres_meta_trimestral_list->addColumn($column_meta_trimestral_repres_meta_trimestral_ap_representante_id);

        $this->meta_trimestral_repres_meta_trimestral_list->addColumn($column_meta_trimestral_repres_meta_trimestral__row__data);

        $this->meta_trimestral_repres_meta_trimestral_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->meta_trimestral_repres_meta_trimestral_list);
        $this->detailFormMetaTrimestralRepresMetaTrimestral->addContent([$tableResponsiveDiv]);

        $column_meta_trimestral_repres_meta_trimestral_pontuacao_inicial_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_meta_trimestral_repres_meta_trimestral_pontuacao_alvo_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        });        $row7 = $tab_66c5efae826e7->addFields([$this->detailFormMetaTrimestralRepresMetaTrimestral]);
        $row7->layout = ['col-sm-12'];

        $row8 = $this->form->addFields([$tab_66c5efae826e7]);
        $row8->layout = ['col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['MetaTrimestralList', 'onShow']), 'fas:arrow-left #000000');
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

    }

    public  function onAddDetailMetaTrimestralRepresMetaTrimestral($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields[] = ['label'=>"Ap representante id", 'name'=>"meta_trimestral_repres_meta_trimestral_ap_representante_id", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Valor", 'name'=>"meta_trimestral_repres_meta_trimestral_valor", 'class'=>'TRequiredValidator', 'value'=>[]];
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

            if($data->meta_trimestral_repres_meta_trimestral_pontuacao_inicial > 0){
                $data->meta_trimestral_repres_meta_trimestral_pontuacao_alvo = 
                    $data->meta_trimestral_repres_meta_trimestral_pontuacao_inicial + 
                    ($data->meta_trimestral_repres_meta_trimestral_pontuacao_inicial * ($data->meta_trimestral_repres_meta_trimestral_valor/100));
            }else{
                $data->meta_trimestral_repres_meta_trimestral_pontuacao_alvo = 1;
            }

            $__row__id = !empty($data->meta_trimestral_repres_meta_trimestral__row__id) ? $data->meta_trimestral_repres_meta_trimestral__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new MetaTrimestralRepres();
            $grid_data->__row__id = $__row__id;
            $grid_data->ap_representante_id = $data->meta_trimestral_repres_meta_trimestral_ap_representante_id;
            $grid_data->id = $data->meta_trimestral_repres_meta_trimestral_id;
            $grid_data->valor = $data->meta_trimestral_repres_meta_trimestral_valor;
            $grid_data->pontuacao_inicial = $data->meta_trimestral_repres_meta_trimestral_pontuacao_inicial;
            $grid_data->pontuacao_alvo = $data->meta_trimestral_repres_meta_trimestral_pontuacao_alvo;
            $grid_data->pontuacao_atual = $data->meta_trimestral_repres_meta_trimestral_pontuacao_atual;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['ap_representante_id'] =  $param['meta_trimestral_repres_meta_trimestral_ap_representante_id'] ?? null;
            $__row__data['__display__']['id'] =  $param['meta_trimestral_repres_meta_trimestral_id'] ?? null;
            $__row__data['__display__']['valor'] =  $param['meta_trimestral_repres_meta_trimestral_valor'] ?? null;
            $__row__data['__display__']['pontuacao_inicial'] =  $param['meta_trimestral_repres_meta_trimestral_pontuacao_inicial'] ?? null;
            $__row__data['__display__']['pontuacao_alvo'] =  $param['meta_trimestral_repres_meta_trimestral_pontuacao_alvo'] ?? null;
            $__row__data['__display__']['pontuacao_atual'] =  $param['meta_trimestral_repres_meta_trimestral_pontuacao_atual'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->meta_trimestral_repres_meta_trimestral_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('meta_trimestral_repres_meta_trimestral_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->meta_trimestral_repres_meta_trimestral_ap_representante_id = '';
            $data->meta_trimestral_repres_meta_trimestral_id = '';
            $data->meta_trimestral_repres_meta_trimestral_valor = '';
            $data->meta_trimestral_repres_meta_trimestral_pontuacao_inicial = '';
            $data->meta_trimestral_repres_meta_trimestral_pontuacao_alvo = '';
            $data->meta_trimestral_repres_meta_trimestral_pontuacao_atual = '';
            $data->meta_trimestral_repres_meta_trimestral__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#66c5f001826ec');
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

    public static function onEditDetailMetaTrimestralRepres($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;
            $fireEvents = true;
            $aggregate = false;

            $data = new stdClass;
            $data->meta_trimestral_repres_meta_trimestral_ap_representante_id = $__row__data->__display__->ap_representante_id ?? null;
            $data->meta_trimestral_repres_meta_trimestral_id = $__row__data->__display__->id ?? null;
            $data->meta_trimestral_repres_meta_trimestral_valor = $__row__data->__display__->valor ?? null;
            $data->meta_trimestral_repres_meta_trimestral_pontuacao_inicial = $__row__data->__display__->pontuacao_inicial ?? null;
            $data->meta_trimestral_repres_meta_trimestral_pontuacao_alvo = $__row__data->__display__->pontuacao_alvo ?? null;
            $data->meta_trimestral_repres_meta_trimestral_pontuacao_atual = $__row__data->__display__->pontuacao_atual ?? null;
            $data->meta_trimestral_repres_meta_trimestral__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data, $aggregate, $fireEvents);
            TScript::create("
               var element = $('#66c5f001826ec');
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
    public static function onDeleteDetailMetaTrimestralRepres($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->meta_trimestral_repres_meta_trimestral_ap_representante_id = '';
            $data->meta_trimestral_repres_meta_trimestral_id = '';
            $data->meta_trimestral_repres_meta_trimestral_valor = '';
            $data->meta_trimestral_repres_meta_trimestral_pontuacao_inicial = '';
            $data->meta_trimestral_repres_meta_trimestral_pontuacao_alvo = '';
            $data->meta_trimestral_repres_meta_trimestral_pontuacao_atual = '';
            $data->meta_trimestral_repres_meta_trimestral__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('meta_trimestral_repres_meta_trimestral_list', $__row__data->__row__id);
            TScript::create("
               var element = $('#66c5f001826ec');
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

            $object = new MetaTrimestral(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            if($object->data_inicial > $object->data_final){
                throw new Exception("A data final deve ser maior que a data inicial.");
            }

            $object->store(); // save the object 

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

//<generatedAutoCode>
            $this->meta_trimestral_repres_meta_trimestral_criteria->setProperty('order', 'fantasia asc');
//</generatedAutoCode>
            $meta_trimestral_repres_meta_trimestral_items = $this->storeMasterDetailItems('MetaTrimestralRepres', 'meta_trimestral_id', 'meta_trimestral_repres_meta_trimestral', $object, $param['meta_trimestral_repres_meta_trimestral_list___row__data'] ?? [], $this->form, $this->meta_trimestral_repres_meta_trimestral_list, function($masterObject, $detailObject){ 

                $detailObject->fantasia = $detailObject->ap_representante->fantasia;

            }, $this->meta_trimestral_repres_meta_trimestral_criteria); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('MetaTrimestralList', 'onShow', $loadPageParam); 

                        TScript::create("Template.closeRightPanel();"); 

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

                $object = new MetaTrimestral($key); // instantiates the Active Record 

//<generatedAutoCode>
                $this->meta_trimestral_repres_meta_trimestral_criteria->setProperty('order', 'fantasia asc');
//</generatedAutoCode>
                $meta_trimestral_repres_meta_trimestral_items = $this->loadMasterDetailItems('MetaTrimestralRepres', 'meta_trimestral_id', 'meta_trimestral_repres_meta_trimestral', $object, $this->form, $this->meta_trimestral_repres_meta_trimestral_list, $this->meta_trimestral_repres_meta_trimestral_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

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

    }

    public function onShow($param = null)
    {

        TTransaction::open(self::$database);

        $data = new stdClass();

        $metaAberta = MetaTrimestral::where('status','=',1)->first();
        if($metaAberta){
            $data->status = 0;
            $mes = str_pad(($metaAberta->mes)+1, 2, 0, STR_PAD_LEFT);
            $ano = $metaAberta->ano;
            if($mes == "13"){
                $mes = "01";
                $ano = $metaAberta->ano+1;
            }
        }else{
            $data->status = 1;
        }

        $data->mes = $mes ?? date('m');
        $data->ano = $ano ?? date('Y');

        TForm::sendData(self::$formName, $data);

        TTransaction::close();
    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

