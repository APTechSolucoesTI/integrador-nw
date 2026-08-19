<?php

class RestricoesPersianasForm extends TWindow
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'RestricaoPremio';
    private static $primaryKey = 'id';
    private static $formName = 'form_RestricoesPersianasForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Restrições Persianas");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Restrições Persianas");

        $criteria_premio_id = new TCriteria();
        $criteria_ap_grupo_estoque_id = new TCriteria();
        $criteria_ap_subgrupo_estoque_id = new TCriteria();

        $id = new TEntry('id');
        $premio_id = new TDBCombo('premio_id', 'integrador', 'Premio', 'id', '{id}','id asc' , $criteria_premio_id );
        $ap_grupo_estoque_id = new TDBCombo('ap_grupo_estoque_id', 'integrador', 'ApGrupoEstoque', 'id', '{descricao}','descricao asc' , $criteria_ap_grupo_estoque_id );
        $ap_subgrupo_estoque_id = new TDBCombo('ap_subgrupo_estoque_id', 'integrador', 'ApSubgrupoEstoque', 'id', '{descricao}','descricao asc' , $criteria_ap_subgrupo_estoque_id );

        $premio_id->addValidation("Premio id", new TRequiredValidator()); 
        $ap_grupo_estoque_id->addValidation("Ap grupo estoque id", new TRequiredValidator()); 
        $ap_subgrupo_estoque_id->addValidation("Ap subgrupo estoque id", new TRequiredValidator()); 

        $id->setEditable(false);
        $premio_id->enableSearch();
        $ap_grupo_estoque_id->enableSearch();
        $ap_subgrupo_estoque_id->enableSearch();

        $id->setSize(100);
        $premio_id->setSize('100%');
        $ap_grupo_estoque_id->setSize('100%');
        $ap_subgrupo_estoque_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Premio id:", '#ff0000', '14px', null, '100%'),$premio_id]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Ap grupo estoque id:", '#ff0000', '14px', null, '100%'),$ap_grupo_estoque_id],[new TLabel("Ap subgrupo estoque id:", '#ff0000', '14px', null, '100%'),$ap_subgrupo_estoque_id]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new RestricaoPremio(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

                TWindow::closeWindow(parent::getId()); 
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

                $object = new RestricaoPremio($key); // instantiates the Active Record 

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

