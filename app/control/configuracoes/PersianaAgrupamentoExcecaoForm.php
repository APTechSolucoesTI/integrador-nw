<?php

class PersianaAgrupamentoExcecaoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'PersianaAgrupamentoExcecao';
    private static $primaryKey = 'id';
    private static $formName = 'form_PersianaAgrupamentoExcecaoForm';

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
        $this->form->setFormTitle("Exceção de Planejamento de Persianas");


        $id = new TEntry('id');
        $persiana_agrupamento_id = new THidden('persiana_agrupamento_id');
        $data = new TDate('data');
        $qtd = new TEntry('qtd');


        $id->setEditable(false);
        $data->setMask('dd/mm/yyyy');
        $data->setDatabaseMask('yyyy-mm-dd');
        $id->setSize(100);
        $data->setSize(110);
        $qtd->setSize('100%');
        $persiana_agrupamento_id->setSize(200);

        $row1 = $this->form->addFields([$id,$persiana_agrupamento_id]);
        $row1->layout = ['col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Data Exceção:", null, '14px', null, '100%'),$data]);
        $row2->layout = ['col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Limite Exceção: ", null, '14px', null),$qtd]);
        $row3->layout = ['col-sm-3'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['PersianaAgrupamentoExcecaoHeaderList', 'onShow']), 'fas:arrow-left #000000');
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

            $object = new PersianaAgrupamentoExcecao(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            if (empty($data->persiana_agrupamento_id))
            {
                throw new Exception(
                    'Agrupamento de persiana não identificado'
                );
            }
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
            TApplication::loadPage('PersianaAgrupamentoExcecaoHeaderList', 'onShow', $loadPageParam); 

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

                $object = new PersianaAgrupamentoExcecao($key); // instantiates the Active Record 

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

        if (!empty($param['persiana_agrupamento_id']))
        {
            $data = $this->form->getData();

            $data->persiana_agrupamento_id =
                (int) $param['persiana_agrupamento_id'];

            $this->form->setData($data);

            TSession::setValue(
                __CLASS__ . '_persiana_agrupamento_id',
                (int) $param['persiana_agrupamento_id']
            );

            TSession::setValue(
                'PersianaAgrupamentoExcecaoHeaderList_persiana_agrupamento_id',
                (int) $param['persiana_agrupamento_id']
            );
        }
    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

