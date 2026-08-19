<?php

class PremioHeaderList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'integrador';
    private static $activeRecord = 'Premio';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Premio';
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

        $criteria_tipo_premio_id = new TCriteria();

        $ano = new TEntry('ano');
        $mes = new TCombo('mes');
        $tipo_premio_id = new TDBCombo('tipo_premio_id', 'integrador', 'TipoPremio', 'id', '{descricao}','id asc' , $criteria_tipo_premio_id );
        $obs = new TEntry('obs');

        $ano->exitOnEnter();
        $obs->exitOnEnter();

        $ano->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $obs->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $mes->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $tipo_premio_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $mes->addItems(["1"=>"Janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro"]);
        $mes->enableSearch();
        $tipo_premio_id->enableSearch();

        $ano->setSize('100%');
        $mes->setSize('100%');
        $obs->setSize('100%');
        $tipo_premio_id->setSize('100%');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm(self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_ano = new TDataGridColumn('ano', "Ano", 'left' , '15%');
        $column_mes_transformed = new TDataGridColumn('mes', "Período", 'left' , '15%');
        $column_tipo_premio_descricao = new TDataGridColumn('tipo_premio->descricao', "Tipo prêmio", 'left');
        $column_obs = new TDataGridColumn('obs', "Observação:", 'left');

        $column_mes_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            switch ($value) {
                case 1:
                    return "Janeiro";
                    break;
                case 2:
                    return "Fevereiro";
                    break;
                case 3:
                    return "Março";
                    break;
                case 4:
                    return "Abril";
                    break;
                case 5:
                    return "Maio";
                    break;
                case 6:
                    return "Junho";
                    break;
                case 7:
                    return "Julho";
                    break;
                case 8:
                    return "Agosto";
                    break;
                case 9:
                    return "Setembro";
                    break;
                case 10:
                    return "Outubro";
                    break;
                case 11:
                    return "Novembro";
                    break;
                case 12:
                    return "Dezembro";
                    break;
                default:
                    return "";
                    break;
            }

        });        

        $this->datagrid->addColumn($column_ano);
        $this->datagrid->addColumn($column_mes_transformed);
        $this->datagrid->addColumn($column_tipo_premio_descricao);
        $this->datagrid->addColumn($column_obs);

        $action_onEdit = new TDataGridAction(array('PremioForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $action_onEdit->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('PremioHeaderList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        $action_onShow = new TDataGridAction(array('RestricaoSubGrupo', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Restrições ");
        $action_onShow->setImage('fas:ban #FF2A2A');
        $action_onShow->setField(self::$primaryKey);
        $action_onShow->setDisplayCondition('PremioHeaderList::onExibirRestricoes');
        $action_onShow->setParameter('premio_id', '{id}');

        $this->datagrid->addAction($action_onShow);

        $action_addObs = new TDataGridAction(array('PremioHeaderList', 'addObs'));
        $action_addObs->setUseButton(false);
        $action_addObs->setButtonClass('btn btn-default btn-sm');
        $action_addObs->setLabel("Adicionar Observação");
        $action_addObs->setImage('fas:plus #303030');
        $action_addObs->setField(self::$primaryKey);

        $action_addObs->setParameter('id', '{id}');

        $this->datagrid->addAction($action_addObs);

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
        if(!$action_onShow->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        if(!$action_addObs->isHidden())
        {
            $tr->add(TElement::tag('td', ''));
        }
        $td_ano = TElement::tag('td', $ano);
        $tr->add($td_ano);
        $td_mes = TElement::tag('td', $mes);
        $tr->add($td_mes);
        $td_tipo_premio_id = TElement::tag('td', $tipo_premio_id);
        $tr->add($td_tipo_premio_id);
        $td_obs = TElement::tag('td', $obs);
        $tr->add($td_obs);

        $this->datagrid_form->addField($ano);
        $this->datagrid_form->addField($mes);
        $this->datagrid_form->addField($tipo_premio_id);
        $this->datagrid_form->addField($obs);

        $this->datagrid_form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Listagem de prêmios");
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
        $button_cadastrar->setAction(new TAction(['PremioForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['PremioHeaderList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['PremioHeaderList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $button_sincronizar_premio = new TButton('button_button_sincronizar_premio');
        $button_sincronizar_premio->setAction(new TAction(['PremioHeaderList', 'onSyncPremio']), "Sincronizar Prêmio");
        $button_sincronizar_premio->addStyleClass('btn-default');
        $button_sincronizar_premio->setImage('fas:sync #000000');

        $this->datagrid_form->addField($button_sincronizar_premio);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['PremioHeaderList', 'onExportCsv'],['static' => 1]), self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['PremioHeaderList', 'onExportXls'],['static' => 1]), self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['PremioHeaderList', 'onExportPdf'],['static' => 1]), self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['PremioHeaderList', 'onExportXml'],['static' => 1]), self::$formName, 'far:file-code #95a5a6' );

        $head_left_actions->add($button_cadastrar);
        $head_left_actions->add($button_limpar_filtros);
        $head_left_actions->add($button_atualizar);
        $head_left_actions->add($button_sincronizar_premio);

        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Metas","Premios"]));
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
                $object = new Premio($key, FALSE); 

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
    public static function onExibirRestricoes($object)
    {
        try 
        {
            if($object->restricao == 'S')
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
    public function addObs($param = null) 
    {

    try {
        // abre a conexão padrão da classe
        TTransaction::open(self::$database);

        // id do PRÊMIO (master) vindo do botão
        $premio_id = $param['key'] ?? $param['id'] ?? null;
        if (!$premio_id) {
            throw new Exception('Prêmio não informado.');
        }

        /** @var Premio $premio */
        $premio = Premio::find($premio_id);
        if (!$premio) {
            throw new Exception("Prêmio #{$premio_id} não encontrado.");
        }

        // carrega TODAS as regras vinculadas a este prêmio
        $regras = PremioRegra::where('premio_id', '=', $premio_id)->load();
        if (!$regras) {
            throw new Exception('Este prêmio não possui regras cadastradas.');
        }

        // helpers
        $fmtNum = function($n) {
            $s = number_format((float)$n, 2, ',', '.');
            return preg_replace('/,00$/', '', $s);
        };
        $fmtPremio = function($valor, $tipo_valor) use ($fmtNum) {
            if ((int)$tipo_valor === 1) return $fmtNum($valor) . '%';        // bônus
            return 'R$ ' . number_format((float)$valor, 2, ',', '.');        // prêmio
        };

        // cache de comparadores (evita N queries)
        $cmpCache = [];
        $getCmpTexto = function($id) use (&$cmpCache) {
            if (!isset($cmpCache[$id])) {
                $c = Comparador::find($id);
                $cmpCache[$id] = $c ? $c->descricao : 'Condição';
            }
            return $cmpCache[$id];
        };

        $linhas = [];

        /** @var PremioRegra $r */
        foreach ($regras as $r) {
            $descCmp        = $getCmpTexto((int)$r->comparador_id);
            $rotulo         = ((int)$r->tipo_valor === 1) ? 'bônus' : 'prêmio';
            $valorPremiacao = $fmtPremio($r->bonus, $r->tipo_valor);
            $v0             = ($r->dado_0 !== null) ? $fmtNum($r->dado_0) : '—';
            $v1             = ($r->dado_1 !== null) ? $fmtNum($r->dado_1) : '—';

            switch ((int)$r->comparador_id) {
                case 6: // Está Entre (between)
                    $linhas[] = "Entre {$v0}  e {$v1} receberá um {$rotulo} de {$valorPremiacao}.";
                    break;
                case 5: // Igual
                    $linhas[] = "Igual {$v0} receberá um {$rotulo} de {$valorPremiacao}";
                    break;
                case 4: // Menor ou Igual
                    $linhas[] = "Menor ou Igual {$v0} receberá um {$rotulo} de {$valorPremiacao}.";
                    break;
                case 3: // Maior ou Igual
                    $linhas[] = "Maior ou Igual {$v0} receberá um {$rotulo} de {$valorPremiacao}.";
                    break;
                case 2: // Menor
                    $linhas[] = "Menor {$v0} receberá um {$rotulo} de {$valorPremiacao}.";
                    break;
                case 1: // Maior
                    $linhas[] = "Maior {$v0} receberá um {$rotulo} de {$valorPremiacao}.";
                    break;
                default:
                    $linhas[] = "{$descCmp} {$v0} receberá um {$rotulo} de {$valorPremiacao}.";
                    break;
            }
        }

        // junta tudo em OBS (mantendo texto anterior, se houver)
        $texto = implode(PHP_EOL, $linhas);
        $premio->obs = trim($premio->obs ?? '') ? ($premio->obs . PHP_EOL . $texto) : $texto;
        $premio->store();

        TTransaction::close();

        new TMessage('info', 'OBS Cadastrada com Sucesso!.');

            $this->onReload($param);

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
    public function onSyncPremio($param = null) 
    { 
        try
            {
                TTransaction::open(self::$database);

                $meta = Meta::where('status', '=', 1)
                    ->where('system_unit_id', '=', TSession::getValue('userunitid'))
                    ->first();

                if (!$meta) {
                    throw new Exception('Nenhuma meta aberta foi encontrada.');
                }

                $mesDestino = (int) $meta->mes;
                $anoDestino = (int) $meta->ano;

                $premioJaExiste = Premio::where('mes', '=', $mesDestino)
                    ->where('ano', '=', $anoDestino)
                    ->first();

                if ($premioJaExiste) {
                    throw new Exception("Já existem prêmios cadastrados para {$mesDestino}/{$anoDestino}.");
                }

                $mesBusca = $mesDestino;
                $anoBusca = $anoDestino;
                $premiosOrigem = null;
                $contador = 0;
                $limiteRetrocesso = 12;

                while (!$premiosOrigem && $contador < $limiteRetrocesso) {
                    $timestamp = mktime(0, 0, 0, $mesBusca - 1, 1, $anoBusca);
                    $mesBusca = (int) date('n', $timestamp);
                    $anoBusca = (int) date('Y', $timestamp);

                    $premiosOrigem = Premio::where('mes', '=', $mesBusca)
                        ->where('ano', '=', $anoBusca)
                        ->load();

                    $contador++;
                }

                if (!$premiosOrigem) {
                    throw new Exception('Nenhum prêmio foi encontrado nos últimos 12 meses para sincronizar.');
                }

                foreach ($premiosOrigem as $oldPremio) {
                    $novoPremio = clone $oldPremio;
                    $novoPremio->id  = null;
                    $novoPremio->mes = $mesDestino;
                    $novoPremio->ano = $anoDestino;
                    $novoPremio->store();

                    foreach ($oldPremio->getPremioRegras() as $oldRegra) {
                        $novaRegra = clone $oldRegra;
                        $novaRegra->id = null;
                        $novaRegra->premio_id = $novoPremio->id;
                        $novaRegra->store();
                    }
                }

                TTransaction::close();

                TToast::show('success', 'Prêmios sincronizados com sucesso.', 'topRight', 'far:check-circle');

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

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->mes) AND ( (is_scalar($data->mes) AND $data->mes !== '') OR (is_array($data->mes) AND (!empty($data->mes)) )) )
        {

            $filters[] = new TFilter('mes', '=', $data->mes);// create the filter 
        }

        if (isset($data->tipo_premio_id) AND ( (is_scalar($data->tipo_premio_id) AND $data->tipo_premio_id !== '') OR (is_array($data->tipo_premio_id) AND (!empty($data->tipo_premio_id)) )) )
        {

            $filters[] = new TFilter('tipo_premio_id', '=', $data->tipo_premio_id);// create the filter 
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

            // creates a repository for Premio
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

        $object = new Premio($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

    private function fmtNum($n)
    {
        if ($n === null || $n === '') return '';
        $s = number_format((float)$n, 2, ',', '.');
        // remove zeros e vírgula se não precisar
        $s = rtrim(rtrim($s, '0'), ',');
        return $s;
    }

}

