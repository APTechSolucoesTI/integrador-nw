<?php

class TrimestreClienteInicialHeaderList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'integrador';
    private static $activeRecord = 'TrimestreClienteInicial';
    private static $primaryKey = 'id';
    private static $formName = 'formList_TrimestreClienteInicial';
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

        $criteria_meta_trimestral_id = new TCriteria();
        $criteria_grupo_id = new TCriteria();

        $meta_trimestral_id = new TDBCombo('meta_trimestral_id', 'integrador', 'MetaTrimestral', 'id', '{descricao}','descricao asc' , $criteria_meta_trimestral_id );
        $tipo_pessoa = new TCombo('tipo_pessoa');
        $cod_clifor = new TEntry('cod_clifor');
        $razao_social = new TEntry('razao_social');
        $grupo_id = new TDBCombo('grupo_id', 'integrador', 'ApGrupoCliente', 'id', '{id}','id asc' , $criteria_grupo_id );
        $ativo = new TCombo('ativo');
        $reativacao = new TEntry('reativacao');
        $cidade_id = new TEntry('cidade_id');
        $estado_id = new TEntry('estado_id');
        $cod_principal = new TEntry('cod_principal');

        $cod_clifor->exitOnEnter();
        $razao_social->exitOnEnter();
        $reativacao->exitOnEnter();
        $cidade_id->exitOnEnter();
        $estado_id->exitOnEnter();
        $cod_principal->exitOnEnter();

        $cod_clifor->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $razao_social->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $reativacao->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $cidade_id->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $estado_id->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $cod_principal->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $meta_trimestral_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $tipo_pessoa->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $grupo_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $ativo->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $ativo->addItems(["S"=>"Sim","N"=>"Não"]);
        $tipo_pessoa->addItems(["F"=>"Física","J"=>"Jurídica"]);

        $ativo->enableSearch();
        $grupo_id->enableSearch();
        $tipo_pessoa->enableSearch();
        $meta_trimestral_id->enableSearch();

        $ativo->setSize('100%');
        $grupo_id->setSize('100%');
        $cidade_id->setSize('100%');
        $estado_id->setSize('100%');
        $cod_clifor->setSize('100%');
        $reativacao->setSize('100%');
        $tipo_pessoa->setSize('100%');
        $razao_social->setSize('100%');
        $cod_principal->setSize('100%');
        $meta_trimestral_id->setSize('100%');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm(self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid->setGroupColumn('repres_id', " {repres->fantasia} ");
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_meta_trimestral_descricao = new TDataGridColumn('meta_trimestral->descricao', "Trimestre", 'left');
        $column_data_insercao_transformed = new TDataGridColumn('data_insercao', "Data da Inserção", 'left');
        $column_tipo_pessoa_transformed = new TDataGridColumn('tipo_pessoa', "Tipo de Pessoa", 'left');
        $column_cod_clifor = new TDataGridColumn('cod_clifor', "Código", 'left');
        $column_razao_social = new TDataGridColumn('razao_social', "Razão Social", 'left');
        $column_dt_cadastro_transformed = new TDataGridColumn('dt_cadastro', "Data de Cadastro", 'left');
        $column_grupo_descricao = new TDataGridColumn('grupo->descricao', "Grupo", 'left');
        $column_ativo_transformed = new TDataGridColumn('ativo', "Ativo", 'left');
        $column_reativacao = new TDataGridColumn('reativacao', "Reativação", 'left');
        $column_cidade_id = new TDataGridColumn('cidade_id', "Cidade", 'left');
        $column_estado_id = new TDataGridColumn('estado_id', "UF", 'left');
        $column_cod_principal = new TDataGridColumn('cod_principal', "Principal", 'left');

        $column_data_insercao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_tipo_pessoa_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            switch ($value) {
                case 'F':
                    return 'Física';
                    break;
                case 'J':
                    return 'Jurídica';
                    break;
                default:
                    return $value;
                    break;
            }

        });

        $column_dt_cadastro_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_ativo_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if($value === true || $value == 't' || $value === 1 || $value == '1' || $value == 's' || $value == 'S' || $value == 'T')
            {
                return 'Sim';
            }
            elseif($value === false || $value == 'f' || $value === 0 || $value == '0' || $value == 'n' || $value == 'N' || $value == 'F')   
            {
                return 'Não';
            }

            return $value;

        });        

        $this->datagrid->addColumn($column_meta_trimestral_descricao);
        $this->datagrid->addColumn($column_data_insercao_transformed);
        $this->datagrid->addColumn($column_tipo_pessoa_transformed);
        $this->datagrid->addColumn($column_cod_clifor);
        $this->datagrid->addColumn($column_razao_social);
        $this->datagrid->addColumn($column_dt_cadastro_transformed);
        $this->datagrid->addColumn($column_grupo_descricao);
        $this->datagrid->addColumn($column_ativo_transformed);
        $this->datagrid->addColumn($column_reativacao);
        $this->datagrid->addColumn($column_cidade_id);
        $this->datagrid->addColumn($column_estado_id);
        $this->datagrid->addColumn($column_cod_principal);

        // create the datagrid model
        $this->datagrid->createModel();

        $tr = new TElement('tr');
        $tr->id = 'datagrid-header-filter-row';
        $this->datagrid->prependRow($tr);

        $td_meta_trimestral_id = TElement::tag('td', $meta_trimestral_id);
        $tr->add($td_meta_trimestral_id);
        $td_empty = TElement::tag('td', "");
        $tr->add($td_empty);
        $td_tipo_pessoa = TElement::tag('td', $tipo_pessoa);
        $tr->add($td_tipo_pessoa);
        $td_cod_clifor = TElement::tag('td', $cod_clifor);
        $tr->add($td_cod_clifor);
        $td_razao_social = TElement::tag('td', $razao_social);
        $tr->add($td_razao_social);
        $td_empty = TElement::tag('td', "");
        $tr->add($td_empty);
        $td_grupo_id = TElement::tag('td', $grupo_id);
        $tr->add($td_grupo_id);
        $td_ativo = TElement::tag('td', $ativo);
        $tr->add($td_ativo);
        $td_reativacao = TElement::tag('td', $reativacao);
        $tr->add($td_reativacao);
        $td_cidade_id = TElement::tag('td', $cidade_id);
        $tr->add($td_cidade_id);
        $td_estado_id = TElement::tag('td', $estado_id);
        $tr->add($td_estado_id);
        $td_cod_principal = TElement::tag('td', $cod_principal);
        $tr->add($td_cod_principal);

        $this->datagrid_form->addField($meta_trimestral_id);
        $this->datagrid_form->addField($tipo_pessoa);
        $this->datagrid_form->addField($cod_clifor);
        $this->datagrid_form->addField($razao_social);
        $this->datagrid_form->addField($grupo_id);
        $this->datagrid_form->addField($ativo);
        $this->datagrid_form->addField($reativacao);
        $this->datagrid_form->addField($cidade_id);
        $this->datagrid_form->addField($estado_id);
        $this->datagrid_form->addField($cod_principal);

        $this->datagrid_form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Clientes no inicio do trimestre");
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

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['TrimestreClienteInicialHeaderList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['TrimestreClienteInicialHeaderList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['TrimestreClienteInicialHeaderList', 'onExportCsv'],['static' => 1]), self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['TrimestreClienteInicialHeaderList', 'onExportXls'],['static' => 1]), self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['TrimestreClienteInicialHeaderList', 'onExportPdf'],['static' => 1]), self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['TrimestreClienteInicialHeaderList', 'onExportXml'],['static' => 1]), self::$formName, 'far:file-code #95a5a6' );

        $head_left_actions->add($button_atualizar);
        $head_left_actions->add($button_limpar_filtros);

        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Trimestral","Clientes no Trimestre"]));
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
    public function onRefresh($param = null) 
    {
        $this->onReload([]);
    }
    public function onClearFilters($param = null) 
    {
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
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

        if (isset($data->meta_trimestral_id) AND ( (is_scalar($data->meta_trimestral_id) AND $data->meta_trimestral_id !== '') OR (is_array($data->meta_trimestral_id) AND (!empty($data->meta_trimestral_id)) )) )
        {

            $filters[] = new TFilter('meta_trimestral_id', '=', $data->meta_trimestral_id);// create the filter 
        }

        if (isset($data->cod_clifor) AND ( (is_scalar($data->cod_clifor) AND $data->cod_clifor !== '') OR (is_array($data->cod_clifor) AND (!empty($data->cod_clifor)) )) )
        {

            $filters[] = new TFilter('cod_clifor', 'like', "%{$data->cod_clifor}%");// create the filter 
        }

        if (isset($data->razao_social) AND ( (is_scalar($data->razao_social) AND $data->razao_social !== '') OR (is_array($data->razao_social) AND (!empty($data->razao_social)) )) )
        {

            $filters[] = new TFilter('razao_social', 'like', "%{$data->razao_social}%");// create the filter 
        }

        if (isset($data->grupo_id) AND ( (is_scalar($data->grupo_id) AND $data->grupo_id !== '') OR (is_array($data->grupo_id) AND (!empty($data->grupo_id)) )) )
        {

            $filters[] = new TFilter('grupo_id', '=', $data->grupo_id);// create the filter 
        }

        if (isset($data->reativacao) AND ( (is_scalar($data->reativacao) AND $data->reativacao !== '') OR (is_array($data->reativacao) AND (!empty($data->reativacao)) )) )
        {

            $filters[] = new TFilter('reativacao', 'like', "%{$data->reativacao}%");// create the filter 
        }

        if (isset($data->cidade_id) AND ( (is_scalar($data->cidade_id) AND $data->cidade_id !== '') OR (is_array($data->cidade_id) AND (!empty($data->cidade_id)) )) )
        {

            $filters[] = new TFilter('cidade_id', 'like', "%{$data->cidade_id}%");// create the filter 
        }

        if (isset($data->estado_id) AND ( (is_scalar($data->estado_id) AND $data->estado_id !== '') OR (is_array($data->estado_id) AND (!empty($data->estado_id)) )) )
        {

            $filters[] = new TFilter('estado_id', 'like', "%{$data->estado_id}%");// create the filter 
        }

        if (isset($data->cod_principal) AND ( (is_scalar($data->cod_principal) AND $data->cod_principal !== '') OR (is_array($data->cod_principal) AND (!empty($data->cod_principal)) )) )
        {

            $filters[] = new TFilter('cod_principal', 'like', "%{$data->cod_principal}%");// create the filter 
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

            // creates a repository for TrimestreClienteInicial
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'repres_id, id';    
            }
            elseif($param['order'] != 'repres_id, id')
            {
                $param['order'] = "repres_id, id,{$param['order']}"; 
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

        $object = new TrimestreClienteInicial($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

