<?php

class PlanejamentoImportForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'integrador';
    private static $activeRecord = 'PlanejamentoImport';
    private static $primaryKey = 'id';
    private static $formName = 'form_PlanejamentoImportForm';

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
        $this->form->setFormTitle("Planejamento IMPORT ");


        $id = new THidden('id');
        $qtd = new TEntry('qtd');
        $listagem = new BPageContainer();
        $excecao = new BPageContainer();


        $listagem->setAction(new TAction(['PlanejamentoImportList', 'onShow']));
        $excecao->setAction(new TAction(['PlanejamentoImportExcecaoHeaderList', 'onShow']));

        $excecao->setId('b6936d69de6ac2');
        $listagem->setId('b69338661617c7');

        $id->setSize(200);
        $qtd->setSize('100%');
        $excecao->setSize('100%');
        $listagem->setSize('100%');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $listagem->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $excecao->add($loadingContainer);

        $this->listagem = $listagem;
        $this->excecao = $excecao;

             try
            {
                TTransaction::open(self::$database);

                // tenta buscar o registro 1
                $plan = PlanejamentoImport::find(1);

                if ($plan)
                {
                    // Preenche os campos do form
                    $id->setValue($plan->id);
                    $qtd->setValue($plan->qtd);

                    // Mostra a listagem e passa o ID
                    $this->listagem->unhide();
                    $this->listagem->setParameter('key', $plan->id);
                }

                TTransaction::close();
            }
            catch (Exception $e)
            {
                TTransaction::rollback();
                new TMessage('error', $e->getMessage());
            }

        $row1 = $this->form->addFields([$id,new TLabel("Limite IMPORT:", null, '14px', null, '100%'),$qtd]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#FFFFFF')]);
        $row3 = $this->form->addFields([$listagem]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([$excecao]);
        $row4->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Configurações","Planejamento IMPORT"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $data = $this->form->getData();
            /*
            $object = new PlanejamentoImport(); // create an empty object 

            $object->store(); // save the object 

            $data->id = $object->id; 
            */
            $object = PlanejamentoImport::find(1);

            if (!$object)
            {
            // Se ainda não existir o registro, cria um
                $object = new PlanejamentoImport;

            }

            // Atualiza só os campos necessários
            $object->qtd = $data->qtd;

            $object->store(); // save the object

            // garante que o form fique com o ID correto
            $data->id = $object->id;

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

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
         /*
                $object = new PlanejamentoImport($key); // instantiates the Active Record 

                                $this->listagem->setParameter('planejamento_import_id', $object->id);
                $this->excecao->setParameter('planejamento_import_id', $object->id);

                $this->form->setData($object); // fill the form 

            */  $key = 1;

                TTransaction::open(self::$database);

                $object = new PlanejamentoImport($key);

                $this->listagem->unhide();
                $this->listagem->setParameter('planejamento_import_id', $object->id);

                $this->form->setData($object);

                TTransaction::close(); 

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

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

