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
class PieChartView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct( $show_breadcrumb = true )
    {
        parent::__construct();
        
        $html = new THtmlRenderer('app/resources/google_pie_chart.html');
        $data = array();
        $data[] = [ 'Pessoa', 'Value' ];
        $data[] = [ '10 e 13 anos',   30 ];
        $data[] = [ '14 e 18 anos',   70 ];
        
        # PS: If you use values from database ($row['total'), 
        # cast to float. Ex: (float) $row['total']
        
        $panel = new TPanelGroup('');
        $panel->style = 'width: 100%';
        $panel->add($html);
        
        // replace the main section variables
        $html->enableSection('main', array('data'   => json_encode($data),
                                           'width'  => '100%',
                                           'height'  => '300px',
                                           'title'  => '',
                                           'ytitle' => '', 
                                           'xtitle' => '',
                                           'uniqid' => uniqid()));
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        if ($show_breadcrumb)
        {
            $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($panel);
        parent::add($container);
    }
}
