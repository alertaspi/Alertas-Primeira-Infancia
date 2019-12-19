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
class BarraIndicadorMobilidade02 extends TPage
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
        $column = $this->datagrid->addQuickColumn('Percentual', 'percent', 'center', '35%');
        
        $this->datagrid->addColumn($subtotal);
        
        
        $subtotal->setTotalFunction( function($values) {
            return array_sum((array) $values);
        });
        
        // define the transformer method over image
        $column->setTransformer( function($percent) {
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
        $vbox->add(TPanelGroup::pack(('Mobilidade - Atendimento pré natal (gestação concluída)'), $this->datagrid));

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
           
           $sql6="select c.ano, c.mes, c.mes_desc, concat(c.mes_desc,'/',c.ano) mesanoref , sum(tb1.qtd),round(count(tb1.qtd) / SUM(count(*)) OVER () * 100, 2) AS pct 
from (
select pessoa_id, count(distinct evento_id) qtd
from scperfil.fatos 
where sistema_id=1 and evento_id in (1,22) 
and pessoa_id in (
select pessoa_id
from scperfil.fatos where tipo_id=7 and valor_dado!='' and round((current_date - valor_dado::date)/7,0) between 40 and 300
) 
group by pessoa_id
) tb1
join scperfil.fatos_dados b on tb1.pessoa_id=b.pessoa_id 
join scperfil.tempo c on b.data_evento=c.datainfo
where b.sistema_id=1 and round((current_date - dum::date)/7,0) between 40 and 300
group by c.ano, c.mes, c.mes_desc
order by 1 desc, 2 desc";             
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
                          $item->percent = $row[5];
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
