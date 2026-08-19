<?php

class VincularTabelaPrecoWindowList extends TPage
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

        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');
        $column_cod_tabelapreco = new TDataGridColumn('cod_tabelapreco', "Cód. Tabela Preço", 'center');
        $column_descricao = new TDataGridColumn('descricao', "Descrição", 'center');
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

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_cod_tabelapreco);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_ativo);
        $this->datagrid->addColumn($column_system_unit_name);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';
        $headerActions->style = 'justify-content: space-between;';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $panel->getBody()->insert(0, $headerActions);

        $button_vincular = new TButton('button_button_vincular');
        $button_vincular->setAction(new TAction(['VincularTabelaPrecoWindowList', 'onVincularPreco']), "Vincular");
        $button_vincular->addStyleClass('btn-primary');
        $button_vincular->setImage('fas:link #FFFFFF');
        $button_vincular->getAction()->setParameter("campanha_id", "id");

        $this->datagrid_form->addField($button_vincular);

        $head_left_actions->add($button_vincular);

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $form = new BootstrapFormBuilder(self::$formName);
        $form->setTagName('div');
        $form->setFormTitle('&nbsp;');
        $form->addContent([$panel]);
        $form->addHeaderWidget($btnClose);

        parent::add($form);

    }

    public function onVincularPreco($param = null) 
    {

        try {
            TTransaction::open('integrador');

            $campanha_id = TSession::getValue('campanha_id');
            $idsSelecionados = TSession::getValue('VincularTabelaPrecoWindowListbuilder_datagrid_check');

            if (!$campanha_id) {
                throw new Exception("Campanha não informada.");
            }

            // Pega todas as tabelas de preço do sistema
            $tabelas = ApTabelaPreco::all(); 

            foreach ($tabelas as $tabela) {
                $ap_tabela_preco_id = $tabela->id;

                // Define se deve ser 'S' ou 'N'
                $fazparte = (in_array($ap_tabela_preco_id, $idsSelecionados ?? [])) ? 'S' : 'N';

                // Procura o vínculo
                $registro = CampanhaTabelaPreco::where('campanha_id', '=', $campanha_id)
                    ->where('ap_tabela_preco_id', '=', $ap_tabela_preco_id)
                    ->first();

                if ($registro) {
                    $registro->fazparte = $fazparte;
                } else {
                    $registro = new CampanhaTabelaPreco;
                    $registro->campanha_id = $campanha_id;
                    $registro->ap_tabela_preco_id = $ap_tabela_preco_id;
                    $registro->fazparte = $fazparte;
                }

                $registro->store();
            }

            TTransaction::close();

            TToast::show('success', 'Vínculo(s) atualizado(s) com sucesso!');
            TScript::create("Template.closeRightPanel()");

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }

    }
    /***
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
            $session_checks = TSession::getValue(__CLASS__.'builder_datagrid_check');

            $criteria->add(new TFilter('ativo', '=', 'S'));

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

        if (isset($param['campanha_id'])) {
            TSession::setValue('campanha_id', $param['campanha_id']);
        }

        $campanha_id = $param['campanha_id'] ?? TSession::getValue('campanha_id');
        $idsMarcados = [];

        if ($campanha_id) {
            TTransaction::open('integrador');

            $registros = CampanhaTabelaPreco::where('campanha_id', '=', $campanha_id)
                                            ->where('fazparte', '=', 'S')
                                            ->load();

            foreach ($registros as $r) {
                $idsMarcados[] = (string) $r->ap_tabela_preco_id;
            }

            TTransaction::close();

            TSession::setValue('VincularTabelaPrecoWindowListbuilder_datagrid_check', array_combine($idsMarcados, $idsMarcados));
        } else {
            TSession::setValue('VincularTabelaPrecoWindowListbuilder_datagrid_check', []);
        }

       $this->onReload();

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

        $object = new ApTabelaPreco($id);

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

}

