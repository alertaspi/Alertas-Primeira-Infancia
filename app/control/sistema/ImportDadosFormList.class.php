<?php
/**
 * ImportDadosFormList Form List
 * @author  <your name here>
 */
class ImportDadosFormList extends TPage
{
    protected $form; // form
    protected $datagrid; // datagrid
    protected $pageNavigation;
    protected $loaded;
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('form_ImportDados');
        $this->form->setFormTitle('ImportDados');
        

        // create the form fields
        $id = new TEntry('id');
        $pessoa_id = new TEntry('pessoa_id');
        //$sistema_id = new TEntry('sistema_id');
        $sistema_id = new TDBCombo('sistema_id','dbpmbv', 'Sistemas', 'id', 'nome');
        //$evento_id = new TEntry('evento_id');
        $evento_id = new TDBCombo('evento_id','dbpmbv', 'Eventos', 'id', 'nome');
        $data_importacao = new TDate('data_importacao');
        $nome = new TText('nome');
        $cns = new TEntry('cns');
        $cpf = new TEntry('cpf');
        $pis = new TEntry('pis');
        $data_nascimento = new TDate('data_nascimento');
        $mae = new TText('mae');
        $id_pessoa_origem = new TEntry('id_pessoa_origem');
        $data_evento = new TDate('data_evento');
        $competencia = new TEntry('competencia');
        $id_do_evento_na_origem = new TEntry('id_do_evento_na_origem');
        $status = new TEntry('status');
        $gestacao = new TEntry('gestacao');
        $peso = new TEntry('peso');
        $altura = new TEntry('altura');
        $dum = new TDate('dum');
        $dpp = new TDate('dpp');
        $semana_gestacional = new TEntry('semana_gestacional');
        $local_nome = new TText('local_nome');
        $profissional = new TText('profissional');
        $foi_processado = new TEntry('foi_processado');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Pessoa Id') ], [ $pessoa_id ] );
        $this->form->addFields( [ new TLabel('Sistema Id') ], [ $sistema_id ] );
        $this->form->addFields( [ new TLabel('Evento Id') ], [ $evento_id ] );
        $this->form->addFields( [ new TLabel('Data Importacao') ], [ $data_importacao ] );
        $this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Cns') ], [ $cns ] );
        $this->form->addFields( [ new TLabel('Cpf') ], [ $cpf ] );
        $this->form->addFields( [ new TLabel('Pis') ], [ $pis ] );
        $this->form->addFields( [ new TLabel('Data Nascimento') ], [ $data_nascimento ] );
        $this->form->addFields( [ new TLabel('Mae') ], [ $mae ] );
        $this->form->addFields( [ new TLabel('Id Pessoa Origem') ], [ $id_pessoa_origem ] );
        $this->form->addFields( [ new TLabel('Data Evento') ], [ $data_evento ] );
        $this->form->addFields( [ new TLabel('Competencia') ], [ $competencia ] );
        $this->form->addFields( [ new TLabel('Id Do Evento Na Origem') ], [ $id_do_evento_na_origem ] );
        $this->form->addFields( [ new TLabel('Status') ], [ $status ] );
        $this->form->addFields( [ new TLabel('Gestacao') ], [ $gestacao ] );
        $this->form->addFields( [ new TLabel('Peso') ], [ $peso ] );
        $this->form->addFields( [ new TLabel('Altura') ], [ $altura ] );
        $this->form->addFields( [ new TLabel('Dum') ], [ $dum ] );
        $this->form->addFields( [ new TLabel('Dpp') ], [ $dpp ] );
        $this->form->addFields( [ new TLabel('Semana Gestacional') ], [ $semana_gestacional ] );
        $this->form->addFields( [ new TLabel('Local Nome') ], [ $local_nome ] );
        $this->form->addFields( [ new TLabel('Profissional') ], [ $profissional ] );
        $this->form->addFields( [ new TLabel('Foi Processado') ], [ $foi_processado ] );



