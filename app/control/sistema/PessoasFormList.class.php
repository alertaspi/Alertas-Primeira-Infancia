<?php

/**
 * PessoasFormList Form List
 * @author  <your name here>
 */
class PessoasFormList extends TPage {

    protected $form; // form
    protected $datagrid; // datagrid
    protected $pageNavigation;
    protected $loaded;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct($param) {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_Pessoas');
        $this->form->setFormTitle('Pessoas');


        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $sexo = new TEntry('sexo');
        $data_nascimento = new TDate('data_nascimento');
        $data_falecimento = new TDate('data_falecimento');
        $pai = new TEntry('pai');
        $mae = new TEntry('mae');
        $rg = new TEntry('rg');
        $cpf = new TEntry('cpf');
        $cns = new TEntry('cns');
        $fone = new TEntry('fone');
        $email = new TEntry('email');
        $escolaridade = new TEntry('escolaridade');
        $profissao = new TEntry('profissao');
        $cep = new TEntry('cep');
        $endereco = new TEntry('endereco');
        $numero_endereco = new TEntry('numero_endereco');
        $referencia_endereco = new TEntry('referencia_endereco');
        $bairro = new TEntry('bairro');
        $cidade = new TEntry('cidade');
        $uf = new TEntry('uf');
        $cidade_nascimneto = new TEntry('cidade_nascimneto');
        $uf_nascimento = new TEntry('uf_nascimento');
        $nacionalidade = new TEntry('nacionalidade');
        $tipo_familia_id = new TEntry('tipo_familia_id');
        $id_origem = new TEntry('id_origem');
        $data_info = new TDate('data_info');
        $latitude = new TEntry('latitude');
        $longitude = new TEntry('longitude');
        $id_origem2 = new TEntry('id_origem2');
        $pis = new TEntry('pis');


        // add the fields
        $this->form->addFields([new TLabel('Id')], [$id]);
        $this->form->addFields([new TLabel('Nome')], [$nome]);
        $this->form->addFields([new TLabel('Sexo')], [$sexo]);
        $this->form->addFields([new TLabel('Data Nascimento')], [$data_nascimento]);
        $this->form->addFields([new TLabel('Data Falecimento')], [$data_falecimento]);
        $this->form->addFields([new TLabel('Pai')], [$pai]);
        $this->form->addFields([new TLabel('Mae')], [$mae]);
        $this->form->addFields([new TLabel('Rg')], [$rg]);
        $this->form->addFields([new TLabel('Cpf')], [$cpf]);
        $this->form->addFields([new TLabel('Cns')], [$cns]);
        $this->form->addFields([new TLabel('Fone')], [$fone]);
        $this->form->addFields([new TLabel('Email')], [$email]);
        $this->form->addFields([new TLabel('Escolaridade')], [$escolaridade]);
        $this->form->addFields([new TLabel('Profissao')], [$profissao]);
        $this->form->addFields([new TLabel('Cep')], [$cep]);
        $this->form->addFields([new TLabel('Endereco')], [$endereco]);
        $this->form->addFields([new TLabel('Numero Endereco')], [$numero_endereco]);
        $this->form->addFields([new TLabel('Referencia Endereco')], [$referencia_endereco]);
        $this->form->addFields([new TLabel('Bairro')], [$bairro]);
        $this->form->addFields([new TLabel('Cidade')], [$cidade]);
        $this->form->addFields([new TLabel('Uf')], [$uf]);
        $this->form->addFields([new TLabel('Cidade Nascimneto')], [$cidade_nascimneto]);
        $this->form->addFields([new TLabel('Uf Nascimento')], [$uf_nascimento]);
        $this->form->addFields([new TLabel('Nacionalidade')], [$nacionalidade]);
        $this->form->addFields([new TLabel('Tipo Familia Id')], [$tipo_familia_id]);
        $this->form->addFields([new TLabel('Id Origem')], [$id_origem]);
        $this->form->addFields([new TLabel('Data Info')], [$data_info]);
        $this->form->addFields([new TLabel('Latitude')], [$latitude]);
        $this->form->addFields([new TLabel('Longitude')], [$longitude]);
        $this->form->addFields([new TLabel('Id Origem2')], [$id_origem2]);
        $this->form->addFields([new TLabel('Pis')], [$pis]);

        $nome->addValidation('Nome', new TRequiredValidator);
        $sexo->addValidation('Sexo', new TRequiredValidator);
        $data_nascimento->addValidation('Data Nascimento', new TRequiredValidator);
        $cns->addValidation('Cns', new TRequiredValidator);


        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $sexo->setSize('100%');
        $data_nascimento->setSize('100%');
        $data_falecimento->setSize('100%');
        $pai->setSize('100%');
        $mae->setSize('100%');
        $rg->setSize('100%');
        $cpf->setSize('100%');
        $cns->setSize('100%');
        $fone->setSize('100%');
        $email->setSize('100%');
        $escolaridade->setSize('100%');
        $profissao->setSize('100%');
        $cep->setSize('100%');
        $endereco->setSize('100%');
        $numero_endereco->setSize('100%');
        $referencia_endereco->setSize('100%');
        $bairro->setSize('100%');
        $cidade->setSize('100%');
        $uf->setSize('100%');
        $cidade_nascimneto->setSize('100%');
        $uf_nascimento->setSize('100%');
        $nacionalidade->setSize('100%');
        $tipo_familia_id->setSize('100%');
        $id_origem->setSize('100%');
        $data_info->setSize('100%');
        $latitude->setSize('100%');
        $longitude->setSize('100%');
        $id_origem2->setSize('100%');
        $pis->setSize('100%');



        if (!empty($id)) {
            $id->setEditable(FALSE);
        }

        /** samples
          $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
          $fieldX->setSize( '100%' ); // set size
         * */
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'), new TAction([$this, 'onEdit']), 'fa:eraser red');
        $this->form->addAction(_t('File'), new TAction([$this, 'onImport']), 'fa:file-o');

        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        // $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'left');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_sexo = new TDataGridColumn('sexo', 'Sexo', 'left');
        $column_data_nascimento = new TDataGridColumn('data_nascimento', 'Data Nascimento', 'left');
        $column_data_falecimento = new TDataGridColumn('data_falecimento', 'Data Falecimento', 'left');
        $column_pai = new TDataGridColumn('pai', 'Pai', 'left');
        $column_mae = new TDataGridColumn('mae', 'Mae', 'left');
        $column_rg = new TDataGridColumn('rg', 'Rg', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'Cpf', 'left');
        $column_cns = new TDataGridColumn('cns', 'Cns', 'left');
        $column_fone = new TDataGridColumn('fone', 'Fone', 'left');
        $column_email = new TDataGridColumn('email', 'Email', 'left');
        $column_escolaridade = new TDataGridColumn('escolaridade', 'Escolaridade', 'left');
        $column_profissao = new TDataGridColumn('profissao', 'Profissao', 'left');
        $column_cep = new TDataGridColumn('cep', 'Cep', 'left');
        $column_endereco = new TDataGridColumn('endereco', 'Endereco', 'left');
        $column_numero_endereco = new TDataGridColumn('numero_endereco', 'Numero Endereco', 'left');
        $column_referencia_endereco = new TDataGridColumn('referencia_endereco', 'Referencia Endereco', 'left');
        $column_bairro = new TDataGridColumn('bairro', 'Bairro', 'left');
        $column_cidade = new TDataGridColumn('cidade', 'Cidade', 'left');
        $column_uf = new TDataGridColumn('uf', 'Uf', 'left');
        $column_cidade_nascimneto = new TDataGridColumn('cidade_nascimneto', 'Cidade Nascimneto', 'left');
        $column_uf_nascimento = new TDataGridColumn('uf_nascimento', 'Uf Nascimento', 'left');
        $column_nacionalidade = new TDataGridColumn('nacionalidade', 'Nacionalidade', 'left');
        $column_tipo_familia_id = new TDataGridColumn('tipo_familia_id', 'Tipo Familia Id', 'left');
        $column_id_origem = new TDataGridColumn('id_origem', 'Id Origem', 'left');
        $column_data_info = new TDataGridColumn('data_info', 'Data Info', 'left');
        $column_latitude = new TDataGridColumn('latitude', 'Latitude', 'left');
        $column_longitude = new TDataGridColumn('longitude', 'Longitude', 'left');
        $column_id_origem2 = new TDataGridColumn('id_origem2', 'Id Origem2', 'left');
        $column_pis = new TDataGridColumn('pis', 'Pis', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_sexo);
        $this->datagrid->addColumn($column_data_nascimento);
        //$this->datagrid->addColumn($column_data_falecimento);
        //$this->datagrid->addColumn($column_pai);
        $this->datagrid->addColumn($column_mae);
        $this->datagrid->addColumn($column_rg);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_cns);
        $this->datagrid->addColumn($column_fone);
        $this->datagrid->addColumn($column_email);
        //$this->datagrid->addColumn($column_escolaridade);
        //$this->datagrid->addColumn($column_profissao);
        //$this->datagrid->addColumn($column_cep);
        //$this->datagrid->addColumn($column_endereco);
        //$this->datagrid->addColumn($column_numero_endereco);
        //$this->datagrid->addColumn($column_referencia_endereco);
        //$this->datagrid->addColumn($column_bairro);
        //$this->datagrid->addColumn($column_cidade);
        //$this->datagrid->addColumn($column_uf);
        //$this->datagrid->addColumn($column_cidade_nascimneto);
        //$this->datagrid->addColumn($column_uf_nascimento);
        //$this->datagrid->addColumn($column_nacionalidade);
        //$this->datagrid->addColumn($column_tipo_familia_id);
        //$this->datagrid->addColumn($column_id_origem);
        //$this->datagrid->addColumn($column_data_info);
        //$this->datagrid->addColumn($column_latitude);
        //$this->datagrid->addColumn($column_longitude);
        //$this->datagrid->addColumn($column_id_origem2);
        //$this->datagrid->addColumn($column_pis);


        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onEdit']);
        //$action1->setUseButton(TRUE);
        //$action1->setButtonClass('btn btn-default');
        $action1->setLabel(_t('Edit'));
        $action1->setImage('fa:pencil-square-o blue fa-lg');
        $action1->setField('id');

