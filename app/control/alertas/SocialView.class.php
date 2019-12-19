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
class SocialView extends \Adianti\Control\TPage {

    //put your code here
    var $form = "";

    function __construct() {
        parent::__construct();
        parent::include_css('app/resources/myframe.css');

        $vbox = new TVBox;

        $html1 = new THtmlRenderer('app/resources/alertas/social.html');
        $html1->enableSection('main', array());
        
        $panel1 = new TPanelGroup('Alertas Social');
        $panel1->add($html1);
        
        
        $link = new TElement('a');
        $link->__set('generator', 'adiante');
        $link->class = 'btn btn-success btn-lg';
        $link->__set('href', 'index.php?class=AcompPreNatalView');
        $link->add('A11 - Mãezinhas em falta com as consultas de pré natal');
        
        $hbox1 = new THBox;
        $hbox1->addRowSet($link);
        
        $panel1->add($hbox1);
		
		$link = new TElement('a');
        $link->__set('generator', 'adiante');
        $link->class = 'btn btn-success btn-lg';
        $link->__set('href', 'index.php?class=GestanteForaFqaView');
        $link->add('A15 - Crianças que retornam aos hospitais pelos mesmos motivos.');
        
        $hbox1->addRowSet($link);
        
        $link = new TElement('a');
        $link->__set('generator', 'adiante');
        $link->class = 'btn btn-success btn-lg';
        $link->__set('href', 'index.php?class=GestanteForaFqaView');
        $link->add('A23 - Jovens mãezinhas que não frequentam o UBB');
        
        $hbox1->addRowSet($link);
        
        $link = new TElement('a');
        $link->__set('generator', 'adiante');
        $link->class = 'btn btn-success btn-lg';
        $link->__set('href', 'index.php?class=BaixaRendaForaFqaView');
        $link->add(' A37 - Mãezinhas gravidas de baixa renda que não frequentam o UBB');
        
        $hbox1->addRowSet($link);
       
        
        $link = new TElement('a');
        $link->__set('generator', 'adiante');
        $link->class = 'btn btn-success btn-lg';
        $link->__set('href', 'index.php?class=AtencaoMedicaView');
        $link->add('A40 - Atenção medica primerissima infancia');
        
        $hbox1->addRowSet($link);
        
        
        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';

        // add the template to the page
        parent::add($vbox);
    }

}
