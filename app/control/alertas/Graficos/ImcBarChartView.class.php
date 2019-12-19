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
class ImcBarChartView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct( $show_breadcrumb = true )
    {
        parent::__construct();
        
        $html = new THtmlRenderer('app/resources/google_bar_chart.html');
        $data = array();
        
        try{
               
           TTransaction::open('dbpmbv');
           $conn = TTransaction::get();
           
           $sql4="select d.ano,d.mes, concat(d.mes_desc,'/',d.ano), e.nome,  
                    count(*) total,
					count(*) filter (where extract(year from age(CURRENT_DATE, f.data_nascimento)) between 8 and 13) entre_8_e_13_anos,
					count(*) filter (where extract(year from age(CURRENT_DATE, f.data_nascimento)) between 14 and 16) entre_14_e_16_anos,
					count(*) filter (where extract(year from age(CURRENT_DATE, f.data_nascimento)) between 17 and 18) entre_17_e_18_anos
                    from ( SELECT DISTINCT fatos.pessoa_id,
            fatos.sistema_id,
            fatos.evento_id,
            fatos.tempo_id,
            fatos.tipo_id,
            fatos.valor_dado,
            fatos.data_info
           FROM scperfil.fatos) a
                    join scperfil.fatos_dados_tipos b on a.tipo_id=b.id
                    join scperfil.fatos_dados c on c.pessoa_id = a.pessoa_id and c.sistema_id=a.sistema_id and c.evento_id=a.evento_id
                    join scperfil.tempo d on d.datainfo=c.data_evento
					join scperfil.eventos e on e.sistema_id = a.sistema_id and e.id=a.evento_id
					join scperfil.pessoas f on a.pessoa_id=f.id
                    where a.tipo_id=9 and a.valor_dado<='40' and extract(year from age(CURRENT_DATE, f.data_nascimento))<=18
                    group by d.ano, d.mes,d.mes_desc, e.nome
                    order by 1,2";
            $sql4="select * from scperfil.vw_imc_grafico order by 2, 1";
             
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
            $ano=date('Y');
            $data[] =['Mês/Ano','a baixo do peso','peso adequado','sobrepeso'];
            //$data[] =['Mês','de 8 a 13 anos em '.$ano,'de 14 a 16 anos em '.$ano,'de 17 a 18 anos em '.$ano];
            foreach($results4 as $row){
                          
                          $data[]=[$row[2],$row[3],$row[4],$row[5]];                
                                          
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
        
        $panel = new TPanelGroup('IMC (Menores de 19 anos)');
        $panel->style = 'width: 100%';
        $panel->add($html);
        
        // replace the main section variables
        $html->enableSection('main', array('data'   => json_encode($data),
                                           'width'  => '100%',
                                           'height'  => '300px',
                                           'title'  => '',
                                           'ytitle' => 'Quntidade', 
                                           'xtitle' => '',
                                           'uniqid' => uniqid()));
        
        // add the template to the page
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
