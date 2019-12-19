<?php
/**
 * PessoasSituacaoFormList Form List
 * @author  <your name here>
 */
class PessoasSituacaoFormList extends TPage
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
        
        $this->form = new BootstrapFormBuilder('form_PessoasSituacao');
        $this->form->setFormTitle('PessoasSituacao');
        TSession::regenerate();
        $data_atual = new TDateTime('created');
        $data_atual->setValue(date('Y-m-d H:i'));

        // create the form fields
        $id = new TEntry('id');
        $pessoa_id = new TEntry('pessoa_id', 'dbpmbv', 'Pessoas', 'id', 'nome');
        //$pessoa_id = new TDBUniqueSearch('pessoa_id[]', 'dbpmbv', 'Pessoas', 'pessoa_id', 'nome');
        //$pessoa_id    = new TDBSeekButton('pessoa_id', 'dbpmbv', $this->form->getName(), 'VwGravidezAdolecenciaAlerta', 'nome', 'pessoa_id', 'nome');
        //$pessoa_id->getAction();
        
        if($param['method']=='onEdit'){
          //var_dump($param);
          TTransaction::open('dbpmbv');        
          if(isset($param['key'])){
               $key=$param['key'];                           
              }else{
               $key=$param['pessoa_id'];                            
         }
         $p = new VwGravidezAdolecenciaAlerta($key);
         TTransaction::close();
         $nome  = new TEntry('nome');
         $nome->setValue($p->nome);
         $pessoa_id->setValue($p->pessoa_id);
        }
        $nome  = new TEntry('nome');
        $nome->setValue($p->nome);
        $evento_id = new TDBCombo('evento_id', 'dbpmbv', 'Eventos', 'id', 'nome');
        $situacao_id = new TDBCombo('situacao_id', 'dbpmbv', 'PessoasSituacaoTipo', 'id', 'nome');
        $obs_situacao = new TText('obs_situacao');
        $usuario_id = new TEntry('usuario_id');
        $usuario_id->setValue(TSession::getValue('userid'));
       
        $ativo = new TRadioGroup('ativo');
        //$ativo = new TEntry('ativo');
        $data_info = new TDate('data_info');
        $data_info=$data_atual;
        
        $itens = array();
        $itens['1'] ='SIM';
        $itens['0'] ='NÃO';
        $ativo->addItems($itens);
        $ativo->setValue(1);


// set sizes
        $id->setSize('100%');
        $pessoa_id->setSize('30%');
        $nome->setSize('100%');        
        $nome->setEditable(FALSE);
        $evento_id->setSize('100%');
        $situacao_id->setSize('100%');
        $obs_situacao->setSize('100%');
        $usuario_id->setSize('100%');
        $ativo->setSize('100%');
        $data_info->setSize('100%');

        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Pessoa Id') ], [ $pessoa_id ]);
        $this->form->addFields( [ new TLabel('Nome') ],[$nome] );        
        $this->form->addFields( [ new TLabel('Evento Id') ], [ $evento_id ] );
        $this->form->addFields( [ new TLabel('Situacao Id') ], [ $situacao_id ] );
        $this->form->addFields( [ new TLabel('Obs Situacao') ], [ $obs_situacao ] );
        $this->form->addFields( [ new TLabel('Usuario Id') ], [ $usuario_id ] );
        $this->form->addFields( [ new TLabel('Ativo') ], [ $ativo ] );
        $this->form->addFields( [ new TLabel('Data Info') ], [ $data_info ] );

        $pessoa_id->addValidation('Pessoa Id', new TRequiredValidator);
        $evento_id->addValidation('Evento Id', new TRequiredValidator);
        $situacao_id->addValidation('Situacao Id', new TRequiredValidator);


        



        if (!empty($id))
        {
            $id->setEditable(FALSE);
            $pessoa_id->setEditable(FALSE);
            $usuario_id->setEditable(FALSE);
            $data_info->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
        
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        //$this->form->addAction(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        $this->form->addAction(_t('New'),  new TAction(['GestanteForaFqaView', 'onReload']), 'fa:eraser red');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        // $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'left');
        $column_pessoa_id = new TDataGridColumn('pessoa_id', 'Pessoa Id', 'left');               
        $column_evento_id = new TDataGridColumn('evento_id', 'Evento Id', 'left');
        $column_situacao_id = new TDataGridColumn('situacao_id', 'Situacao Id', 'left');
        $column_obs_situacao = new TDataGridColumn('obs_situacao', 'Obs Situacao', 'left');
        $column_usuario_id = new TDataGridColumn('usuario_id', 'Usuario Id', 'left');
        $column_ativo = new TDataGridColumn('ativo', 'Ativo', 'left');
        $column_data_info = new TDataGridColumn('data_info', 'Data Info', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_pessoa_id);
        $this->datagrid->addColumn($column_evento_id);
        $this->datagrid->addColumn($column_situacao_id);
        $this->datagrid->addColumn($column_obs_situacao);
        $this->datagrid->addColumn($column_usuario_id);
        $this->datagrid->addColumn($column_ativo);
        $this->datagrid->addColumn($column_data_info);

        
        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onEdit']);
        //$action1->setUseButton(TRUE);
        //$action1->setButtonClass('btn btn-default');
        $action1->setLabel(_t('Edit'));
        $action1->setImage('fa:pencil-square-o blue fa-lg');
        $action1->setField('id');
        $action1->setField('pessoa_id');
        
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
            
            // creates a repository for PessoasSituacao
            $repository = new TRepository('PessoasSituacao');
            //$repository->where
            $limit = 10;
            //$p = new VwGravidezAdolecenciaAlerta($param['key']);
            //var_dump($p);
            // creates a criteria
            $criteria = new TCriteria;
            //var_dump($param);
            if(!isset($param['onEdit'])){
            if(isset($param['key'])){
              $key=$param['key'];                           
            }
            if(isset($param['pessoa_id'])){
              $key=$param['pessoa_id'];                            
            }
            if(empty($key)){
               TApplication::loadPage('SaudeView');         
            }
            $criteria->add(new TFilter("pessoa_id","=",$key));
            }
            /*                        
            if($param['method']=='onSeve'){
                 $key=$param['pessoa_id'];                                  
            }else{
                 $key=$param['key'];                                    
            }
            */
            
            
            
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
            
            //$this->datagrid->clear();
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
            $object = new PessoasSituacao($key, FALSE); // instantiates the Active Record
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
            
            $object = new PessoasSituacao;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            //var_dump($param);
            //exit(0);
            if($object->ativo==0){
               $object->ativo=false;                         
            }
            if($object->ativo==1){
               $object->ativo=true;                         
            }
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
        
    //var_dump($param);
        try
        {
            if (isset($param['id']))
            {
                $key = $param['id'];  // get the parameter $key
                TTransaction::open('dbpmbv'); // open a transaction
                $object = new PessoasSituacao($key); // instantiates the Active Record
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
            $this->form->clear(TRUE);
            $key = $param['key'];  // get the parameter $key
            TTransaction::open('dbpmbv'); // open a transaction
            $object = new PessoasSituacao(); // instantiates the Active Record
            $object->pessoa_id=$key;
            $this->form->setData($object); // fill the form
            TTransaction::close(); // close the transaction
            //$pessoa_id=$param['key'];
            //$this->form->
            //new TMessage('error', $e->getMessage()); // shows the exception error message
            //TTransaction::rollback(); // undo all pending operations
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
