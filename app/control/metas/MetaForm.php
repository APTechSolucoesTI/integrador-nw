<?php

class MetaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'Meta';
    private static $primaryKey = 'id';
    private static $formName = 'form_MetaForm';

    use BuilderMasterDetailTrait;

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
        $this->form->setFormTitle("Cadastro de Metas");

        $criteria_meta_repres_meta_repres_id = new TCriteria();

        $filterVar = "S";
        $criteria_meta_repres_meta_repres_id->add(new TFilter('ativo', '=', $filterVar)); 

        $id = new TEntry('id');
        $status = new TCombo('status');
        $ano = new TSpinner('ano');
        $mes = new TCombo('mes');
        $data_inicial = new TDateTime('data_inicial');
        $data_final = new TDateTime('data_final');
        $data_entrega = new TDateTime('data_entrega');
        $data_me = new TDateTime('data_me');
        $data_abertura = new TDateTime('data_abertura');
        $dias_uteis = new TEntry('dias_uteis');
        $feriados = new TEntry('feriados');
        $dias_disponiveis = new TEntry('dias_disponiveis');
        $meta_feriado_meta_data_feriado = new TDate('meta_feriado_meta_data_feriado');
        $meta_feriado_meta_id = new THidden('meta_feriado_meta_id');
        $meta_feriado_meta_descricao = new TEntry('meta_feriado_meta_descricao');
        $button_adicionar_meta_feriado_meta = new TButton('button_adicionar_meta_feriado_meta');
        $meta_repres_meta_repres_id = new TDBUniqueSearch('meta_repres_meta_repres_id', 'integrador', 'ApRepresentante', 'id', 'fantasia','fantasia asc' , $criteria_meta_repres_meta_repres_id );
        $meta_repres_meta_id = new THidden('meta_repres_meta_id');
        $meta_repres_meta_valor_meta = new TNumeric('meta_repres_meta_valor_meta', '2', ',', '.' );
        $meta_repres_meta_valor_super_meta = new TNumeric('meta_repres_meta_valor_super_meta', '2', ',', '.' );
        $meta_repres_meta_perc_import = new TNumeric('meta_repres_meta_perc_import', '2', ',', '.' );
        $meta_repres_meta_valor_import = new TNumeric('meta_repres_meta_valor_import', '2', ',', '.' );
        $meta_repres_meta_perc_persianas = new TNumeric('meta_repres_meta_perc_persianas', '2', ',', '.' );
        $meta_repres_meta_valor_persianas = new TNumeric('meta_repres_meta_valor_persianas', '2', ',', '.' );
        $meta_repres_meta_valor_cortina = new TNumeric('meta_repres_meta_valor_cortina', '2', ',', '.' );
        $meta_repres_meta_valor_mostruario = new TNumeric('meta_repres_meta_valor_mostruario', '2', ',', '.' );
        $meta_repres_meta_valor_prosp_reat = new TNumeric('meta_repres_meta_valor_prosp_reat', '2', ',', '.' );
        $meta_repres_meta_perc_site = new TSpinner('meta_repres_meta_perc_site');
        $button_adicionar_meta_repres_meta = new TButton('button_adicionar_meta_repres_meta');

        $mes->setChangeAction(new TAction([$this,'onSelectMes']));

        $meta_repres_meta_repres_id->setMinLength(2);
        $meta_repres_meta_repres_id->setFilterColumns(["cod_repres","fantasia"]);
        $status->addItems(["0"=>"Aguardando","1"=>"Aberto","2"=>"Fechado","3"=>"Cancelado"]);
        $mes->addItems(["01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro"]);

        $mes->enableSearch();
        $status->enableSearch();

        $ano->setRange(2020, 3000, 1);
        $meta_repres_meta_perc_site->setRange(0, 100, 1);

        $button_adicionar_meta_repres_meta->setAction(new TAction([$this, 'onAddDetailMetaRepresMetas'],['static' => 1]), "Adicionar");
        $button_adicionar_meta_feriado_meta->setAction(new TAction([$this, 'onAddDetailMetaFeriadoMetas'],['static' => 1]), "Adicionar");

        $button_adicionar_meta_repres_meta->addStyleClass('btn-default');
        $button_adicionar_meta_feriado_meta->addStyleClass('btn-default');

        $button_adicionar_meta_repres_meta->setImage('fas:plus #2ecc71');
        $button_adicionar_meta_feriado_meta->setImage('fas:plus #2ecc71');

        $status->setValue('1');
        $ano->setValue(date('Y'));
        $mes->setValue(date('m'));

        $id->setEditable(false);
        $feriados->setEditable(false);
        $dias_uteis->setEditable(false);
        $dias_disponiveis->setEditable(false);

        $meta_repres_meta_valor_meta->setMaxLength(17);
        $meta_repres_meta_valor_cortina->setMaxLength(17);
        $meta_repres_meta_valor_super_meta->setMaxLength(17);
        $meta_repres_meta_valor_mostruario->setMaxLength(17);
        $meta_repres_meta_valor_prosp_reat->setMaxLength(17);

        $data_me->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_final->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_inicial->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_entrega->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_abertura->setDatabaseMask('yyyy-mm-dd hh:ii');
        $meta_feriado_meta_data_feriado->setDatabaseMask('yyyy-mm-dd');

        $data_me->setMask('dd/mm/yyyy hh:ii');
        $data_final->setMask('dd/mm/yyyy hh:ii');
        $data_inicial->setMask('dd/mm/yyyy hh:ii');
        $data_entrega->setMask('dd/mm/yyyy hh:ii');
        $data_abertura->setMask('dd/mm/yyyy hh:ii');
        $meta_repres_meta_repres_id->setMask('{fantasia}');
        $meta_feriado_meta_data_feriado->setMask('dd/mm/yyyy');

        $id->setSize(100);
        $ano->setSize('100%');
        $mes->setSize('100%');
        $data_me->setSize(150);
        $status->setSize('100%');
        $data_final->setSize(150);
        $feriados->setSize('100%');
        $data_inicial->setSize(150);
        $data_entrega->setSize(150);
        $data_abertura->setSize(150);
        $dias_uteis->setSize('100%');
        $dias_disponiveis->setSize('100%');
        $meta_repres_meta_id->setSize(200);
        $meta_feriado_meta_id->setSize(200);
        $meta_repres_meta_repres_id->setSize('100%');
        $meta_repres_meta_perc_site->setSize('100%');
        $meta_feriado_meta_descricao->setSize('100%');
        $meta_repres_meta_valor_meta->setSize('100%');
        $meta_repres_meta_perc_import->setSize('35%');
        $meta_repres_meta_valor_import->setSize('40%');
        $meta_feriado_meta_data_feriado->setSize('100%');
        $meta_repres_meta_perc_persianas->setSize('35%');
        $meta_repres_meta_valor_cortina->setSize('100%');
        $meta_repres_meta_valor_persianas->setSize('40%');
        $meta_repres_meta_valor_super_meta->setSize('100%');
        $meta_repres_meta_valor_mostruario->setSize('100%');
        $meta_repres_meta_valor_prosp_reat->setSize('100%');

        $button_adicionar_meta_repres_meta->id = '6616c513de1d8';
        $button_adicionar_meta_feriado_meta->id = '6616c4dcde1d4';

        $this->form->appendPage("Meta");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Status:", null, '14px', null, '100%'),$status]);
        $row1->layout = [' col-sm-4',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Ano:", null, '14px', null, '100%'),$ano],[new TLabel("Mês:", null, '14px', null, '100%'),$mes]);
        $row2->layout = [' col-sm-4',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("Data Inicial:", null, '14px', null, '100%'),$data_inicial],[new TLabel("Data Final:", null, '14px', null, '100%'),$data_final]);
        $row3->layout = [' col-sm-4',' col-sm-4'];

        $row4 = $this->form->addFields([new TLabel("Data Entrega:", null, '14px', null, '100%'),$data_entrega],[new TLabel("Data Mega Entrega:", null, '14px', null, '100%'),$data_me],[new TLabel("Data Abertura:", null, '14px', null, '100%'),$data_abertura]);
        $row4->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row5 = $this->form->addFields([new TLabel("Dias Uteis:", null, '14px', null, '100%'),$dias_uteis],[new TLabel("Feriados:", null, '14px', null, '100%'),$feriados],[new TLabel("Dias Disponíveis:", null, '14px', null, '100%'),$dias_disponiveis]);
        $row5->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $this->form->appendPage("Feriados");

        $this->detailFormMetaFeriadoMeta = new BootstrapFormBuilder('detailFormMetaFeriadoMeta');
        $this->detailFormMetaFeriadoMeta->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormMetaFeriadoMeta->setProperty('class', 'form-horizontal builder-detail-form');

        $row6 = $this->detailFormMetaFeriadoMeta->addFields([new TLabel("Data do Feriado:", null, '14px', null, '100%'),$meta_feriado_meta_data_feriado,$meta_feriado_meta_id],[new TLabel("Descrição:", null, '14px', null, '100%'),$meta_feriado_meta_descricao]);
        $row6->layout = ['col-sm-3',' col-sm-9'];

        $row7 = $this->detailFormMetaFeriadoMeta->addFields([$button_adicionar_meta_feriado_meta]);
        $row7->layout = [' col-sm-12'];

        $row8 = $this->detailFormMetaFeriadoMeta->addFields([new THidden('meta_feriado_meta__row__id')]);
        $this->meta_feriado_meta_criteria = new TCriteria();

        $this->meta_feriado_meta_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->meta_feriado_meta_list->generateHiddenFields();
        $this->meta_feriado_meta_list->setId('meta_feriado_meta_list');

        $this->meta_feriado_meta_list->style = 'width:100%';
        $this->meta_feriado_meta_list->class .= ' table-bordered';

        $column_meta_feriado_meta_data_feriado_transformed = new TDataGridColumn('data_feriado', "Data feriado", 'left');
        $column_meta_feriado_meta_descricao = new TDataGridColumn('descricao', "Descrição", 'left');

        $column_meta_feriado_meta__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_meta_feriado_meta__row__data->setVisibility(false);

        $action_onEditDetailMetaFeriado = new TDataGridAction(array('MetaForm', 'onEditDetailMetaFeriado'));
        $action_onEditDetailMetaFeriado->setUseButton(false);
        $action_onEditDetailMetaFeriado->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailMetaFeriado->setLabel("Editar");
        $action_onEditDetailMetaFeriado->setImage('far:edit #478fca');
        $action_onEditDetailMetaFeriado->setFields(['__row__id', '__row__data']);

        $this->meta_feriado_meta_list->addAction($action_onEditDetailMetaFeriado);
        $action_onDeleteDetailMetaFeriado = new TDataGridAction(array('MetaForm', 'onDeleteDetailMetaFeriado'));
        $action_onDeleteDetailMetaFeriado->setUseButton(false);
        $action_onDeleteDetailMetaFeriado->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailMetaFeriado->setLabel("Excluir");
        $action_onDeleteDetailMetaFeriado->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailMetaFeriado->setFields(['__row__id', '__row__data']);

        $this->meta_feriado_meta_list->addAction($action_onDeleteDetailMetaFeriado);

        $this->meta_feriado_meta_list->addColumn($column_meta_feriado_meta_data_feriado_transformed);
        $this->meta_feriado_meta_list->addColumn($column_meta_feriado_meta_descricao);

        $this->meta_feriado_meta_list->addColumn($column_meta_feriado_meta__row__data);

        $this->meta_feriado_meta_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->meta_feriado_meta_list);
        $this->detailFormMetaFeriadoMeta->addContent([$tableResponsiveDiv]);

        $column_meta_feriado_meta_data_feriado_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });        $row9 = $this->form->addFields([$this->detailFormMetaFeriadoMeta]);
        $row9->layout = [' col-sm-12'];

        $this->form->appendPage("Consultor(a)");

        $this->detailFormMetaRepresMeta = new BootstrapFormBuilder('detailFormMetaRepresMeta');
        $this->detailFormMetaRepresMeta->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormMetaRepresMeta->setProperty('class', 'form-horizontal builder-detail-form');

        $row10 = $this->detailFormMetaRepresMeta->addFields([new TLabel("Consultor(a):", null, '14px', null, '100%'),$meta_repres_meta_repres_id,$meta_repres_meta_id]);
        $row10->layout = [' col-sm-8'];

        $row11 = $this->detailFormMetaRepresMeta->addFields([new TLabel("Meta:", null, '14px', null, '100%'),$meta_repres_meta_valor_meta],[new TLabel("Super Meta:", null, '14px', null, '100%'),$meta_repres_meta_valor_super_meta]);
        $row11->layout = [' col-sm-6',' col-sm-6'];

        $row12 = $this->detailFormMetaRepresMeta->addFields([new TLabel("IMPORT:", null, '14px', null, '100%'),$meta_repres_meta_perc_import,new TLabel(" acima de  ", null, '14px', null),$meta_repres_meta_valor_import],[new TLabel("PERSIANAS:", null, '14px', null, '100%'),$meta_repres_meta_perc_persianas,new TLabel(" acima de ", null, '14px', null),$meta_repres_meta_valor_persianas]);
        $row12->layout = [' col-sm-6',' col-sm-6'];

        $row13 = $this->detailFormMetaRepresMeta->addFields([new TLabel("Cortina:", null, '14px', null, '100%'),$meta_repres_meta_valor_cortina],[new TLabel("Mostruário:", null, '14px', null, '100%'),$meta_repres_meta_valor_mostruario]);
        $row13->layout = [' col-sm-6',' col-sm-6'];

        $row14 = $this->detailFormMetaRepresMeta->addFields([new TLabel("Prospecção & Reativação:", null, '14px', null, '100%'),$meta_repres_meta_valor_prosp_reat]);
        $row14->layout = ['col-sm-6'];

        $row15 = $this->detailFormMetaRepresMeta->addFields([new TFormSeparator("Percentual", '#333', '14', '#eee')]);
        $row15->layout = [' col-sm-12'];

        $row16 = $this->detailFormMetaRepresMeta->addFields([new TLabel("Site:", null, '14px', null, '100%'),$meta_repres_meta_perc_site]);
        $row16->layout = ['col-sm-3'];

        $row17 = $this->detailFormMetaRepresMeta->addFields([$button_adicionar_meta_repres_meta]);
        $row17->layout = [' col-sm-12'];

        $row18 = $this->detailFormMetaRepresMeta->addFields([new THidden('meta_repres_meta__row__id')]);
        $this->meta_repres_meta_criteria = new TCriteria();

        $this->meta_repres_meta_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->meta_repres_meta_list->generateHiddenFields();
        $this->meta_repres_meta_list->setId('meta_repres_meta_list');

        $this->meta_repres_meta_list->style = 'width:100%';
        $this->meta_repres_meta_list->class .= ' table-bordered';

        $column_meta_repres_meta_repres_fantasia = new TDataGridColumn('repres->fantasia', "Consultor(a)", 'left');
        $column_meta_repres_meta_valor_meta_transformed = new TDataGridColumn('valor_meta', "Meta", 'left');
        $column_meta_repres_meta_valor_super_meta_transformed = new TDataGridColumn('valor_super_meta', "Super Meta", 'left');
        $column_meta_repres_meta_valor_cortina_transformed = new TDataGridColumn('valor_cortina', "Cortina", 'left');
        $column_meta_repres_meta_valor_mostruario_transformed = new TDataGridColumn('valor_mostruario', "Mostruário", 'left');
        $column_meta_repres_meta_valor_prospeccao_transformed = new TDataGridColumn('valor_prospeccao', "Prospecção", 'left');
        $column_meta_repres_meta_valor_reativacao_transformed = new TDataGridColumn('valor_reativacao', "Reativação", 'left');
        $column_meta_repres_meta_valor_prosp_reat_transformed = new TDataGridColumn('valor_prosp_reat', "Prospecção & Reativação", 'left');
        $column_meta_repres_meta_perc_site_transformed = new TDataGridColumn('perc_site', "Site", 'left');
        $column_meta_repres_meta_fantasia_transformed = new TDataGridColumn('fantasia', "Meta IMPORT", 'left');
        $column_meta_repres_meta_fantasia_transformed1 = new TDataGridColumn('fantasia', "Meta PERSIANAS", 'left');

        $column_meta_repres_meta__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_meta_repres_meta__row__data->setVisibility(false);

        $action_onEditDetailMetaRepres = new TDataGridAction(array('MetaForm', 'onEditDetailMetaRepres'));
        $action_onEditDetailMetaRepres->setUseButton(false);
        $action_onEditDetailMetaRepres->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailMetaRepres->setLabel("Editar");
        $action_onEditDetailMetaRepres->setImage('far:edit #478fca');
        $action_onEditDetailMetaRepres->setFields(['__row__id', '__row__data']);

        $this->meta_repres_meta_list->addAction($action_onEditDetailMetaRepres);
        $action_onDeleteDetailMetaRepres = new TDataGridAction(array('MetaForm', 'onDeleteDetailMetaRepres'));
        $action_onDeleteDetailMetaRepres->setUseButton(false);
        $action_onDeleteDetailMetaRepres->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailMetaRepres->setLabel("Excluir");
        $action_onDeleteDetailMetaRepres->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailMetaRepres->setFields(['__row__id', '__row__data']);

        $this->meta_repres_meta_list->addAction($action_onDeleteDetailMetaRepres);

        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta_repres_fantasia);
        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta_valor_meta_transformed);
        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta_valor_super_meta_transformed);
        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta_valor_cortina_transformed);
        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta_valor_mostruario_transformed);
        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta_valor_prospeccao_transformed);
        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta_valor_reativacao_transformed);
        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta_valor_prosp_reat_transformed);
        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta_perc_site_transformed);
        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta_fantasia_transformed);
        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta_fantasia_transformed1);

        $this->meta_repres_meta_list->addColumn($column_meta_repres_meta__row__data);

        $this->meta_repres_meta_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->meta_repres_meta_list);
        $this->detailFormMetaRepresMeta->addContent([$tableResponsiveDiv]);

        $column_meta_repres_meta_valor_meta_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_meta_repres_meta_valor_super_meta_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_meta_repres_meta_valor_cortina_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_meta_repres_meta_valor_mostruario_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_meta_repres_meta_valor_prospeccao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_meta_repres_meta_valor_reativacao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_meta_repres_meta_valor_prosp_reat_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_meta_repres_meta_perc_site_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(empty($value)) $value = 0;
            return number_format($value, 2, ',', '')."%";

        });

        $column_meta_repres_meta_fantasia_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(empty($object->perc_import)) $object->perc_import = 0;
            if(empty($object->valor_import)) $object->valor_import = 0;
            return number_format((float)$object->perc_import, 2, ',', '')."% acima de R$".number_format((float)$object->valor_import, 2, ',', '');

        });

        $column_meta_repres_meta_fantasia_transformed1->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(empty($object->perc_persianas)) $object->perc_persianas = 0;
            if(empty($object->valor_persianas)) $object->valor_persianas = 0;
            return number_format((float)$object->perc_persianas, 2, ',', '')."% acima de R$".number_format((float)$object->valor_persianas, 2, ',', '');

        });        $row19 = $this->form->addFields([$this->detailFormMetaRepresMeta]);
        $row19->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['MetaHeaderList', 'onShow']), 'fas:arrow-left #000000');
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

        $style = new TStyle('right-panel > .container-part[page-name=MetaForm]');
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

                //Data de entrega e mega entrega é o primeiro dia util do proximo mês
                $entregaMes = (date_add($dataBaseCalc, $intervalo))->format('m');
                $entregaAno = (date_add($dataBaseCalc, $intervalo))->format('Y');

                $data->data_entrega = $data->data_me = calculaDataService::primeiroDiaUtil($entregaMes, $entregaAno). " 23:59";

                //Abertura é o primeiro dia util do mês anterior
                $dataBaseCalc = new DateTime("$ano-$mes");
                $aberturaMes = (date_sub($dataBaseCalc, $intervalo))->format('m');
                $aberturaAno = (date_sub($dataBaseCalc, $intervalo))->format('Y');

                $data->data_abertura = calculaDataService::primeiroDiaUtil($aberturaMes, $aberturaAno) . " 00:00";

                TForm::sendData(self::$formName, $data);

                TTransaction::close();
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailMetaFeriadoMetas($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $data_feriado = new DateTime($data->meta_feriado_meta_data_feriado);

            if($data_feriado->format('m') != $data->mes || $data_feriado->format('Y') != $data->ano){
                throw new Exception("A data deve estar no mês e ano informado.");
            }

            $__row__id = !empty($data->meta_feriado_meta__row__id) ? $data->meta_feriado_meta__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new MetaFeriado();
            $grid_data->__row__id = $__row__id;
            $grid_data->data_feriado = $data->meta_feriado_meta_data_feriado;
            $grid_data->id = $data->meta_feriado_meta_id;
            $grid_data->descricao = $data->meta_feriado_meta_descricao;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['data_feriado'] =  $param['meta_feriado_meta_data_feriado'] ?? null;
            $__row__data['__display__']['id'] =  $param['meta_feriado_meta_id'] ?? null;
            $__row__data['__display__']['descricao'] =  $param['meta_feriado_meta_descricao'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->meta_feriado_meta_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('meta_feriado_meta_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->meta_feriado_meta_data_feriado = '';
            $data->meta_feriado_meta_id = '';
            $data->meta_feriado_meta_descricao = '';
            $data->meta_feriado_meta__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#6616c4dcde1d4');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public  function onAddDetailMetaRepresMetas($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            if(empty($data->meta_represmetas_valor_meta)) $data->meta_represmetas_valor_meta = 0;
            if(empty($data->meta_represmetas_valor_super_meta)) $data->meta_represmetas_valor_super_meta = 0;
            if(empty($data->meta_represmetas_valor_cortina)) $data->meta_represmetas_valor_cortina = 0;
            if(empty($data->meta_represmetas_valor_moc)) $data->meta_represmetas_valor_moc = 0;
            if(empty($data->meta_represmetas_valor_mostruario)) $data->meta_represmetas_valor_mostruario = 0;
            if(empty($data->meta_represmetas_valor_prospeccao)) $data->meta_represmetas_valor_prospeccao = 0;
            if(empty($data->meta_represmetas_valor_reativacao)) $data->meta_represmetas_valor_reativacao = 0;
            if(empty($data->meta_represmetas_perc_site)) $data->meta_represmetas_perc_site = 0;
            if(empty($data->meta_represmetas_perc_aprov_carteira)) $data->meta_represmetas_perc_aprov_carteira = 0;
            if(empty($data->meta_represmetas_perc_cliente_abaixo)) $data->meta_represmetas_perc_cliente_abaixo = 0;
            if(empty($data->meta_represmetas_perc_fora_estado)) $data->meta_represmetas_perc_fora_estado = 0;

            $errors = [];
            $requiredFields = [];
            $requiredFields[] = ['label'=>"Consultor(a)", 'name'=>"meta_repres_meta_repres_id", 'class'=>'TRequiredValidator', 'value'=>[]];
            foreach($requiredFields as $requiredField)
            {
                try
                {
                    (new $requiredField['class'])->validate($requiredField['label'], $data->{$requiredField['name']}, $requiredField['value']);
                }
                catch(Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
             }
             if(count($errors) > 0)
             {
                 throw new Exception(implode('<br>', $errors));
             }

            $__row__id = !empty($data->meta_repres_meta__row__id) ? $data->meta_repres_meta__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new MetaRepres();
            $grid_data->__row__id = $__row__id;
            $grid_data->repres_id = $data->meta_repres_meta_repres_id;
            $grid_data->id = $data->meta_repres_meta_id;
            $grid_data->valor_meta = $data->meta_repres_meta_valor_meta;
            $grid_data->valor_super_meta = $data->meta_repres_meta_valor_super_meta;
            $grid_data->perc_import = $data->meta_repres_meta_perc_import;
            $grid_data->valor_import = $data->meta_repres_meta_valor_import;
            $grid_data->perc_persianas = $data->meta_repres_meta_perc_persianas;
            $grid_data->valor_persianas = $data->meta_repres_meta_valor_persianas;
            $grid_data->valor_cortina = $data->meta_repres_meta_valor_cortina;
            $grid_data->valor_mostruario = $data->meta_repres_meta_valor_mostruario;
            $grid_data->valor_prosp_reat = $data->meta_repres_meta_valor_prosp_reat;
            $grid_data->perc_site = $data->meta_repres_meta_perc_site;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['repres_id'] =  $param['meta_repres_meta_repres_id'] ?? null;
            $__row__data['__display__']['id'] =  $param['meta_repres_meta_id'] ?? null;
            $__row__data['__display__']['valor_meta'] =  $param['meta_repres_meta_valor_meta'] ?? null;
            $__row__data['__display__']['valor_super_meta'] =  $param['meta_repres_meta_valor_super_meta'] ?? null;
            $__row__data['__display__']['perc_import'] =  $param['meta_repres_meta_perc_import'] ?? null;
            $__row__data['__display__']['valor_import'] =  $param['meta_repres_meta_valor_import'] ?? null;
            $__row__data['__display__']['perc_persianas'] =  $param['meta_repres_meta_perc_persianas'] ?? null;
            $__row__data['__display__']['valor_persianas'] =  $param['meta_repres_meta_valor_persianas'] ?? null;
            $__row__data['__display__']['valor_cortina'] =  $param['meta_repres_meta_valor_cortina'] ?? null;
            $__row__data['__display__']['valor_mostruario'] =  $param['meta_repres_meta_valor_mostruario'] ?? null;
            $__row__data['__display__']['valor_prosp_reat'] =  $param['meta_repres_meta_valor_prosp_reat'] ?? null;
            $__row__data['__display__']['perc_site'] =  $param['meta_repres_meta_perc_site'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->meta_repres_meta_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('meta_repres_meta_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->meta_repres_meta_repres_id = '';
            $data->meta_repres_meta_id = '';
            $data->meta_repres_meta_valor_meta = '';
            $data->meta_repres_meta_valor_super_meta = '';
            $data->meta_repres_meta_perc_import = '';
            $data->meta_repres_meta_valor_import = '';
            $data->meta_repres_meta_perc_persianas = '';
            $data->meta_repres_meta_valor_persianas = '';
            $data->meta_repres_meta_valor_cortina = '';
            $data->meta_repres_meta_valor_mostruario = '';
            $data->meta_repres_meta_valor_prosp_reat = '';
            $data->meta_repres_meta_perc_site = '';
            $data->meta_repres_meta__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#6616c513de1d8');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public static function onEditDetailMetaFeriado($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;
            $fireEvents = true;
            $aggregate = false;

            $data = new stdClass;
            $data->meta_feriado_meta_data_feriado = $__row__data->__display__->data_feriado ?? null;
            $data->meta_feriado_meta_id = $__row__data->__display__->id ?? null;
            $data->meta_feriado_meta_descricao = $__row__data->__display__->descricao ?? null;
            $data->meta_feriado_meta__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data, $aggregate, $fireEvents);
            TScript::create("
               var element = $('#6616c4dcde1d4');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='far fa-edit' style='color:#478fca;padding-right:4px;'></i>Editar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailMetaFeriado($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->meta_feriado_meta_data_feriado = '';
            $data->meta_feriado_meta_id = '';
            $data->meta_feriado_meta_descricao = '';
            $data->meta_feriado_meta__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('meta_feriado_meta_list', $__row__data->__row__id);
            TScript::create("
               var element = $('#6616c4dcde1d4');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onEditDetailMetaRepres($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;
            $fireEvents = true;
            $aggregate = false;

            $data = new stdClass;
            $data->meta_repres_meta_repres_id = $__row__data->__display__->repres_id ?? null;
            $data->meta_repres_meta_id = $__row__data->__display__->id ?? null;
            $data->meta_repres_meta_valor_meta = $__row__data->__display__->valor_meta ?? null;
            $data->meta_repres_meta_valor_super_meta = $__row__data->__display__->valor_super_meta ?? null;
            $data->meta_repres_meta_perc_import = $__row__data->__display__->perc_import ?? null;
            $data->meta_repres_meta_valor_import = $__row__data->__display__->valor_import ?? null;
            $data->meta_repres_meta_perc_persianas = $__row__data->__display__->perc_persianas ?? null;
            $data->meta_repres_meta_valor_persianas = $__row__data->__display__->valor_persianas ?? null;
            $data->meta_repres_meta_valor_cortina = $__row__data->__display__->valor_cortina ?? null;
            $data->meta_repres_meta_valor_mostruario = $__row__data->__display__->valor_mostruario ?? null;
            $data->meta_repres_meta_valor_prosp_reat = $__row__data->__display__->valor_prosp_reat ?? null;
            $data->meta_repres_meta_perc_site = $__row__data->__display__->perc_site ?? null;
            $data->meta_repres_meta__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data, $aggregate, $fireEvents);
            TScript::create("
               var element = $('#6616c513de1d8');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='far fa-edit' style='color:#478fca;padding-right:4px;'></i>Editar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailMetaRepres($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->meta_repres_meta_repres_id = '';
            $data->meta_repres_meta_id = '';
            $data->meta_repres_meta_valor_meta = '';
            $data->meta_repres_meta_valor_super_meta = '';
            $data->meta_repres_meta_perc_import = '';
            $data->meta_repres_meta_valor_import = '';
            $data->meta_repres_meta_perc_persianas = '';
            $data->meta_repres_meta_valor_persianas = '';
            $data->meta_repres_meta_valor_cortina = '';
            $data->meta_repres_meta_valor_mostruario = '';
            $data->meta_repres_meta_valor_prosp_reat = '';
            $data->meta_repres_meta_perc_site = '';
            $data->meta_repres_meta__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('meta_repres_meta_list', $__row__data->__row__id);
            TScript::create("
               var element = $('#6616c513de1d8');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Meta(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->system_unit_id = TSession::getValue('userunitid');

            if($object->data_inicial > $object->data_final){
                throw new Exception("A data final deve ser maior que a data inicial.");
            }
            if($data->data_abertura > $data->data_inicial){
                throw new Exception("A data de abertura deve ser menor ou igual a data inicial.");
            }
            if($data->data_entrega < $data->data_final){
                throw new Exception("A data de entrega deve ser maior que data final.");
            }
            if($data->data_me < $data->data_final){
                throw new Exception("A data ME deve ser maior que data final.");
            }

            $data_inicial = new DateTime($object->data_inicial);
            $data_final = new DateTime($object->data_final);

            if($data_inicial->format('m') != $object->mes || $data_inicial->format('Y') != $object->ano){
                throw new Exception("A data inicial deve estar no mês e ano informado.");
            }
            if($data_final->format('m') != $object->mes || $data_final->format('Y') != $object->ano){
                throw new Exception("A data final deve estar no mês e ano informado.");
            }

            $object->dias_uteis = $object->calculaDiasUteis();
            $object->feriados = 0;
            $object->dias_disponiveis = $object->dias_uteis;

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

            $novo = false;
            if($object->id == null){
                $novo = true;
            }
            $object->store(); // save the object 

            if($novo){
                $mes = (int) $object->mes;
                $ano = (int) $object->ano;

                // Verifica se já existe uma regra para o mês e ano informados
                $regrap = RegraProspeccao::where('mes','=',$mes)->where('ano','=',$ano)->first();

                if (!$regrap) { 
                    $contador = 0;
                    $limiteRetrocesso = 12; // Evita loop infinito

                    // Retrocede mês a mês até encontrar uma regra ou atingir o limite
                    while (!$regrap && $contador < $limiteRetrocesso) {
                        $timestamp = mktime(0, 0, 0, $mes - 1, 1, $ano);
                        $mes = (int) date('n', $timestamp); // 'n' retorna sem zero à esquerda
                        $ano = (int) date('Y', $timestamp);

                        $regrap = RegraProspeccao::where('mes', '=', $mes)->where('ano', '=', $ano)->first();
                        $contador++;
                    }

                    if ($regrap) {
                        $regrap->id = null;
                        $regrap->mes = $object->mes;
                        $regrap->ano = $object->ano;
                        $regrap->store();
                    }
                }

                $premios = Premio::where('mes', '=', $mes)->where('ano', '=', $ano)->load();

                if (!$premios) {
                    $contador = 0;
                    $limiteRetrocesso = 12;

                    while (!$premios && $contador < $limiteRetrocesso) {
                        $timestamp = mktime(0, 0, 0, $mes - 1, 1, $ano);
                        $mes = (int) date('n', $timestamp);
                        $ano = (int) date('Y', $timestamp);

                        $premios = Premio::where('mes', '=', $mes)->where('ano', '=', $ano)->load();
                        $contador++;
                    }

                    if ($premios) {
                        foreach ($premios as $oldPremio) {
                            $novoPremio = clone $oldPremio;
                            $novoPremio->id  = null;
                            $novoPremio->mes = $object->mes;
                            $novoPremio->ano = $object->ano;
                            $novoPremio->store();

                            foreach ($oldPremio->getPremioRegras() as $oldRegra) {
                                $novaRegra = clone $oldRegra;
                                $novaRegra->id = null;
                                $novaRegra->premio_id = $novoPremio->id;
                                $novaRegra->store();
                            }
                        }
                    }
                }
            }

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            if(isset($param['meta_represmetas_list_cod_repres'])){
                $courses = $param['meta_represmetas_list_cod_repres'];
                $arr = array_count_values($courses);

                foreach($arr as $key => $value){
                    if($value > 1){
                        throw new Exception("Não é possível adicionar um consultor(a) mais de uma vez.");
                    }
                }
            }

//<generatedAutoCode>
            $this->meta_repres_meta_criteria->setProperty('order', 'fantasia asc');
//</generatedAutoCode>
            $meta_repres_meta_items = $this->storeMasterDetailItems('MetaRepres', 'meta_id', 'meta_repres_meta', $object, $param['meta_repres_meta_list___row__data'] ?? [], $this->form, $this->meta_repres_meta_list, function($masterObject, $detailObject){ 

                $detailObject->fantasia = $detailObject->repres->fantasia;

            }, $this->meta_represmetas_criteria); 

            $meta_feriado_meta_items = $this->storeMasterDetailItems('MetaFeriado', 'meta_id', 'meta_feriado_meta', $object, $param['meta_feriado_meta_list___row__data'] ?? [], $this->form, $this->meta_feriado_meta_list, function($masterObject, $detailObject){ 

                $masterObject->feriados++;
                $masterObject->dias_disponiveis--;

            }, $this->meta_feriado_metas_criteria); 
            if (!empty($meta_feriado_meta_items))
            {
                foreach ($meta_feriado_meta_items as $feriado)
                {

                    $dataFeriado = $feriado->data_feriado ?? null;

                    if (!$dataFeriado) {
                        continue;
                    }

                    $existe = PlanejamentoImportExcecao::where('data', '=', $dataFeriado)
                                ->first();

                    if (!$existe)
                    {
                        $exc = new PlanejamentoImportExcecao;
                        $exc->planejamento_import_id = 1;
                        $exc->data = $dataFeriado;
                        $exc->qtd = 0; 
                        $exc->store();
                    }
                }
            }
            // get the generated {PRIMARY_KEY}
            $object->store();
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            if($novo){
                LogCrontab::enviarAppChat("Meta $object->mes_ano cadastrada.");
            }

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('MetaHeaderList', 'onShow', $loadPageParam); 

                        TScript::create("Template.closeRightPanel();"); 

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

                $object = new Meta($key); // instantiates the Active Record 

//<generatedAutoCode>
                $this->meta_repres_meta_criteria->setProperty('order', 'fantasia asc');
//</generatedAutoCode>
                $meta_repres_meta_items = $this->loadMasterDetailItems('MetaRepres', 'meta_id', 'meta_repres_meta', $object, $this->form, $this->meta_repres_meta_list, $this->meta_repres_meta_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $meta_feriado_meta_items = $this->loadMasterDetailItems('MetaFeriado', 'meta_id', 'meta_feriado_meta', $object, $this->form, $this->meta_feriado_meta_list, $this->meta_feriado_meta_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

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

    }

    public function onShow($param = null)
    {

        TTransaction::open(self::$database);

        $data = new stdClass();

        $metaAberta = Meta::where('system_unit_id','=',TSession::getValue('userunitid'))->where('status','=',1)->first();
        if($metaAberta){
            $data->status = 0;
            $mes = str_pad(($metaAberta->mes)+1, 2, 0, STR_PAD_LEFT);
            $ano = $metaAberta->ano;
            if($mes == "13"){
                $mes = "01";
                $ano = $metaAberta->ano+1;
            }
        }else{
            $data->status = 1;
        }

        $data->mes = $mes ?? date('m');
        $data->ano = $ano ?? date('Y');

        TForm::sendData(self::$formName, $data);

        TTransaction::close();
    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

