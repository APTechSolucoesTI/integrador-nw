<?php

class MetaImportVisualizarTabelaPreco extends TPage
{

    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'integrador';
    private static $activeRecord = 'ApTabelaPreco';
    private static $primaryKey = 'id';
    private static $formName = 'formList_ApTabelaPreco';
    private $limit = 20;

    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        $this->limit = 0;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');
        $column_cod_tabelapreco = new TDataGridColumn('cod_tabelapreco', "Cód. Tabela de Preço", 'left');
        $column_descricao = new TDataGridColumn('descricao', "Descrição", 'left');
        $column_ativo = new TDataGridColumn('ativo', "Ativo", 'left');
        $column_system_unit_name = new TDataGridColumn('system_unit->name', "System unit id", 'left');

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        $order_cod_tabelapreco = new TAction(array($this, 'onReload'));
        $order_cod_tabelapreco->setParameter('order', 'cod_tabelapreco');
        $column_cod_tabelapreco->setAction($order_cod_tabelapreco);

        $column_id->hide();
        $column_ativo->hide();
        $column_system_unit_name->hide();

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_cod_tabelapreco);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_ativo);
        $this->datagrid->addColumn($column_system_unit_name);

        // create the datagrid model
        $this->datagrid->createModel();

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Metas","Meta Import Tabelas de Preco"]));
        }
        $container->add($panel);

        parent::add($container);

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

            // creates a repository for ApTabelaPreco
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

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

         Try{ 

        TTransaction::open(self::$database);

        $meta_import_id = $param['meta_import_id'] ?? null;

        if ($meta_import_id) {
            // Buscar os IDs das tabelas vinculadas com 'S'
            $ids_tabelas = MetaImportTabelaPreco::where('meta_import_id', '=', $meta_import_id)
                                ->where('fazparte', '=', 'S')
                                ->getIndexedArray('ap_tabela_preco_id', 'ap_tabela_preco_id');

            if ($ids_tabelas) {
                $criteria = new TCriteria();
                $criteria->add(new TFilter('id', 'IN', array_values($ids_tabelas)));

                TSession::setValue(__CLASS__ . '_filter', $criteria); 

                // Define o filtro no datagrid
                $this->datagrid->clear();
                $repo = new TRepository(ApTabelaPreco::class);
                $registros = $repo->load($criteria);

                foreach ($registros as $obj) {
                    $this->datagrid->addItem($obj);
                }
            } else {
                $this->datagrid->clear();
            }
        }

        TTransaction::close();
    } catch (Exception $e) {
        new TMessage('error', $e->getMessage());
        TTransaction::rollback();

    }
    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
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

        $object = new ApTabelaPreco($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