        $action2 = new TDataGridAction([$this, 'onDelete']);
        //$action2->setUseButton(TRUE);
        //$action2->setButtonClass('btn btn-default');
        $action2->setLabel(_t('Delete'));
        $action2->setImage('fa:trash-o red fa-lg');
        $action2->setField('id');

        $tipo='Perfil';
        $action3 = new TDataGridAction(['Painel02View', 'onReload']);
        //$action2->setUseButton(TRUE);
        //$action2->setButtonClass('btn btn-default');
        $action3->setLabel('Perfil');
        $action3->setImage('fa:user-o red fa-lg');
        $action3->setField('id');
        //var_dump($action3);
        $action3->setParameter('pessoa_id',$action3->getParameter('id'));
        $action3->setParameter('sistema_id',0);
        $action3->setParameter('evento_id',0);
        $action3->setParameter('tipo',$tipo);
        /*
        $action_select2->setField('pessoa_id');
        $action_select2->setField('sistema_id');
        $action_select2->setField('evento_id');
        $action_select2->setParameter('tipo',$tipo);
        */
        /*
         $action4 = new TDataGridAction([$this, 'onImport']);
        //$action2->setUseButton(TRUE);
        //$action2->setButtonClass('btn btn-default');
        $action4->setLabel('Perfil');
        $action4->setImage('fa:file-o blue fa-lg');
        $action4->setField('id');
         * 
         */

