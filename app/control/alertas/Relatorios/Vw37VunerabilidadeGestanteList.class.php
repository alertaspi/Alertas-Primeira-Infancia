<?php
/**
 * Vw37VunerabilidadeGestanteList Listing
 * @author  <your name here>
 */
class Vw37VunerabilidadeGestanteList extends TPage
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
        $this->form = new BootstrapFormBuilder('form_Vw37VunerabilidadeGestante');
        $this->form->setFormTitle('Vw37VunerabilidadeGestante');
        

        // create the form fields
        $pessoa_id = new TEntry('pessoa_id');
        $sistema_id = new TEntry('sistema_id');
        $evento_id = new TEntry('evento_id');
        $ano = new TEntry('ano');
        $mes_desc = new TEntry('mes_desc');
        $nome = new TEntry('nome');
        $mae = new TEntry('mae');
        $cns = new TEntry('cns');
        $descricao = new TEntry('descricao');
        $valor_dado = new TEntry('valor_dado');
        $idade = new TEntry('idade');


        // add the fields
        $this->form->addFields( [ new TLabel('Pessoa Id') ], [ $pessoa_id ] );
        $this->form->addFields( [ new TLabel('Sistema Id') ], [ $sistema_id ] );
        $this->form->addFields( [ new TLabel('Evento Id') ], [ $evento_id ] );
        $this->form->addFields( [ new TLabel('Ano') ], [ $ano ] );
        $this->form->addFields( [ new TLabel('Mes Desc') ], [ $mes_desc ] );
        $this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Mae') ], [ $mae ] );
        $this->form->addFields( [ new TLabel('Cns') ], [ $cns ] );
        $this->form->addFields( [ new TLabel('Descricao') ], [ $descricao ] );
        $this->form->addFields( [ new TLabel('Valor Dado') ], [ $valor_dado ] );
        $this->form->addFields( [ new TLabel('Idade') ], [ $idade ] );


        // set sizes
        $pessoa_id->setSize('100%');
        $sistema_id->setSize('100%');
        $evento_id->setSize('100%');
        $ano->setSize('100%');
        $mes_desc->setSize('100%');
        $nome->setSize('100%');
        $mae->setSize('100%');
        $cns->setSize('100%');
        $descricao->setSize('100%');
        $valor_dado->setSize('100%');
        $idade->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Vw37VunerabilidadeGestante_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['Vw37VunerabilidadeGestanteForm', 'onEdit']), 'fa:plus green');
        
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
        $column_mae = new TDataGridColumn('mae', 'Mae', 'left');
        $column_cns = new TDataGridColumn('cns', 'Cns', 'left');
        $column_descricao = new TDataGridColumn('descricao', 'Descricao', 'left');
        $column_valor_dado = new TDataGridColumn('valor_dado', 'Valor Dado', 'left');
        $column_idade = new TDataGridColumn('idade', 'Idade', 'right');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_pessoa_id);
        $this->datagrid->addColumn($column_sistema_id);
        $this->datagrid->addColumn($column_evento_id);
        $this->datagrid->addColumn($column_tempo_id);
        $this->datagrid->addColumn($column_ano);
        $this->datagrid->addColumn($column_mes_desc);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_mae);
        $this->datagrid->addColumn($column_cns);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_valor_dado);
        $this->datagrid->addColumn($column_idade);

        
        // create EDIT action
        $action_edit = new TDataGridAction(['Vw37VunerabilidadeGestanteForm', 'onEdit']);
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
            $object = new Vw37VunerabilidadeGestante($key); // instantiates the Active Record
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
        TSession::setValue('Vw37VunerabilidadeGestanteList_filter_pessoa_id',   NULL);
        TSession::setValue('Vw37VunerabilidadeGestanteList_filter_sistema_id',   NULL);
        TSession::setValue('Vw37VunerabilidadeGestanteList_filter_evento_id',   NULL);
        TSession::setValue('Vw37VunerabilidadeGestanteList_filter_ano',   NULL);
        TSession::setValue('Vw37VunerabilidadeGestanteList_filter_mes_desc',   NULL);
        TSession::setValue('Vw37VunerabilidadeGestanteList_filter_nome',   NULL);
        TSession::setValue('Vw37VunerabilidadeGestanteList_filter_mae',   NULL);
        TSession::setValue('Vw37VunerabilidadeGestanteList_filter_cns',   NULL);
        TSession::setValue('Vw37VunerabilidadeGestanteList_filter_descricao',   NULL);
        TSession::setValue('Vw37VunerabilidadeGestanteList_filter_valor_dado',   NULL);
        TSession::setValue('Vw37VunerabilidadeGestanteList_filter_idade',   NULL);

        if (isset($data->pessoa_id) AND ($data->pessoa_id)) {
            $filter = new TFilter('pessoa_id', '=', "$data->pessoa_id"); // create the filter
            TSession::setValue('Vw37VunerabilidadeGestanteList_filter_pessoa_id',   $filter); // stores the filter in the session
        }


        if (isset($data->sistema_id) AND ($data->sistema_id)) {
            $filter = new TFilter('sistema_id', 'like', "%{$data->sistema_id}%"); // create the filter
            TSession::setValue('Vw37VunerabilidadeGestanteList_filter_sistema_id',   $filter); // stores the filter in the session
        }


        if (isset($data->evento_id) AND ($data->evento_id)) {
            $filter = new TFilter('evento_id', 'like', "%{$data->evento_id}%"); // create the filter
            TSession::setValue('Vw37VunerabilidadeGestanteList_filter_evento_id',   $filter); // stores the filter in the session
        }


        if (isset($data->ano) AND ($data->ano)) {
            $filter = new TFilter('ano', 'like', "%{$data->ano}%"); // create the filter
            TSession::setValue('Vw37VunerabilidadeGestanteList_filter_ano',   $filter); // stores the filter in the session
        }


        if (isset($data->mes_desc) AND ($data->mes_desc)) {
            $filter = new TFilter('mes_desc', 'like', "%{$data->mes_desc}%"); // create the filter
            TSession::setValue('Vw37VunerabilidadeGestanteList_filter_mes_desc',   $filter); // stores the filter in the session
        }


        if (isset($data->nome) AND ($data->nome)) {
            $filter = new TFilter('nome', 'ilike', "%{$data->nome}%"); // create the filter
            TSession::setValue('Vw37VunerabilidadeGestanteList_filter_nome',   $filter); // stores the filter in the session
        }


        if (isset($data->mae) AND ($data->mae)) {
            $filter = new TFilter('mae', 'ilike', "%{$data->mae}%"); // create the filter
            TSession::setValue('Vw37VunerabilidadeGestanteList_filter_mae',   $filter); // stores the filter in the session
        }


        if (isset($data->cns) AND ($data->cns)) {
            $filter = new TFilter('cns', 'ilike', "%{$data->cns}%"); // create the filter
            TSession::setValue('Vw37VunerabilidadeGestanteList_filter_cns',   $filter); // stores the filter in the session
        }


        if (isset($data->descricao) AND ($data->descricao)) {
            $filter = new TFilter('descricao', 'like', "%{$data->descricao}%"); // create the filter
            TSession::setValue('Vw37VunerabilidadeGestanteList_filter_descricao',   $filter); // stores the filter in the session
        }


        if (isset($data->valor_dado) AND ($data->valor_dado)) {
            $filter = new TFilter('valor_dado', 'ilike', "%{$data->valor_dado}%"); // create the filter
            TSession::setValue('Vw37VunerabilidadeGestanteList_filter_valor_dado',   $filter); // stores the filter in the session
        }


        if (isset($data->idade) AND ($data->idade)) {
            $filter = new TFilter('idade', '=', "$data->idade"); // create the filter
            TSession::setValue('Vw37VunerabilidadeGestanteList_filter_idade',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Vw37VunerabilidadeGestante_filter_data', $data);
        
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
            
            // creates a repository for Vw37VunerabilidadeGestante
            $repository = new TRepository('Vw37VunerabilidadeGestante');
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
            

            if (TSession::getValue('Vw37VunerabilidadeGestanteList_filter_pessoa_id')) {
                $criteria->add(TSession::getValue('Vw37VunerabilidadeGestanteList_filter_pessoa_id')); // add the session filter
            }


            if (TSession::getValue('Vw37VunerabilidadeGestanteList_filter_sistema_id')) {
                $criteria->add(TSession::getValue('Vw37VunerabilidadeGestanteList_filter_sistema_id')); // add the session filter
            }


            if (TSession::getValue('Vw37VunerabilidadeGestanteList_filter_evento_id')) {
                $criteria->add(TSession::getValue('Vw37VunerabilidadeGestanteList_filter_evento_id')); // add the session filter
            }


            if (TSession::getValue('Vw37VunerabilidadeGestanteList_filter_ano')) {
                $criteria->add(TSession::getValue('Vw37VunerabilidadeGestanteList_filter_ano')); // add the session filter
            }


            if (TSession::getValue('Vw37VunerabilidadeGestanteList_filter_mes_desc')) {
                $criteria->add(TSession::getValue('Vw37VunerabilidadeGestanteList_filter_mes_desc')); // add the session filter
            }


            if (TSession::getValue('Vw37VunerabilidadeGestanteList_filter_nome')) {
                $criteria->add(TSession::getValue('Vw37VunerabilidadeGestanteList_filter_nome')); // add the session filter
            }


            if (TSession::getValue('Vw37VunerabilidadeGestanteList_filter_mae')) {
                $criteria->add(TSession::getValue('Vw37VunerabilidadeGestanteList_filter_mae')); // add the session filter
            }


            if (TSession::getValue('Vw37VunerabilidadeGestanteList_filter_cns')) {
                $criteria->add(TSession::getValue('Vw37VunerabilidadeGestanteList_filter_cns')); // add the session filter
            }


            if (TSession::getValue('Vw37VunerabilidadeGestanteList_filter_descricao')) {
                $criteria->add(TSession::getValue('Vw37VunerabilidadeGestanteList_filter_descricao')); // add the session filter
            }


            if (TSession::getValue('Vw37VunerabilidadeGestanteList_filter_valor_dado')) {
                $criteria->add(TSession::getValue('Vw37VunerabilidadeGestanteList_filter_valor_dado')); // add the session filter
            }


            if (TSession::getValue('Vw37VunerabilidadeGestanteList_filter_idade')) {
                $criteria->add(TSession::getValue('Vw37VunerabilidadeGestanteList_filter_idade')); // add the session filter
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
            $object = new Vw37VunerabilidadeGestante($key, FALSE); // instantiates the Active Record
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
