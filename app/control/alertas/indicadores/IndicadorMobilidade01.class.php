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
class IndicadorMobilidade01 extends \Adianti\Control\TPage {

    private $form = "";
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $html;

    function __construct() {
        parent::__construct();

        $this->html = new THtmlRenderer('app/resources/alertas/educacao.html');

        $this->html->enableSection('main', array());

        $panel1 = new TPanelGroup('Mobilidade - Local de Atendimento');      
        /*
        TTransaction::open('dbpmbv');  
        $conn=TTransaction::get();
        
        $sql="select a.valor_dado
FROM scperfil.fatos a
join scperfil.tempo b on a.tempo_id=b.id
where a.tipo_id=10 and b.ano=extract(year from current_date) and a.valor_dado!=''
group by a.valor_dado
order by 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $count = $stmt->rowCount();
            $results = $stmt->fetchAll();
            $div = new TElement('div');
            $x=0;
            
            foreach($results as $row){
              $div->add( $c = new GraficoIndicadorMobilidade01($row[0]) );  
                                      
            }
            

        
        $local="UNIDADE BASICA DE SAUDE 13 DE SETEMBRO";
        */
        $div = new TElement('div');
        $div->add( $d = new BarraIndicadorMobilidade01(false) );
        //$div->add( $c = new GraficoIndicadorMobilidade01($local) );
        //$div->add( $d = new Vw40AtencaoMedicaList(false) );
        
        $panel1->add($div);
        

        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';

        // add the template to the page
        parent::add($vbox);
        
    }    
}
