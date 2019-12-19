<?php
/**
 * VwImcList Listing
 * @author  <your name here>
 */
class VwImcList extends TPage
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
        $this->form = new BootstrapFormBuilder('form_VwImc');
        $this->form->setFormTitle('VwImc');
        

        // create the form fields
        $pessoa_id = new TEntry('pessoa_id');
        $ano = new TEntry('ano');
        //$nome = new TEntry('nome');
        $idade = new TEntry('idade');
        $peso = new TEntry('peso');
        //$altura = new TEntry('altura');
        //$tipo_imc = new TEntry('tipo_imc');


        // add the fields
        $this->form->addFields( [ new TLabel('Pessoa Id') ], [ $pessoa_id ] );
        $this->form->addFields( [ new TLabel('Ano') ], [ $ano ] );
        //$this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Idade') ], [ $idade ] );
        $this->form->addFields( [ new TLabel('Peso') ], [ $peso ] );
        //$this->form->addFields( [ new TLabel('Altura') ], [ $altura ] );
        //$this->form->addFields( [ new TLabel('Tipo Imc') ], [ $tipo_imc ] );


        // set sizes
        $pessoa_id->setSize('100%');
        $ano->setSize('100%');
        //$nome->setSize('100%');
        $idade->setSize('100%');
        $peso->setSize('100%');
        //$altura->setSize('100%');
        //$tipo_imc->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('VwImc_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        //$this->form->addActionLink(_t('New'), new TAction(['VwImcForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_pessoa_id = new TDataGridColumn('pessoa_id', 'Pessoa Id', 'right');
        //$column_ano = new TDataGridColumn('ano', 'Ano', 'right');
        //$column_mes = new TDataGridColumn('mes', 'Mes', 'right');
        $column_mes_ano = new TDataGridColumn('mes_ano', 'Mes Ano', 'left');
        //$column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_idade = new TDataGridColumn('idade_pessoa', 'Idade', 'right');
        $column_peso = new TDataGridColumn('peso', 'Peso', 'right');
        $column_altura = new TDataGridColumn('altura', 'Altura', 'right');
        $column_imcv = new TDataGridColumn('fator', 'Imcv', 'right');
        
        //$column_baixo_peso = new TDataGridColumn('baixo_peso', 'Baixo Peso', 'right');
        //$column_adequado = new TDataGridColumn('adequado', 'Adequado', 'right');
        //$column_sobrepeso = new TDataGridColumn('sobrepeso', 'Sobrepeso', 'right');
        $column_tipo_imc = new TDataGridColumn('tipo_imc', 'Tipo Imc', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_pessoa_id);
        //$this->datagrid->addColumn($column_ano);
        //$this->datagrid->addColumn($column_mes);
        $this->datagrid->addColumn($column_mes_ano);
        //$this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_idade);
        $this->datagrid->addColumn($column_peso);
        $this->datagrid->addColumn($column_altura);
        $this->datagrid->addColumn($column_imcv);
        //$this->datagrid->addColumn($column_baixo_peso);
        //$this->datagrid->addColumn($column_adequado);
        //$this->datagrid->addColumn($column_sobrepeso);
        $this->datagrid->addColumn($column_tipo_imc);
        
        

        /*
        // create EDIT action
        $action_edit = new TDataGridAction(['VwImcForm', 'onEdit']);
        //$action_edit->setUseButton(TRUE);
        //$action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('pessoa_id');
        $this->datagrid->addAction($action_edit);
        */
        /*
        $action_select2 = new TDataGridAction(array('Painel02View', 'onReload'));
        $action_select2->setUseButton(TRUE);
        $action_select2->setButtonClass('nopadding');
        $action_select2->setLabel('Perfil');
        $action_select2->setImage('fa:hand-pointer-o red');
        $action_select2->setField('pessoa_id');
        $this->datagrid->addAction($action_select2);
        */
        $tipo='A34 - Crianças Obesas';
        $sistema_id=2;
        $evento_id=2;
        $action_select2 = new TDataGridAction(array('Painel02View', 'onReload'));
        $action_select2->setUseButton(TRUE);
        $action_select2->setButtonClass('nopadding');
        $action_select2->setLabel('Perfil');
        $action_select2->setImage('fa:hand-pointer-o red');
        $action_select2->setField('pessoa_id');
        //$action_select2->setField('sistema_id');
        //$action_select2->setField('evento_id');
        $action_select2->setParameter('sistema_id',$sistema_id);
        $action_select2->setParameter('evento_id',$evento_id);
        $action_select2->setParameter('tipo',$tipo);
        //$action_select2->setField('tipo','A23 - Gestantes adolescentes fora do FQA');
        $this->datagrid->addAction($action_select2);

        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $div = new TElement('div');
        $div->add( $b = new ImcBarChartView(false) );
        


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
            $object = new VwImc($key); // instantiates the Active Record
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
        TSession::setValue('VwImcList_filter_pessoa_id',   NULL);
        TSession::setValue('VwImcList_filter_ano',   NULL);
        TSession::setValue('VwImcList_filter_nome',   NULL);
        TSession::setValue('VwImcList_filter_idade',   NULL);
        TSession::setValue('VwImcList_filter_peso',   NULL);
        TSession::setValue('VwImcList_filter_altura',   NULL);
        TSession::setValue('VwImcList_filter_tipo_imc',   NULL);

        if (isset($data->pessoa_id) AND ($data->pessoa_id)) {
            $filter = new TFilter('pessoa_id', '=', "$data->pessoa_id"); // create the filter
            TSession::setValue('VwImcList_filter_pessoa_id',   $filter); // stores the filter in the session
        }


        if (isset($data->ano) AND ($data->ano)) {
            $filter = new TFilter('ano', '=', "$data->ano"); // create the filter
            TSession::setValue('VwImcList_filter_ano',   $filter); // stores the filter in the session
        }


        if (isset($data->nome) AND ($data->nome)) {
            $filter = new TFilter('nome', 'ilike', "%{$data->nome}%"); // create the filter
            TSession::setValue('VwImcList_filter_nome',   $filter); // stores the filter in the session
        }


        if (isset($data->idade) AND ($data->idade)) {
            $filter = new TFilter('idade', '=', "$data->idade"); // create the filter
            TSession::setValue('VwImcList_filter_idade',   $filter); // stores the filter in the session
        }


        if (isset($data->peso) AND ($data->peso)) {
            $filter = new TFilter('peso', '=', "$data->peso"); // create the filter
            TSession::setValue('VwImcList_filter_peso',   $filter); // stores the filter in the session
        }


        if (isset($data->altura) AND ($data->altura)) {
            $filter = new TFilter('altura', 'ilike', "%{$data->altura}%"); // create the filter
            TSession::setValue('VwImcList_filter_altura',   $filter); // stores the filter in the session
        }


        if (isset($data->tipo_imc) AND ($data->tipo_imc)) {
            $filter = new TFilter('tipo_imc', 'ilike', "%{$data->tipo_imc}%"); // create the filter
            TSession::setValue('VwImcList_filter_tipo_imc',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('VwImc_filter_data', $data);
        
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
            
            // creates a repository for VwImc
            $repository = new TRepository('VwImc');
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
            

            if (TSession::getValue('VwImcList_filter_pessoa_id')) {
                $criteria->add(TSession::getValue('VwImcList_filter_pessoa_id')); // add the session filter
            }


            if (TSession::getValue('VwImcList_filter_ano')) {
                $criteria->add(TSession::getValue('VwImcList_filter_ano')); // add the session filter
            }


            if (TSession::getValue('VwImcList_filter_nome')) {
                $criteria->add(TSession::getValue('VwImcList_filter_nome')); // add the session filter
            }


            if (TSession::getValue('VwImcList_filter_idade')) {
                $criteria->add(TSession::getValue('VwImcList_filter_idade')); // add the session filter
            }


            if (TSession::getValue('VwImcList_filter_peso')) {
                $criteria->add(TSession::getValue('VwImcList_filter_peso')); // add the session filter
            }


            if (TSession::getValue('VwImcList_filter_altura')) {
                $criteria->add(TSession::getValue('VwImcList_filter_altura')); // add the session filter
            }


            if (TSession::getValue('VwImcList_filter_tipo_imc')) {
                $criteria->add(TSession::getValue('VwImcList_filter_tipo_imc')); // add the session filter
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
            $object = new VwImc($key, FALSE); // instantiates the Active Record
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
