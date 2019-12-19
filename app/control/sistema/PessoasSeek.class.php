<?php
/**
 * PessoasSeek Listing
 * @author  <your name here>
 //TWindow
 */
class PessoasSeek extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        //parent::setTitle( AdiantiCoreTranslator::translate('Search record') );
        //parent::setSize(0.7, null);
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Pessoas');
        //$this->form->setFormTitle('Pessoas');
        $this->form->setFormTitle('teste');
        

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $mae = new TEntry('mae');
        $cns = new TEntry('cns');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Mae') ], [ $mae ] );
        $this->form->addFields( [ new TLabel('Cns') ], [ $cns ] );


        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $mae->setSize('100%');
        $cns->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Pessoas_filter_data') );
        
        // add the search form actions
        $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'right');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_mae = new TDataGridColumn('mae', 'Mae', 'left');
        $column_cns = new TDataGridColumn('cns', 'Cns', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_mae);
        $this->datagrid->addColumn($column_cns);

        
        // create SELECT action
        //$action_select = new TDataGridAction(array($this, 'onSelect'));
        $action_select = new TDataGridAction(array('PessoasFormList', 'onEdit'));
        $action_select->setUseButton(TRUE);
        $action_select->setButtonClass('nopadding');
        $action_select->setLabel('');
        $action_select->setImage('fa:hand-pointer-o green');
        $action_select->setField('id');
        
        $tipo='Perfil';
        $evento_id=0;
        $sistema_id=0;
        //$pessoa_id=0;
        $action_select2 = new TDataGridAction(array('Painel02View', 'onReload'));
        $action_select2->setUseButton(TRUE);
        $action_select2->setButtonClass('nopadding');
        $action_select2->setLabel('');
        $action_select2->setImage('fa:hand-pointer-o red');
        $action_select2->setField('id');
        
        $action_select2->setField('id');
        //$action_select2->setField('sistema_id');
        //$action_select2->setField('evento_id');
        $action_select2->setParameter('tipo',$tipo);
        $action_select2->setParameter('evento_id',$evento_id);
        $action_select2->setParameter('sistema_id',$sistema_id);
        $action_select2->setParameter('pessoa_id',$action_select2->getParameter('key'));
        //var_dump($action_select);
        
        
        
        
        $this->datagrid->addAction($action_select2);
        $this->datagrid->addAction($action_select);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%;margin-bottom:0;border-radius:0';
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue('PessoasSeek_filter_id',   NULL);
        TSession::setValue('PessoasSeek_filter_nome',   NULL);
        TSession::setValue('PessoasSeek_filter_mae',   NULL);
        TSession::setValue('PessoasSeek_filter_cns',   NULL);

        if (isset($data->id) AND ($data->id)) {
            $filter = new TFilter('id', '=', "$data->id"); // create the filter
            TSession::setValue('PessoasSeek_filter_id',   $filter); // stores the filter in the session
        }


        if (isset($data->nome) AND ($data->nome)) {
            $filter = new TFilter('nome', 'ilike', "%{$data->nome}%"); // create the filter
            TSession::setValue('PessoasSeek_filter_nome',   $filter); // stores the filter in the session
        }


        if (isset($data->mae) AND ($data->mae)) {
            $filter = new TFilter('mae', 'ilike', "%{$data->mae}%"); // create the filter
            TSession::setValue('PessoasSeek_filter_mae',   $filter); // stores the filter in the session
        }


        if (isset($data->cns) AND ($data->cns)) {
            $filter = new TFilter('cns', 'ilike', "%{$data->cns}%"); // create the filter
            TSession::setValue('PessoasSeek_filter_cns',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Pessoas_filter_data', $data);
        
        $param=array();
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
            
            // creates a repository for Pessoas
            $repository = new TRepository('Pessoas');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'nome';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('PessoasSeek_filter_id')) {
                $criteria->add(TSession::getValue('PessoasSeek_filter_id')); // add the session filter
            }


            if (TSession::getValue('PessoasSeek_filter_nome')) {
                $criteria->add(TSession::getValue('PessoasSeek_filter_nome')); // add the session filter
            }


            if (TSession::getValue('PessoasSeek_filter_mae')) {
                $criteria->add(TSession::getValue('PessoasSeek_filter_mae')); // add the session filter
            }


            if (TSession::getValue('PessoasSeek_filter_cns')) {
                $criteria->add(TSession::getValue('PessoasSeek_filter_cns')); // add the session filter
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
     * Executed when the user chooses the record
     */
    public static function onSelect($param)
    {
        try
        {
            $key = $param['key'];
            TTransaction::open('dbpmbv');
            
            // load the active record
            $object = Pessoas::find($key);
            
            // closes the transaction
            TTransaction::close();
            
            $send = new StdClass;
            $send->pessoas_id = $object->id;
            $send->key=$object->id;
            $send->method='onEdit';
            //$teste='PessoasFormList&method=onEdit&key=$object->id&id=$object->id';
            TForm::sendData(TSession::getValue('PessoasFormView'), $send);
            
            parent::closeWindow(); // closes the window
        }
        catch (Exception $e)
        {
            $send = new StdClass;
            $send->pessoas_id = '';
            TForm::sendData('form_name_REPLACE_HERE', $send);
            
            // undo pending operations
            TTransaction::rollback();
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
