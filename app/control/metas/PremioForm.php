<?php

class PremioForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'Premio';
    private static $primaryKey = 'id';
    private static $formName = 'form_PremioForm';

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
        $this->form->setFormTitle("Cadastro de prêmio");

        $criteria_tipo_premio_id = new TCriteria();
        $criteria_premio_regra_premio_comparador_id = new TCriteria();

        $id = new TEntry('id');
        $tipo_premio_id = new TDBCombo('tipo_premio_id', 'integrador', 'TipoPremio', 'id', '{descricao}','id asc' , $criteria_tipo_premio_id );
        $dado1 = new TLabel(" ", null, '14px', null, '100%');
        $dado2 = new TLabel(" ", null, '14px', null, '100%');
        $mes = new TSpinner('mes');
        $ano = new TSpinner('ano');
        $restricao = new TCombo('restricao');
        $obs = new TText('obs');
        $premio_regra_premio_comparador_id = new TDBCombo('premio_regra_premio_comparador_id', 'integrador', 'Comparador', 'id', '{descricao}','id asc' , $criteria_premio_regra_premio_comparador_id );
        $premio_regra_premio_id = new THidden('premio_regra_premio_id');
        $premio_regra_premio_dado_0 = new TNumeric('premio_regra_premio_dado_0', '2', ',', '.' );
        $premio_regra_premio_dado_1 = new TNumeric('premio_regra_premio_dado_1', '2', ',', '.' );
        $premio_regra_premio_tipo_valor = new TCombo('premio_regra_premio_tipo_valor');
        $premio_regra_premio_bonus = new TNumeric('premio_regra_premio_bonus', '2', ',', '.' );
        $button_adicionar_premio_regra_premio = new TButton('button_adicionar_premio_regra_premio');

        $tipo_premio_id->setChangeAction(new TAction([$this,'onSelectTipoPremio']));

        $tipo_premio_id->addValidation("Tipo premio id", new TRequiredValidator()); 

        $id->setEditable(false);
        $button_adicionar_premio_regra_premio->setAction(new TAction([$this, 'onAddDetailPremioRegraPremio'],['static' => 1]), "Adicionar");
        $button_adicionar_premio_regra_premio->addStyleClass('btn-default');
        $button_adicionar_premio_regra_premio->setImage('fas:plus #2ecc71');
        $mes->setRange(1, 12, 1);
        $ano->setRange(2020, 3000, 1);

        $mes->setValue(date('m'));
        $ano->setValue(date('Y'));

        $restricao->addItems(["S"=>" Sim","N"=>" Não"]);
        $premio_regra_premio_tipo_valor->addItems(["1"=>"Percentual","2"=>"Valor monetário"]);

        $restricao->enableSearch();
        $tipo_premio_id->enableSearch();
        $premio_regra_premio_tipo_valor->enableSearch();
        $premio_regra_premio_comparador_id->enableSearch();

        $id->setSize('100%');
        $mes->setSize('100%');
        $ano->setSize('100%');
        $obs->setSize('100%', 70);
        $restricao->setSize('100%');
        $tipo_premio_id->setSize('100%');
        $premio_regra_premio_id->setSize(200);
        $premio_regra_premio_bonus->setSize('100%');
        $premio_regra_premio_dado_0->setSize('100%');
        $premio_regra_premio_dado_1->setSize('100%');
        $premio_regra_premio_tipo_valor->setSize('100%');
        $premio_regra_premio_comparador_id->setSize('100%');

        $button_adicionar_premio_regra_premio->id = '688b84488e119';

        $dado1->name = 'dado1';
        $dado2->name = 'dado2';
        $premio_regra_premio_dado_1->name = 'fim';
        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id]);
        $row1->layout = ['col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Categoria de Premiação:", '#ff0000', '14px', null, '100%'),$tipo_premio_id],[new TLabel("Coluna", null, '14px', null, '100%'),$dado1],[new TLabel("Sequencial", null, '14px', null, '100%'),$dado2]);
        $row2->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("Mês:", '#ff0000', '14px', null, '100%'),$mes],[new TLabel("Ano:", null, '14px', null, '100%'),$ano],[new TLabel("Restrição:", null, '14px', null, '100%'),$restricao]);
        $row3->layout = ['col-sm-4','col-sm-4','col-sm-2'];

        $row4 = $this->form->addFields([new TLabel("Obs:", null, '14px', null),$obs]);
        $row4->layout = [' col-sm-12'];

        $row5 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);

        $this->detailFormPremioRegraPremio = new BootstrapFormBuilder('detailFormPremioRegraPremio');
        $this->detailFormPremioRegraPremio->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormPremioRegraPremio->setProperty('class', 'form-horizontal builder-detail-form');

        $row6 = $this->detailFormPremioRegraPremio->addFields([new TFormSeparator("Regras de premiação", '#333', '18', '#eee')]);
        $row6->layout = [' col-sm-12'];

        $row7 = $this->detailFormPremioRegraPremio->addFields([new TLabel("Operador:", '#ff0000', '14px', null, '100%'),$premio_regra_premio_comparador_id,$premio_regra_premio_id],[new TLabel("Inicio:", '#ff0000', '14px', null, '100%'),$premio_regra_premio_dado_0],[new TLabel("Fim:", null, '14px', null, '100%'),$premio_regra_premio_dado_1]);
        $row7->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row8 = $this->detailFormPremioRegraPremio->addFields([new TLabel("Tipo de aplicação:", '#ff0000', '14px', null, '100%'),$premio_regra_premio_tipo_valor],[new TLabel("Premiação:", '#ff0000', '14px', null, '100%'),$premio_regra_premio_bonus]);
        $row8->layout = [' col-sm-4',' col-sm-4'];

        $row9 = $this->detailFormPremioRegraPremio->addFields([$button_adicionar_premio_regra_premio]);
        $row9->layout = [' col-sm-12'];

        $row10 = $this->detailFormPremioRegraPremio->addFields([new THidden('premio_regra_premio__row__id')]);
        $this->premio_regra_premio_criteria = new TCriteria();

        $this->premio_regra_premio_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->premio_regra_premio_list->generateHiddenFields();
        $this->premio_regra_premio_list->setId('premio_regra_premio_list');

        $this->premio_regra_premio_list->style = 'width:100%';
        $this->premio_regra_premio_list->class .= ' table-bordered';

        $column_premio_regra_premio_comparador_descricao = new TDataGridColumn('comparador->descricao', "Operador", 'left');
        $column_premio_regra_premio_dado_0 = new TDataGridColumn('dado_0', "Percentual", 'left');
        $column_premio_regra_premio_dado_1 = new TDataGridColumn('dado_1', "Percentual Extra", 'left');
        $column_premio_regra_premio_bonus_transformed = new TDataGridColumn('bonus', "Premiação", 'left');

        $column_premio_regra_premio__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_premio_regra_premio__row__data->setVisibility(false);

        $action_onEditDetailPremioRegra = new TDataGridAction(array('PremioForm', 'onEditDetailPremioRegra'));
        $action_onEditDetailPremioRegra->setUseButton(false);
        $action_onEditDetailPremioRegra->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailPremioRegra->setLabel("Editar");
        $action_onEditDetailPremioRegra->setImage('far:edit #478fca');
        $action_onEditDetailPremioRegra->setFields(['__row__id', '__row__data']);

        $this->premio_regra_premio_list->addAction($action_onEditDetailPremioRegra);
        $action_onDeleteDetailPremioRegra = new TDataGridAction(array('PremioForm', 'onDeleteDetailPremioRegra'));
        $action_onDeleteDetailPremioRegra->setUseButton(false);
        $action_onDeleteDetailPremioRegra->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailPremioRegra->setLabel("Excluir");
        $action_onDeleteDetailPremioRegra->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailPremioRegra->setFields(['__row__id', '__row__data']);

        $this->premio_regra_premio_list->addAction($action_onDeleteDetailPremioRegra);

        $this->premio_regra_premio_list->addColumn($column_premio_regra_premio_comparador_descricao);
        $this->premio_regra_premio_list->addColumn($column_premio_regra_premio_dado_0);
        $this->premio_regra_premio_list->addColumn($column_premio_regra_premio_dado_1);
        $this->premio_regra_premio_list->addColumn($column_premio_regra_premio_bonus_transformed);

        $this->premio_regra_premio_list->addColumn($column_premio_regra_premio__row__data);

        $this->premio_regra_premio_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->premio_regra_premio_list);
        $this->detailFormPremioRegraPremio->addContent([$tableResponsiveDiv]);

        $column_premio_regra_premio_bonus_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $valor = number_format($object->bonus ?? 0, 2, ",", ".");
            if($object->tipo_valor == 1){
                return $valor.'%';
            }
            return 'R$ '.$valor;
        });        $row11 = $this->form->addFields([$this->detailFormPremioRegraPremio]);
        $row11->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['PremioHeaderList', 'onShow']), 'fas:arrow-left #000000');
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

        $style = new TStyle('right-panel > .container-part[page-name=PremioForm]');
        $style->width = '60% !important';   
        $style->show(true);

    }

    public static function onSelectTipoPremio($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            if($param['tipo_premio_id'])
            {
                $stmt = TipoPremio::find( $param['tipo_premio_id']);
                TScript::create("$(\"[name='dado1']\").html('$stmt->coluna')");
                TScript::create("$(\"[name='dado2']\").html('{$stmt->seq}')");
            }

            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailPremioRegraPremio($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields[] = ['label'=>"Operador", 'name'=>"premio_regra_premio_comparador_id", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Percentual", 'name'=>"premio_regra_premio_dado_0", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Tipo de aplicação", 'name'=>"premio_regra_premio_tipo_valor", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Premiação", 'name'=>"premio_regra_premio_bonus", 'class'=>'TRequiredValidator', 'value'=>[]];
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

            $__row__id = !empty($data->premio_regra_premio__row__id) ? $data->premio_regra_premio__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new PremioRegra();
            $grid_data->__row__id = $__row__id;
            $grid_data->comparador_id = $data->premio_regra_premio_comparador_id;
            $grid_data->id = $data->premio_regra_premio_id;
            $grid_data->dado_0 = $data->premio_regra_premio_dado_0;
            $grid_data->dado_1 = $data->premio_regra_premio_dado_1;
            $grid_data->tipo_valor = $data->premio_regra_premio_tipo_valor;
            $grid_data->bonus = $data->premio_regra_premio_bonus;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['comparador_id'] =  $param['premio_regra_premio_comparador_id'] ?? null;
            $__row__data['__display__']['id'] =  $param['premio_regra_premio_id'] ?? null;
            $__row__data['__display__']['dado_0'] =  $param['premio_regra_premio_dado_0'] ?? null;
            $__row__data['__display__']['dado_1'] =  $param['premio_regra_premio_dado_1'] ?? null;
            $__row__data['__display__']['tipo_valor'] =  $param['premio_regra_premio_tipo_valor'] ?? null;
            $__row__data['__display__']['bonus'] =  $param['premio_regra_premio_bonus'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->premio_regra_premio_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('premio_regra_premio_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->premio_regra_premio_comparador_id = '';
            $data->premio_regra_premio_id = '';
            $data->premio_regra_premio_dado_0 = '';
            $data->premio_regra_premio_dado_1 = '';
            $data->premio_regra_premio_tipo_valor = '';
            $data->premio_regra_premio_bonus = '';
            $data->premio_regra_premio__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#688b84488e119');
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

    public static function onEditDetailPremioRegra($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;
            $fireEvents = true;
            $aggregate = false;

            $data = new stdClass;
            $data->premio_regra_premio_comparador_id = $__row__data->__display__->comparador_id ?? null;
            $data->premio_regra_premio_id = $__row__data->__display__->id ?? null;
            $data->premio_regra_premio_dado_0 = $__row__data->__display__->dado_0 ?? null;
            $data->premio_regra_premio_dado_1 = $__row__data->__display__->dado_1 ?? null;
            $data->premio_regra_premio_tipo_valor = $__row__data->__display__->tipo_valor ?? null;
            $data->premio_regra_premio_bonus = $__row__data->__display__->bonus ?? null;
            $data->premio_regra_premio__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data, $aggregate, $fireEvents);
            TScript::create("
               var element = $('#688b84488e119');
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
    public static function onDeleteDetailPremioRegra($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->premio_regra_premio_comparador_id = '';
            $data->premio_regra_premio_id = '';
            $data->premio_regra_premio_dado_0 = '';
            $data->premio_regra_premio_dado_1 = '';
            $data->premio_regra_premio_tipo_valor = '';
            $data->premio_regra_premio_bonus = '';
            $data->premio_regra_premio__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('premio_regra_premio_list', $__row__data->__row__id);
            TScript::create("
               var element = $('#688b84488e119');
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

            $object = new Premio(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $qtde = ($object->id) 
                ? Premio::where('mes','=',$object->mes)->where('ano','=',$object->ano)->where('tipo_premio_id','=',$object->tipo_premio_id)->where('id','<>',$object->id)->count()
                :  Premio::where('mes','=',$object->mes)->where('ano','=',$object->ano)->where('tipo_premio_id','=',$object->tipo_premio_id)->count();
            if($qtde>0){
                throw new Exception("Prêmio para {$object->mes}/{$object->ano} já cadastrado para esse tipo de prêmio.");
            }

            $object->store(); // save the object 

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            $premio_regra_premio_items = $this->storeMasterDetailItems('PremioRegra', 'premio_id', 'premio_regra_premio', $object, $param['premio_regra_premio_list___row__data'] ?? [], $this->form, $this->premio_regra_premio_list, function($masterObject, $detailObject){ 

                //code here

            }, $this->premio_regra_premio_criteria); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('PremioHeaderList', 'onShow', $loadPageParam); 

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

                $object = new Premio($key); // instantiates the Active Record 

                $premio_regra_premio_items = $this->loadMasterDetailItems('PremioRegra', 'premio_id', 'premio_regra_premio', $object, $this->form, $this->premio_regra_premio_list, $this->premio_regra_premio_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 

                self::onSelectTipoPremio(['tipo_premio_id'=>$object->tipo_premio_id]);
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

        TScript::create("$(\"[name='regra_premio_dado_1']\").closest('.fb-inline-field-container').hide()");
        TScript::create("$(\"[name='fim']\").hide();");

        //self::onSelectedTipo($param);
    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

