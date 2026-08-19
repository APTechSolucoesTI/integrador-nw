<?php

class ImportMetaImportItemForm extends TWindow
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'MetaImportItem';
    private static $primaryKey = 'id';
    private static $formName = 'form_ImportMetaImportItemForm';

    use Adianti\Base\AdiantiFileSaveTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Importar Itens ");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Importar Itens ");


        $import = new TFile('import');


        $import->setSize('100%');
        $import->enableFileHandling();
        $import->setAllowedExtensions(["csv"]);

        $row1 = $this->form->addFields([new TLabel("Arquivo de Importação:", '#FF0000', '14px', null)],[$import]);

        // create the form actions
        $btnImportar = $this->form->addAction("Importar", new TAction([$this, 'onImport']), 'fas:file-import #FFFFFFF');
        $this->btnImportar = $btnImportar;
        $btnImportar->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public function onImport($param = null) 
    {
        $handle = null;

    try
    {
        $fileName = json_decode(urldecode($param['import']))->fileName;
        $handle   = fopen($fileName, "r");

        if (!$handle) {
            throw new Exception("Não foi possível abrir o arquivo.");
        }

        TTransaction::open(self::$database);

        $count     = 0;
        $linha     = 0;
        $separador = ';';
        $limite    = 0;

        while (($dados = fgetcsv($handle, $limite, $separador)) !== false)
        {
            $linha++;

            // exige 2 colunas (mesmo que vazias)
            if (!array_key_exists(0, $dados) || !array_key_exists(1, $dados)) {
                throw new Exception("Linha {$linha}: Linha inválida. Esperado: id_quadro;codigo.");
            }

            $rawId    = trim((string) $dados[0]);
            $rawCodigo = trim((string) $dados[1]);

            // pula cabeçalho
            if ($linha === 1 && mb_strtolower($rawId) === 'id_quadro') {
                continue;
            }

            if ($rawId === '') {
                throw new Exception("Linha {$linha}: id_quadro está vazio.");
            }
            if ($rawCodigo === '') {
                throw new Exception("Linha {$linha}: codigo está vazio.");
            }

            if (!is_numeric($rawId)) {
                throw new Exception("Linha {$linha}: id_quadro '{$rawId}' inválido (deve ser número).");
            }

            $metaImportId = (int) $rawId;
            $codigo       = $rawCodigo;

            // garante que a MetaImport existe
            $meta = MetaImport::find($metaImportId);
            if (!$meta) {
                throw new Exception("Linha {$linha}: Meta Import (id_quadro) {$metaImportId} não existe.");
            }

            // evita duplicar: se já existir, não cria outro
            $jaExiste = MetaImportItem::where('meta_import_id', '=', $metaImportId)
                ->where('cod_item', '=', $codigo)
                ->first();

            if ($jaExiste) {
                continue;
            }

            $item = new MetaImportItem;
            $item->meta_import_id = $metaImportId;
            $item->cod_item       = $codigo;
            $item->store();

            $count++;
        }

        TTransaction::close();

        if ($handle) {
            fclose($handle);
        }

        TWindow::closeWindow(parent::getId());

        TToast::show("success", "{$count} itens importados.", "topRight", "");

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

                $object = new MetaImportItem($key); // instantiates the Active Record 

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

