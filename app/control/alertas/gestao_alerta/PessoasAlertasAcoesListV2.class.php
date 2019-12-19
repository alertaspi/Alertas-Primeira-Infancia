<?php
/**
 * PessoasAlertasAcoesList Listing
 * @author  <your name here>
 */
class PessoasAlertasAcoesListV2 extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $deleteButton;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_PessoasAlertasAcoes');
        $this->form->setFormTitle('PessoasAlertasAcoes');
        

        // create the form fields
        $id = new TEntry('id');
        $pessoas_alertas_id = new TDBUniqueSearch('pessoas_alertas_id', 'dbpmbv', 'PessoasAlertas', 'id', 'pessoa_id');
        $usuario_id = new TEntry('usuario_id');
        $acao_tomada = new TEntry('acao_tomada');
        $descricao_acao = new TEntry('descricao_acao');
        $id_pai = new TEntry('id_pai');
        $data_info = new TEntry('data_info');
        $status = new TEntry('status');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Pessoas Alertas Id') ], [ $pessoas_alertas_id ] );
        $this->form->addFields( [ new TLabel('Usuario Id') ], [ $usuario_id ] );
        $this->form->addFields( [ new TLabel('Acao Tomada') ], [ $acao_tomada ] );
        $this->form->addFields( [ new TLabel('Descricao Acao') ], [ $descricao_acao ] );
        $this->form->addFields( [ new TLabel('Id Pai') ], [ $id_pai ] );
        $this->form->addFields( [ new TLabel('Data Info') ], [ $data_info ] );
        $this->form->addFields( [ new TLabel('Status') ], [ $status ] );


        // set sizes
        $id->setSize('100%');
        $pessoas_alertas_id->setSize('100%');
        $usuario_id->setSize('100%');
        $acao_tomada->setSize('100%');
        $descricao_acao->setSize('100%');
        $id_pai->setSize('100%');
        $data_info->setSize('100%');
        $status->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('PessoasAlertasAcoes_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['PessoasAlertasAcoesForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'right');
        $column_pessoas_alertas_id = new TDataGridColumn('pessoas_alertas_id', 'Pessoas Alertas Id', 'right');
        $column_usuario_id = new TDataGridColumn('usuario_id', 'Usuario Id', 'right');
        $column_acao_tomada = new TDataGridColumn('acao_tomada', 'Acao Tomada', 'left');
        $column_descricao_acao = new TDataGridColumn('descricao_acao', 'Descricao Acao', 'left');
        $column_id_pai = new TDataGridColumn('id_pai', 'Id Pai', 'right');
        $column_data_info = new TDataGridColumn('data_info', 'Data Info', 'left');
        $column_status = new TDataGridColumn('status', 'Status', 'right');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_pessoas_alertas_id);
        $this->datagrid->addColumn($column_usuario_id);
        $this->datagrid->addColumn($column_acao_tomada);
        $this->datagrid->addColumn($column_descricao_acao);
        $this->datagrid->addColumn($column_id_pai);
        $this->datagrid->addColumn($column_data_info);
        $this->datagrid->addColumn($column_status);


        // creates the datagrid column actions
        $column_acao_tomada->setAction(new TAction([$this, 'onReload']), ['order' => 'acao_tomada']);
        $column_descricao_acao->setAction(new TAction([$this, 'onReload']), ['order' => 'descricao_acao']);

        
        // create EDIT action
        $action_edit = new TDataGridAction(['PessoasAlertasAcoesForm', 'onEdit']);
        //$action_edit->setUseButton(TRUE);
        //$action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        

        
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
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
    
    /**
     * Inline record editing
     * @param $param Array containing:
     *              key: object ID value
     *              field name: object attribute to be updated
     *              value: new attribute content 
     */
    public function onInlineEdit($param)
    {
        try
        {
            // get the parameter $key
            $field = $param['field'];
            $key   = $param['key'];
            $value = $param['value'];
            
            TTransaction::open('dbpmbv'); // open a transaction with database
            $object = new PessoasAlertasAcoes($key); // instantiates the Active Record
            $object->{$field} = $value;
            $object->store(); // update the object in the database
            TTransaction::close(); // close the transaction
            
            $this->onReload($param); // reload the listing
            new TMessage('info', "Record Updated");
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue('PessoasAlertasAcoesList_filter_id',   NULL);
        TSession::setValue('PessoasAlertasAcoesList_filter_pessoas_alertas_id',   NULL);
        TSession::setValue('PessoasAlertasAcoesList_filter_usuario_id',   NULL);
        TSession::setValue('PessoasAlertasAcoesList_filter_acao_tomada',   NULL);
        TSession::setValue('PessoasAlertasAcoesList_filter_descricao_acao',   NULL);
        TSession::setValue('PessoasAlertasAcoesList_filter_id_pai',   NULL);
        TSession::setValue('PessoasAlertasAcoesList_filter_data_info',   NULL);
        TSession::setValue('PessoasAlertasAcoesList_filter_status',   NULL);

        if (isset($data->id) AND ($data->id)) {
            $filter = new TFilter('id', '=', "$data->id"); // create the filter
            TSession::setValue('PessoasAlertasAcoesList_filter_id',   $filter); // stores the filter in the session
        }


        if (isset($data->pessoas_alertas_id) AND ($data->pessoas_alertas_id)) {
            $filter = new TFilter('pessoas_alertas_id', '=', "$data->pessoas_alertas_id"); // create the filter
            TSession::setValue('PessoasAlertasAcoesList_filter_pessoas_alertas_id',   $filter); // stores the filter in the session
        }


        if (isset($data->usuario_id) AND ($data->usuario_id)) {
            $filter = new TFilter('usuario_id', 'like', "%{$data->usuario_id}%"); // create the filter
            TSession::setValue('PessoasAlertasAcoesList_filter_usuario_id',   $filter); // stores the filter in the session
        }


        if (isset($data->acao_tomada) AND ($data->acao_tomada)) {
            $filter = new TFilter('acao_tomada', 'like', "%{$data->acao_tomada}%"); // create the filter
            TSession::setValue('PessoasAlertasAcoesList_filter_acao_tomada',   $filter); // stores the filter in the session
        }


        if (isset($data->descricao_acao) AND ($data->descricao_acao)) {
            $filter = new TFilter('descricao_acao', 'like', "%{$data->descricao_acao}%"); // create the filter
            TSession::setValue('PessoasAlertasAcoesList_filter_descricao_acao',   $filter); // stores the filter in the session
        }


        if (isset($data->id_pai) AND ($data->id_pai)) {
            $filter = new TFilter('id_pai', 'like', "%{$data->id_pai}%"); // create the filter
            TSession::setValue('PessoasAlertasAcoesList_filter_id_pai',   $filter); // stores the filter in the session
        }


        if (isset($data->data_info) AND ($data->data_info)) {
            $filter = new TFilter('data_info', 'like', "%{$data->data_info}%"); // create the filter
            TSession::setValue('PessoasAlertasAcoesList_filter_data_info',   $filter); // stores the filter in the session
        }


        if (isset($data->status) AND ($data->status)) {
            $filter = new TFilter('status', 'like', "%{$data->status}%"); // create the filter
            TSession::setValue('PessoasAlertasAcoesList_filter_status',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('PessoasAlertasAcoes_filter_data', $data);
        
        $param = array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
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
            
            // creates a repository for PessoasAlertasAcoes
            $repository = new TRepository('PessoasAlertasAcoes');
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
            

            if (TSession::getValue('PessoasAlertasAcoesList_filter_id')) {
                $criteria->add(TSession::getValue('PessoasAlertasAcoesList_filter_id')); // add the session filter
            }


            if (TSession::getValue('PessoasAlertasAcoesList_filter_pessoas_alertas_id')) {
                $criteria->add(TSession::getValue('PessoasAlertasAcoesList_filter_pessoas_alertas_id')); // add the session filter
            }


            if (TSession::getValue('PessoasAlertasAcoesList_filter_usuario_id')) {
                $criteria->add(TSession::getValue('PessoasAlertasAcoesList_filter_usuario_id')); // add the session filter
            }


            if (TSession::getValue('PessoasAlertasAcoesList_filter_acao_tomada')) {
                $criteria->add(TSession::getValue('PessoasAlertasAcoesList_filter_acao_tomada')); // add the session filter
            }


            if (TSession::getValue('PessoasAlertasAcoesList_filter_descricao_acao')) {
                $criteria->add(TSession::getValue('PessoasAlertasAcoesList_filter_descricao_acao')); // add the session filter
            }


            if (TSession::getValue('PessoasAlertasAcoesList_filter_id_pai')) {
                $criteria->add(TSession::getValue('PessoasAlertasAcoesList_filter_id_pai')); // add the session filter
            }


            if (TSession::getValue('PessoasAlertasAcoesList_filter_data_info')) {
                $criteria->add(TSession::getValue('PessoasAlertasAcoesList_filter_data_info')); // add the session filter
            }


            if (TSession::getValue('PessoasAlertasAcoesList_filter_status')) {
                $criteria->add(TSession::getValue('PessoasAlertasAcoesList_filter_status')); // add the session filter
            }

            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            if (is_callable($this->transformCallback))
            {
                call_user_func($this->transformCallback, $objects, $param);
            }
            
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
            $key=$param['key']; // get the parameter $key
            TTransaction::open('dbpmbv'); // open a transaction with database
            $object = new PessoasAlertasAcoes($key, FALSE); // instantiates the Active Record
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
}
