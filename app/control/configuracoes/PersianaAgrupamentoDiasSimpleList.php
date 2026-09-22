<?php

class PersianaAgrupamentoDiasSimpleList extends TPage
{

    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'integrador';
    private static $activeRecord = 'PersianaAgrupamentoDias';
    private static $primaryKey = 'id';
    private static $formName = 'formList_PersianaAgrupamentoDias';
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

        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id_transformed = new TDataGridColumn('id', "Id", 'center' , '70px');
        $column_persiana_agrupamento_id = new TDataGridColumn('persiana_agrupamento_id', "Persiana agrupamento id", 'left');
        $column_dia_transformed = new TDataGridColumn('dia', "", 'left');

        $column_id_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
              $map = [
                1 => 'Segunda',
                2 => 'Terça',
                3 => 'Quarta',
                4 => 'Quinta',
                5 => 'Sexta',
                6 => 'Sábado',
                7 => 'Domingo'
            ];

            return $map[(int)$value] ?? 'Desconhecido';

        });

        $column_dia_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
              $map = [
                1 => 'Segunda',
                2 => 'Terça',
                3 => 'Quarta',
                4 => 'Quinta',
                5 => 'Sexta',
                6 => 'Sábado',
                7 => 'Domingo'
            ];

            return $map[(int)$value] ?? 'Desconhecido';

        });        

        $order_id_transformed = new TAction(array($this, 'onReload'));
        $order_id_transformed->setParameter('order', 'id');
        $column_id_transformed->setAction($order_id_transformed);

        $column_id_transformed->hide();
        $column_persiana_agrupamento_id->hide();

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

        $this->datagrid->addColumn($column_id_transformed);
        $this->datagrid->addColumn($column_persiana_agrupamento_id);
        $this->datagrid->addColumn($column_dia_transformed);

        // create the datagrid model
        $this->datagrid->createModel();

        $panel = new TPanelGroup("Dias Válidos ");
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
            $container->add(TBreadCrumb::create(["Configurações","Dias Válidos"]));
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

            // creates a repository for PersianaAgrupamentoDias
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if (empty($param['order']))
            {
                $param['order'] = 'dia';    
            }
            if (empty($param['direction']))
            {
                $param['direction'] = 'asc';
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
            $session_checks = TSession::getValue(__CLASS__.'builder_datagrid_check');

            if (!empty($param['persiana_agrupamento_id']))
                {
                    TSession::setValue(
                        __CLASS__ . '_persiana_agrupamento_id',
                        (int) $param['persiana_agrupamento_id']
                    );
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
                    // SEM agrupamento = não mostra absolutamente nada.
                    // Isso impede vazar dias de outro planejamento.
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

                    if ($object->valido === 'S')
                    {
                        $object->builder_datagrid_check->setValue([
                            $object->id => $object->id
                        ]);
                    }
                    else
                    {
                        $object->builder_datagrid_check->setValue([]);
                    }

                    $object->builder_datagrid_check->setChangeAction(
                        new TAction([__CLASS__, 'onToggleValido'])
                    );

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

        $object = new PersianaAgrupamentoDias($id);

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

    public static function onToggleValido($param)
    {
        try
        {
            TTransaction::open(self::$database);

            $valueOn = null;

            if (!empty($param['_field_data_json']))
            {
                $obj = json_decode($param['_field_data_json']);

                if ($obj)
                {
                    $valueOn = $obj->valueOn;
                }
            }

            $id = empty($param['key'])
                ? $valueOn
                : $param['key'];

            if (empty($id))
            {
                throw new Exception('Dia não identificado');
            }

            $dia = new PersianaAgrupamentoDias($id);

            if (
                !empty($param['builder_datagrid_check'])
                && in_array($id, $param['builder_datagrid_check'])
            )
            {
                $dia->valido = 'S';
            }
            else
            {
                $dia->valido = 'N';
            }

            $dia->store();

            TTransaction::close();
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

}

