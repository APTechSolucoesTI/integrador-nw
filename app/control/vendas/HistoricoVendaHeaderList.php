<?php

class HistoricoVendaHeaderList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'integrador';
    private static $activeRecord = 'HistoricoVenda';
    private static $primaryKey = 'id';
    private static $formName = 'formList_HistoricoVenda';
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

        $criteria_meta_id = new TCriteria();
        $criteria_cidade_id = new TCriteria();
        $criteria_estado_cod_estado = new TCriteria();
        $criteria_grupo_cliente_id = new TCriteria();
        $criteria_repres_id = new TCriteria();

        $filterVar = "3";
        $criteria_meta_id->add(new TFilter('status', '!=', $filterVar)); 
        $filterVar = TSession::getValue("userunitid");
        $criteria_meta_id->add(new TFilter('system_unit_id', '=', $filterVar)); 

        $meta_id = new TDBCombo('meta_id', 'integrador', 'Meta', 'id', '{mes_ano}','id desc' , $criteria_meta_id );
        $data_emissao = new TDate('data_emissao');
        $nro = new TEntry('nro');
        $cod_clifor = new TEntry('cod_clifor');
        $razao = new TEntry('razao');
        $cidade_id = new TDBCombo('cidade_id', 'integrador', 'ApCidade', 'id', '{id}','id asc' , $criteria_cidade_id );
        $estado_cod_estado = new TDBCombo('estado_cod_estado', 'integrador', 'ApEstado', 'id', '{cod_estado}','cod_estado asc' , $criteria_estado_cod_estado );
        $grupo_cliente_id = new TDBCombo('grupo_cliente_id', 'integrador', 'ApGrupoCliente', 'id', '{id}','id asc' , $criteria_grupo_cliente_id );
        $valor_total = new TNumeric('valor_total', '2', ',', '.' );
        $repres_id = new TDBCombo('repres_id', 'integrador', 'ApRepresentante', 'id', '{id}','id asc' , $criteria_repres_id );

        $nro->exitOnEnter();
        $cod_clifor->exitOnEnter();
        $razao->exitOnEnter();
        $valor_total->exitOnEnter();

        $data_emissao->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $nro->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $cod_clifor->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $razao->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $valor_total->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $meta_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $cidade_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $estado_cod_estado->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $grupo_cliente_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $repres_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $data_emissao->setMask('dd/mm/yyyy');
        $data_emissao->setDatabaseMask('yyyy-mm-dd');
        $meta_id->enableSearch();
        $cidade_id->enableSearch();
        $repres_id->enableSearch();
        $grupo_cliente_id->enableSearch();
        $estado_cod_estado->enableSearch();

        $nro->setSize('100%');
        $razao->setSize('100%');
        $meta_id->setSize('100%');
        $cidade_id->setSize('100%');
        $repres_id->setSize('100%');
        $cod_clifor->setSize('100%');
        $valor_total->setSize('100%');
        $data_emissao->setSize('100%');
        $grupo_cliente_id->setSize('100%');
        $estado_cod_estado->setSize('100%');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm(self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $filterVar = TSession::getValue("userunitid");
        $this->filter_criteria->add(new TFilter('system_unit_id', '=', $filterVar));

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_meta_mes_ano = new TDataGridColumn('meta->mes_ano', "Mês", 'left');
        $column_data_emissao_transformed = new TDataGridColumn('data_emissao', "Data de Emissão", 'left');
        $column_nro = new TDataGridColumn('nro', "Número NF", 'left');
        $column_cod_clifor = new TDataGridColumn('cod_clifor', "Código", 'left');
        $column_razao = new TDataGridColumn('razao', "Razão Social", 'left');
        $column_cidade_nome = new TDataGridColumn('cidade->nome', "Cidade", 'left');
        $column_estado_cod_estado = new TDataGridColumn('estado->cod_estado', "UF", 'left');
        $column_grupo_cliente_descricao = new TDataGridColumn('grupo_cliente->descricao', "Categoria", 'left');
        $column_valor_total_transformed = new TDataGridColumn('valor_total', "Valor Total", 'left');
        $column_repres_fantasia = new TDataGridColumn('repres->fantasia', "Consultor(a)", 'left');

        $column_data_emissao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_valor_total_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });        

        $this->datagrid->addColumn($column_meta_mes_ano);
        $this->datagrid->addColumn($column_data_emissao_transformed);
        $this->datagrid->addColumn($column_nro);
        $this->datagrid->addColumn($column_cod_clifor);
        $this->datagrid->addColumn($column_razao);
        $this->datagrid->addColumn($column_cidade_nome);
        $this->datagrid->addColumn($column_estado_cod_estado);
        $this->datagrid->addColumn($column_grupo_cliente_descricao);
        $this->datagrid->addColumn($column_valor_total_transformed);
        $this->datagrid->addColumn($column_repres_fantasia);

        $action_onShow = new TDataGridAction(array('HistoricoVendaFormView', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("");
        $action_onShow->setImage('fas:search-plus #000000');
        $action_onShow->setField(self::$primaryKey);

        $action_onShow->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onShow);

        // create the datagrid model
        $this->datagrid->createModel();

        $tr = new TElement('tr');
        $tr->id = 'datagrid-header-filter-row';
        $this->datagrid->prependRow($tr);

        if(!$action_onShow->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        $td_meta_id = TElement::tag('td', $meta_id);
        $tr->add($td_meta_id);
        $td_data_emissao = TElement::tag('td', $data_emissao);
        $tr->add($td_data_emissao);
        $td_nro = TElement::tag('td', $nro);
        $tr->add($td_nro);
        $td_cod_clifor = TElement::tag('td', $cod_clifor);
        $tr->add($td_cod_clifor);
        $td_razao = TElement::tag('td', $razao);
        $tr->add($td_razao);
        $td_cidade_id = TElement::tag('td', $cidade_id);
        $tr->add($td_cidade_id);
        $td_estado_cod_estado = TElement::tag('td', $estado_cod_estado);
        $tr->add($td_estado_cod_estado);
        $td_grupo_cliente_id = TElement::tag('td', $grupo_cliente_id);
        $tr->add($td_grupo_cliente_id);
        $td_valor_total = TElement::tag('td', $valor_total);
        $tr->add($td_valor_total);
        $td_repres_id = TElement::tag('td', $repres_id);
        $tr->add($td_repres_id);

        $this->datagrid_form->addField($meta_id);
        $this->datagrid_form->addField($data_emissao);
        $this->datagrid_form->addField($nro);
        $this->datagrid_form->addField($cod_clifor);
        $this->datagrid_form->addField($razao);
        $this->datagrid_form->addField($cidade_id);
        $this->datagrid_form->addField($estado_cod_estado);
        $this->datagrid_form->addField($grupo_cliente_id);
        $this->datagrid_form->addField($valor_total);
        $this->datagrid_form->addField($repres_id);

        $this->datagrid_form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $panel->getHeader()->style = ' display:none !important; ';
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

        $button_sincronizar = new TButton('button_button_sincronizar');
        $button_sincronizar->setAction(new TAction(['HistoricoVendaHeaderList', 'onSincronizar']), "Sincronizar");
        $button_sincronizar->addStyleClass('btn-default');
        $button_sincronizar->setImage('fas:sync #000000');

        $this->datagrid_form->addField($button_sincronizar);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['HistoricoVendaHeaderList', 'onExportCsv'],['static' => 1]), self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['HistoricoVendaHeaderList', 'onExportXls'],['static' => 1]), self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['HistoricoVendaHeaderList', 'onExportPdf'],['static' => 1]), self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['HistoricoVendaHeaderList', 'onExportXml'],['static' => 1]), self::$formName, 'far:file-code #95a5a6' );

        $head_left_actions->add($button_sincronizar);

        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Vendas","Histórico de Vendas"]));
        }

        $container->add($panel);

        parent::add($container);

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
    public function onSincronizar($param = null) 
    {
        try 
        {
            HistoricoVendaService::registrarVendas();
            $this->onReload();

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
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

        if (isset($data->data_emissao) AND ( (is_scalar($data->data_emissao) AND $data->data_emissao !== '') OR (is_array($data->data_emissao) AND (!empty($data->data_emissao)) )) )
        {
            $emissao = $data->data_emissao;
            $data->data_emissao .= " 00:00:00";
        } 

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->meta_id) AND ( (is_scalar($data->meta_id) AND $data->meta_id !== '') OR (is_array($data->meta_id) AND (!empty($data->meta_id)) )) )
        {

            $filters[] = new TFilter('meta_id', '=', $data->meta_id);// create the filter 
        }

        if (isset($data->data_emissao) AND ( (is_scalar($data->data_emissao) AND $data->data_emissao !== '') OR (is_array($data->data_emissao) AND (!empty($data->data_emissao)) )) )
        {

            $filters[] = new TFilter('data_emissao', '=', $data->data_emissao);// create the filter 
        }

        if (isset($data->nro) AND ( (is_scalar($data->nro) AND $data->nro !== '') OR (is_array($data->nro) AND (!empty($data->nro)) )) )
        {

            $filters[] = new TFilter('nro', '=', $data->nro);// create the filter 
        }

        if (isset($data->cod_clifor) AND ( (is_scalar($data->cod_clifor) AND $data->cod_clifor !== '') OR (is_array($data->cod_clifor) AND (!empty($data->cod_clifor)) )) )
        {

            $filters[] = new TFilter('cod_clifor', '=', $data->cod_clifor);// create the filter 
        }

        if (isset($data->razao) AND ( (is_scalar($data->razao) AND $data->razao !== '') OR (is_array($data->razao) AND (!empty($data->razao)) )) )
        {

            $filters[] = new TFilter('razao', 'like', "%{$data->razao}%");// create the filter 
        }

        if (isset($data->cidade_id) AND ( (is_scalar($data->cidade_id) AND $data->cidade_id !== '') OR (is_array($data->cidade_id) AND (!empty($data->cidade_id)) )) )
        {

            $filters[] = new TFilter('cidade_id', '=', $data->cidade_id);// create the filter 
        }

        if (isset($data->estado_cod_estado) AND ( (is_scalar($data->estado_cod_estado) AND $data->estado_cod_estado !== '') OR (is_array($data->estado_cod_estado) AND (!empty($data->estado_cod_estado)) )) )
        {

            $filters[] = new TFilter('estado_id', '=', $data->estado_cod_estado);// create the filter 
        }

        if (isset($data->grupo_cliente_id) AND ( (is_scalar($data->grupo_cliente_id) AND $data->grupo_cliente_id !== '') OR (is_array($data->grupo_cliente_id) AND (!empty($data->grupo_cliente_id)) )) )
        {

            $filters[] = new TFilter('grupo_cliente_id', '=', $data->grupo_cliente_id);// create the filter 
        }

        if (isset($data->repres_id) AND ( (is_scalar($data->repres_id) AND $data->repres_id !== '') OR (is_array($data->repres_id) AND (!empty($data->repres_id)) )) )
        {

            $filters[] = new TFilter('repres_id', '=', $data->repres_id);// create the filter 
        }

        if (isset($data->data_emissao) AND ( (is_scalar($data->data_emissao) AND $data->data_emissao !== '') OR (is_array($data->data_emissao) AND (!empty($data->data_emissao)) )) )
        {
            $data->data_emissao = $emissao;
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

            // creates a repository for HistoricoVenda
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'data_emissao';    
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

        $object = new HistoricoVenda($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

