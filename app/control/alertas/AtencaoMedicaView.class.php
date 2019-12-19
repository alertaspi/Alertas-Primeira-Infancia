<?php

/**
 * DatagridActionGroupView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class AtencaoMedicaView extends TPage {

    private $form = "";
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $html;

    function __construct() {
        parent::__construct();

        $this->html = new THtmlRenderer('app/resources/alertas/acompPreNatal.html');
        //$html1->enableSection('main', array());
        
        
        $this->form = new BootstrapFormBuilder('form_search_alertas_fqa');
        // create the form fields
        $pessoa_id = new TEntry('pessoa_id');
        $nome = new TEntry('nome');
        $mae = new TEntry('mae');
        $cns = new TEntry('cns');
        $idade = new TEntry('idade');
        
        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $pessoa_id ] );        
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
        //$html1->enableSection('main', array());
        
        
        $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('pessoa_id', 'Id', 'right');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_mae = new TDataGridColumn('mae', 'Mae', 'left');
        $column_cns = new TDataGridColumn('cns', 'Cns', 'left');
        $column_idade = new TDataGridColumn('idade', 'Idade', 'left');
        $column_qtd = new TDataGridColumn('qtd', 'Qtd Situações', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_mae);
        $this->datagrid->addColumn($column_cns);
        $this->datagrid->addColumn($column_idade);
        $this->datagrid->addColumn($column_qtd);
        
        $tipo='A40 - Atenção medica primerissima infancia';
        $action_select2 = new TDataGridAction(array('Painel02View', 'onReload'));
        $action_select2->setUseButton(TRUE);
        $action_select2->setButtonClass('nopadding');
        $action_select2->setLabel('Perfil');
        $action_select2->setImage('fa:hand-pointer-o red');
        $action_select2->setField('pessoa_id');
        $action_select2->setField('sistema_id');
        $action_select2->setField('evento_id');
        $action_select2->setParameter('tipo',$tipo);
        //$action_select2->setField('tipo','A23 - Gestantes adolescentes fora do FQA');
        $this->datagrid->addAction($action_select2);
        
        $action_select = new TDataGridAction(array('PessoasFormList', 'onEdit'));
        $action_select->setUseButton(TRUE);
        $action_select->setButtonClass('nopadding');
        $action_select->setLabel('Cadastro');
        $action_select->setImage('fa:hand-pointer-o green');
        $action_select->setField('pessoa_id');
        $this->datagrid->addAction($action_select);
        
        
        
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        
        
        $this->html->enableSection('main', array());

        $panel1 = new TPanelGroup('A40 - Atenção medica primerissima infancia');        
        //$panel1->add($html1); 
        $div = new TElement('div');
        $div->add( $c = new Grafico40BarChartView(false) );
        //$panel1->add($div);       
        $panel1->add($this->html);
        $panel1->add($this->form);
        $panel1->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));

        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';

        // add the template to the page
        parent::add($vbox);
        
        /*

        $panel1 = new TPanelGroup('A11 - Acompanhamento Pré natal');
        $div = new TElement('div');
        $div->add( $c = new Vw11AcompanhamentoPreNatalList(false) );
               
        $panel1->add($this->html);
        $panel1->add($html1);
        $panel1->add($div);
        

        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';

        // add the template to the page
        parent::add($vbox);
        */
    }
    
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'dbpmbv'
            TTransaction::open('dbpmbv');
            
            // creates a repository for Pessoas
            $repository = new TRepository('Vw40AtencaoMedicaPrimerissimaInfancia');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            //$criteria->add(new TFilter("id","=",1));
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'nome';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('PessoasSeek_filter_pessoa_id')) {
                $criteria->add(TSession::getValue('PessoasSeek_filter_pessoa_id')); // add the session filter
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
            
            if (TSession::getValue('PessoasSeek_filter_idade')) {
                $criteria->add(TSession::getValue('PessoasSeek_filter_idade')); // add the session filter
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
    
    public static function onSelect($param)
    {
        try
        {
            $key = $param['key'];
            TTransaction::open('dbpmbv');
            
            // load the active record
            $object = Pessoas::find($key);
            
            var_dump($param);
            exit(0);
            // closes the transaction
            TTransaction::close();
            
            $send = new StdClass;
            $send->pessoas_id = $object->pessoa_id;
            $send->key=$object->pessoa_id;
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
    
    function onSearch(){
            $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue('PessoasSeek_filter_pessoa_id',   NULL);
        TSession::setValue('PessoasSeek_filter_nome',   NULL);
        TSession::setValue('PessoasSeek_filter_mae',   NULL);
        TSession::setValue('PessoasSeek_filter_cns',   NULL);
        TSession::setValue('PessoasSeek_filter_idade',   NULL);

        if (isset($data->pessoa_id) AND ($data->pessoa_id)) {
            $filter = new TFilter('pessoa_id', '=', "$data->pessoa_id"); // create the filter
            TSession::setValue('PessoasSeek_filter_pessoa_id',   $filter); // stores the filter in the session
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
        
        
       if (isset($data->idade) AND ($data->idade)) {
            $filter = new TFilter('idade', '=', "$data->idade"); // create the filter
            TSession::setValue('PessoasSeek_filter_idade',   $filter); // stores the filter in the session
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
