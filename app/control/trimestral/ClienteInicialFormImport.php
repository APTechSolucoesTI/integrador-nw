<?php

class ClienteInicialFormImport extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_ClienteInicialFormImport';

    use Adianti\Base\AdiantiFileSaveTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Importação do Inicio do Trimestre");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Importação do Inicio do Trimestre");


        $import = new TFile('import');
        $meta_trimestral_id = new THidden('meta_trimestral_id');


        $import->enableFileHandling();
        $import->setAllowedExtensions(["csv"]);
        $meta_trimestral_id->setValue($param['meta_trimestral_id']);
        $import->setSize('100%');
        $meta_trimestral_id->setSize(200);


        $row1 = $this->form->addFields([new TLabel("Arquivo:", '#FF0000', '14px', null)],[$import,$meta_trimestral_id]);

        // create the form actions
        $btn_onimport = $this->form->addAction("Importar", new TAction([$this, 'onImport']), 'fas:file-import #ffffff');
        $this->btn_onimport = $btn_onimport;
        $btn_onimport->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public function onImport($param = null) 
    {
        try
        {
            if(!$param['meta_trimestral_id'] || !$param['import']) exit;

            TrimestreService::importarInicioTrimestre($param);

            TWindow::closeWindow(parent::getId());
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow($param = null)
    {               

    } 

}

