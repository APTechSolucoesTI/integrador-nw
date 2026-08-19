<?php

class PremioRestricaoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'Premio';
    private static $primaryKey = 'id';
    private static $formName = 'form_PremioRestricaoForm';

    use BuilderMasterDetailFieldListTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Restrições Prêmio");

        $criteria_restricao_premio_premio_ap_subgrupo_estoque_id = new TCriteria();
        $criteria_restricao_premio_premio_ap_grupo_estoque_id = new TCriteria();

        $id = new THidden('id');
        $restricao_premio_premio_id = new TEntry('restricao_premio_premio_id[]');
        $restricao_premio_premio_id->setEditable(false);
        $restricao_premio_premio_id->setSize('100%');

        $restricao_premio_premio___row__id = new THidden('restricao_premio_premio___row__id[]');
        $restricao_premio_premio___row__data = new THidden('restricao_premio_premio___row__data[]');
        $restricao_premio_premio_ap_subgrupo_estoque_id = new TDBCombo('restricao_premio_premio_ap_subgrupo_estoque_id[]', 'integrador', 'ApSubgrupoEstoque', 'id', '{cod_grupoestoque} | {cod_subgrupoestoque}','descricao asc' , $criteria_restricao_premio_premio_ap_subgrupo_estoque_id );
        $restricao_premio_premio_ap_grupo_estoque_id = new TDBCombo('restricao_premio_premio_ap_grupo_estoque_id[]', 'integrador', 'ApGrupoEstoque', 'id', '{descricao} - {cod_grupoestoque}','descricao asc' , $criteria_restricao_premio_premio_ap_grupo_estoque_id );
        $this->fieldList_6967a797e9c15 = new TFieldList();

        $this->fieldList_6967a797e9c15->addField(new TLabel("ID Restrição", null, '14px', null), $restricao_premio_premio_id, ['width' => '10%']);
        $this->fieldList_6967a797e9c15->addField(null, $restricao_premio_premio___row__id, ['uniqid' => true]);
        $this->fieldList_6967a797e9c15->addField(null, $restricao_premio_premio___row__data, []);
        $this->fieldList_6967a797e9c15->addField(new TLabel("Grupo/SubGrupo Estoque:", null, '14px', null), $restricao_premio_premio_ap_subgrupo_estoque_id, ['width' => '50%']);
        $this->fieldList_6967a797e9c15->addField(new TLabel("Grupo Estoque (Sem SubGrupo)", null, '14px', null), $restricao_premio_premio_ap_grupo_estoque_id, ['width' => '100%']);

        $this->fieldList_6967a797e9c15->width = '100%';
        $this->fieldList_6967a797e9c15->setFieldPrefix('restricao_premio_premio');
        $this->fieldList_6967a797e9c15->name = 'fieldList_6967a797e9c15';

        $this->criteria_fieldList_6967a797e9c15 = new TCriteria();
        $this->default_item_fieldList_6967a797e9c15 = new stdClass();

        $this->form->addField($restricao_premio_premio_id);
        $this->form->addField($restricao_premio_premio___row__id);
        $this->form->addField($restricao_premio_premio___row__data);
        $this->form->addField($restricao_premio_premio_ap_subgrupo_estoque_id);
        $this->form->addField($restricao_premio_premio_ap_grupo_estoque_id);

        $this->fieldList_6967a797e9c15->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $id->setValue($param["key"] ?? "");
        $restricao_premio_premio_ap_grupo_estoque_id->enableSearch();
        $restricao_premio_premio_ap_subgrupo_estoque_id->enableSearch();

        $id->setSize(200);
        $restricao_premio_premio_ap_grupo_estoque_id->setSize('100%');
        $restricao_premio_premio_ap_subgrupo_estoque_id->setSize('100%');