        //$this->form->addAction( 'Find', new TAction(['PessoasSeek', 'onSearch']), 'fa:search blue');
        // add the actions to the datagrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);
        $this->datagrid->addAction($action3);
        //$this->datagrid->addAction($action4);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid));
        $container->add($this->pageNavigation);

        parent::add($container);
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL) {
        try {
            // open a transaction with database 'dbpmbv'
            TTransaction::open('dbpmbv');

            // creates a repository for Pessoas
            $repository = new TRepository('Pessoas');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;

            // default order
            if (empty($param['order'])) {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects) {
                // iterate the collection of active records
                foreach ($objects as $object) {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count = $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        } catch (Exception $e) { // in case of exception
            // shows the exception error message
            new TMessage('error', $e->getMessage());

            // undo all pending operations
            TTransaction::rollback();
        }
    }

    /**
     * Ask before deletion
     */
    public static function onDelete($param) {
        // define the delete action
        $action = new TAction([__CLASS__, 'Delete']);
        $action->setParameters($param); // pass the key parameter ahead
        // shows a dialog to the user
        new TQuestion(TAdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }

    /**
     * Delete a record
     */
    public static function Delete($param) {
        try {
            $key = $param['key']; // get the parameter $key
            TTransaction::open('dbpmbv'); // open a transaction with database
            $object = new Pessoas($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction

            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', TAdiantiCoreTranslator::translate('Record deleted'), $pos_action); // success message
        } catch (Exception $e) { // in case of exception
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave($param) {
        try {
            TTransaction::open('dbpmbv'); // open a transaction

            /**
              // Enable Debug logger for SQL operations inside the transaction
              TTransaction::setLogger(new TLoggerSTD); // standard output
              TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
             * */
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array

            $object = new Pessoas;  // create an empty object
            $object->fromArray((array) $data); // load the object with data
            $object->store(); // save the object
            // get the generated id
            $data->id = $object->id;

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved')); // success message
            $this->onReload(); // reload the listing
        } catch (Exception $e) { // in case of exception
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData($this->form->getData()); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear($param) {
        $this->form->clear(TRUE);
    }

    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit($param) {
        try {
            if (isset($param['key'])) {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('dbpmbv'); // open a transaction
                $object = new Pessoas($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            } else {
                $this->form->clear(TRUE);
            }
        } catch (Exception $e) { // in case of exception
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onPerfil() {
        
    }
    
    //function import file
    public function onImport() {
        try {
            
            //new TMessage('alert', 'Opção de importação de dados ainda não disponível.');
            TApplication::loadPage('ContainerWindowView');
            
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage()); // shows the exception error message   
        }
    }

    /**
     * method show()
     * Shows the page
     */
    public function show() {
        // check if the datagrid is already loaded
        if (!$this->loaded AND ( !isset($_GET['method']) OR $_GET['method'] !== 'onReload')) {
            $this->onReload(func_get_arg(0));
        }
        parent::show();
    }

}
