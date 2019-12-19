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
class EvasaoFqaView extends \Adianti\Control\TPage {

    var $form = "";
    //private $datagrid;

    function __construct() {
        parent::__construct();

        $html1 = new THtmlRenderer('app/resources/alertas/evasaoFqa.html');
        $html1->enableSection('main', array());

        $panel1 = new TPanelGroup('Alerta');
        $panel1->add($html1);

        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';

        // add the template to the page
        parent::add($vbox);
    }

}
