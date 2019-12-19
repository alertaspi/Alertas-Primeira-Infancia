<?php
/**
 * DatagridProgressView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class BarraIndicadorSociedade02 extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TQuickGrid);
        $this->datagrid->style = 'width: 100%';
        
        // add the columns
        //$this->datagrid->addQuickColumn('#id',  'id', 'center', '10%');
        $this->datagrid->addQuickColumn('Mês/Ano Ref',  'mesref', 'left',   '35%');
        //$this->datagrid->addQuickColumn('Qtd',  'qtd', 'center', '20%');
        $subtotal = new TDataGridColumn('= {qtd}', 'Subtotal', 'right');
        $column1 = $this->datagrid->addQuickColumn('de 0 a 1 ano', 'pct_0_a_1', 'center', '10%');
        $column2 = $this->datagrid->addQuickColumn('de 1 a 2 anos', 'pct_1_a_2', 'center', '10%');
        $column3 = $this->datagrid->addQuickColumn('de 2 a 3 anos', 'pct_2_a_3', 'center', '10%');
        $column4 = $this->datagrid->addQuickColumn('de 3 a 4 anos', 'pct_3_a_4', 'center', '10%');
        $column5 = $this->datagrid->addQuickColumn('de 4 a 5 anos', 'pct_4_a_5', 'center', '10%');
        
        $this->datagrid->addColumn($subtotal);
        
        
        $subtotal->setTotalFunction( function($values) {
            return array_sum((array) $values);
        });
        
        // define the transformer method over image
            $column1->setTransformer( function($percent) {
            $bar = new TProgressBar;
            $bar->setMask(' <b>{value}</b>% completo');
            $bar->setValue($percent);
            //$bar->style('background-color: #00a65a;');
            $bar->style .= 'background-color: #00a65a';
            if ($percent == 100) {
                $bar->setClass('success');
                
            }
            else if ($percent >= 75) {
                $bar->setClass('info');
            }
            else if ($percent >= 50) {
                $bar->setClass('warning');
            }
            else {
                $bar->setClass('danger');
            }
            return $bar;
        });
         $column2->setTransformer( function($percent) {
            $bar = new TProgressBar;
            $bar->setMask(' <b>{value}</b>% completo');
            $bar->setValue($percent);
            //$bar->style('background-color: #00a65a;');
            $bar->style .= 'background-color: #00a65a';
            if ($percent == 100) {
                $bar->setClass('success');
                
            }
            else if ($percent >= 75) {
                $bar->setClass('info');
            }
            else if ($percent >= 50) {
                $bar->setClass('warning');
            }
            else {
                $bar->setClass('danger');
            }
            return $bar;
        });
         $column3->setTransformer( function($percent) {
            $bar = new TProgressBar;
            $bar->setMask(' <b>{value}</b>% completo');
            $bar->setValue($percent);
            //$bar->style('background-color: #00a65a;');
            $bar->style .= 'background-color: #00a65a';
            if ($percent == 100) {
                $bar->setClass('success');
                
            }
            else if ($percent >= 75) {
                $bar->setClass('info');
            }
            else if ($percent >= 50) {
                $bar->setClass('warning');
            }
            else {
                $bar->setClass('danger');
            }
            return $bar;
        });
         $column4->setTransformer( function($percent) {
            $bar = new TProgressBar;
            $bar->setMask(' <b>{value}</b>% completo');
            $bar->setValue($percent);
            //$bar->style('background-color: #00a65a;');
            $bar->style .= 'background-color: #00a65a';
            if ($percent == 100) {
                $bar->setClass('success');
                
            }
            else if ($percent >= 75) {
                $bar->setClass('info');
            }
            else if ($percent >= 50) {
                $bar->setClass('warning');
            }
            else {
                $bar->setClass('danger');
            }
            return $bar;
        });
         $column5->setTransformer( function($percent) {
            $bar = new TProgressBar;
            $bar->setMask(' <b>{value}</b>% completo');
            $bar->setValue($percent);
            //$bar->style('background-color: #00a65a;');
            $bar->style .= 'background-color: #00a65a';
            if ($percent == 100) {
                $bar->setClass('success');
                
            }
            else if ($percent >= 75) {
                $bar->setClass('info');
            }
            else if ($percent >= 50) {
                $bar->setClass('warning');
            }
            else {
                $bar->setClass('danger');
            }
            return $bar;
        });
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add(TPanelGroup::pack(('Sociedade - Atendimento a Crianças de 0 a 5 anos'), $this->datagrid));

        parent::add($vbox);
    }
    
    /**
     * Load the data into the datagrid
     */
    function onReload()
    {
        $this->datagrid->clear();
        
        
        try{
               
           TTransaction::open('dbpmbv');
           $conn = TTransaction::get();
           
           $sql6="select d.ano, d.mes, d.mes_desc, concat(d.mes_desc,'/',d.ano) mesanoref,   count(a.*) qtd,
       round(count(distinct a.id) filter(where extract(year  from age(CURRENT_DATE, a.data_nascimento) ) between 0 and 1)   / SUM(count(*)) OVER () * 100, 2) AS pct_0_e_1,
	   round(count(distinct a.id) filter(where extract(year  from age(CURRENT_DATE, a.data_nascimento) ) between 1 and 2)   / SUM(count(*)) OVER () * 100, 2) AS pct_1_e_2,
	   round(count(distinct a.id) filter(where extract(year  from age(CURRENT_DATE, a.data_nascimento) ) between 2 and 3)   / SUM(count(*)) OVER () * 100, 2) AS pct_2_e_3,
	   round(count(distinct a.id) filter(where extract(year  from age(CURRENT_DATE, a.data_nascimento) ) between 3 and 4)   / SUM(count(*)) OVER () * 100, 2) AS pct_3_e_4,
	   round(count(distinct a.id) filter(where extract(year  from age(CURRENT_DATE, a.data_nascimento) ) between 4 and 5)   / SUM(count(*)) OVER () * 100, 2) AS pct_4_e_5 
from scperfil.pessoas a
join scperfil.fatos_dados c on a.id=c.pessoa_id
join scperfil.tempo d on c.data_evento=d.datainfo
group by d.ano, d.mes, d.mes_desc
order by d.ano desc, d.mes desc";             
            $stmt6 = $conn->prepare($sql6);
            $stmt6->execute();
            $results6 = $stmt6->fetchAll();
            //var_dump($results6);
            $ano=date('Y');
            //$data[] =['Sistema','Qtd Cadastro'];
            foreach($results6 as $row){
                          $item = new StdClass;
                          //$item->id = $row[0];
                          $item->mesref = $row[3];
                          $item->qtd = $row[4];
                          $item->pct_0_a_1 = $row[5];
                          $item->pct_1_a_2 = $row[6];
                          $item->pct_2_a_3 = $row[7];
                          $item->pct_3_a_4 = $row[8];
                          $item->pct_4_a_5 = $row[9];
                          $this->datagrid->addItem($item);
                          //$data[]=[$row[0],$row[1]];
              }
           TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
        
        /*
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '1';
        $item->task      = 'Install Ubuntu Server';
        $item->percent   = '100';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '2';
        $item->task      = 'Install Apache';
        $item->percent   = '80';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '3';
        $item->task      = 'Install PHP';
        $item->percent   = '60';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '4';
        $item->task      = 'Install PostgreSQL';
        $item->percent   = '40';
        $this->datagrid->addItem($item);
        */
    }
    
    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}
