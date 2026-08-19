<?php

class SolicitacaoProdutoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'AguardoProduto';
    private static $primaryKey = 'id';
    private static $formName = 'form_AguardoProdutoForm';

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
        $this->form->setFormTitle("Cadastro de Solicitação de Produto");

        $criteria_repres_id = new TCriteria();
        $criteria_ap_item_id = new TCriteria();
        $criteria_cod_clifor = new TCriteria();

        $filterVar = TSession::getValue("userunitid");
        $criteria_repres_id->add(new TFilter('system_unit_id', '=', $filterVar)); 
        $filterVar = "S";
        $criteria_repres_id->add(new TFilter('ativo', '=', $filterVar)); 

        $system_unit_id = new THidden('system_unit_id');
        $id = new TEntry('id');
        $system_users_id = new THidden('system_users_id');
        $repres_id = new TDBCombo('repres_id', 'integrador', 'ApRepresentante', 'id', '{razao}','razao asc' , $criteria_repres_id );
        $ap_item_id = new TDBUniqueSearch('ap_item_id', 'integrador', 'ApItem', 'id', 'descricao','codigo asc' , $criteria_ap_item_id );
        $quantidade = new TNumeric('quantidade', '2', ',', '.' );
        $cod_clifor = new TDBUniqueSearch('cod_clifor', 'nw', 'Clifor', 'cod_clifor', 'razao','cod_clifor asc' , $criteria_cod_clifor );

        $repres_id->addValidation("Representante", new TRequiredValidator()); 
        $ap_item_id->addValidation("Item", new TRequiredValidator()); 
        $quantidade->addValidation("Quantidade", new TRequiredValidator()); 

        $repres_id->enableSearch();
        $ap_item_id->disableIdSearch();
        $quantidade->setAllowNegative(false);
        $system_users_id->setValue(TSession::getValue("userid"));
        $system_unit_id->setValue(TSession::getValue("userunitid"));

        $id->setEditable(false);
        $repres_id->setEditable(false);

        $ap_item_id->setMinLength(3);
        $cod_clifor->setMinLength(3);

        $ap_item_id->setMask('{codigo} - {descricao}');
        $cod_clifor->setMask('{cod_clifor} - {razao}');

        $ap_item_id->setFilterColumns(["codigo","descricao"]);
        $cod_clifor->setFilterColumns(["cod_clifor","razao"]);

        $id->setSize(100);
        $repres_id->setSize('100%');
        $ap_item_id->setSize('100%');
        $quantidade->setSize('100%');
        $cod_clifor->setSize('100%');
        $system_unit_id->setSize(200);
        $system_users_id->setSize(200);

        if(TSession::getValue('userid') <= 2 || (TSession::getValue('userid') >= 23 && TSession::getValue('userid') <= 26)){
            $repres_id->setEditable(true);
        }
        $row1 = $this->form->addFields([$system_unit_id,new TLabel("Id:", null, '14px', null, '100%'),$id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([$system_users_id,new TLabel("Consultor(a):", '#ff0000', '14px', null, '100%'),$repres_id]);
        $row2->layout = ['col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Item:", '#FF0000', '14px', null, '100%'),$ap_item_id]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([new TLabel("Quantidade:", '#FF0000', '14px', null, '100%'),$quantidade]);
        $row4->layout = ['col-sm-3'];

        $row5 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null, '100%'),$cod_clifor]);
        $row5->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['SolicitacaoProdutoHeaderList', 'onShow']), 'fas:arrow-left #000000');
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

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new AguardoProduto(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data
            $object->status = 'A';

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('SolicitacaoProdutoHeaderList', 'onShow', $loadPageParam); 

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

                $object = new AguardoProduto($key); // instantiates the Active Record 

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
        $represLogin = ApRepresentante::where('system_users_id','=',TSession::getValue('userid'))->where('system_unit_id','=',TSession::getValue('userunitid'))->first();
        if($represLogin){
            $object = new stdClass();
            $object->repres_id = $represLogin->id;
            TForm::sendData(self::$formName, $object);
        }
        TTransaction::close();
    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

