<?php

class ImportMetaRepresForm extends TWindow
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'MetaRepres';
    private static $primaryKey = 'id';
    private static $formName = 'form_ImportMetaRepresForm';

    use Adianti\Base\AdiantiFileSaveTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Importar Meta Representante");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Importar Meta Representante");


        $import = new TFile('import');
        $meta_id = new THidden('meta_id');

        $import->setCompleteAction(new TAction([$this,'onImport']));

        $import->enableFileHandling();
        $import->setAllowedExtensions(["csv"]);
        $meta_id->setValue($param['meta_id']);
        $meta_id->setSize(200);
        $import->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Arquivo de Importação:", '#FF0000', '14px', null)],[$import,$meta_id]);

        // create the form actions
        $btn_onaction = $this->form->addAction("Importar", new TAction([$this, 'onAction']), 'fas:file-import #FFFFFF');
        $this->btn_onaction = $btn_onaction;
        $btn_onaction->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public static function onImport($param = null) 
    {
       try {
        if (!isset($param['meta_id']) || !$param['meta_id']) {
            throw new Exception("Meta Inválida.");
        }

        $fileName = json_decode(urldecode($param['import']))->fileName;
        if (!is_readable($fileName)) {
            throw new Exception("Arquivo não encontrado ou sem permissão de leitura.");
        }

        // Helpers
        $toFloat = function ($raw) {
            if ($raw === null) return null;
            $s = trim((string)$raw);
            if ($s === '' || strtoupper($s) === 'NULL') return null;
            // remove milhar ".", espaços e converte vírgula para ponto
            $s = str_replace([' ', "\u{00A0}", '.'], '', $s);
            $s = str_replace(',', '.', $s);
            // mantém apenas primeiros dígitos/decimal/negativo
            if (!preg_match('/^-?\d+(?:\.\d+)?$/', $s)) {
                // tenta extrair o primeiro número válido que aparecer
                if (preg_match('/-?\d+(?:[.,]\d+)?/', (string)$raw, $m)) {
                    $s = str_replace(',', '.', $m[0]);
                }
            }
            return is_numeric($s) ? (float)$s : null;
        };

        // Aceita: "25 acima de 2500", "33,33% / 1000", "25;2500", "25|2500", etc.
        $parsePercMin = function ($raw) use ($toFloat) {
            if ($raw === null) return [null, null];
            $txt = trim((string)$raw);
            if ($txt === '') return [null, null];

            preg_match_all('/-?\d+(?:[.,]\d+)?/', $txt, $nums);
            $perc = isset($nums[0][0]) ? $toFloat($nums[0][0]) : null;
            $min  = isset($nums[0][1]) ? $toFloat($nums[0][1]) : null;
            return [$perc, $min];
        };

        $handle = fopen($fileName, "r");
        if (!$handle) throw new Exception("Falha ao abrir o arquivo.");

        TTransaction::open(self::$database);

        $separador = ';';
        $limite_da_linha = 0;
        $linha = 0;
        $count = 0;
        $erros = [];

        while (($dados = fgetcsv($handle, $limite_da_linha, $separador)) !== false) {
            $linha++;

            // Pula linhas vazias
            if (count($dados) === 0 || (count($dados) === 1 && trim($dados[0]) === '')) {
                continue;
            }

            // Detecção simples de cabeçalho (se na primeira linha tiver texto tipo "fantasia")
          if ($linha === 1) {
                $first = mb_strtolower(trim((string)($dados[0] ?? '')), 'UTF-8');
                if ($first === 'consultor' || $first === 'fantasia') {
                    continue;
                }
            }

            // --------- MAPA DE COLUNAS (ver ordem acima) ----------
            $fantasia          = isset($dados[0]) ? trim($dados[0]) : '';
            $valor_meta        = isset($dados[1]) ? $toFloat($dados[1]) : null;
            $valor_super_meta  = isset($dados[2]) ? $toFloat($dados[2]) : null;

            // IMPORT (coluna 4 pode carregar "perc e mínimo" juntos)
            [$perc_import_auto, $valor_import_auto] = isset($dados[3]) ? $parsePercMin($dados[3]) : [null, null];
            $perc_import      = $perc_import_auto;
            $valor_import     = $valor_import_auto;

            // Se vierem separados (coluna 5 como mínimo), usa o explícito
            if (isset($dados[4]) && trim($dados[4]) !== '') {
                $valor_import = $toFloat($dados[4]);
            }
            // Se coluna 4 era só o percentual (sem mínimo), garante parse
            if ($perc_import === null && isset($dados[3])) {
                $perc_import = $toFloat($dados[3]);
            }

            // PERSIANAS (mesma lógica das duas colunas)
            [$perc_pers_auto, $valor_pers_auto] = isset($dados[5]) ? $parsePercMin($dados[5]) : [null, null];
            $perc_persianas   = $perc_pers_auto;
            $valor_persianas  = $valor_pers_auto;
            if (isset($dados[6]) && trim($dados[6]) !== '') {
                $valor_persianas = $toFloat($dados[6]);
            }
            if ($perc_persianas === null && isset($dados[5])) {
                $perc_persianas = $toFloat($dados[5]);
            }

            $valor_cortina     = isset($dados[7])  ? $toFloat($dados[7])  : null;
            $valor_mostruario  = isset($dados[8])  ? $toFloat($dados[8])  : null;
            $valor_prosp_reat = isset($dados[9])  ? $toFloat($dados[9])  : null;

            // --------- Busca do representante por fantasia (case/accents friendly) ----------
            $nome = preg_replace('/[\x{FEFF}\x{200B}]/u', '', $fantasia);
            $nome = mb_convert_encoding($nome, 'UTF-8', 'UTF-8');

            $repres = ApRepresentante::where('fantasia', 'ILIKE', $nome)->first();

            if (!$repres) {
                $erros[] = "Linha {$linha}: Representante '{$fantasia}' não encontrado.";
                continue;
            }

            // --------- Persiste MetaRepres ----------
            $meta = new MetaRepres;
            $meta->meta_id            = $param['meta_id'];
            $meta->repres_id          = $repres->id;

            $meta->valor_meta         = $valor_meta;
            $meta->valor_super_meta   = $valor_super_meta;

            $meta->perc_import        = $perc_import;
            $meta->valor_import       = $valor_import;

            $meta->perc_persianas     = $perc_persianas;
            $meta->valor_persianas    = $valor_persianas;

            $meta->valor_cortina      = $valor_cortina;
            $meta->valor_mostruario   = $valor_mostruario;
            $meta->valor_prosp_reat   = $valor_prosp_reat;

            $meta->store();
            $count++;
        }

        TTransaction::close();
        fclose($handle);

        TWindow::closeWindow(parent::getId());

        TToast::show("success", "$count consultores foram importados.", "topRight", "");

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onAction($param = null) 
    {
        try 
        {
            //code here

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

                $object = new MetaRepres($key); // instantiates the Active Record 

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

