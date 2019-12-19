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
class AcompPreNatalView_old extends \Adianti\Control\TPage {

    var $form = "";
    //private $datagrid;

    function __construct() {
        parent::__construct();

        $html1 = new THtmlRenderer('app/resources/alertas/acompPreNatal.html');
        $html1->enableSection('main', array());

        $panel1 = new TPanelGroup('A11 - Acompanhamento Pré natal');
        $div = new TElement('div');
        $div->add( $c = new Grafico11BarChartView(false) );
               
        $panel1->add($this->html);
        $panel1->add($html1);
        $panel1->add($div);
        

        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';

        // add the template to the page
        parent::add($vbox);
    }

}
