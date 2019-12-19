<?php
/**
 * Vw11AcompanhamentoPreNatalList Listing
 * @author  <your name here>
 */
class Vw11AcompanhamentoPreNatalList extends TPage
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
        $this->form = new BootstrapFormBuilder('form_Vw11AcompanhamentoPreNatal');
        $this->form->setFormTitle('Vw11AcompanhamentoPreNatal');
        

        // create the form fields
        $pessoa_id = new TEntry('pessoa_id');
        $nome = new TEntry('nome');
        $mae = new TEntry('mae');
        $cns = new TEntry('cns');
        $idade = new TEntry('idade');


        // add the fields
        $this->form->addFields( [ new TLabel('Pessoa Id') ], [ $pessoa_id ] );
        $this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Mae') ], [ $mae ] );
        $this->form->addFields( [ new TLabel('Cns') ], [ $cns ] );
        $this->form->addFields( [ new TLabel('Idade') ], [ $idade ] );


        // set sizes
        $pessoa_id->setSize('100%');
        $nome->setSize('100%');
        $mae->setSize('100%');
        $cns->setSize('100%');
        $idade->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Vw11AcompanhamentoPreNatal_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['Vw11AcompanhamentoPreNatalForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_pessoa_id = new TDataGridColumn('pessoa_id', 'Pessoa Id', 'right');
        $column_sistema_id = new TDataGridColumn('sistema_id', 'Sistema Id', 'right');
        $column_evento_id = new TDataGridColumn('evento_id', 'Evento Id', 'right');
        $column_tempo_id = new TDataGridColumn('tempo_id', 'Tempo Id', 'right');
        $column_ano = new TDataGridColumn('ano', 'Ano', 'right');
        $column_mes_desc = new TDataGridColumn('mes_desc', 'Mes Desc', 'left');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_data_nascimento = new TDataGridColumn('data_nascimento', 'Data Nascimento', 'left');
        $column_mae = new TDataGridColumn('mae', 'Mae', 'left');
        $column_cns = new TDataGridColumn('cns', 'Cns', 'left');
        $column_descricao = new TDataGridColumn('descricao', 'Descricao', 'left');
        $column_idade = new TDataGridColumn('idade', 'Idade', 'right');
        $column_qtd = new TDataGridColumn('qtd', 'Qtd', 'right');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_pessoa_id);
        $this->datagrid->addColumn($column_sistema_id);
        $this->datagrid->addColumn($column_evento_id);
        $this->datagrid->addColumn($column_tempo_id);
        $this->datagrid->addColumn($column_ano);
        $this->datagrid->addColumn($column_mes_desc);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_data_nascimento);
        $this->datagrid->addColumn($column_mae);
        $this->datagrid->addColumn($column_cns);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_idade);
        $this->datagrid->addColumn($column_qtd);

        
        // create EDIT action
        $action_edit = new TDataGridAction(['Vw11AcompanhamentoPreNatalForm', 'onEdit']);
        //$action_edit->setUseButton(TRUE);
        //$action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('pessoa_id');
        $this->datagrid->addAction($action_edit);
        

        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        
        $div = new TElement('div');
        $div->add( $c = new Grafico11BarChartView(false) );
        


        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($div);
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
            $object = new Vw11AcompanhamentoPreNatal($key); // instantiates the Active Record
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
        TSession::setValue('Vw11AcompanhamentoPreNatalList_filter_pessoa_id',   NULL);
        TSession::setValue('Vw11AcompanhamentoPreNatalList_filter_nome',   NULL);
        TSession::setValue('Vw11AcompanhamentoPreNatalList_filter_mae',   NULL);
        TSession::setValue('Vw11AcompanhamentoPreNatalList_filter_cns',   NULL);
        TSession::setValue('Vw11AcompanhamentoPreNatalList_filter_idade',   NULL);

        if (isset($data->pessoa_id) AND ($data->pessoa_id)) {
            $filter = new TFilter('pessoa_id', '=', "$data->pessoa_id"); // create the filter
            TSession::setValue('Vw11AcompanhamentoPreNatalList_filter_pessoa_id',   $filter); // stores the filter in the session
        }


        if (isset($data->nome) AND ($data->nome)) {
            $filter = new TFilter('nome', 'like', "%{$data->nome}%"); // create the filter
            TSession::setValue('Vw11AcompanhamentoPreNatalList_filter_nome',   $filter); // stores the filter in the session
        }


        if (isset($data->mae) AND ($data->mae)) {
            $filter = new TFilter('mae', 'like', "%{$data->mae}%"); // create the filter
            TSession::setValue('Vw11AcompanhamentoPreNatalList_filter_mae',   $filter); // stores the filter in the session
        }


        if (isset($data->cns) AND ($data->cns)) {
            $filter = new TFilter('cns', 'like', "%{$data->cns}%"); // create the filter
            TSession::setValue('Vw11AcompanhamentoPreNatalList_filter_cns',   $filter); // stores the filter in the session
        }


        if (isset($data->idade) AND ($data->idade)) {
            $filter = new TFilter('idade', '=', "$data->idade"); // create the filter
            TSession::setValue('Vw11AcompanhamentoPreNatalList_filter_idade',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Vw11AcompanhamentoPreNatal_filter_data', $data);
        
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
            
            // creates a repository for Vw11AcompanhamentoPreNatal
            $repository = new TRepository('Vw11AcompanhamentoPreNatal');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'pessoa_id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('Vw11AcompanhamentoPreNatalList_filter_pessoa_id')) {
                $criteria->add(TSession::getValue('Vw11AcompanhamentoPreNatalList_filter_pessoa_id')); // add the session filter
            }


            if (TSession::getValue('Vw11AcompanhamentoPreNatalList_filter_nome')) {
                $criteria->add(TSession::getValue('Vw11AcompanhamentoPreNatalList_filter_nome')); // add the session filter
            }


            if (TSession::getValue('Vw11AcompanhamentoPreNatalList_filter_mae')) {
                $criteria->add(TSession::getValue('Vw11AcompanhamentoPreNatalList_filter_mae')); // add the session filter
            }


            if (TSession::getValue('Vw11AcompanhamentoPreNatalList_filter_cns')) {
                $criteria->add(TSession::getValue('Vw11AcompanhamentoPreNatalList_filter_cns')); // add the session filter
            }


            if (TSession::getValue('Vw11AcompanhamentoPreNatalList_filter_idade')) {
                $criteria->add(TSession::getValue('Vw11AcompanhamentoPreNatalList_filter_idade')); // add the session filter
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
            $object = new Vw11AcompanhamentoPreNatal($key, FALSE); // instantiates the Active Record
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
