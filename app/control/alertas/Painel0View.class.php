<?php
/**
 * Chart
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Painel0View extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
     
    var $form = "";
      
      
    function __construct()
    {
        parent::__construct();
        
        $html1 = new THtmlRenderer('app/resources/alertas/painel0.html');
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        
        $div = new TElement('div');
        $div->add( $a = new OrigemDadosDatagridProgressView());
        $div->add( $b = new CoberturaBarChartView(false) );
        //$div->add( $c = new GravidasBarChartView(false) );
        //$div->add( $f = new ImcBarChartView(false) );
        //$div->add( $h = new VulnerabilidadeDadosPieChartView(false) );
        //$div->add( $d = new PieChartView(false) );
        
        //$a->class = 'col-sm-6';
        //$b->class = 'col-sm-6';
        //$c->class = 'col-sm-12';
        //$d->class = 'col-sm-6';
        
        $panel1 = new TPanelGroup('Olá, seja bem vindo');
        $panel1->add($html1);
        $panel1->add($div);

        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';

        // add the template to the page
        parent::add($vbox);
    }
}
