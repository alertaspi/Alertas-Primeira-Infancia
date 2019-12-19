<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Painel01View
 *
 * @author Luiz Fernandes
 */
class MapaCurumimView extends \Adianti\Control\TPage {
    //put your code here
    
    var $form="";
    
    function __construct()
    {
        parent::__construct();
     
        
        $html1 = new THtmlRenderer('app/resources/alertas/mapaCurumim.html');
        $html1->enableSection('main', array());
        
        $div = new TElement('div');
        $div->add( $c = new PieChartView(false) );
       
        $panel1 = new TPanelGroup('Olá, seja bem vindo');
        $panel1->add($html1);
        $panel1->add($div);
        
        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';
        
        // add the template to the page
        parent::add( $vbox );
        
    }
    
    
    
}
