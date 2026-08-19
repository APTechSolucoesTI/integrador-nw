<?php

class RestricaoSubGrupo extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'integrador';
    private static $activeRecord = 'ApSubgrupoEstoque';
    private static $primaryKey = 'id';
    private static $formName = 'form_RestricaoSubGrupo';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters', 'onGlobalSearch'];
    private $limit = 20;

    private $premio_id;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Restrição de Prêmio");
        $this->limit = 0;

        $this->premio_id = $param['premio_id'] ?? null;

        if (empty($this->premio_id)) {
            $this->premio_id = TSession::getValue(__CLASS__.'_premio_id');
        }

        if (empty($this->premio_id)) {
            new TMessage('error', 'premio_id não informado ao abrir a janela de restrição.');
            return;
        }

        TSession::setValue(__CLASS__.'_premio_id', $this->premio_id);

        $cod_grupoestoque = new TEntry('cod_grupoestoque');


        $cod_grupoestoque->setSize('100%');
        $cod_grupoestoque->setMaxLength(8);

        $row1 = $this->form->addFields([new TLabel("Código GRUPO ESTOQUE:", null, '14px', null, '100%'),$cod_grupoestoque]);
        $row1->layout = [' col-sm-12'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_cod_grupoestoque = new TDataGridColumn('cod_grupoestoque', "Cód. Grupo Estoque", 'left');
        $column_cod_subgrupoestoque = new TDataGridColumn('cod_subgrupoestoque', "Cód. Sub-Grupo Estoque", 'left');

        $order_cod_grupoestoque = new TAction(array($this, 'onReload'));
        $order_cod_grupoestoque->setParameter('order', 'cod_grupoestoque');
        $column_cod_grupoestoque->setAction($order_cod_grupoestoque);
        $order_cod_subgrupoestoque = new TAction(array($this, 'onReload'));
        $order_cod_subgrupoestoque->setParameter('order', 'cod_subgrupoestoque');
        $column_cod_subgrupoestoque->setAction($order_cod_subgrupoestoque);

        $this->builder_datagrid_check_all = new TCheckButton('builder_datagrid_check_all');
        $this->builder_datagrid_check_all->setIndexValue('on');
        $this->builder_datagrid_check_all->onclick = "Builder.checkAll(this)";
        $this->builder_datagrid_check_all->style = 'cursor:pointer';
        $this->builder_datagrid_check_all->setProperty('class', 'filled-in');
        $this->builder_datagrid_check_all->id = 'builder_datagrid_check_all';

        $label = new TLabel('');
        $label->style = 'margin:0';
        $label->class = 'checklist-label';
        $this->builder_datagrid_check_all->after($label);
        $label->for = 'builder_datagrid_check_all';

        $this->builder_datagrid_check = $this->datagrid->addColumn( new TDataGridColumn('builder_datagrid_check', $this->builder_datagrid_check_all, 'center',  '1%') );

        $this->datagrid->addColumn($column_cod_grupoestoque);
        $this->datagrid->addColumn($column_cod_subgrupoestoque);

        // create the datagrid model
        $this->datagrid->createModel();

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;

        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';
        $headerActions->style = 'justify-content: space-between;';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $this->datagrid_form->add($headerActions);

        $button_vincular = new TButton('button_button_vincular');
        $button_vincular->setAction(new TAction(['RestricaoSubGrupo', 'onVincular']), "Vincular");
        $button_vincular->addStyleClass('btn-default');
        $button_vincular->setImage('fas:link #000000');

        $this->datagrid_form->addField($button_vincular);

        $head_left_actions->add($button_vincular);

        $this->datagrid_form->add($this->datagrid);

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);
        parent::add($panel);

        $style = new TStyle('right-panel > .container-part[page-name=RestricaoSubGrupo]');
        $style->width = '40% !important';   
        $style->show(true);

    }

    public function onVincular($param = null) 
    {
        try 
        {
            $session_checks = (array) TSession::getValue(__CLASS__.'builder_datagrid_check');
            $idsSelecionados = array_map('intval', array_keys($session_checks));

            if (empty($idsSelecionados)) {
                new TMessage('warning', 'Selecione pelo menos um subgrupo.');
                return;
            }

            if (empty($this->premio_id)) {

                $this->premio_id = TSession::getValue(__CLASS__.'_premio_id');
                }
                if (empty($this->premio_id)) {
                    new TMessage('error', 'premio_id não está definido nesta janela.');
                    return;
                }

            TTransaction::open(self::$database);

            $qtdInseridos = 0;
            $qtdJaExistia = 0;

            foreach ($idsSelecionados as $subgrupo_id) {

                $sub = ApSubgrupoEstoque::find($subgrupo_id);
                if (!$sub) {
                    continue;
                }

                $grupo_id = (int) $sub->grupo_estoque_id;

                // Evita duplicar
                $existe = RestricaoPremio::where('premio_id', '=', $this->premio_id)
                    ->where('ap_grupo_estoque_id', '=', $grupo_id)
                    ->where('ap_subgrupo_estoque_id', '=', $subgrupo_id)
                    ->first();

                if ($existe) {
                    $qtdJaExistia++;
                    continue;
                }

                $r = new RestricaoPremio;
                $r->premio_id = (int) $this->premio_id;
                $r->ap_grupo_estoque_id = $grupo_id;
                $r->ap_subgrupo_estoque_id = $subgrupo_id;
                $r->store();

                $qtdInseridos++;
            }

            TTransaction::close();

            $this->syncChecksFromDb();

            new TMessage(
                'info',
                "Foram Vinculado(s): {$qtdInseridos}."
            );

            $this->onReload($param);
            //</autoCode>
        }
        catch (Exception $e) 
        {
        new TMessage('error', $e->getMessage());
        TTransaction::rollback();        }
    }

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->cod_grupoestoque) AND ( (is_scalar($data->cod_grupoestoque) AND $data->cod_grupoestoque !== '') OR (is_array($data->cod_grupoestoque) AND (!empty($data->cod_grupoestoque)) )) )
        {

            $filters[] = new TFilter('cod_grupoestoque', 'like', "%{$data->cod_grupoestoque}%");// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
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

            // creates a repository for ApSubgrupoEstoque
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $this->limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }
            $session_checks = TSession::getValue(__CLASS__.'builder_datagrid_check');

