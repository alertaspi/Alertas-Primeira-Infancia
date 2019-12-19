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
class GraficoIndicadorMobilidade01 extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct( $show_breadcrumb = true )
    {
        parent::__construct();
        
        //var_dump($show_breadcrumb);
        //$html = new THtmlRenderer('app/resources/google_bar_chart.html');
        $html = new THtmlRenderer('app/resources/google_bar_chart.html');
        $data = array();
        
        try{
               
           TTransaction::open('dbpmbv');
           $conn = TTransaction::get();
           
           
           $sql4="select * from (
select * from crosstab(
                    'select a.valor_dado,  d.mes_desc, count(*)
                     from scperfil.fatos a                    
                    join scperfil.fatos_dados c on c.pessoa_id = a.pessoa_id and c.sistema_id=a.sistema_id and c.evento_id=a.evento_id
                    join scperfil.tempo d on d.datainfo=c.data_evento
	                where d.ano=extract(year from current_date) and a.tipo_id=10 and a.valor_dado!=''''
                    group by a.valor_dado, d.mes_desc
                    order by 1,2'
	                ) as ct (unidade text, Janeiro bigint,Fevereiro bigint,Março bigint,Abril bigint,Maio bigint,Junho bigint,Julho bigint,Agosto bigint,Setembro bigint,Outubro bigint,Novembro bigint,Dezembro bigint )
	)tb1
					--where tb1.unidade = '$show_breadcrumb'
					";    


                 
            $stmt4 = $conn->prepare($sql4);
            $stmt4->execute();
            $results4 = $stmt4->fetchAll();
            //var_dump($results4);
            /*
            $data[] =[ 'Ano', 'Janeiro', 'Fevereiro', 'Março', 'Abtil', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro' ];
            foreach($results4 as $row){
                         $data[]=[$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12]];
            }
            */
            //$data[] =['Mês','Quantidade'];
            $data[] =[ 'Local', 'Janeiro', 'Fevereiro', 'Março', 'Abtil', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro' ];
            foreach($results4 as $row){
                          
                          //$data[]=[$row[2],$row[3],$row[4],$row[5],$row[6]];
                          $data[]=[$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12]];                
                                          
                                      }
            
            
           TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());

            TTransaction::rollback();
        }
        
        
        
        
        /*
        $data[] = [ 'Day', 'Value 1', 'Value 2', 'Value 3' ];
        $data[] = [ 'Day 1',   100,       120,       140 ];
        $data[] = [ 'Day 2',   120,       140,       160 ];
        $data[] = [ 'Day 3',   140,       160,       180 ];
        */
        # PS: If you use values from database ($row['total'), 
        # cast to float. Ex: (float) $row['total']
        
        $panel = new TPanelGroup('Mobilidade - Local de Atendimento');
        $panel->style = 'width: 100%';
        $panel->add($html);
        
        // replace the main section variables
        $html->enableSection('main', array('data'   => json_encode($data),
                                           'width'  => '100%',
                                           'height'  => '300px',
                                           'title'  => 'Accesses by day',
                                           'ytitle' => 'Accesses', 
                                           'xtitle' => 'Day',
                                           'uniqid' => uniqid()));
        
        // add the template to the page
        $container = new TVBox;
        $container->style = 'width: 100%';
        /*
        if ($show_breadcrumb)
        {
            $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        */
        $container->add($panel);
        parent::add($container);
    }
}