        // set sizes
        $id->setSize('100%');
        $pessoa_id->setSize('100%');
        $sistema_id->setSize('100%');
        $evento_id->setSize('100%');
        $data_importacao->setSize('100%');
        $nome->setSize('100%');
        $cns->setSize('100%');
        $cpf->setSize('100%');
        $pis->setSize('100%');
        $data_nascimento->setSize('100%');
        $mae->setSize('100%');
        $id_pessoa_origem->setSize('100%');
        $data_evento->setSize('100%');
        $competencia->setSize('100%');
        $id_do_evento_na_origem->setSize('100%');
        $status->setSize('100%');
        $gestacao->setSize('100%');
        $peso->setSize('100%');
        $altura->setSize('100%');
        $dum->setSize('100%');
        $dpp->setSize('100%');
        $semana_gestacional->setSize('100%');
        $local_nome->setSize('100%');
        $profissional->setSize('100%');
        $foi_processado->setSize('100%');



        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
        
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        // $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'left');
        $column_pessoa_id = new TDataGridColumn('pessoa_id', 'Pessoa Id', 'left');
        $column_sistema_id = new TDataGridColumn('sistema_id', 'Sistema Id', 'left');
        $column_evento_id = new TDataGridColumn('evento_id', 'Evento Id', 'left');
        $column_data_importacao = new TDataGridColumn('data_importacao', 'Data Importacao', 'left');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_cns = new TDataGridColumn('cns', 'Cns', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'Cpf', 'left');
        $column_pis = new TDataGridColumn('pis', 'Pis', 'left');
        $column_data_nascimento = new TDataGridColumn('data_nascimento', 'Data Nascimento', 'left');
        $column_mae = new TDataGridColumn('mae', 'Mae', 'left');
        $column_id_pessoa_origem = new TDataGridColumn('id_pessoa_origem', 'Id Pessoa Origem', 'left');
        $column_data_evento = new TDataGridColumn('data_evento', 'Data Evento', 'left');
        $column_competencia = new TDataGridColumn('competencia', 'Competencia', 'left');
        $column_id_do_evento_na_origem = new TDataGridColumn('id_do_evento_na_origem', 'Id Do Evento Na Origem', 'left');
        $column_status = new TDataGridColumn('status', 'Status', 'left');
        $column_gestacao = new TDataGridColumn('gestacao', 'Gestacao', 'left');
        $column_peso = new TDataGridColumn('peso', 'Peso', 'left');
        $column_altura = new TDataGridColumn('altura', 'Altura', 'left');
        $column_dum = new TDataGridColumn('dum', 'Dum', 'left');
        $column_dpp = new TDataGridColumn('dpp', 'Dpp', 'left');
        $column_semana_gestacional = new TDataGridColumn('semana_gestacional', 'Semana Gestacional', 'left');
        $column_local_nome = new TDataGridColumn('local_nome', 'Local Nome', 'left');
        $column_profissional = new TDataGridColumn('profissional', 'Profissional', 'left');
        $column_foi_processado = new TDataGridColumn('foi_processado', 'Foi Processado', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_pessoa_id);
        $this->datagrid->addColumn($column_sistema_id);
        $this->datagrid->addColumn($column_evento_id);
        $this->datagrid->addColumn($column_data_importacao);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_cns);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_pis);
        $this->datagrid->addColumn($column_data_nascimento);
        $this->datagrid->addColumn($column_mae);
        $this->datagrid->addColumn($column_id_pessoa_origem);
        $this->datagrid->addColumn($column_data_evento);
        $this->datagrid->addColumn($column_competencia);
        $this->datagrid->addColumn($column_id_do_evento_na_origem);
        $this->datagrid->addColumn($column_status);
        $this->datagrid->addColumn($column_gestacao);
        $this->datagrid->addColumn($column_peso);
        $this->datagrid->addColumn($column_altura);
        $this->datagrid->addColumn($column_dum);
        $this->datagrid->addColumn($column_dpp);
        $this->datagrid->addColumn($column_semana_gestacional);
        $this->datagrid->addColumn($column_local_nome);
        $this->datagrid->addColumn($column_profissional);
        $this->datagrid->addColumn($column_foi_processado);

        
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
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);
        
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
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'dbpmbv'
            TTransaction::open('dbpmbv');
            
            // creates a repository for ImportDados
            $repository = new TRepository('ImportDados');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    public static function onDelete($param)
    {
        // define the delete action
        $action = new TAction([__CLASS__, 'Delete']);
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(TAdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public static function Delete($param)
    {
        try
        {
            $key = $param['key']; // get the parameter $key
            TTransaction::open('dbpmbv'); // open a transaction with database
            $object = new ImportDados($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            
            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', TAdiantiCoreTranslator::translate('Record deleted'), $pos_action); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('dbpmbv'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new ImportDados;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved')); // success message
            $this->onReload(); // reload the listing
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('dbpmbv'); // open a transaction
                $object = new ImportDados($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') )
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}
