<?php

class CampanhaFechamentoSimpleList extends TPage
{

    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'integrador';
    private static $activeRecord = 'CampanhaFechamento';
    private static $primaryKey = 'id';
    private static $formName = 'formList_CampanhaFechamento';
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
        $column_ap_representante_fantasia = new TDataGridColumn('ap_representante->fantasia', "Representante", 'left');
        $column_campanha_descricao = new TDataGridColumn('campanha->descricao', "Campanha id", 'left');
        $column_quantidade_total_transformed = new TDataGridColumn('quantidade_total', "Quantidade Total Vendido", 'center');
        $column_premio_transformed = new TDataGridColumn('premio', "Prêmio Recebido", 'right');

        $column_quantidade_total_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_premio_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $column_id->hide();
        $column_campanha_descricao->hide();

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_ap_representante_fantasia);
        $this->datagrid->addColumn($column_campanha_descricao);
        $this->datagrid->addColumn($column_quantidade_total_transformed);
        $this->datagrid->addColumn($column_premio_transformed);

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
            $container->add(TBreadCrumb::create(["Metas","Campanha Fechamento"]));
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

            // creates a repository for CampanhaFechamento
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

            try {
            TTransaction::open(self::$database);

            $campanha_id = $param['campanha_id'] ?? null;

            if ($campanha_id) {
                $criteria = new TCriteria();
                $criteria->add(new TFilter('campanha_id', '=', $campanha_id));

                // Pega IDs dos representantes ATIVOS
                $idsAtivos = ApRepresentante::where('ativo', '=', 'S')
                                // se precisar isolar por unidade, descomente a linha abaixo:
                                // ->where('system_unit_id','=', TSession::getValue('userunitid') ?? 1)
                                ->getIndexedArray('id', 'id');

                if (!empty($idsAtivos)) {
                    $criteria->add(new TFilter('ap_representante_id', 'IN', array_values($idsAtivos)));
                } else {
                    // sem ativos → limpa e sai
                    $this->datagrid->clear();
                    TTransaction::close();
                    return;
                }

                // Ordena do maior prêmio para o menor
                $criteria->setProperty('order', 'quantidade_total');
                $criteria->setProperty('direction', 'desc');
                // (opcional) paginação
                // $criteria->setProperty('limit', $this->limit);

                TSession::setValue(__CLASS__ . '_filter', $criteria);

                $this->datagrid->clear();
                $repo = new TRepository(CampanhaFechamento::class);
                $registros = $repo->load($criteria);

                foreach ($registros as $obj) {
                    $this->datagrid->addItem($obj);
                }
            } else {
                $this->datagrid->clear();
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

        $object = new CampanhaFechamento($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

