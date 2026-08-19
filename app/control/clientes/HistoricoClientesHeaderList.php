<?php

class HistoricoClientesHeaderList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'integrador';
    private static $activeRecord = 'HistoricoCliRepres';
    private static $primaryKey = 'id';
    private static $formName = 'formList_HistoricoCliRepres';
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

        $criteria_grupo_descricao = new TCriteria();

        $filterVar = TSession::getValue("userunitid");
        $criteria_grupo_descricao->add(new TFilter('system_unit_id', '=', $filterVar)); 

        // Código gerado pelo snippet: "Recarregar combo através de filtros" 
        TTransaction::open(self::$database);
        $criteria = new TCriteria();
        $criteria->add(new TFilter('system_unit_id', '=', TSession::getValue('userunitid')));
        $metas = HistoricoCliRepres::where('system_unit_id', '=', TSession::getValue('userunitid'))->orderby('id')->load();
        $array = array();
        $array[''] = "";
        foreach($metas as $meta){
            switch ($meta->mes) {
                case 1: $mes = "Janeiro"; break;
                case 2: $mes = "Fevereiro"; break;
                case 3: $mes = "Março"; break;
                case 4: $mes = "Abril"; break;
                case 5: $mes = "Maio"; break;
                case 6: $mes = "Junho"; break;
                case 7: $mes = "Julho"; break;
                case 8: $mes = "Agosto"; break;
                case 9: $mes = "Setembro"; break;
                case 10: $mes = "Outubro"; break;
                case 11: $mes = "Novembro"; break;
                case 12: $mes = "Dezembro"; break;
                default: $mes = ""; break;
            }
            $array[$meta->mes."_".$meta->ano] = "$mes/$meta->ano";
        }

        $array = array_reverse($array);

        TTransaction::close();

        $mes_ano = new TCombo('mes_ano');
        $cod_clifor = new TEntry('cod_clifor');
        $razao_clifor = new TEntry('razao_clifor');
        $cod_estado = new TCombo('cod_estado');
        $rota = new TEntry('rota');
        $grupo_descricao = new TDBCombo('grupo_descricao', 'integrador', 'ApGrupoCliente', 'id', '{descricao}','descricao asc' , $criteria_grupo_descricao );
        $repres_razao = new TEntry('repres_razao');
        $dt_cadastro = new TDate('dt_cadastro');
        $ativo = new TCombo('ativo');
        $tipo = new TCombo('tipo');
        $prospeccao = new TCombo('prospeccao');
        $cod_principal = new TEntry('cod_principal');

        $cod_clifor->exitOnEnter();
        $razao_clifor->exitOnEnter();
        $rota->exitOnEnter();
        $repres_razao->exitOnEnter();
        $cod_principal->exitOnEnter();

        $cod_clifor->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $razao_clifor->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $rota->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $repres_razao->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $dt_cadastro->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $cod_principal->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $mes_ano->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $cod_estado->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $grupo_descricao->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $ativo->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $tipo->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $prospeccao->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $dt_cadastro->setMask('dd/mm/yyyy');
        $dt_cadastro->setDatabaseMask('yyyy-mm-dd');
        $razao_clifor->forceUpperCase();
        $repres_razao->forceUpperCase();

        $mes_ano->addItems($array);
        $ativo->addItems(["S"=>"Sim","N"=>"Não"]);
        $prospeccao->addItems(["S"=>"Sim","N"=>"Não"]);
        $tipo->addItems(["Normal"=>"Normal","Novo"=>"Novo","Reativado"=>"Reativado"]);
        $cod_estado->addItems(["AC"=>"Acre","AL"=>"Alagoas","AP"=>"Amapá","AM"=>"Amazonas","BA"=>"Bahia","CE"=>"Ceará","ES"=>"Espírito Santo","GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RS"=>"Rio Grande do Sul","RO"=>"Rondônia","RR"=>"Roraima","SC"=>"Santa Catarina","SP"=>"São Paulo","SE"=>"Sergipe","TO"=>"Tocantins","DF"=>"Distrito Federal"]);

        $tipo->enableSearch();
        $ativo->enableSearch();
        $mes_ano->enableSearch();
        $cod_estado->enableSearch();
        $prospeccao->enableSearch();
        $grupo_descricao->enableSearch();

        $rota->setSize('100%');
        $tipo->setSize('100%');
        $ativo->setSize('100%');
        $mes_ano->setSize('100%');
        $cod_clifor->setSize('100%');
        $cod_estado->setSize('100%');
        $prospeccao->setSize('100%');
        $dt_cadastro->setSize('100%');
        $razao_clifor->setSize('100%');
        $repres_razao->setSize('100%');
        $cod_principal->setSize('100%');
        $grupo_descricao->setSize('100%');

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

        $column_mes_transformed = new TDataGridColumn('mes', "Mês", 'left');
        $column_cod_clifor = new TDataGridColumn('cod_clifor', "Código", 'left');
        $column_razao_clifor = new TDataGridColumn('razao_clifor', "Cliente", 'left');
        $column_cod_estado = new TDataGridColumn('cod_estado', "Estado", 'left');
        $column_rota = new TDataGridColumn('rota', "Rota", 'left');
        $column_grupo_descricao = new TDataGridColumn('grupo->descricao', "Grupo", 'left');
        $column_repres_razao = new TDataGridColumn('repres->razao', "Consultor(a)", 'left');
        $column_dt_cadastro_transformed = new TDataGridColumn('dt_cadastro', "Cadastro", 'left');
        $column_ativo_transformed = new TDataGridColumn('ativo', "Ativo", 'left');
        $column_tipo = new TDataGridColumn('tipo', "Tipo", 'left');
        $column_prospeccao_transformed = new TDataGridColumn('prospeccao', "Prospecção", 'left');
        $column_cod_principal = new TDataGridColumn('cod_principal', "Principal", 'left');

        $column_mes_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            switch ((string)$value) {
                case '01': $retorno = "Janeiro"; break;
                case '02': $retorno = "Fevereiro"; break;
                case '03': $retorno = "Março"; break;
                case '04': $retorno = "Abril"; break;
                case '05': $retorno = "Maio"; break;
                case '06': $retorno = "Junho"; break;
                case '07': $retorno = "Julho"; break;
                case '08': $retorno = "Agosto"; break;
                case '09': $retorno = "Setembro"; break;
                case '10': $retorno = "Outubro"; break;
                case '11': $retorno = "Novembro"; break;
                case '12': $retorno = "Dezembro"; break;
                default: $retorno = ""; break;
            }
            return $retorno."/".$object->ano;

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

        $column_prospeccao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $order_mes_transformed = new TAction(array($this, 'onReload'));
        $order_mes_transformed->setParameter('order', 'mes');
        $column_mes_transformed->setAction($order_mes_transformed);
        $order_cod_clifor = new TAction(array($this, 'onReload'));
        $order_cod_clifor->setParameter('order', 'cod_clifor');
        $column_cod_clifor->setAction($order_cod_clifor);
        $order_razao_clifor = new TAction(array($this, 'onReload'));
        $order_razao_clifor->setParameter('order', 'razao_clifor');
        $column_razao_clifor->setAction($order_razao_clifor);
        $order_cod_estado = new TAction(array($this, 'onReload'));
        $order_cod_estado->setParameter('order', 'cod_estado');
        $column_cod_estado->setAction($order_cod_estado);
        $order_dt_cadastro_transformed = new TAction(array($this, 'onReload'));
        $order_dt_cadastro_transformed->setParameter('order', 'dt_cadastro');
        $column_dt_cadastro_transformed->setAction($order_dt_cadastro_transformed);
        $order_ativo_transformed = new TAction(array($this, 'onReload'));
        $order_ativo_transformed->setParameter('order', 'ativo');
        $column_ativo_transformed->setAction($order_ativo_transformed);
        $order_tipo = new TAction(array($this, 'onReload'));
        $order_tipo->setParameter('order', 'tipo');
        $column_tipo->setAction($order_tipo);
        $order_prospeccao_transformed = new TAction(array($this, 'onReload'));
        $order_prospeccao_transformed->setParameter('order', 'prospeccao');
        $column_prospeccao_transformed->setAction($order_prospeccao_transformed);
        $order_cod_principal = new TAction(array($this, 'onReload'));
        $order_cod_principal->setParameter('order', 'cod_principal');
        $column_cod_principal->setAction($order_cod_principal);

        $this->datagrid->addColumn($column_mes_transformed);
        $this->datagrid->addColumn($column_cod_clifor);
        $this->datagrid->addColumn($column_razao_clifor);
        $this->datagrid->addColumn($column_cod_estado);
        $this->datagrid->addColumn($column_rota);
        $this->datagrid->addColumn($column_grupo_descricao);
        $this->datagrid->addColumn($column_repres_razao);
        $this->datagrid->addColumn($column_dt_cadastro_transformed);
        $this->datagrid->addColumn($column_ativo_transformed);
        $this->datagrid->addColumn($column_tipo);
        $this->datagrid->addColumn($column_prospeccao_transformed);
        $this->datagrid->addColumn($column_cod_principal);

        // create the datagrid model
        $this->datagrid->createModel();

        $tr = new TElement('tr');
        $tr->id = 'datagrid-header-filter-row';
        $this->datagrid->prependRow($tr);

        $td_mes_ano = TElement::tag('td', $mes_ano);
        $tr->add($td_mes_ano);
        $td_cod_clifor = TElement::tag('td', $cod_clifor);
        $tr->add($td_cod_clifor);
        $td_razao_clifor = TElement::tag('td', $razao_clifor);
        $tr->add($td_razao_clifor);
        $td_cod_estado = TElement::tag('td', $cod_estado);
        $tr->add($td_cod_estado);
        $td_rota = TElement::tag('td', $rota);
        $tr->add($td_rota);
        $td_grupo_descricao = TElement::tag('td', $grupo_descricao);
        $tr->add($td_grupo_descricao);
        $td_repres_razao = TElement::tag('td', $repres_razao);
        $tr->add($td_repres_razao);
        $td_dt_cadastro = TElement::tag('td', $dt_cadastro);
        $tr->add($td_dt_cadastro);
        $td_ativo = TElement::tag('td', $ativo);
        $tr->add($td_ativo);
        $td_tipo = TElement::tag('td', $tipo);
        $tr->add($td_tipo);
        $td_prospeccao = TElement::tag('td', $prospeccao);
        $tr->add($td_prospeccao);
        $td_cod_principal = TElement::tag('td', $cod_principal);
        $tr->add($td_cod_principal);

        $this->datagrid_form->addField($mes_ano);
        $this->datagrid_form->addField($cod_clifor);
        $this->datagrid_form->addField($razao_clifor);
        $this->datagrid_form->addField($cod_estado);
        $this->datagrid_form->addField($rota);
        $this->datagrid_form->addField($grupo_descricao);
        $this->datagrid_form->addField($repres_razao);
        $this->datagrid_form->addField($dt_cadastro);
        $this->datagrid_form->addField($ativo);
        $this->datagrid_form->addField($tipo);
        $this->datagrid_form->addField($prospeccao);
        $this->datagrid_form->addField($cod_principal);

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

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['HistoricoClientesHeaderList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['HistoricoClientesHeaderList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $btnSincronizar = new TButton('button_btnSincronizar');
        $btnSincronizar->setAction(new TAction(['HistoricoClientesHeaderList', 'onSincronizar']), "Sincronizar");
        $btnSincronizar->addStyleClass('btn-default');
        $btnSincronizar->setImage('fas:sync-alt #000000');

        $this->datagrid_form->addField($btnSincronizar);

        $button_complementares = new TButton('button_button_complementares');
        $button_complementares->setAction(new TAction(['HistoricoClientesHeaderList', 'onSyncComplementares']), "Complementares");
        $button_complementares->addStyleClass('btn-default');
        $button_complementares->setImage('fas:sync-alt #000000');

        $this->datagrid_form->addField($button_complementares);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['HistoricoClientesHeaderList', 'onExportCsv'],['static' => 1]), self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['HistoricoClientesHeaderList', 'onExportXls'],['static' => 1]), self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['HistoricoClientesHeaderList', 'onExportPdf'],['static' => 1]), self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['HistoricoClientesHeaderList', 'onExportXml'],['static' => 1]), self::$formName, 'far:file-code #95a5a6' );

        $head_left_actions->add($btnSincronizar);
        $head_left_actions->add($button_complementares);

        $head_right_actions->add($button_limpar_filtros);
        $head_right_actions->add($button_atualizar);
        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Clientes","Histórico de Clientes"]));
        }

        $container->add($panel);

        parent::add($container);

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
                                $value = strip_tags(call_user_func($transformer, $value, $object, null));
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
            HistoricoClienteService::registrarHistoricoCliente();
            $this->onReload();

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onSyncComplementares($param = null) 
    {
        try 
        {
            if(TSession::getValue('userid') == 1){
                HistoricoClienteService::marcarPertencentesGrupo();
                HistoricoClienteService::verificarProspeccaoGrupo();
                HistoricoClienteService::vincularPrincipalFiliais();
            }
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

        if (isset($data->dt_cadastro) AND ( (is_scalar($data->dt_cadastro) AND $data->dt_cadastro !== '') OR (is_array($data->dt_cadastro) AND (!empty($data->dt_cadastro)) )) )
        {$data->dt_cadastro .= " 00:00";} 

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->cod_clifor) AND ( (is_scalar($data->cod_clifor) AND $data->cod_clifor !== '') OR (is_array($data->cod_clifor) AND (!empty($data->cod_clifor)) )) )
        {

            $filters[] = new TFilter('cod_clifor', 'like', "%{$data->cod_clifor}%");// create the filter 
        }

        if (isset($data->razao_clifor) AND ( (is_scalar($data->razao_clifor) AND $data->razao_clifor !== '') OR (is_array($data->razao_clifor) AND (!empty($data->razao_clifor)) )) )
        {

            $filters[] = new TFilter('razao_clifor', 'like', "%{$data->razao_clifor}%");// create the filter 
        }

        if (isset($data->cod_estado) AND ( (is_scalar($data->cod_estado) AND $data->cod_estado !== '') OR (is_array($data->cod_estado) AND (!empty($data->cod_estado)) )) )
        {

            $filters[] = new TFilter('cod_estado', '=', $data->cod_estado);// create the filter 
        }

        if (isset($data->rota) AND ( (is_scalar($data->rota) AND $data->rota !== '') OR (is_array($data->rota) AND (!empty($data->rota)) )) )
        {

            $filters[] = new TFilter('rota', 'ilike', "%{$data->rota}%");// create the filter 
        }

        if (isset($data->grupo_descricao) AND ( (is_scalar($data->grupo_descricao) AND $data->grupo_descricao !== '') OR (is_array($data->grupo_descricao) AND (!empty($data->grupo_descricao)) )) )
        {

            $filters[] = new TFilter('grupo_id', '=', $data->grupo_descricao);// create the filter 
        }

        if (isset($data->repres_razao) AND ( (is_scalar($data->repres_razao) AND $data->repres_razao !== '') OR (is_array($data->repres_razao) AND (!empty($data->repres_razao)) )) )
        {

            $filters[] = new TFilter('repres_id', 'in', "(SELECT id FROM ap_representante WHERE razao like '%{$data->repres_razao}%')");// create the filter 
        }

        if (isset($data->dt_cadastro) AND ( (is_scalar($data->dt_cadastro) AND $data->dt_cadastro !== '') OR (is_array($data->dt_cadastro) AND (!empty($data->dt_cadastro)) )) )
        {

            $filters[] = new TFilter('dt_cadastro', '=', $data->dt_cadastro);// create the filter 
        }

        if (isset($data->ativo) AND ( (is_scalar($data->ativo) AND $data->ativo !== '') OR (is_array($data->ativo) AND (!empty($data->ativo)) )) )
        {

            $filters[] = new TFilter('ativo', '=', $data->ativo);// create the filter 
        }

        if (isset($data->tipo) AND ( (is_scalar($data->tipo) AND $data->tipo !== '') OR (is_array($data->tipo) AND (!empty($data->tipo)) )) )
        {

            $filters[] = new TFilter('tipo', 'like', "%{$data->tipo}%");// create the filter 
        }

        if (isset($data->prospeccao) AND ( (is_scalar($data->prospeccao) AND $data->prospeccao !== '') OR (is_array($data->prospeccao) AND (!empty($data->prospeccao)) )) )
        {

            $filters[] = new TFilter('prospeccao', '=', $data->prospeccao);// create the filter 
        }

        if (isset($data->cod_principal) AND ( (is_scalar($data->cod_principal) AND $data->cod_principal !== '') OR (is_array($data->cod_principal) AND (!empty($data->cod_principal)) )) )
        {

            $filters[] = new TFilter('cod_principal', 'ilike', "%{$data->cod_principal}%");// create the filter 
        }

        if (isset($data->mes_ano) && !empty($data->mes_ano))
        {
            $mesAno = explode("_", $data->mes_ano);
            $filters[] = new TFilter('mes', '=', $mesAno[0]);// create the filter 
            $filters[] = new TFilter('ano', '=', $mesAno[1]);// create the filter 
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

            // creates a repository for HistoricoCliRepres
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

        $object = new HistoricoCliRepres($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

