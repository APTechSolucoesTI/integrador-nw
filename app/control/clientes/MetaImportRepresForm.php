<?php

class MetaImportRepresForm extends TWindow
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'MetaImportRepres';
    private static $primaryKey = 'id';
    private static $formName = 'form_MetaImportRepresForm';

    use Adianti\Base\AdiantiFileSaveTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Importar Consultor(a) ");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Importar Consultor(a) ");


        $import = new TFile('import');


        $import->setSize('100%');
        $import->enableFileHandling();
        $import->setAllowedExtensions(["csv","xlsx"]);

        $row1 = $this->form->addFields([new TLabel("Arquivo de Importação:", '#FF0000', '14px', null)],[$import]);
        $row1->layout = [' col-sm-2','col-sm-6'];

        // create the form actions
        $btnImportar = $this->form->addAction("Importar", new TAction([$this, 'onImport']), 'fas:file-import #FFFFFF');
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

        // detecta separador pelo cabeçalho (';' ou ',')
        $first = '';
        while (($first = fgets($handle)) !== false) {
            if (trim($first) !== '') break;
        }
        $separador = (substr_count($first, ';') >= 2) ? ';' : ',';
        rewind($handle);

        TTransaction::open(self::$database);

        $count  = 0;
        $linha  = 0;
        $limite = 0;

        while (($dados = fgetcsv($handle, $limite, $separador)) !== false)
        {
            $linha++;

            if (!array_key_exists(0, $dados) || !array_key_exists(1, $dados) || !array_key_exists(2, $dados)) {
                throw new Exception("Linha {$linha}: Linha inválida. Esperado: id_quadro{$separador}consultor{$separador}quantidade.");
            }

            $rawId   = $this->normalizarCampoCsv($dados[0]);
            $rawNome = $this->normalizarCampoCsv($dados[1]);
            $rawQtd  = $this->normalizarCampoCsv($dados[2]);

            // pula cabeçalho
            if ($linha === 1 && mb_strtolower($rawId) === 'id_quadro') {
                continue;
            }

            if ($rawId === '') {
                throw new Exception("Linha {$linha}: id_quadro está vazio.");
            }
            if ($rawNome === '') {
                throw new Exception("Linha {$linha}: consultor está vazio. Dados lidos: " . json_encode($dados));
            }
            if ($rawQtd === '') {
                throw new Exception("Linha {$linha}: quantidade está vazia.");
            }

            if (!is_numeric($rawId)) {
                throw new Exception("Linha {$linha}: id_quadro '{$rawId}' inválido (deve ser número).");
            }

            $metaImportId = (int) $rawId;

            $qtdStr = str_replace(',', '.', $rawQtd);
            if (!is_numeric($qtdStr)) {
                throw new Exception("Linha {$linha}: quantidade '{$rawQtd}' inválida.");
            }

            $qtd = (float) $qtdStr;
            if ($qtd <= 0) {
                throw new Exception("Linha {$linha}: quantidade deve ser maior que 0.");
            }

            $meta = MetaImport::find($metaImportId);
            if (!$meta) {
                throw new Exception("Linha {$linha}: Meta Import (id_quadro) {$metaImportId} não existe.");
            }

            $repres = ApRepresentante::where('fantasia', 'ILIKE', $rawNome)->first();
            if (!$repres) {
                throw new Exception("Linha {$linha}: Representante '{$rawNome}' não encontrado.");
            }

            $item = MetaImportRepres::where('meta_import_id', '=', $metaImportId)
                ->where('ap_representante_id', '=', $repres->id)
                ->first();

            if (!$item) {
                $item = new MetaImportRepres;
                $item->meta_import_id      = $metaImportId;
                $item->ap_representante_id = $repres->id;
            }

            $item->qtd = $qtd;
            $item->store();

            $count++;
        }

        TTransaction::close();

        if ($handle) fclose($handle);

        TWindow::closeWindow(parent::getId());
        TToast::show("success", "{$count} linhas importadas.", "topRight", "");

        }catch (Exception $e)
            {
            if (TTransaction::get()) {
                TTransaction::rollback();
            }

            if ($handle) {
                fclose($handle);
            }

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

                $object = new MetaImportRepres($key); // instantiates the Active Record 

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

    private function normalizarCampoCsv($valor)
    {
       $s = (string) $valor;

        // remove BOM e null bytes
        $s = str_replace(["\xEF\xBB\xBF", "\x00"], '', $s);

        // converte para UTF-8 antes de usar regex unicode
        $s = mb_convert_encoding($s, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');

        // garante UTF-8 válido
        if (!mb_check_encoding($s, 'UTF-8')) {
            $s = iconv('UTF-8', 'UTF-8//IGNORE', $s);
        }

        // remove quebra de linha dentro do campo (caso WILLIANS)
        $s = str_replace(["\r", "\n", "\t"], ' ', $s);

        // remove invisíveis unicode
        $s = preg_replace('/[\x{FEFF}\x{200B}]/u', '', $s);

        $s = trim($s);
        $s = preg_replace('/\s+/u', ' ', $s);

        return $s;
    }

}

