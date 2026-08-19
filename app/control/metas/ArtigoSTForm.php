<?php

class ArtigoSTForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'MiniMeta';
    private static $primaryKey = 'id';
    private static $formName = 'form_MiniMetaForm';

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
        $this->form->setFormTitle("Cadastro de Artigo com ST");

        $criteria_mini_meta_tipo_id = new TCriteria();
        $criteria_mini_meta_item_mini_meta_cod_item = new TCriteria();

        $id = new TEntry('id');
        $system_unit_id = new THidden('system_unit_id');
        $mini_meta_tipo_id = new TDBCombo('mini_meta_tipo_id', 'integrador', 'MiniMetaTipo', 'id', '{nome}','nome asc' , $criteria_mini_meta_tipo_id );
        $status = new TCombo('status');
        $painel = new TSpinner('painel');
        $descricao = new TEntry('descricao');
        $ano = new TSpinner('ano');
        $mes = new TCombo('mes');
        $data_inicial = new TDateTime('data_inicial');
        $data_final = new TDateTime('data_final');
        $min = new TNumeric('min', '2', ',', '.' );
        $qtde = new TNumeric('qtde', '2', ',', '.' );
        $valor_unitario_min = new TNumeric('valor_unitario_min', '2', ',', '.' );
        $mini_meta_item_mini_meta_id = new THidden('mini_meta_item_mini_meta_id[]');
        $mini_meta_item_mini_meta___row__id = new THidden('mini_meta_item_mini_meta___row__id[]');
        $mini_meta_item_mini_meta___row__data = new THidden('mini_meta_item_mini_meta___row__data[]');
        $mini_meta_item_mini_meta_cod_item = new TDBUniqueSearch('mini_meta_item_mini_meta_cod_item[]', 'integrador', 'ApItem', 'codigo', 'descricao','id asc' , $criteria_mini_meta_item_mini_meta_cod_item );
        $this->fieldList_6682a189b989b = new TFieldList();
        $stFechamento = new BPageContainer();
        $miniMetaFechamento = new BPageContainer();

        $this->fieldList_6682a189b989b->addField(null, $mini_meta_item_mini_meta_id, []);
        $this->fieldList_6682a189b989b->addField(null, $mini_meta_item_mini_meta___row__id, ['uniqid' => true]);
        $this->fieldList_6682a189b989b->addField(null, $mini_meta_item_mini_meta___row__data, []);
        $this->fieldList_6682a189b989b->addField(new TLabel("Item", null, '14px', null), $mini_meta_item_mini_meta_cod_item, ['width' => '100%']);

        $this->fieldList_6682a189b989b->width = '100%';
        $this->fieldList_6682a189b989b->setFieldPrefix('mini_meta_item_mini_meta');
        $this->fieldList_6682a189b989b->name = 'fieldList_6682a189b989b';

        $this->criteria_fieldList_6682a189b989b = new TCriteria();
        $this->default_item_fieldList_6682a189b989b = new stdClass();

        $this->form->addField($mini_meta_item_mini_meta_id);
        $this->form->addField($mini_meta_item_mini_meta___row__id);
        $this->form->addField($mini_meta_item_mini_meta___row__data);
        $this->form->addField($mini_meta_item_mini_meta_cod_item);

        $this->fieldList_6682a189b989b->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $mes->setChangeAction(new TAction([$this,'onSelectMes']));

        $mini_meta_tipo_id->addValidation("Tipo", new TRequiredValidator()); 
        $descricao->addValidation("Descrição", new TRequiredValidator()); 
        $ano->addValidation("Ano", new TRequiredValidator()); 
        $mes->addValidation("Mês", new TRequiredValidator()); 

        $id->setEditable(false);
        $descricao->setMaxLength(255);
        $mini_meta_item_mini_meta_cod_item->setMinLength(2);
        $mini_meta_item_mini_meta_cod_item->setFilterColumns(["codigo","descricao"]);
        $status->addItems(["0"=>"Aguardando","1"=>"Aberto","2"=>"Fechado","3"=>"Cancelado"]);
        $mes->addItems(["01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro"]);

        $painel->setRange(1, 2000, 1);
        $ano->setRange(2024, 3024, 1);

        $data_final->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_inicial->setDatabaseMask('yyyy-mm-dd hh:ii');

        $stFechamento->setAction(new TAction(['STApTabelaPrecoSimpleList', 'onShow']));
        $miniMetaFechamento->setAction(new TAction(['MiniMetaFechamentoSimpleList', 'onShow']));

        $stFechamento->setId('b68949d09178b9');
        $miniMetaFechamento->setId('b689dc445da2ee');

        $stFechamento->hide();
        $miniMetaFechamento->hide();

        $mes->enableSearch();
        $status->enableSearch();
        $mini_meta_tipo_id->enableSearch();

        $data_final->setMask('dd/mm/yyyy hh:ii');
        $data_inicial->setMask('dd/mm/yyyy hh:ii');
        $mini_meta_item_mini_meta_cod_item->setMask('{codigo} - {descricao}');

        $min->setValue('0');
        $qtde->setValue('0');
        $status->setValue('1');
        $ano->setValue(date('Y'));
        $mes->setValue(date('m'));
        $mini_meta_tipo_id->setValue(MiniMetaTipo::QTDE);
        $system_unit_id->setValue(TSession::getValue("userunitid"));

        $id->setSize(100);
        $ano->setSize('100%');
        $mes->setSize('100%');
        $min->setSize('100%');
        $qtde->setSize('100%');
        $status->setSize('100%');
        $painel->setSize('100%');
        $data_final->setSize(150);
        $descricao->setSize('100%');
        $data_inicial->setSize(150);
        $system_unit_id->setSize(200);
        $stFechamento->setSize('100%');
        $mini_meta_tipo_id->setSize('100%');
        $valor_unitario_min->setSize('100%');
        $miniMetaFechamento->setSize('100%');
        $mini_meta_item_mini_meta_cod_item->setSize('100%');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $stFechamento->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $miniMetaFechamento->add($loadingContainer);

        $this->stFechamento = $stFechamento;
        $this->miniMetaFechamento = $miniMetaFechamento;

        $this->form->appendPage("Descrição");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id,$system_unit_id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Tipo:", '#ff0000', '14px', null, '100%'),$mini_meta_tipo_id],[new TLabel("Status:", '#FF0000', '14px', null, '100%'),$status],[new TLabel("Painel:", '#FF0000', '14px', null, '100%'),$painel]);
        $row2->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("Descrição:", '#FF0000', '14px', null, '100%'),$descricao]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([new TLabel("Ano:", '#FF0000', '14px', null, '100%'),$ano],[new TLabel("Mês:", '#FF0000', '14px', null, '100%'),$mes]);
        $row4->layout = [' col-sm-4',' col-sm-4'];

        $row5 = $this->form->addFields([new TLabel("Data inicial:", null, '14px', null, '100%'),$data_inicial],[new TLabel("Data final:", null, '14px', null, '100%'),$data_final]);
        $row5->layout = [' col-sm-4',' col-sm-4'];

        $row6 = $this->form->addFields([new TLabel("Mínimo:", null, '14px', null, '100%'),$min],[new TLabel("Valor Prêmio:", null, '14px', null, '100%'),$qtde],[new TLabel("Valor Unitário Mínimo:", '#FF0000', '14px', null),$valor_unitario_min]);
        $row6->layout = ['col-sm-4','col-sm-4',' col-sm-4'];

        $this->form->appendPage("Itens");
        $row7 = $this->form->addFields([$this->fieldList_6682a189b989b]);
        $row7->layout = [' col-sm-12'];

        $this->form->appendPage("Tabela de Preço");
        $row8 = $this->form->addFields([$stFechamento]);
        $row8->layout = [' col-sm-12'];

        $this->form->appendPage("Fechamento");
        $row9 = $this->form->addFields([$miniMetaFechamento]);
        $row9->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['ArtigosSTHeaderList', 'onShow']), 'fas:arrow-left #000000');
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

        $style = new TStyle('right-panel > .container-part[page-name=ArtigoSTForm]');
        $style->width = '75% !important';   
        $style->show(true);

    }

    public static function onSelectMes($param = null) 
    {
        try 
        {
            if($param['ano'] && $param['mes']){
                TTransaction::open(self::$database);

                $mes = $param['mes'];
                $ano = $param['ano'];
                $dataBaseCalc = new DateTime("$ano-$mes");
                $ultimoDia = $dataBaseCalc->format('t');

                $data = new stdClass();

                $data->data_inicial = "01/$mes/$ano 00:00";
                $data->data_final = "$ultimoDia/$mes/$ano 23:59";

                $intervalo = new DateInterval( "P1M");

                TForm::sendData(self::$formName, $data);

                TTransaction::close();
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new MiniMeta(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->system_unit_id = TSession::getValue('userunitid');

            $configuradosDashboard = 8; // Número de paineis configurados no BI Adianti Reports
            if($object->painel > $configuradosDashboard){
                TToast::show("info", "Entre em contato com os desenvolvedores para mais de $configuradosDashboard paineis.", "topLeft", "");
            }

            if($object->data_inicial > $object->data_final){
                throw new Exception("A data final deve ser maior que a data inicial.");
            }

            $data_inicial = new DateTime($object->data_inicial);
            $data_final = new DateTime($object->data_final);

            switch((int) $object->mes){
                case 1:
                    $object->mes_ano = "Janeiro/$object->ano";
                    break;
                case 2:
                    $object->mes_ano = "Fevereiro/$object->ano";
                    break;
                case 3:
                    $object->mes_ano = "Março/$object->ano";
                    break;
                case 4:
                    $object->mes_ano = "Abril/$object->ano";
                    break;
                case 5:
                    $object->mes_ano = "Maio/$object->ano";
                    break;
                case 6:
                    $object->mes_ano = "Junho/$object->ano";
                    break;
                case 7:
                    $object->mes_ano = "Julho/$object->ano";
                    break;
                case 8:
                    $object->mes_ano = "Agosto/$object->ano";
                    break;
                case 9:
                    $object->mes_ano = "Setembro/$object->ano";
                    break;
                case 10:
                    $object->mes_ano = "Outubro/$object->ano";
                    break;
                case 11:
                    $object->mes_ano = "Novembro/$object->ano";
                    break;
                case 12:
                    $object->mes_ano = "Dezembro/$object->ano";
                    break;
                default:
                    $object->mes_ano = (int)$object->mes."/".$object->ano;
                    break;
            }

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            $mini_meta_item_mini_meta_items = $this->storeItems('MiniMetaItem', 'mini_meta_id', $object, $this->fieldList_6682a189b989b, function($masterObject, $detailObject){ 

                $detailObject->cod_item = trim($detailObject->cod_item);

            }, $this->criteria_fieldList_6682a189b989b); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('ArtigosSTHeaderList', 'onShow', $loadPageParam); 

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

                $object = new MiniMeta($key); // instantiates the Active Record 

                                $this->stFechamento->unhide();
                $this->stFechamento->setParameter('mini_meta_id', $object->id);
                $this->miniMetaFechamento->unhide();
                $this->miniMetaFechamento->setParameter('mini_meta_id', $object->id);

                $this->fieldList_6682a189b989b_items = $this->loadItems('MiniMetaItem', 'mini_meta_id', $object, $this->fieldList_6682a189b989b, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_fieldList_6682a189b989b); 

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

        $this->fieldList_6682a189b989b->addHeader();
        $this->fieldList_6682a189b989b->addDetail($this->default_item_fieldList_6682a189b989b);

        $this->fieldList_6682a189b989b->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    }

    public function onShow($param = null)
    {
        $this->fieldList_6682a189b989b->addHeader();
        $this->fieldList_6682a189b989b->addDetail($this->default_item_fieldList_6682a189b989b);

        $this->fieldList_6682a189b989b->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

