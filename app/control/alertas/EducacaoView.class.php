<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EducacaoView
 *
 * @author Luiz Fernandes
 */
class EducacaoView extends \Adianti\Control\TPage {

    //put your code here
    var $form = "";

    function __construct() {
        parent::__construct();
        parent::include_css('app/resources/myframe.css');

        $vbox = new TVBox;

        $html1 = new THtmlRenderer('app/resources/alertas/educacao.html');
        $html1->enableSection('main', array());
        
        $panel1 = new TPanelGroup('Alerta Educação');
        $panel1->add($html1);
        
        $tipo='A39 - Os pequenos da primeirissima infancia que não tomaram as vacinas';
        //$tipo='A40 - Atenção medica primerissima infancia';
        
        
        $link = new TElement('a');
        $link->__set('generator', 'adiante');
        $link->class = 'btn btn-success btn-lg';
        //$link->__set('href', 'index.php?class=AtencaoMedicaView');
        $link->__set('href', 'index.php?class=EducacaoView&method=msnAlerta');
        $link->add($tipo);
        
        $hbox1 = new THBox;
        //$hbox1->addRowSet($link);
        
        $panel1->add($hbox1);
        
        $link = new TElement('a');
        $link->__set('generator', 'adiante');
        $link->class = 'btn btn-success btn-lg';
        //$link->__set('href', 'index.php?class=ProtecaoCriancaView');
        $link->__set('href', 'index.php?class=EducacaoView&method=msnAlerta');
        $link->add(' A15 - Crianças que retornam aos hospitais pelos mesmos motivos');
        
        //$hbox1->addRowSet($link);
        
        //new TMessage('Info','Procedimento não implementado! Acesso a informação não consedida no projeto');
        
        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';

        // add the template to the page
        parent::add($vbox);
    }
    
    public function msnAlerta(){
       
       new TMessage('Info','Procedimento não implementado! Acesso a informação não consedida no projeto');                   
       
    }

}
