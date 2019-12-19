<?php
/**
 * PessoasSelectionList Record selection
 * @author  <your name here>
 */
class PessoasSelectionList extends TPage
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
        $this->setActiveRecord('Pessoas');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        $this->addFilterField('sexo', 'like', 'sexo'); // filterField, operator, formField
        $this->addFilterField('data_nascimento', 'like', 'data_nascimento'); // filterField, operator, formField
        $this->addFilterField('mae', 'like', 'mae'); // filterField, operator, formField
        $this->addFilterField('rg', 'like', 'rg'); // filterField, operator, formField
        $this->addFilterField('cpf', 'like', 'cpf'); // filterField, operator, formField
        $this->addFilterField('cns', 'like', 'cns'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Pessoas');
        $this->form->setFormTitle('Pessoas');
        

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $sexo = new TEntry('sexo');
        $data_nascimento = new TEntry('data_nascimento');
        $mae = new TEntry('mae');
        $rg = new TEntry('rg');
        $cpf = new TEntry('cpf');
        $cns = new TEntry('cns');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Sexo') ], [ $sexo ] );
        $this->form->addFields( [ new TLabel('Data Nascimento') ], [ $data_nascimento ] );
        $this->form->addFields( [ new TLabel('Mae') ], [ $mae ] );
        $this->form->addFields( [ new TLabel('Rg') ], [ $rg ] );
        $this->form->addFields( [ new TLabel('Cpf') ], [ $cpf ] );
        $this->form->addFields( [ new TLabel('Cns') ], [ $cns ] );


        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $sexo->setSize('100%');
        $data_nascimento->setSize('100%');
        $mae->setSize('100%');
        $rg->setSize('100%');
        $cpf->setSize('100%');
        $cns->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Pessoas_filter_data') );
        
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
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_sexo = new TDataGridColumn('sexo', 'Sexo', 'left');
        $column_data_nascimento = new TDataGridColumn('data_nascimento', 'Data Nascimento', 'left');
        $column_mae = new TDataGridColumn('mae', 'Mae', 'left');
        $column_rg = new TDataGridColumn('rg', 'Rg', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'Cpf', 'left');
        $column_cns = new TDataGridColumn('cns', 'Cns', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_sexo);
        $this->datagrid->addColumn($column_data_nascimento);
        $this->datagrid->addColumn($column_mae);
        $this->datagrid->addColumn($column_rg);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_cns);

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
        $object = new Pessoas($param['key']); // load the object
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
        $datagrid->addQuickColumn('Nome', 'nome', 'left');
        $datagrid->addQuickColumn('Sexo', 'sexo', 'left');
        $datagrid->addQuickColumn('Data Nascimento', 'data_nascimento', 'left');
        $datagrid->addQuickColumn('Mae', 'mae', 'left');
        $datagrid->addQuickColumn('Rg', 'rg', 'left');
        $datagrid->addQuickColumn('Cpf', 'cpf', 'left');
        $datagrid->addQuickColumn('Cns', 'cns', 'left');
        
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
