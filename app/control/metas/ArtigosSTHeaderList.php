<?php

class ArtigosSTHeaderList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'integrador';
    private static $activeRecord = 'MiniMeta';
    private static $primaryKey = 'id';
    private static $formName = 'formList_MiniMeta';
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

        $criteria_mini_meta_tipo_id = new TCriteria();

        // Código gerado pelo snippet: "Recarregar combo através de filtros" 
        TTransaction::open(self::$database);
        $criteria = new TCriteria();
        $criteria->add(new TFilter('system_unit_id', '=', TSession::getValue('userunitid')));
        $miniMetas = MiniMeta::where('system_unit_id', '=', TSession::getValue('userunitid'))->load();
        $array = array();
        $array[''] = "";
        foreach($miniMetas as $miniMeta){
            switch ($miniMeta->mes) {
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
            $array[$miniMeta->mes."_".$miniMeta->ano] = "$mes/$miniMeta->ano";
        }
        TTransaction::close();

        $mes = new TCombo('mes');
        $mini_meta_tipo_id = new TDBCombo('mini_meta_tipo_id', 'integrador', 'MiniMetaTipo', 'id', '{nome}','nome asc' , $criteria_mini_meta_tipo_id );
        $descricao = new TEntry('descricao');
        $status = new TCombo('status');

        $descricao->exitOnEnter();

        $descricao->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $mes->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $mini_meta_tipo_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $status->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $mes->addItems($array);
        $status->addItems(["0"=>"Aguardando","1"=>"Aberto","2"=>"Fechado","3"=>"Cancelado"]);

        $mes->enableSearch();
        $status->enableSearch();
        $mini_meta_tipo_id->enableSearch();

        $mes->setSize('100%');
        $status->setSize('100%');
        $descricao->setSize('100%');
        $mini_meta_tipo_id->setSize('100%');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm(self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_mes_transformed = new TDataGridColumn('mes', "Período", 'left');
        $column_mini_meta_tipo_nome = new TDataGridColumn('mini_meta_tipo->nome', "Tipo", 'left');
        $column_painel = new TDataGridColumn('painel', "Painel", 'left');
        $column_descricao = new TDataGridColumn('descricao', "Descrição", 'left');
        $column_data_inicial_transformed = new TDataGridColumn('data_inicial', "Data inicial", 'left');
        $column_data_final_transformed = new TDataGridColumn('data_final', "Data final", 'left');
        $column_min_transformed = new TDataGridColumn('min', "Mínimo", 'left');
        $column_qtde_transformed = new TDataGridColumn('qtde', "Valor", 'left');
        $column_status_transformed = new TDataGridColumn('status', "Status", 'left');

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

        $column_data_inicial_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_data_final_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_min_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }

        });

        $column_qtde_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $this->datagrid->addColumn($column_mes_transformed);
        $this->datagrid->addColumn($column_mini_meta_tipo_nome);
        $this->datagrid->addColumn($column_painel);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_data_inicial_transformed);
        $this->datagrid->addColumn($column_data_final_transformed);
        $this->datagrid->addColumn($column_min_transformed);
        $this->datagrid->addColumn($column_qtde_transformed);
        $this->datagrid->addColumn($column_status_transformed);

        $action_onEdit = new TDataGridAction(array('ArtigoSTForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('ArtigosSTHeaderList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        $action_onIniciar = new TDataGridAction(array('ArtigosSTHeaderList', 'onIniciar'));
        $action_onIniciar->setUseButton(false);
        $action_onIniciar->setButtonClass('btn btn-default btn-sm');
        $action_onIniciar->setLabel("");
        $action_onIniciar->setImage('fas:play-circle #0000FF');
        $action_onIniciar->setField(self::$primaryKey);
        $action_onIniciar->setDisplayCondition('ArtigosSTHeaderList::canIniciar');

        $this->datagrid->addAction($action_onIniciar);

        $action_onFechar = new TDataGridAction(array('ArtigosSTHeaderList', 'onFechar'));
        $action_onFechar->setUseButton(false);
        $action_onFechar->setButtonClass('btn btn-default btn-sm');
        $action_onFechar->setLabel("");
        $action_onFechar->setImage('fas:check-circle #4CAF50');
        $action_onFechar->setField(self::$primaryKey);
        $action_onFechar->setDisplayCondition('ArtigosSTHeaderList::canFechar');
        $action_onFechar->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onFechar);

        $action_onCancelar = new TDataGridAction(array('ArtigosSTHeaderList', 'onCancelar'));
        $action_onCancelar->setUseButton(false);
        $action_onCancelar->setButtonClass('btn btn-default btn-sm');
        $action_onCancelar->setLabel("");
        $action_onCancelar->setImage('fas:times-circle #FF0000');
        $action_onCancelar->setField(self::$primaryKey);
        $action_onCancelar->setDisplayCondition('ArtigosSTHeaderList::canCancelar');

        $this->datagrid->addAction($action_onCancelar);

        $action_onShow = new TDataGridAction(array('ArtigosSTItemImport', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Importar Itens");
        $action_onShow->setImage('fas:file-import #000000');
        $action_onShow->setField(self::$primaryKey);

        $action_onShow->setParameter('mini_meta_id', '{id}');

        $this->datagrid->addAction($action_onShow);

        $action_STVincularTabelaPrecoWindowList_onShow = new TDataGridAction(array('STVincularTabelaPrecoWindowList', 'onShow'));
        $action_STVincularTabelaPrecoWindowList_onShow->setUseButton(false);
        $action_STVincularTabelaPrecoWindowList_onShow->setButtonClass('btn btn-default btn-sm');
        $action_STVincularTabelaPrecoWindowList_onShow->setLabel("Vincular Tabela de Preço");
        $action_STVincularTabelaPrecoWindowList_onShow->setImage('fas:external-link-alt #000000');
        $action_STVincularTabelaPrecoWindowList_onShow->setField(self::$primaryKey);

        $action_STVincularTabelaPrecoWindowList_onShow->setParameter('mini_meta_id', '{id}');

        $this->datagrid->addAction($action_STVincularTabelaPrecoWindowList_onShow);

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
        if(!$action_onIniciar->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        if(!$action_onFechar->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        if(!$action_onCancelar->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        if(!$action_onShow->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        if(!$action_STVincularTabelaPrecoWindowList_onShow->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        $td_mes = TElement::tag('td', $mes);
        $tr->add($td_mes);
        $td_mini_meta_tipo_id = TElement::tag('td', $mini_meta_tipo_id);
        $tr->add($td_mini_meta_tipo_id);
        $td_empty = TElement::tag('td', "");
        $tr->add($td_empty);
        $td_descricao = TElement::tag('td', $descricao);
        $tr->add($td_descricao);
        $td_empty = TElement::tag('td', "");
        $tr->add($td_empty);
        $td_empty = TElement::tag('td', "");
        $tr->add($td_empty);
        $td_empty = TElement::tag('td', "");
        $tr->add($td_empty);
        $td_empty = TElement::tag('td', "");
        $tr->add($td_empty);
        $td_status = TElement::tag('td', $status);
        $tr->add($td_status);

        $this->datagrid_form->addField($mes);
        $this->datagrid_form->addField($mini_meta_tipo_id);
        $this->datagrid_form->addField($descricao);
        $this->datagrid_form->addField($status);

        $this->datagrid_form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Listagem de Artigos com ST");
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
        $button_cadastrar->setAction(new TAction(['ArtigoSTForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['ArtigosSTHeaderList', 'onExportCsv'],['static' => 1]), self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['ArtigosSTHeaderList', 'onExportXls'],['static' => 1]), self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['ArtigosSTHeaderList', 'onExportPdf'],['static' => 1]), self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['ArtigosSTHeaderList', 'onExportXml'],['static' => 1]), self::$formName, 'far:file-code #95a5a6' );

        $head_left_actions->add($button_cadastrar);

        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Metas","Artigos com ST"]));
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
                $object = new MiniMeta($key, FALSE); 

                // deletes the object from the database
                MiniMetaItem::where('mini_meta_id','=',$key)->delete();
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                TToast::show('success', AdiantiCoreTranslator::translate('Record deleted'), 'topRight', 'far:check-circle');
                TApplication::loadPage('MiniMetaHeaderList', 'onShow');
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
    public function onIniciar($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);

            $miniMeta = MiniMeta::find($param['key']);
            $miniMeta->status = 1;
            $miniMeta->store();

            TTransaction::close();

            TApplication::loadPage('MiniMetaHeaderList', 'onShow');

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function canIniciar($object)
    {
        try 
        {
            $miniMetaIniciada = MiniMeta::where('status','=',1)->where('system_unit_id','=', TSession::getValue('userunitid'))->count();
            if($object->status == 0 && $miniMetaIniciada == 0)
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
    public function onFechar($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);

            $miniMeta = MiniMeta::find($param['key']);
            $miniMeta->status = 2;
            $miniMeta->store();

            TTransaction::close();

            FechamentoMesService::obterST($param['key']);

            TApplication::loadPage('ArtigosSTHeaderList', 'onShow');

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
    public function onCancelar($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);

            $miniMeta = MiniMeta::find($param['key']);
            $miniMeta->status = 3;
            $miniMeta->store();

            TTransaction::close();

            TApplication::loadPage('MiniMetaHeaderList', 'onShow');

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function canCancelar($object)
    {
        try 
        {
            if($object->status != 2 && $object->status != 3)
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

        if (isset($data->mes) AND ( (is_scalar($data->mes) AND $data->mes !== '') OR (is_array($data->mes) AND (!empty($data->mes)) )) )
        {

            $filters[] = new TFilter('id::text', 'not like', $data->mes);// create the filter 
        }

        if (isset($data->mini_meta_tipo_id) AND ( (is_scalar($data->mini_meta_tipo_id) AND $data->mini_meta_tipo_id !== '') OR (is_array($data->mini_meta_tipo_id) AND (!empty($data->mini_meta_tipo_id)) )) )
        {

            $filters[] = new TFilter('mini_meta_tipo_id', '=', $data->mini_meta_tipo_id);// create the filter 
        }

        if (isset($data->descricao) AND ( (is_scalar($data->descricao) AND $data->descricao !== '') OR (is_array($data->descricao) AND (!empty($data->descricao)) )) )
        {

            $filters[] = new TFilter('descricao', 'like', "%{$data->descricao}%");// create the filter 
        }

        if (isset($data->mes) AND ( (is_scalar($data->mes) AND $data->mes !== '') OR (is_array($data->mes) AND (!empty($data->mes)) )) )
        {
            $array = explode('_',$data->mes);
            $filters[] = new TFilter('mes', '=', $array[0]);// create the filter 
            $filters[] = new TFilter('ano', '=', $array[1]);// create the filter 
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

            // creates a repository for MiniMeta
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

        $object = new MiniMeta($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

