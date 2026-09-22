<?php

class PersianaAgrupamentoExcecaoHeaderList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'integrador';
    private static $activeRecord = 'PersianaAgrupamentoExcecao';
    private static $primaryKey = 'id';
    private static $formName = 'formList_PersianaAgrupamentoExcecao';
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

        $this->limit = 0;

        $criteria_persiana_agrupamento_id = new TCriteria();

        if (!empty($param['persiana_agrupamento_id']))
        {
            TSession::setValue(
                __CLASS__ . '_persiana_agrupamento_id',
                (int) $param['persiana_agrupamento_id']
            );
        }

        $id = new TEntry('id');
        $persiana_agrupamento_id = new TDBCombo('persiana_agrupamento_id', 'integrador', 'PersianaAgrupamento', 'id', '{id}','id asc' , $criteria_persiana_agrupamento_id );
        $data = new TDate('data');
        $qtd = new TEntry('qtd');

        $id->exitOnEnter();
        $qtd->exitOnEnter();

        $id->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $data->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $qtd->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $persiana_agrupamento_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $persiana_agrupamento_id->enableSearch();
        $data->setMask('dd/mm/yyyy');
        $data->setDatabaseMask('yyyy-mm-dd');
        $data->setSize(110);
        $id->setSize('100%');
        $qtd->setSize('100%');
        $persiana_agrupamento_id->setSize('100%');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm(self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');
        $column_persiana_agrupamento_id = new TDataGridColumn('persiana_agrupamento_id', "Persiana agrupamento id", 'left');
        $column_data = new TDataGridColumn('data', "Data Exceção:", 'left');
        $column_qtd = new TDataGridColumn('qtd', "Limite Diário:", 'left');

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $column_id->hide();
        $column_persiana_agrupamento_id->hide();

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_persiana_agrupamento_id);
        $this->datagrid->addColumn($column_data);
        $this->datagrid->addColumn($column_qtd);

        $action_onEdit = new TDataGridAction(array('PersianaAgrupamentoExcecaoForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('PersianaAgrupamentoExcecaoHeaderList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

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
        $td_id = TElement::tag('td', $id);
        $tr->add($td_id);
        $td_persiana_agrupamento_id = TElement::tag('td', $persiana_agrupamento_id);
        $tr->add($td_persiana_agrupamento_id);
        $td_data = TElement::tag('td', $data);
        $tr->add($td_data);
        $td_qtd = TElement::tag('td', $qtd);
        $tr->add($td_qtd);

        $this->datagrid_form->addField($id);
        $this->datagrid_form->addField($persiana_agrupamento_id);
        $this->datagrid_form->addField($data);
        $this->datagrid_form->addField($qtd);

        $this->datagrid_form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $panel = new TPanelGroup("Exceção de Planejamento de Persianas");
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $panel->getBody()->class .= ' table-responsive';

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
        $button_cadastrar->setAction(new TAction(['PersianaAgrupamentoExcecaoForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $head_left_actions->add($button_cadastrar);

        $this->datagrid_form->add($this->datagrid);

        $agrupamentoId = TSession::getValue(
            __CLASS__ . '_persiana_agrupamento_id'
        );

        if (!empty($agrupamentoId))
        {
            $actionCadastrar = new TAction([
                'PersianaAgrupamentoExcecaoForm',
                'onShow'
            ]);

            $actionCadastrar->setParameter(
                'persiana_agrupamento_id',
                $agrupamentoId
            );

            $button_cadastrar->setAction(
                $actionCadastrar,
                'Cadastrar'
            );
        }

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Configurações","Exceção de Planejamento de Persianas"]));
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
                $object = new PersianaAgrupamentoExcecao($key, FALSE); 

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

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->persiana_agrupamento_id) AND ( (is_scalar($data->persiana_agrupamento_id) AND $data->persiana_agrupamento_id !== '') OR (is_array($data->persiana_agrupamento_id) AND (!empty($data->persiana_agrupamento_id)) )) )
        {

            $filters[] = new TFilter('persiana_agrupamento_id', '=', $data->persiana_agrupamento_id);// create the filter 
        }

        if (isset($data->qtd) AND ( (is_scalar($data->qtd) AND $data->qtd !== '') OR (is_array($data->qtd) AND (!empty($data->qtd)) )) )
        {

            $filters[] = new TFilter('qtd', '=', $data->qtd);// create the filter 
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

            // creates a repository for PersianaAgrupamentoExcecao
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

            $agrupamentoId = TSession::getValue(
                __CLASS__ . '_persiana_agrupamento_id'
            );

            if (!empty($agrupamentoId))
            {
                $criteria->add(
                    new TFilter(
                        'persiana_agrupamento_id',
                        '=',
                        $agrupamentoId
                    )
                );
            }
            else
            {
                $criteria->add(
                    new TFilter('id', '=', -1)
                );
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

            $this->datagrid->initPopoverHeaderFilters();

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

        $object = new PersianaAgrupamentoExcecao($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

