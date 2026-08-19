<?php

class ArtigosSTItemImport extends TWindow
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'MiniMetaItem';
    private static $primaryKey = 'id';
    private static $formName = 'form_MiniMetaItemImport';

    use Adianti\Base\AdiantiFileSaveTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(500, null);
        parent::setTitle("Importar Artigos com ST");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Importar Artigos com ST");


        $import = new TFile('import');
        $mini_meta_id = new THidden('mini_meta_id');


        $import->enableFileHandling();
        $import->setAllowedExtensions(["csv"]);
        $mini_meta_id->setValue($param['mini_meta_id']);
        $import->setSize('100%');
        $mini_meta_id->setSize(200);

        $row1 = $this->form->addFields([new TLabel("Arquivo de Importação:", '#FF0000', '14px', null)],[$import,$mini_meta_id]);

        // create the form actions
        $btnImportar = $this->form->addAction("Importar", new TAction([$this, 'onImport']), 'fas:file-import #FFFFFF');
        $this->btnImportar = $btnImportar;
        $btnImportar->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public function onImport($param = null) 
    {
        try 
        {
            if(!isset($param['mini_meta_id']) || !$param['mini_meta_id'] || empty($param['mini_meta_id']) || $param['mini_meta_id']==null){
                throw new Exception("Painel de Artigo com ST inválido.");
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
                    $item = new MiniMetaItem;
                    $item->mini_meta_id = $param['mini_meta_id'];
                    $item->cod_item = $dados[0];

                    //Insere um novo cliente
                    $item->store();
                    $count++;
                }
            }

            //Fecha a transação
            TTransaction::close();

            //Fecha o arquivo
            fclose($handle);

            TWindow::closeWindow(parent::getId());

            TToast::show("success", "$count Artigos com ST foram importados.", "topRight", "");
            TApplication::loadPage('ArtigoSTForm', 'onEdit', ['key' => $param['mini_meta_id']]);

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

                $object = new MiniMetaItem($key); // instantiates the Active Record 

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

