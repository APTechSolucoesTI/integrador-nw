<?php

class MetaImportHeaderList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'integrador';
    private static $activeRecord = 'MetaImport';
    private static $primaryKey = 'id';
    private static $formName = 'formList_MetaImport';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters', 'onGlobalSearch'];
    private $limit = 20;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();
        // creates the form

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        $this->limit = 20;

        $system_unit_id = new TEntry('system_unit_id');
        $id = new TEntry('id');
        $descricao = new TEntry('descricao');
        $data_inicial = new TDate('data_inicial');
        $data_final = new TDate('data_final');
        $status = new TCombo('status');
        $painel = new TEntry('painel');

        $system_unit_id->exitOnEnter();
        $id->exitOnEnter();
        $descricao->exitOnEnter();
        $painel->exitOnEnter();

        $system_unit_id->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $id->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $descricao->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $data_inicial->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $data_final->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $painel->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $status->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $status->addItems(["0"=>"Aguardando","1"=>"Aberto","2"=>"Fechado","3"=>"Cancelado"]);
        $status->enableSearch();
        $data_final->setMask('dd/mm/yyyy');
        $data_inicial->setMask('dd/mm/yyyy');

        $data_final->setDatabaseMask('yyyy-mm-dd');
        $data_inicial->setDatabaseMask('yyyy-mm-dd');

        $id->setSize('100%');
        $status->setSize('100%');
        $painel->setSize('100%');
        $data_final->setSize(110);
        $descricao->setSize('100%');
        $data_inicial->setSize(110);
        $system_unit_id->setSize('100%');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm(self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_system_unit_id = new TDataGridColumn('system_unit_id', "System unit id", 'left');
        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');
        $column_descricao = new TDataGridColumn('descricao', "Descrição", 'left');
        $column_data_inicial = new TDataGridColumn('data_inicial', "Data inicial", 'left');
        $column_data_final = new TDataGridColumn('data_final', "Data final", 'left');
        $column_status_transformed = new TDataGridColumn('status', "Status", 'left');
        $column_painel = new TDataGridColumn('painel', "Painel", 'left');

        $column_status_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            switch($value){
                case 0: return "Aguardando"; break;
                case 1: return "Aberto"; break;
                case 2: return "Fechado"; break;
                case 3: return "Cancelado"; break;
                default: return ""; break;
            }

        });        

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $column_system_unit_id->hide();
        $column_id->hide();

        $this->datagrid->addColumn($column_system_unit_id);
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_data_inicial);
        $this->datagrid->addColumn($column_data_final);
        $this->datagrid->addColumn($column_status_transformed);
        $this->datagrid->addColumn($column_painel);

        $action_onEdit = new TDataGridAction(array('MetaImportForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('MetaImportHeaderList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        $action_onFechar = new TDataGridAction(array('MetaImportHeaderList', 'onFechar'));
        $action_onFechar->setUseButton(false);
        $action_onFechar->setButtonClass('btn btn-default btn-sm');
        $action_onFechar->setLabel("Fechar Meta Import");
        $action_onFechar->setImage('fas:check-circle #4CAF50');
        $action_onFechar->setField(self::$primaryKey);
        $action_onFechar->setDisplayCondition('MetaImportHeaderList::canFechar');
        $action_onFechar->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onFechar);

        $action_onSincronizar = new TDataGridAction(array('MetaImportHeaderList', 'onSincronizar'));
        $action_onSincronizar->setUseButton(false);
        $action_onSincronizar->setButtonClass('btn btn-default btn-sm');
        $action_onSincronizar->setLabel("Sincronizar Tabelas de Preço");
        $action_onSincronizar->setImage('fas:sync #4E5DAF');
        $action_onSincronizar->setField(self::$primaryKey);
        $action_onSincronizar->setDisplayCondition('MetaImportHeaderList::canSincronizar');
        $action_onSincronizar->setParameter('meta_import_id', '{id}');

        $this->datagrid->addAction($action_onSincronizar);

        $action_onShow = new TDataGridAction(array('MetaImportApTabelaPrecoSimpleList', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Vincular Tabela de Preço");
        $action_onShow->setImage('fas:external-link-alt #000000');
        $action_onShow->setField(self::$primaryKey);
        $action_onShow->setDisplayCondition('MetaImportHeaderList::canVincular');
        $action_onShow->setParameter('meta_import_id', '{id}');

        $this->datagrid->addAction($action_onShow);

        // create the datagrid model
        $this->datagrid->createModel();

        $tr = new TElement('tr');
        $tr->id = 'datagrid-header-filter-row';
        $this->datagrid->prependRow($tr);

        if(!$action_onEdit->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        if(!$action_onDelete->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        if(!$action_onFechar->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        if(!$action_onSincronizar->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        if(!$action_onShow->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        $td_system_unit_id = TElement::tag('td', $system_unit_id);
        $tr->add($td_system_unit_id);
        $td_id = TElement::tag('td', $id);
        $tr->add($td_id);
        $td_descricao = TElement::tag('td', $descricao);
        $tr->add($td_descricao);
        $td_data_inicial = TElement::tag('td', $data_inicial);
        $tr->add($td_data_inicial);
        $td_data_final = TElement::tag('td', $data_final);
        $tr->add($td_data_final);
        $td_status = TElement::tag('td', $status);
        $tr->add($td_status);
        $td_painel = TElement::tag('td', $painel);
        $tr->add($td_painel);

        $this->datagrid_form->addField($system_unit_id);
        $this->datagrid_form->addField($id);
        $this->datagrid_form->addField($descricao);
        $this->datagrid_form->addField($data_inicial);
        $this->datagrid_form->addField($data_final);
        $this->datagrid_form->addField($status);
        $this->datagrid_form->addField($painel);

        $this->datagrid_form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Listagem de meta import");
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $this->datagrid_form->add($headerActions);
        $panel->add($this->datagrid_form);

        $button_cadastrar = new TButton('button_button_cadastrar');
        $button_cadastrar->setAction(new TAction(['MetaImportForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $button_importar_consultores = new TButton('button_button_importar_consultores');
        $button_importar_consultores->setAction(new TAction(['MetaImportRepresForm', 'onShow']), "Importar Consultores");
        $button_importar_consultores->addStyleClass('btn-default');
        $button_importar_consultores->setImage('fas:file-import #4E5DAF');

        $this->datagrid_form->addField($button_importar_consultores);

        $button_importar_itens = new TButton('button_button_importar_itens');
        $button_importar_itens->setAction(new TAction(['ImportMetaImportItemForm', 'onEdit']), "Importar Itens");
        $button_importar_itens->addStyleClass('btn-default');
        $button_importar_itens->setImage('fas:box #FF9800');

        $this->datagrid_form->addField($button_importar_itens);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['MetaImportHeaderList', 'onExportCsv'],['static' => 1]), self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['MetaImportHeaderList', 'onExportXls'],['static' => 1]), self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['MetaImportHeaderList', 'onExportPdf'],['static' => 1]), self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['MetaImportHeaderList', 'onExportXml'],['static' => 1]), self::$formName, 'far:file-code #95a5a6' );

        $head_left_actions->add($button_cadastrar);
        $head_left_actions->add($button_importar_consultores);
        $head_left_actions->add($button_importar_itens);

        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Metas","Meta Import"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new MetaImport($key, FALSE); 

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                TToast::show('success', AdiantiCoreTranslator::translate('Record deleted'), 'topRight', 'far:check-circle');
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
    }
    public function onFechar($param = null) 
    {
       try 
        {

            MetaImportService::obterMetaImport($param['key']);

            TTransaction::open(self::$database);

            $metaImport = MetaImport::find($param['key']);
            $metaImport->status = 2;
            $metaImport->store();

            TTransaction::close();

            //TApplication::loadPage('MetaImportHeaderList', 'onShow');
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function canFechar($object)
    {
        try 
        {
            if($object->status == 1)
            {
                return true;
            }

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onSincronizar($param = null) 
    {
       try
    {
        if (empty($param['meta_import_id'])) {
            throw new Exception("Meta Import não informado.");
        }

        $meta_import_id = (int) $param['meta_import_id'];

        // ------------------- 1) Integrador: pegar TODOS os itens do quadro -------------------
        TTransaction::open(self::$database);

        $meta = MetaImport::find($meta_import_id);
        if (!$meta) {
            throw new Exception("Meta Import {$meta_import_id} não existe.");
        }

        $itensMeta = MetaImportItem::where('meta_import_id', '=', $meta_import_id)->load();
        if (!$itensMeta || count($itensMeta) === 0) {
            throw new Exception("Não existe item cadastrado (MetaImportItem) para a Meta Import {$meta_import_id}.");
        }

        // monta lista de códigos (i.codigo no NW)
        $codigosItens = [];
        foreach ($itensMeta as $itemMeta) {
            $codigoItem = trim((string) $itemMeta->cod_item);
            if ($codigoItem === '') {
                throw new Exception("Existe item vazio em MetaImportItem para a Meta Import {$meta_import_id}.");
            }
            $codigosItens[] = $codigoItem;
        }

        $codigosItens = array_values(array_unique($codigosItens));

        TTransaction::close();

        // ------------------- 2) NW: buscar cod_tabelapreco ativos para TODOS os itens -------------------
        TTransaction::open('nw');
        $connNw = TTransaction::get();

        // set (chave = cod_tabelapreco) pra não duplicar
        $setTabelasNW = [];

        $sql = "
            SELECT DISTINCT
                ti.cod_tabelapreco
            FROM tabpreco_item ti
            INNER JOIN item i ON (i.cod_item = ti.cod_item)
            WHERE i.codigo = :codigo
              AND ti.dt_validadeinicial <= current_date
              AND ti.dt_validadefinal >= current_date
        ";

        $stmt = $connNw->prepare($sql);

        foreach ($codigosItens as $codigoItem) {
            $stmt->bindValue(':codigo', $codigoItem);
            $stmt->execute();

            $tabelasItem = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if ($tabelasItem) {
                foreach ($tabelasItem as $codTabela) {
                    $codTabela = trim((string) $codTabela);
                    if ($codTabela !== '') {
                        $setTabelasNW[$codTabela] = true; // sem duplicar
                    }
                }
            }
        }

        TTransaction::close();

        $codigosTabelaNW = array_keys($setTabelasNW);

        if (!$codigosTabelaNW || count($codigosTabelaNW) === 0) {
            throw new Exception("Nenhuma tabela de preço ativa encontrada no NW para os itens desse quadro.");
        }

        // ------------------- 3) Integrador: criar tudo e setar S/N -------------------
        TTransaction::open(self::$database);

        $tabelas = ApTabelaPreco::all();

        $inseridos = 0;
        $marcadosS = 0;

        foreach ($tabelas as $tabela)
        {
            $ap_tabela_preco_id = (int) $tabela->id;
            $cod_tabelapreco    = trim((string) $tabela->cod_tabelapreco);

            $fazparte = isset($setTabelasNW[$cod_tabelapreco]) ? 'S' : 'N';

            $registro = MetaImportTabelaPreco::where('meta_import_id', '=', $meta_import_id)
                ->where('ap_tabela_preco_id', '=', $ap_tabela_preco_id)
                ->first();

            if ($registro) {
                $registro->fazparte = $fazparte;
            } else {
                $registro = new MetaImportTabelaPreco;
                $registro->meta_import_id     = $meta_import_id;
                $registro->ap_tabela_preco_id = $ap_tabela_preco_id;
                $registro->fazparte           = $fazparte;
                $inseridos++;
            }

            if ($fazparte === 'S') {
                $marcadosS++;
            }

            $registro->store();
        }

        TTransaction::close();

        TToast::show(
            'success',
            "Foi sincronizado um total de {$marcadosS} Tabelas de Preço.",
            'topRight',
            ''
        );
            //</autoCode>
        }
        catch (Exception $e) 
        {
             if (TTransaction::get()) {
            TTransaction::rollback();
        }
        new TMessage('error', $e->getMessage());  
        }
    }
    public static function canSincronizar($object)
    {

        try 
        {
            if($object->status != 2)
            {
                return true;
            }
            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function canVincular($object)
    {
    try {
            if($object->status != 2)
            {
                return true;
            }

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onExportCsv($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.csv';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $objects = $this->onReload();

                if ($objects)
                {
                    $handler = fopen($output, 'w');
                    TTransaction::open(self::$database);

                    foreach ($objects as $object)
                    {
                        $row = [];
                        foreach ($this->datagrid->getColumns() as $column)
                        {
                            $column_name = $column->getName();

                            if (isset($object->$column_name))
                            {
                                $row[] = is_scalar($object->$column_name) ? $object->$column_name : '';
                            }
                            else if (method_exists($object, 'render'))
                            {
                                $column_name = (strpos((string)$column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $row[] = $object->render($column_name);
                            }
                        }

                        fputcsv($handler, $row);
                    }

                    fclose($handler);
                    TTransaction::close();
                }
                else
                {
                    throw new Exception(_t('No records found'));
                }

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportXls($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.xls';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $widths = [];
                $titles = [];

                foreach ($this->datagrid->getColumns() as $column)
                {
                    $titles[] = $column->getLabel();
                    $width    = 100;

                    if (is_null($column->getWidth()))
                    {
                        $width = 100;
                    }
                    else if (strpos((string)$column->getWidth(), '%') !== false)
                    {
                        $width = ((int) $column->getWidth()) * 5;
                    }
                    else if (is_numeric($column->getWidth()))
                    {
                        $width = $column->getWidth();
                    }

                    $widths[] = $width;
                }

                $table = new \TTableWriterXLS($widths);
                $table->addStyle('title',  'Helvetica', '10', 'B', '#ffffff', '#617FC3');
                $table->addStyle('data',   'Helvetica', '10', '',  '#000000', '#FFFFFF', 'LR');

                $table->addRow();

                foreach ($titles as $title)
                {
                    $table->addCell($title, 'center', 'title');
                }

                $this->limit = 0;
                $objects = $this->onReload();

                TTransaction::open(self::$database);
                if ($objects)
                {
                    foreach ($objects as $object)
                    {
                        $table->addRow();
                        foreach ($this->datagrid->getColumns() as $column)
                        {
                            $column_name = $column->getName();
                            $value = '';
                            if (isset($object->$column_name))
                            {
                                $value = is_scalar($object->$column_name) ? $object->$column_name : '';
                            }
                            else if (method_exists($object, 'render'))
                            {
                                $column_name = (strpos((string)$column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $value = $object->render($column_name);
                            }

                            $transformer = $column->getTransformer();
                            if ($transformer)
                            {
                                $value = strip_tags((string)call_user_func($transformer, $value, $object, null));
                            }

                            $table->addCell($value, 'center', 'data');
                        }
                    }
                }
                $table->save($output);
                TTransaction::close();

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportPdf($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.pdf';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $this->datagrid->prepareForPrinting();
                $this->onReload();

                $html = clone $this->datagrid;
                $contents = file_get_contents('app/resources/styles-print.html') . $html->getContents();

                $dompdf = new \Dompdf\Dompdf;
                $dompdf->loadHtml($contents);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                file_put_contents($output, $dompdf->output());

                $window = TWindow::create('PDF', 0.8, 0.8);
                $object = new TElement('iframe');
                $object->src  = $output;
                $object->type  = 'application/pdf';
                $object->style = "width: 100%; height:calc(100% - 10px)";

                $window->add($object);
                $window->show();
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportXml($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.xml';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $objects = $this->onReload();

                if ($objects)
                {
                    TTransaction::open(self::$database);

                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->{'formatOutput'} = true;
                    $dataset = $dom->appendChild( $dom->createElement('dataset') );

                    foreach ($objects as $object)
                    {
                        $row = $dataset->appendChild( $dom->createElement( self::$activeRecord ) );

                        foreach ($this->datagrid->getColumns() as $column)
                        {
                            $column_name = $column->getName();
                            $column_name_raw = str_replace(['(','{','->', '-','>','}',')', ' '], ['','','_','','','','','_'], $column_name);

                            if (isset($object->$column_name))
                            {
                                $value = is_scalar($object->$column_name) ? $object->$column_name : '';
                                $row->appendChild($dom->createElement($column_name_raw, $value)); 
                            }
                            else if (method_exists($object, 'render'))
                            {
                                $column_name = (strpos((string)$column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $value = $object->render($column_name);
                                $row->appendChild($dom->createElement($column_name_raw, $value));
                            }
                        }
                    }

                    $dom->save($output);

                    TTransaction::close();
                }
                else
                {
                    throw new Exception(_t('No records found'));
                }

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        // get the search form data
        $data = $this->datagrid_form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->system_unit_id) AND ( (is_scalar($data->system_unit_id) AND $data->system_unit_id !== '') OR (is_array($data->system_unit_id) AND (!empty($data->system_unit_id)) )) )
        {

            $filters[] = new TFilter('system_unit_id', '=', $data->system_unit_id);// create the filter 
        }

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->descricao) AND ( (is_scalar($data->descricao) AND $data->descricao !== '') OR (is_array($data->descricao) AND (!empty($data->descricao)) )) )
        {

            $filters[] = new TFilter('descricao', 'like', "%{$data->descricao}%");// create the filter 
        }

        if (isset($data->painel) AND ( (is_scalar($data->painel) AND $data->painel !== '') OR (is_array($data->painel) AND (!empty($data->painel)) )) )
        {

            $filters[] = new TFilter('painel', '=', $data->painel);// create the filter 
        }

        // fill the form with data again
        $this->datagrid_form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        if (isset($param['static']) && ($param['static'] == '1') )
        {
            $class = get_class($this);
            $onReloadParam = ['offset' => 0, 'first_page' => 1, 'target_container' => $param['target_container'] ?? null];
            AdiantiCoreApplication::loadPage($class, 'onReload', $onReloadParam);
            TScript::create('$(".select2").prev().select2("close");');
        }
        else
        {
            $this->onReload(['offset' => 0, 'first_page' => 1]);
        }
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'integrador'
            TTransaction::open(self::$database);

            // creates a repository for MetaImport
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';    
            }
            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $this->limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($this->limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;

            return $objects;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if($openTransaction)
        {
            TTransaction::open(self::$database);    
        }

        $object = new MetaImport($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

