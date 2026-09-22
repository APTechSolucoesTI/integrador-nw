<?php

class PersianaAgrupamentoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'PersianaAgrupamento';
    private static $primaryKey = 'id';
    private static $formName = 'form_PersianaAgrupamentoForm';

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
        $this->form->setFormTitle("Cadastro de persiana agrupamento");

        $criteria_persiana_agrupamento_grupo_persiana_agrupamento_ap_grupo_estoque_id = new TCriteria();

        $nome = new TEntry('nome');
        $id = new THidden('id');
        $qtd = new TEntry('qtd');
        $ativo = new TCombo('ativo');
        $eficiencia_operacional = new TNumeric('eficiencia_operacional', '2', ',', '.' );
        $data_inicio = new TDate('data_inicio');
        $data_fim = new TDate('data_fim');
        $persiana_agrupamento_grupo_persiana_agrupamento_id = new THidden('persiana_agrupamento_grupo_persiana_agrupamento_id[]');
        $persiana_agrupamento_grupo_persiana_agrupamento___row__id = new THidden('persiana_agrupamento_grupo_persiana_agrupamento___row__id[]');
        $persiana_agrupamento_grupo_persiana_agrupamento___row__data = new THidden('persiana_agrupamento_grupo_persiana_agrupamento___row__data[]');
        $persiana_agrupamento_grupo_persiana_agrupamento_ap_grupo_estoque_id = new TDBCombo('persiana_agrupamento_grupo_persiana_agrupamento_ap_grupo_estoque_id[]', 'integrador', 'ApGrupoEstoque', 'id', '{cod_grupoestoque} - {descricao}','descricao asc' , $criteria_persiana_agrupamento_grupo_persiana_agrupamento_ap_grupo_estoque_id );
        $this->fieldList_6aa05d2d8ed33 = new TFieldList();
        $persiana_agrupamento_id_dias = new BPageContainer();
        $excecoes_persianas = new BPageContainer();

        $this->fieldList_6aa05d2d8ed33->addField(null, $persiana_agrupamento_grupo_persiana_agrupamento_id, []);
        $this->fieldList_6aa05d2d8ed33->addField(null, $persiana_agrupamento_grupo_persiana_agrupamento___row__id, ['uniqid' => true]);
        $this->fieldList_6aa05d2d8ed33->addField(null, $persiana_agrupamento_grupo_persiana_agrupamento___row__data, []);
        $this->fieldList_6aa05d2d8ed33->addField(new TLabel("Grupos de Estoque", null, '14px', null), $persiana_agrupamento_grupo_persiana_agrupamento_ap_grupo_estoque_id, ['width' => '100%']);

        $this->fieldList_6aa05d2d8ed33->width = '100%';
        $this->fieldList_6aa05d2d8ed33->setFieldPrefix('persiana_agrupamento_grupo_persiana_agrupamento');
        $this->fieldList_6aa05d2d8ed33->name = 'fieldList_6aa05d2d8ed33';

        $this->criteria_fieldList_6aa05d2d8ed33 = new TCriteria();
        $this->default_item_fieldList_6aa05d2d8ed33 = new stdClass();

        $this->form->addField($persiana_agrupamento_grupo_persiana_agrupamento_id);
        $this->form->addField($persiana_agrupamento_grupo_persiana_agrupamento___row__id);
        $this->form->addField($persiana_agrupamento_grupo_persiana_agrupamento___row__data);
        $this->form->addField($persiana_agrupamento_grupo_persiana_agrupamento_ap_grupo_estoque_id);

        $this->fieldList_6aa05d2d8ed33->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $nome->addValidation("Nome do Grupo", new TRequiredValidator()); 
        $qtd->addValidation("Limite Diário", new TRequiredValidator()); 
        $ativo->addValidation("Ativo", new TRequiredValidator()); 
        $eficiencia_operacional->addValidation("E.O", new TRequiredValidator()); 
        $persiana_agrupamento_grupo_persiana_agrupamento_ap_grupo_estoque_id->addValidation("Grupo de Estoque", new TRequiredListValidator()); 

        $nome->setMaxLength(30);
        $ativo->addItems(["S"=>"Sim","N"=>"Não"]);
        $data_fim->setEditable(false);
        $ativo->setValue('S');
        $data_inicio->setValue(date('d/m/Y'));

        $ativo->enableSearch();
        $persiana_agrupamento_grupo_persiana_agrupamento_ap_grupo_estoque_id->enableSearch();

        $data_fim->setMask('dd/mm/yyyy');
        $data_inicio->setMask('dd/mm/yyyy');

        $data_fim->setDatabaseMask('yyyy-mm-dd');
        $data_inicio->setDatabaseMask('yyyy-mm-dd');

        $excecoes_persianas->setAction(new TAction(['PersianaAgrupamentoExcecaoHeaderList', 'onShow']));
        $persiana_agrupamento_id_dias->setAction(new TAction(['PersianaAgrupamentoDiasSimpleList', 'onShow']));

        $excecoes_persianas->setId('b6aa05affc3e9d');
        $persiana_agrupamento_id_dias->setId('b6aa0589aaf2a2');

        $id->setSize(200);
        $qtd->setSize('100%');
        $nome->setSize('100%');
        $ativo->setSize('100%');
        $data_fim->setSize(280);
        $data_inicio->setSize(280);
        $excecoes_persianas->setSize('100%');
        $eficiencia_operacional->setSize('100%');
        $persiana_agrupamento_id_dias->setSize('100%');
        $persiana_agrupamento_grupo_persiana_agrupamento_ap_grupo_estoque_id->setSize('100%');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $persiana_agrupamento_id_dias->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $excecoes_persianas->add($loadingContainer);

        $this->persiana_agrupamento_id_dias = $persiana_agrupamento_id_dias;
        $this->excecoes_persianas = $excecoes_persianas;

        if (empty($param['key']))
        {
            $dias_novo = new TCheckGroup('dias_novo');

            $dias_novo->addItems([
                1 => 'Segunda',
                2 => 'Terça',
                3 => 'Quarta',
                4 => 'Quinta',
                5 => 'Sexta',
                6 => 'Sábado',
                7 => 'Domingo'
            ]);

            // padrão inicial igual ao IMPORT
            $dias_novo->setValue([
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5
            ]);

            $dias_novo->setSize('100%');

            // No cadastro novo substitui o container vazio
            // pelo checklist temporário
            $persiana_agrupamento_id_dias = $dias_novo;
        }
        $row1 = $this->form->addFields([new TLabel("Nome Grupo:", '#ff0000', '14px', null),$nome,$id],[new TLabel("Limite Diário:", '#ff0000', '14px', null),$qtd],[new TLabel("Ativo:", '#FF0000', '14px', null),$ativo],[new TLabel("Eficiência Operacional:", '#FF0000', '14px', null),$eficiencia_operacional]);
        $row1->layout = ['col-sm-4','col-sm-3','col-sm-2',' col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Data Inicio:", null, '14px', null, '100%'),$data_inicio,new TLabel("Data Fim:", null, '14px', null, '100%'),$data_fim]);
        $row2->layout = ['col-sm-4'];

        $row3 = $this->form->addFields([$this->fieldList_6aa05d2d8ed33]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([$persiana_agrupamento_id_dias]);
        $row4->layout = [' col-sm-12'];

        $row5 = $this->form->addFields([$excecoes_persianas]);
        $row5->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['PersianaAgrupamentoHeaderList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=PersianaAgrupamentoForm]');
        $style->width = '65% !important';   
        $style->show(true);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new PersianaAgrupamento(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            self::garantirDiasAgrupamento(
                $object->id,
                $data->dias_novo ?? null
            );

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

//<generatedAutoCode>
            $this->criteria_fieldList_6aa05d2d8ed33->setProperty('order', 'ap_grupo_estoque_id asc');
//</generatedAutoCode>
            $persiana_agrupamento_grupo_persiana_agrupamento_items = $this->storeItems('PersianaAgrupamentoGrupo', 'persiana_agrupamento_id', $object, $this->fieldList_6aa05d2d8ed33, function($masterObject, $detailObject){ 

                //code here

            }, $this->criteria_fieldList_6aa05d2d8ed33); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('PersianaAgrupamentoHeaderList', 'onShow', $loadPageParam); 

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

                $object = new PersianaAgrupamento($key); // instantiates the Active Record 

                self::garantirDiasAgrupamento($object->id);
                                $this->persiana_agrupamento_id_dias->setParameter('persiana_agrupamento_id', $object->id);
                $this->excecoes_persianas->setParameter('persiana_agrupamento_id', $object->id);

                $this->criteria_fieldList_6aa05d2d8ed33->setProperty('order', 'ap_grupo_estoque_id asc');
                $this->fieldList_6aa05d2d8ed33_items = $this->loadItems('PersianaAgrupamentoGrupo', 'persiana_agrupamento_id', $object, $this->fieldList_6aa05d2d8ed33, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_fieldList_6aa05d2d8ed33); 

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

        $this->fieldList_6aa05d2d8ed33->addHeader();
        $this->fieldList_6aa05d2d8ed33->addDetail($this->default_item_fieldList_6aa05d2d8ed33);

        $this->fieldList_6aa05d2d8ed33->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    }

    public function onShow($param = null)
    {
        $this->fieldList_6aa05d2d8ed33->addHeader();
        $this->fieldList_6aa05d2d8ed33->addDetail($this->default_item_fieldList_6aa05d2d8ed33);

        $this->fieldList_6aa05d2d8ed33->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

    private static function garantirDiasAgrupamento($agrupamentoId, $diasSelecionados = null)
    {
        $agrupamentoId = (int) $agrupamentoId;

        for ($dia = 1; $dia <= 7; $dia++)
        {
            $existente = PersianaAgrupamentoDias::where(
                'persiana_agrupamento_id',
                '=',
                $agrupamentoId
            )
            ->where(
                'dia',
                '=',
                $dia
            )
            ->first();

            if (!$existente)
            {
                $registro = new PersianaAgrupamentoDias();

                $registro->persiana_agrupamento_id = $agrupamentoId;
                $registro->dia = $dia;

                if ($diasSelecionados !== null)
                {
                    $registro->valido =
                        in_array($dia, (array) $diasSelecionados)
                        ? 'S'
                        : 'N';
                }
                else
                {
                    // fallback para agrupamentos antigos
                    $registro->valido =
                        ($dia <= 5) ? 'S' : 'N';
                }

                $registro->store();
            }
        }
    }

}

