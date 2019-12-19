<?php
/**
 * PessoasAlertasSelectionList Record selection
 * @author  <your name here>
 */
class PessoasAlertasSelectionList extends TPage
{
    protected $form;     // search form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('dbpmbv');            // defines the database
        $this->setActiveRecord('PessoasAlertas');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('pessoa_id', 'like', 'pessoa_id'); // filterField, operator, formField
        $this->addFilterField('sistema_id', 'like', 'sistema_id'); // filterField, operator, formField
        $this->addFilterField('evento_id', 'like', 'evento_id'); // filterField, operator, formField
        $this->addFilterField('data_info', 'like', 'data_info'); // filterField, operator, formField
        $this->addFilterField('observacao', 'like', 'observacao'); // filterField, operator, formField
        $this->addFilterField('status', 'like', 'status'); // filterField, operator, formField
        $this->addFilterField('tipo', 'like', 'tipo'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_PessoasAlertas');
        $this->form->setFormTitle('Pessoas Alertas');
        

        // create the form fields
        $id = new TEntry('id');
        $pessoa_id = new TSeekButton('pessoa_id', 'dbpmbv', 'Pessoas', 'id', 'nome');
        $sistema_id = new TSeekButton('sistema_id', 'dbpmbv', 'Sistemas', 'id', 'nome');
        $evento_id = new TSeekButton('evento_id', 'dbpmbv', 'Eventos', 'id', 'nome');
        $data_info = new TEntry('data_info');
        $observacao = new TEntry('observacao');
        $status = new TEntry('status');
        $tipo = new TCombo('tipo');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Pessoa Id') ], [ $pessoa_id ] );
        $this->form->addFields( [ new TLabel('Sistema Id') ], [ $sistema_id ] );
        $this->form->addFields( [ new TLabel('Evento Id') ], [ $evento_id ] );
        $this->form->addFields( [ new TLabel('Data Info') ], [ $data_info ] );
        $this->form->addFields( [ new TLabel('Observacao') ], [ $observacao ] );
        $this->form->addFields( [ new TLabel('Status') ], [ $status ] );
        $this->form->addFields( [ new TLabel('Tipo') ], [ $tipo ] );


        // set sizes
        $id->setSize('100%');
        $pessoa_id->setSize('100%');
        $sistema_id->setSize('100%');
        $evento_id->setSize('100%');
        $data_info->setSize('100%');
        $observacao->setSize('100%');
        $status->setSize('100%');
        $tipo->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('PessoasAlertas_filter_data') );
        
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction('Show results', new TAction([$this, 'showResults']), 'fa:check-circle-o green');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'right');
        $column_pessoa_id = new TDataGridColumn('pessoa_id', 'Pessoa Id', 'right');
        $column_sistema_id = new TDataGridColumn('sistema_id', 'Sistema Id', 'right');
        $column_evento_id = new TDataGridColumn('evento_id', 'Evento Id', 'right');
        $column_usuario_id = new TDataGridColumn('usuario_id', 'Usuario Id', 'right');
        $column_data_info = new TDataGridColumn('data_info', 'Data Info', 'left');
        $column_observacao = new TDataGridColumn('observacao', 'Observacao', 'left');
        $column_status = new TDataGridColumn('status', 'Status', 'right');
        $column_tipo = new TDataGridColumn('tipo', 'Tipo', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_pessoa_id);
        $this->datagrid->addColumn($column_sistema_id);
        $this->datagrid->addColumn($column_evento_id);
        $this->datagrid->addColumn($column_usuario_id);
        $this->datagrid->addColumn($column_data_info);
        $this->datagrid->addColumn($column_observacao);
        $this->datagrid->addColumn($column_status);
        $this->datagrid->addColumn($column_tipo);


        // creates the datagrid column actions
        $column_data_info->setAction(new TAction([$this, 'onReload']), ['order' => 'data_info']);
        $column_status->setAction(new TAction([$this, 'onReload']), ['order' => 'status']);
        $column_tipo->setAction(new TAction([$this, 'onReload']), ['order' => 'tipo']);

        $column_id->setTransformer([$this, 'formatRow'] );
        
        // creates the datagrid actions
        $action1 = new TDataGridAction([$this, 'onSelect']);
        $action1->setUseButton(TRUE);
        $action1->setButtonClass('btn btn-default');
        $action1->setLabel(AdiantiCoreTranslator::translate('Select'));
        $action1->setImage('fa:check-circle-o blue');
        $action1->setField('id');
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
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
     * Save the object reference in session
     */
    public function onSelect($param)
    {
        // get the selected objects from session 
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        TTransaction::open('dbpmbv');
        $object = new PessoasAlertas($param['key']); // load the object
        if (isset($selected_objects[$object->id]))
        {
            unset($selected_objects[$object->id]);
        }
        else
        {
            $selected_objects[$object->id] = $object->toArray(); // add the object inside the array
        }
        TSession::setValue(__CLASS__.'_selected_objects', $selected_objects); // put the array back to the session
        TTransaction::close();
        
        // reload datagrids
        $this->onReload( func_get_arg(0) );
    }
    
    /**
     * Highlight the selected rows
     */
    public function formatRow($value, $object, $row)
    {
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        if ($selected_objects)
        {
            if (in_array( (int) $value, array_keys( $selected_objects ) ) )
            {
                $row->style = "background: #FFD965";
            }
        }
        
        return $value;
    }
    
    /**
     * Show selected records
     */
    public function showResults()
    {
        $datagrid = new BootstrapDatagridWrapper(new TQuickGrid);
        
        $datagrid->addQuickColumn('Id', 'id', 'right');
        $datagrid->addQuickColumn('Pessoa Id', 'pessoa_id', 'right');
        $datagrid->addQuickColumn('Sistema Id', 'sistema_id', 'right');
        $datagrid->addQuickColumn('Evento Id', 'evento_id', 'right');
        $datagrid->addQuickColumn('Usuario Id', 'usuario_id', 'right');
        $datagrid->addQuickColumn('Data Info', 'data_info', 'left');
        $datagrid->addQuickColumn('Observacao', 'observacao', 'left');
        $datagrid->addQuickColumn('Status', 'status', 'right');
        $datagrid->addQuickColumn('Tipo', 'tipo', 'left');
        
        // create the datagrid model
        $datagrid->createModel();
        
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        ksort($selected_objects);
        if ($selected_objects)
        {
            $datagrid->clear();
            foreach ($selected_objects as $selected_object)
            {
                $datagrid->addItem( (object) $selected_object );
            }
        }
        
        $win = TWindow::create('Results', 0.6, 0.6);
        $win->add($datagrid);
        $win->show();
    }
}
