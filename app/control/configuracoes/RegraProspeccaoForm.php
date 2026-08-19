<?php

class RegraProspeccaoForm extends TWindow
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'RegraProspeccao';
    private static $primaryKey = 'id';
    private static $formName = 'form_RegraProspeccaoForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(500, null);
        parent::setTitle("Cadastro de Regra de Prospecção");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de Regra de Prospecção");


        $id = new TEntry('id');
        $ano = new TSpinner('ano');
        $mes = new TCombo('mes');
        $filial_op = new TCombo('filial_op');
        $estado_op = new TCombo('estado_op');
        $estado = new TCheckGroup('estado');

        $ano->addValidation("Ano", new TRequiredValidator()); 
        $mes->addValidation("Mês", new TRequiredValidator()); 

        $id->setEditable(false);
        $ano->setRange(2000, 3000, 1);
        $ano->setValue(date('Y'));
        $estado->setLayout('horizontal');
        $estado->setValueSeparator(',');
        $mes->enableSearch();
        $filial_op->enableSearch();
        $estado_op->enableSearch();

        $estado_op->addItems(["1"=>"Esteja entre","2"=>"Não enteja entre"]);
        $filial_op->addItems(["1"=>"Não tenha grupo empresarial","2"=>"Tenha grupo empresarial desde que cadastrado depois da abertura"]);
        $mes->addItems(["01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro"]);
        $estado->addItems(["AC"=>"Acre","AL"=>"Alagoas","AP"=>"Amapá","AM"=>"Amazonas","BA"=>"Bahia","CE"=>"Ceará","ES"=>"Espírito Santo","GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RS"=>"Rio Grande do Sul","RO"=>"Rondônia","RR"=>"Roraima","SC"=>"Santa Catarina","SP"=>"São Paulo","SE"=>"Sergipe","TO"=>"Tocantins","DF"=>"Distrito Federal"]);

        $id->setSize(100);
        $estado->setSize(80);
        $ano->setSize('100%');
        $mes->setSize('100%');
        $filial_op->setSize('100%');
        $estado_op->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Ano:", '#FF0000', '14px', null, '100%'),$ano],[new TLabel("Mês:", '#FF0000', '14px', null, '100%'),$mes]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row4 = $this->form->addFields([new TLabel("Operador para Filial:", null, '14px', null, '100%'),$filial_op]);
        $row4->layout = [' col-sm-12'];

        $row5 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row6 = $this->form->addFields([new TLabel("Operador para Estado:", null, '14px', null, '100%'),$estado_op]);
        $row6->layout = [' col-sm-12'];

        $row7 = $this->form->addFields([new TLabel("Estados:", null, '14px', null, '100%'),$estado]);
        $row7->layout = [' col-sm-12'];

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

            $object = new RegraProspeccao(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->system_unit_id = TSession::getValue('userunitid');

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
            TApplication::loadPage('RegraProspeccaoHeaderList', 'onShow', $loadPageParam); 

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

                $object = new RegraProspeccao($key); // instantiates the Active Record 

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