/*

        $id = new THidden('id');
        $restricao_premio_premio_id = new THidden('restricao_premio_premio_id[]');
        $restricao_premio_premio___row__id = new THidden('restricao_premio_premio___row__id[]');
        $restricao_premio_premio___row__data = new THidden('restricao_premio_premio___row__data[]');
        $restricao_premio_premio_ap_subgrupo_estoque_id = new TDBCombo('restricao_premio_premio_ap_subgrupo_estoque_id[]', 'integrador', 'ApSubgrupoEstoque', 'id', '{cod_grupoestoque} | {cod_subgrupoestoque}','descricao asc' , $criteria_restricao_premio_premio_ap_subgrupo_estoque_id );
        $restricao_premio_premio_ap_grupo_estoque_id = new TDBCombo('restricao_premio_premio_ap_grupo_estoque_id[]', 'integrador', 'ApGrupoEstoque', 'id', '{descricao} - {cod_grupoestoque}','descricao asc' , $criteria_restricao_premio_premio_ap_grupo_estoque_id );
        $this->fieldList_6967a797e9c15 = new TFieldList();

        $this->fieldList_6967a797e9c15->addField(null, $restricao_premio_premio_id, []);
        $this->fieldList_6967a797e9c15->addField(null, $restricao_premio_premio___row__id, ['uniqid' => true]);
        $this->fieldList_6967a797e9c15->addField(null, $restricao_premio_premio___row__data, []);
        $this->fieldList_6967a797e9c15->addField(new TLabel("Grupo/SubGrupo Estoque:", null, '14px', null), $restricao_premio_premio_ap_subgrupo_estoque_id, ['width' => '50%']);
        $this->fieldList_6967a797e9c15->addField(new TLabel("Grupo Estoque (Sem SubGrupo)", null, '14px', null), $restricao_premio_premio_ap_grupo_estoque_id, ['width' => '100%']);

        $this->fieldList_6967a797e9c15->width = '100%';
        $this->fieldList_6967a797e9c15->setFieldPrefix('restricao_premio_premio');
        $this->fieldList_6967a797e9c15->name = 'fieldList_6967a797e9c15';

        $this->criteria_fieldList_6967a797e9c15 = new TCriteria();
        $this->default_item_fieldList_6967a797e9c15 = new stdClass();

        $this->form->addField($restricao_premio_premio_id);
        $this->form->addField($restricao_premio_premio___row__id);
        $this->form->addField($restricao_premio_premio___row__data);
        $this->form->addField($restricao_premio_premio_ap_subgrupo_estoque_id);
        $this->form->addField($restricao_premio_premio_ap_grupo_estoque_id);

        $this->fieldList_6967a797e9c15->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $id->setValue($param["key"] ?? "");
        $restricao_premio_premio_ap_grupo_estoque_id->enableSearch();
        $restricao_premio_premio_ap_subgrupo_estoque_id->enableSearch();

        $id->setSize(200);
        $restricao_premio_premio_ap_grupo_estoque_id->setSize('100%');
        $restricao_premio_premio_ap_subgrupo_estoque_id->setSize('100%');

*/
        $row1 = $this->form->addFields([$id],[]);
        $row1->layout = ['col-sm-6','col-sm-2'];

        $row2 = $this->form->addFields([$this->fieldList_6967a797e9c15]);
        $row2->layout = ['col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=PremioRestricaoForm]');
        $style->width = '40% !important';   
        $style->show(true);

    }

    public function onSave($param = null) 
    {
        /*
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Premio(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $restricao_premio_premio_items = $this->storeItems('RestricaoPremio', 'premio_id', $object, $this->fieldList_6967a797e9c15, function($masterObject, $detailObject){ 

        if (!empty($detailObject->ap_subgrupo_estoque_id)) {
                    // abre transação já está aberta no onSave
                    $sub = new ApSubgrupoEstoque((int) $detailObject->ap_subgrupo_estoque_id);

                    // GRAVA separado pro BI
                    $detailObject->ap_grupo_estoque_id = (int) $sub->grupo_estoque_id;

                }

            }, $this->criteria_fieldList_6967a797e9c15); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

                        TScript::create("Template.closeRightPanel();");
            TForm::sendData(self::$formName, (object)['id' => $object->id]);

    */
     try
    {
        TTransaction::open(self::$database);

        $messageAction = null;

        $this->form->validate();

        $data = $this->form->getData();

        // 1) NÃO criar sempre um novo Premio: se tiver ID, edita o existente
        $object = null;
        if (!empty($data->id)) {
            $object = new Premio((int) $data->id);
        } else {
            $object = new Premio();
        }

        $object->fromArray((array) $data);
        $object->store();

        // 2) Salvar corretamente ap_grupo_estoque_id e ap_subgrupo_estoque_id na RestricaoPremio
        $restricao_premio_premio_items = $this->storeItems(
            'RestricaoPremio',
            'premio_id',
            $object,
            $this->fieldList_6967a797e9c15,
            function($masterObject, $detailObject) {

                $subgrupo_id = !empty($detailObject->ap_subgrupo_estoque_id)
                    ? (int) $detailObject->ap_subgrupo_estoque_id
                    : null;

                $grupo_id = !empty($detailObject->ap_grupo_estoque_id)
                    ? (int) $detailObject->ap_grupo_estoque_id
                    : null;

                // Se selecionou SUBGRUPO: salva subgrupo e força o grupo correspondente dele
                if ($subgrupo_id) {
                    $sub = new ApSubgrupoEstoque($subgrupo_id);

                    $detailObject->ap_subgrupo_estoque_id = $subgrupo_id;
                    $detailObject->ap_grupo_estoque_id    = (int) $sub->grupo_estoque_id;
                    return;
                }

                // Se NÃO selecionou subgrupo e selecionou só GRUPO: salva só grupo e zera subgrupo
                if ($grupo_id) {
                    $detailObject->ap_grupo_estoque_id    = $grupo_id;
                    $detailObject->ap_subgrupo_estoque_id = null;
                    return;
                }

                // Se não selecionou nada, salva nulo nos dois (ou você pode lançar exceção aqui)
                $detailObject->ap_grupo_estoque_id    = null;
                $detailObject->ap_subgrupo_estoque_id = null;

            },
            $this->criteria_fieldList_6967a797e9c15
        );

        $data->id = $object->id;
        $this->form->setData($data);

        TTransaction::close();

        new TMessage('info', "Registro salvo", $messageAction);
        TScript::create("Template.closeRightPanel();");
        TForm::sendData(self::$formName, (object)['id' => $object->id]);

        }
        catch (Exception $e) // in case of exception
        {

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Premio($key); // instantiates the Active Record 

                $this->fieldList_6967a797e9c15_items = $this->loadItems('RestricaoPremio', 'premio_id', $object, $this->fieldList_6967a797e9c15, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_fieldList_6967a797e9c15); 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

        $this->fieldList_6967a797e9c15->addHeader();
        $this->fieldList_6967a797e9c15->addDetail($this->default_item_fieldList_6967a797e9c15);

        $this->fieldList_6967a797e9c15->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

/*

    }

    public function onShow($param = null)
    {
        $this->fieldList_6967a797e9c15->addHeader();
        $this->fieldList_6967a797e9c15->addDetail($this->default_item_fieldList_6967a797e9c15);

        $this->fieldList_6967a797e9c15->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

*/
    $this->fieldList_6967a797e9c15->addHeader();

    // Se estiver editando (veio key), NÃO adiciona linha default vazia
    if (empty($param['key'])) {
        $this->fieldList_6967a797e9c15->addDetail($this->default_item_fieldList_6967a797e9c15);
    }

    $this->fieldList_6967a797e9c15->addCloneAction(null, 'fas:plus #69aa46', "Clonar");
    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

