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
class A40NaoForamMedicoView extends \Adianti\Control\TPage {

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

        $panel1 = new TPanelGroup('A40 - Cunhantãs e Curumins novinhos que não foram ao médico');        

        $div = new TElement('div');
        $div->add( $c = new Grafico40BarChartView(false) );
        $div->add( $d = new Vw40AtencaoMedicaList(false) );
        
        $panel1->add($div);
        

        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';

        // add the template to the page
        parent::add($vbox);
        
    }    
}
