<?php

class SolicitacaoProdutoHeaderList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'integrador';
    private static $activeRecord = 'AguardoProduto';
    private static $primaryKey = 'id';
    private static $formName = 'formList_AguardoProduto';
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

        $criteria_repres_id = new TCriteria();

        $repres_id = new TDBCombo('repres_id', 'integrador', 'ApRepresentante', 'id', '{cod_repres}  - {razao}','razao asc' , $criteria_repres_id );
        $ap_item_codigo = new TEntry('ap_item_codigo');
        $ap_item_descricao = new TEntry('ap_item_descricao');
        $quantidade = new TEntry('quantidade');
        $status = new TCombo('status');

        $ap_item_codigo->exitOnEnter();
        $ap_item_descricao->exitOnEnter();
        $quantidade->exitOnEnter();

        $ap_item_codigo->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $ap_item_descricao->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $quantidade->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $repres_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $status->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $status->addItems(["A"=>"Não Recebido","F"=>"Recebido"]);
        $status->enableSearch();
        $repres_id->enableSearch();

        $status->setSize('100%');
        $repres_id->setSize('100%');
        $quantidade->setSize('100%');
        $ap_item_codigo->setSize('100%');
        $ap_item_descricao->setSize('100%');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm(self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_repres_razao = new TDataGridColumn('repres->razao', "Consultor(a)", 'left');
        $column_ap_item_codigo = new TDataGridColumn('ap_item->codigo', "Código", 'left');
        $column_ap_item_descricao = new TDataGridColumn('ap_item->descricao', "Descrição", 'left');
        $column_quantidade = new TDataGridColumn('quantidade', "Quantidade", 'left');
        $column_status_transformed = new TDataGridColumn('status', "Status", 'left');

        $column_status_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            if($value == 'A'){
                return "Não Recebido";
            }else{
                return "Recebido";
            }

        });        

        $this->datagrid->addColumn($column_repres_razao);
        $this->datagrid->addColumn($column_ap_item_codigo);
        $this->datagrid->addColumn($column_ap_item_descricao);
        $this->datagrid->addColumn($column_quantidade);
        $this->datagrid->addColumn($column_status_transformed);

        $action_onEdit = new TDataGridAction(array('SolicitacaoProdutoForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onAtivarFechar = new TDataGridAction(array('SolicitacaoProdutoHeaderList', 'onAtivarFechar'));
        $action_onAtivarFechar->setUseButton(false);
        $action_onAtivarFechar->setButtonClass('btn btn-default btn-sm');
        $action_onAtivarFechar->setLabel("Ativar/Fechar");
        $action_onAtivarFechar->setImage('fas:exchange-alt #000000');
        $action_onAtivarFechar->setField(self::$primaryKey);

        $action_onAtivarFechar->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onAtivarFechar);

        // create the datagrid model
        $this->datagrid->createModel();

        $tr = new TElement('tr');
        $tr->id = 'datagrid-header-filter-row';
        $this->datagrid->prependRow($tr);

        if(!$action_onEdit->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        if(!$action_onAtivarFechar->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        $td_repres_id = TElement::tag('td', $repres_id);
        $tr->add($td_repres_id);
        $td_ap_item_codigo = TElement::tag('td', $ap_item_codigo);
        $tr->add($td_ap_item_codigo);
        $td_ap_item_descricao = TElement::tag('td', $ap_item_descricao);
        $tr->add($td_ap_item_descricao);
        $td_quantidade = TElement::tag('td', $quantidade);
        $tr->add($td_quantidade);
        $td_status = TElement::tag('td', $status);
        $tr->add($td_status);

        $this->datagrid_form->addField($repres_id);
        $this->datagrid_form->addField($ap_item_codigo);
        $this->datagrid_form->addField($ap_item_descricao);
        $this->datagrid_form->addField($quantidade);
        $this->datagrid_form->addField($status);

        $this->datagrid_form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Listagem de Solicitações de Produtos");
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
        $button_cadastrar->setAction(new TAction(['SolicitacaoProdutoForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['SolicitacaoProdutoHeaderList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['SolicitacaoProdutoHeaderList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['SolicitacaoProdutoHeaderList', 'onExportCsv'],['static' => 1]), self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['SolicitacaoProdutoHeaderList', 'onExportXls'],['static' => 1]), self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['SolicitacaoProdutoHeaderList', 'onExportPdf'],['static' => 1]), self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['SolicitacaoProdutoHeaderList', 'onExportXml'],['static' => 1]), self::$formName, 'far:file-code #95a5a6' );

        $head_left_actions->add($button_cadastrar);
        $head_left_actions->add($button_limpar_filtros);
        $head_left_actions->add($button_atualizar);

        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Comercial","Solicitação de Produtos"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public function onAtivarFechar($param = null) 
    {
        try 
        {
            // get the paramseter $key
            $key = $param['key'];
            // open a transaction with database
            TTransaction::open(self::$database);
            $object = new AguardoProduto($key, FALSE); 

            if($object->status=='A'){
                $object->status = 'F';
            }else{
                $object->status = 'A';
            }

            $object->store();

            TTransaction::close();

            $this->onReload([]);

            //</autoCode>
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
    public function onClearFilters($param = null) 
    {
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }
    public function onRefresh($param = null) 
    {
        $this->onReload([]);
    }

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        // get the search form data
        $data = $this->datagrid_form->getData();
        $filters = [];

        if (isset($data->ap_item_codigo) AND ( (is_scalar($data->ap_item_codigo) AND $data->ap_item_codigo !== '') OR (is_array($data->ap_item_codigo) AND (!empty($data->ap_item_codigo)) )) )
        {
            $ap_item_codigo = $data->ap_item_codigo;
            $data->ap_item_codigo = str_replace(' ','%',TratamentosService::removerAcentos($data->ap_item_codigo));

        }
        if (isset($data->ap_item_descricao) AND ( (is_scalar($data->ap_item_descricao) AND $data->ap_item_descricao !== '') OR (is_array($data->ap_item_descricao) AND (!empty($data->ap_item_descricao)) )) )
        {
            $ap_item_descricao = $data->ap_item_descricao;
            $data->ap_item_descricao = str_replace(' ','%',TratamentosService::removerAcentos($data->ap_item_descricao));
        } 

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->repres_id) AND ( (is_scalar($data->repres_id) AND $data->repres_id !== '') OR (is_array($data->repres_id) AND (!empty($data->repres_id)) )) )
        {

            $filters[] = new TFilter('repres_id', '=', $data->repres_id);// create the filter 
        }

        if (isset($data->ap_item_codigo) AND ( (is_scalar($data->ap_item_codigo) AND $data->ap_item_codigo !== '') OR (is_array($data->ap_item_codigo) AND (!empty($data->ap_item_codigo)) )) )
        {

            $filters[] = new TFilter('cod_clifor', '!=', $data->ap_item_codigo);// create the filter 
        }

        if (isset($data->ap_item_descricao) AND ( (is_scalar($data->ap_item_descricao) AND $data->ap_item_descricao !== '') OR (is_array($data->ap_item_descricao) AND (!empty($data->ap_item_descricao)) )) )
        {

            $filters[] = new TFilter('cod_clifor', '!=', $data->ap_item_descricao);// create the filter 
        }

        if (isset($data->quantidade) AND ( (is_scalar($data->quantidade) AND $data->quantidade !== '') OR (is_array($data->quantidade) AND (!empty($data->quantidade)) )) )
        {

            $filters[] = new TFilter('quantidade', '=', $data->quantidade);// create the filter 
        }

        if (isset($data->ap_item_codigo) AND ( (is_scalar($data->ap_item_codigo) AND $data->ap_item_codigo !== '') OR (is_array($data->ap_item_codigo) AND (!empty($data->ap_item_codigo)) )) )
        {

            $filters[] = new TFilter('ap_item_id', 'in', "(SELECT id FROM ap_item WHERE unaccent(codigo) ilike '%{$data->ap_item_codigo}%')");// create the filter 
        }

        if (isset($data->ap_item_descricao) AND ( (is_scalar($data->ap_item_descricao) AND $data->ap_item_descricao !== '') OR (is_array($data->ap_item_descricao) AND (!empty($data->ap_item_descricao)) )) )
        {

            $filters[] = new TFilter('ap_item_id', 'in', "(SELECT id FROM ap_item WHERE unaccent(descricao) ilike '%{$data->ap_item_descricao}%')");// create the filter 
        }

        if (isset($data->ap_item_codigo) AND ( (is_scalar($data->ap_item_codigo) AND $data->ap_item_codigo !== '') OR (is_array($data->ap_item_codigo) AND (!empty($data->ap_item_codigo)) )) )
        {
            $data->ap_item_codigo = $ap_item_codigo;

        }
        if (isset($data->ap_item_descricao) AND ( (is_scalar($data->ap_item_descricao) AND $data->ap_item_descricao !== '') OR (is_array($data->ap_item_descricao) AND (!empty($data->ap_item_descricao)) )) )
        {
            $data->ap_item_descricao = $ap_item_descricao;
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

            // creates a repository for AguardoProduto
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

            $repres = ApRepresentante::where('system_users_id','=',TSession::getValue('userid'))->first();
            if($repres){
                $criteria->add(new TFilter('repres_id', '=', $repres->id));
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

        $object = new AguardoProduto($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

