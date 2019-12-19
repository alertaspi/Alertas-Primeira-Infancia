<?php
/**
 * PessoasAlertasAcoesList Listing
 * @author  <your name here>
 */
class PessoasAlertasAcoesList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        
        
        parent::__construct();
        $criteria = new TCriteria();
        $this->setDatabase('dbpmbv');            // defines the database
        $this->setActiveRecord('PessoasAlertasAcoes');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        // $this->setCriteria($criteria) // define a standard filter
        //$this->addFilterField('pessoas_alertas_id','=',$_GET['pessoas_alertas_id']);
        //var_dump(TSession::getValue('pessoas_alertas_id'));
        
        if(!isset($_GET['pessoas_alertas_id'])){
          $pessoas_alertas_id=TSession::getValue('pessoas_alertas_id');            
        }else{
          if(isset($_GET['pessoas_alertas_id'])){
            $pessoas_alertas_id=$_GET['pessoas_alertas_id'];   
          }else{  
            
            $pessoas_alertas_id=TSession::getValue('pessoas_alertas_id');    
          }          
        }
        
        //print_r($_GET['pessoas_alertas_id']);
        $criteria->add(new TFilter('pessoas_alertas_id',   '=',      $pessoas_alertas_id));
        $this->setCriteria($criteria);  

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('pessoas_alertas_id', '=', 'pessoas_alertas_id'); // filterField, operator, formField
        $this->addFilterField('usuario_id', 'like', 'usuario_id'); // filterField, operator, formField
        $this->addFilterField('acao_tomada', 'like', 'acao_tomada'); // filterField, operator, formField
        $this->addFilterField('descricao_acao', 'like', 'descricao_acao'); // filterField, operator, formField
        $this->addFilterField('id_pai', 'like', 'id_pai'); // filterField, operator, formField
        $this->addFilterField('data_info', 'like', 'data_info'); // filterField, operator, formField
        $this->addFilterField('status', 'like', 'status'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_PessoasAlertasAcoes');
        $this->form->setFormTitle('PessoasAlertasAcoes');
        

        // create the form fields
        $id = new TEntry('id');
        //$pessoas_alertas_id = new TDBUniqueSearch('pessoas_alertas_id', 'dbpmbv', 'PessoasAlertas', 'id', 'pessoa_id');
        $pessoas_alertas_id = new Tentry('pessoas_alertas_id');
        $usuario_id = new TEntry('usuario_id');
        $acao_tomada = new TEntry('acao_tomada');
        $descricao_acao = new TEntry('descricao_acao');
        $id_pai = new TCombo('id_pai');
        $data_info = new TDate('data_info');
        $status = new TCombo('status', 'dbpmbv', 'AlertaStatus', 'id', 'descricao');


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
        //$this->form->setData( TSession::setValue('pessoas_alertas_id',   $pessoas_alertas_id));
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['PessoasAlertasAcoesForm', 'onEdit']), 'fa:plus green');
        
        // creates a DataGrid
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
        //$column_acao_tomada->setAction(new TAction([$this, 'onReload']), ['order' => 'acao_tomada']);
        //$column_data_info->setAction(new TAction([$this, 'onReload']), ['order' => 'data_info']);
        //$column_status->setAction(new TAction([$this, 'onReload']), ['order' => 'status']);

        
        //var_dump($param);
        
        // create EDIT action
        $action_edit = new TDataGridAction(['PessoasAlertasAcoesForm', 'onEdit']);
        //$action_edit->setUseButton(TRUE);
        //$action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');        
        $action_edit->setField('id');
        $action_edit->setField('pessoas_alertas_id');
        
        $action_edit->setParameter('pessoa_id',$_GET['pessoa_id']);
        $action_edit->setParameter('sistema_id',$_GET['sistema_id']);
        $action_edit->setParameter('evento_id',$_GET['evento_id']);
        //$action_edit->setParameter('pessoas_alertas_id',$pessoas_alertas_id);
        $action_edit->setParameter('tipo',$_GET['tipo']);
        
        
        $this->datagrid->addAction($action_edit);
        

        
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
        //$container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
    

}
