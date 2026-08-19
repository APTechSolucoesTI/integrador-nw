<?php

class ApGrupoClienteForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'ApGrupoCliente';
    private static $primaryKey = 'id';
    private static $formName = 'form_ApGrupoClienteForm';

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
        $this->form->setFormTitle("Cadastro de grupo cliente");


        $id = new TEntry('id');
        $cod_grpcliente = new TEntry('cod_grpcliente');
        $descricao = new TEntry('descricao');
        $pontuacao = new TSpinner('pontuacao');
        $valor_inicial = new TNumeric('valor_inicial', '2', ',', '.' );
        $valor_final = new TNumeric('valor_final', '2', ',', '.' );


        $pontuacao->setRange(0, 2000, 1);
        $descricao->setMaxLength(255);
        $cod_grpcliente->setMaxLength(7);

        $id->setEditable(false);
        $descricao->setEditable(false);
        $cod_grpcliente->setEditable(false);

        $id->setSize(100);
        $descricao->setSize('100%');
        $pontuacao->setSize('100%');
        $valor_final->setSize('100%');
        $valor_inicial->setSize('100%');
        $cod_grpcliente->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[]);
        $row1->layout = [' col-sm-4','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Código do Grupo:", null, '14px', null, '100%'),$cod_grpcliente],[new TLabel("Descrição:", null, '14px', null, '100%'),$descricao]);
        $row2->layout = ['col-sm-4',' col-sm-8'];

        $row3 = $this->form->addFields([new TLabel("Pontuação:", null, '14px', null, '100%'),$pontuacao],[new TLabel("Valor Inicial:", null, '14px', null, '100%'),$valor_inicial],[new TLabel("Valor Final:", null, '14px', null, '100%'),$valor_final]);
        $row3->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['ApGrupoClienteHeaderList', 'onShow']), 'fas:arrow-left #000000');
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

            $object = new ApGrupoCliente(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

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
            TApplication::loadPage('ApGrupoClienteHeaderList', 'onShow', $loadPageParam); 

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

                $object = new ApGrupoCliente($key); // instantiates the Active Record 

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

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