/*

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    $check = new TCheckGroup('builder_datagrid_check');
                    $check->addItems([$object->id => '']);
                    $check->getButtons()[$object->id]->onclick = 'event.stopPropagation()';

                    if(!$this->datagrid_form->getField('builder_datagrid_check[]'))
                    {
                        $this->datagrid_form->setFields([$check]);
                    }

                    $check->setChangeAction(new TAction([$this, 'builderSelectCheck']));
                    $object->builder_datagrid_check = $check;

                    if(!empty($session_checks[$object->id]))
                    {
                        $object->builder_datagrid_check->setValue([$object->id=>$object->id]);
                    }

*/          $objects = $repository->load($criteria, FALSE);

            if ($objects) {
                $session_checks = (array) TSession::getValue(__CLASS__.'builder_datagrid_check');

                usort($objects, function($a, $b) use ($session_checks) {
                    $aChecked = !empty($session_checks[$a->id]) ? 1 : 0;
                    $bChecked = !empty($session_checks[$b->id]) ? 1 : 0;

                    // tickado primeiro
                    if ($aChecked !== $bChecked) {
                        return $bChecked <=> $aChecked;
                    }

                    // desempate: grupo, subgrupo
                    $cmp = strcmp((string)$a->cod_grupoestoque, (string)$b->cod_grupoestoque);
                    if ($cmp !== 0) return $cmp;

                    return strcmp((string)$a->cod_subgrupoestoque, (string)$b->cod_subgrupoestoque);
                });
            }

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    $check = new TCheckGroup('builder_datagrid_check');
                    $check->addItems([$object->id => '']);
                    $check->getButtons()[$object->id]->onclick = 'event.stopPropagation()';

                    if(!$this->datagrid_form->getField('builder_datagrid_check[]'))
                    {
                        $this->datagrid_form->setFields([$check]);
                    }

                    $check->setChangeAction(new TAction([$this, 'builderSelectCheck']));
                    $object->builder_datagrid_check = $check;

                    if(!empty($session_checks[$object->id]))
                    {
                        $object->builder_datagrid_check->setValue([$object->id=>$object->id]);
                    }

                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

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

        $this->syncChecksFromDb();
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

    public static function builderSelectCheck($param)
    {
        $session_checks = TSession::getValue(__CLASS__.'builder_datagrid_check');

        $valueOn = null;
        if(!empty($param['_field_data_json']))
        {
            $obj = json_decode($param['_field_data_json']);
            if($obj)
            {
                $valueOn = $obj->valueOn;
            }
        }

        $key = empty($param['key']) ? $valueOn : $param['key'];

        if(empty($param['builder_datagrid_check']) && !empty($session_checks[$key]))
        {
            unset($session_checks[$key]);
        }
        elseif(!empty($param['builder_datagrid_check']) && !in_array($key, $param['builder_datagrid_check']) && !empty($session_checks[$key]))
        {
            unset($session_checks[$key]);
        }
        elseif(!empty($param['builder_datagrid_check']) && in_array($key, $param['builder_datagrid_check']))
        {
            $session_checks[$key] = $key;
        }

        $premio_id = (int) TSession::getValue(__CLASS__.'_premio_id');

        if (!empty($premio_id) && !empty($key)) {

        // snapshot anterior (antes deste clique)
        $before_map = (array) TSession::getValue(__CLASS__.'builder_datagrid_check_before');
        $wasChecked = !empty($before_map[$key]);
        $isChecked = !empty($session_checks[$key]);

        // Se estava marcado e agora NÃO está, então deletar no banco
        if ($wasChecked && !$isChecked) {
            try {
                $openTransaction = (TTransaction::getDatabase() != self::$database);
                if ($openTransaction) {
                    TTransaction::open(self::$database);
                }

                $grupo_id = null;
                $sub = ApSubgrupoEstoque::find((int)$key);
                if ($sub) {
                    $grupo_id = (int) $sub->grupo_estoque_id;
                }

                $q = RestricaoPremio::where('premio_id', '=', $premio_id)
                    ->where('ap_subgrupo_estoque_id', '=', (int)$key);

                if (!empty($grupo_id)) {
                    $q->where('ap_grupo_estoque_id', '=', $grupo_id);
                }

                $reg = $q->first();
                if ($reg) {
                    $reg->delete();
                }

                if ($openTransaction) {
                    TTransaction::close();
                }
            } catch (Exception $e) {
                if (TTransaction::getDatabase() == self::$database) {
                    TTransaction::rollback();
                }
                // se quiser ver erro:
                // new TMessage('error', $e->getMessage());
            }
        }

        // Atualiza o snapshot pro próximo clique
        TSession::setValue(__CLASS__.'builder_datagrid_check_before', $session_checks);
    }

        TSession::setValue(__CLASS__.'builder_datagrid_check', $session_checks);
    }

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if($openTransaction)
        {
            TTransaction::open(self::$database);    
        }

        $object = new ApSubgrupoEstoque($id);

        $session_checks = TSession::getValue(__CLASS__.'builder_datagrid_check');

        $check = new TCheckGroup('builder_datagrid_check');
        $check->addItems([$object->id => '']);
        $check->getButtons()[$object->id]->onclick = 'event.stopPropagation()';

        if(!$list->datagrid_form->getField('builder_datagrid_check[]'))
        {
            $list->datagrid_form->setFields([$check]);
        }

        $check->setChangeAction(new TAction([$list, 'builderSelectCheck']));
        $object->builder_datagrid_check = $check;

        if(!empty($session_checks[$object->id]))
        {
            $object->builder_datagrid_check->setValue([$object->id=>$object->id]);
        }

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

    private function syncChecksFromDb()
    {
        if (empty($this->premio_id)) {
            $this->premio_id = TSession::getValue(__CLASS__.'_premio_id');
        }

        if (empty($this->premio_id)) {
            return;
        }

        $map = [];

        try {
            TTransaction::open(self::$database);

            $restricoes = RestricaoPremio::where('premio_id', '=', (int) $this->premio_id)->load();

            if ($restricoes) {
                foreach ($restricoes as $r) {
                    if (!empty($r->ap_subgrupo_estoque_id)) {
                        $id = (int) $r->ap_subgrupo_estoque_id;
                        $map[$id] = $id;
                    }
                }
            }

            TTransaction::close();
        } catch (Exception $e) {
            TTransaction::rollback();
        }

        TSession::setValue(__CLASS__.'builder_datagrid_check', $map);
        TSession::setValue(__CLASS__.'builder_datagrid_check_before', $map);

    }

    private static function removerRestricaoDoBanco(int $premio_id, int $subgrupo_id): void
    {
        if (empty($premio_id) || empty($subgrupo_id)) {
            return;
        }

        $openTransaction = (TTransaction::getDatabase() != self::$database);

        try {
            if ($openTransaction) {
                TTransaction::open(self::$database);
            }

            $grupo_id = null;
            $sub = ApSubgrupoEstoque::find($subgrupo_id);
            if ($sub) {
                $grupo_id = (int) $sub->grupo_estoque_id;
            }

            $q = RestricaoPremio::where('premio_id', '=', $premio_id)
                ->where('ap_subgrupo_estoque_id', '=', $subgrupo_id);

            if (!empty($grupo_id)) {
                $q->where('ap_grupo_estoque_id', '=', $grupo_id);
            }

            $reg = $q->first();
            if ($reg) {
                $reg->delete();
            }

            if ($openTransaction) {
                TTransaction::close();
            }
        } catch (Exception $e) {
            if ($openTransaction) {
                TTransaction::rollback();
            }
            // aqui eu deixo silencioso pra não encher o saco do usuário a cada click
            // new TMessage('error', $e->getMessage());
        }
    }

}

