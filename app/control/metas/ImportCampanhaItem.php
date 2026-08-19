<?php

class ImportCampanhaItem extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'CampanhaItem';
    private static $primaryKey = 'id';
    private static $formName = 'form_ImportCampanhaItem';

    use Adianti\Base\AdiantiFileSaveTrait;

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
        $this->form->setFormTitle("Importação Item de Campanha");


        $import = new TFile('import');
        $campanha_id = new THidden('campanha_id');


        $import->enableFileHandling();
        $import->setAllowedExtensions(["csv"]);
        $campanha_id->setValue($param['campanha_id']);
        $import->setSize('100%');
        $campanha_id->setSize(200);

        $row1 = $this->form->addFields([new TLabel("Arquivo de Importação:", '#FF0000', '14px', null)],[$import,$campanha_id]);

        // create the form actions
        $btnImportar = $this->form->addAction("Importar", new TAction([$this, 'onImport']), 'fas:file-import #FFFFFF');
        $this->btnImportar = $btnImportar;
        $btnImportar->addStyleClass('btn-primary'); 

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

    public function onImport($param = null) 
    {
        try 
        {
            if(!isset($param['campanha_id']) || !$param['campanha_id'] || empty($param['campanha_id']) || $param['campanha_id']==null){
                throw new Exception("Campanha Inválida, verifique os dados cadastrados.");
            }
            //Obtém o nome do arquivo
            $fileName = json_decode(urldecode($param['import']))->fileName;

            //Abre o arquivo
            $handle = fopen($fileName, "r");

            //Abre uma transação com o banco de dados
            TTransaction::open(self::$database);

            //Contador de registros inseridos
            $count = 0;

            //Separador das colunas do arquivo CSV
            $separador = ';';

            //Limite de caracteres que uma linha pode ter, 0 = sem limite
            $limite_da_linha = 0;

            //Percorre todas as linhas do arquivos
            while (($dados = fgetcsv($handle, $limite_da_linha, $separador)) !== false)
            {
                if(isset($dados[0]) && !empty($dados[0]) && $dados[0]!=null){
                    //Monta o objeto cliente com as colunas do arquivo CSV
                    $item = new CampanhaItem;
                    $item->campanha_id = $param['campanha_id'];
                    $item->cod_item = $dados[0];

                    $item->store();
                    $count++;
                }
            }

            //Fecha a transação
            TTransaction::close();

            //Fecha o arquivo
            fclose($handle);

            TWindow::closeWindow(parent::getId());

            TToast::show("success", "$count Itens de Campanha foram Importados.", "topRight", "");

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
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

                $object = new CampanhaItem($key); // instantiates the Active Record 

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

