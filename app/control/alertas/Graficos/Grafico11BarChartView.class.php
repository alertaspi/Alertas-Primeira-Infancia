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
class Grafico11BarChartView extends TPage
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
           
           $sql4="select ano, mes_desc, concat(mes_desc,'/',ano) mesano, 
               count(*) filter (where idade BETWEEN 0 and 5 ) idade_0_a_5_anos,
               count(*) filter (where idade BETWEEN 6 and 10 ) idade_6_a_10_anos,
	           count(*) filter (where idade BETWEEN 11 and 15 ) idade_11_a_15_anos,
	           count(*) filter (where idade BETWEEN 16 and 20 ) idade_16_a_20_anos,
	           count(*) filter (where idade>20 ) mais_20_anos
               from scperfil.vw_11_acompanhamento_pre_natal
               group by ano, mes_desc, idade";
               
            $sql4="
            select ano, mes_desc, concat(mes_desc,'/',ano) mesano, 
       count(*) filter (where idade BETWEEN 0 and 10 ) idade_0_a_10_anos,
       count(*) filter (where idade BETWEEN 11 and 15 ) idade_11_a_15_anos,
	   count(*) filter (where idade BETWEEN 16 and 20 ) idade_16_a_20_anos,
	   count(*) filter (where idade>20 ) mais_20_anos
from (
            SELECT a.pessoa_id,
    a.sistema_id,
    a.evento_id,
    a.tempo_id,
    d.ano,
    d.mes_desc,
    f.nome,
    f.data_nascimento,
    f.mae,
    f.cns,
    e.descricao,
    date_part('year'::text, age(CURRENT_DATE::timestamp with time zone, f.data_nascimento::timestamp with time zone))::integer AS idade,
    count(DISTINCT g.*) AS qtd
   FROM ( SELECT DISTINCT fatos.pessoa_id,
            fatos.sistema_id,
            fatos.evento_id,
            fatos.tempo_id,
            fatos.tipo_id,
            fatos.valor_dado,
            fatos.data_info
           FROM scperfil.fatos) a
     JOIN scperfil.fatos_dados_tipos b ON a.tipo_id = b.id
     JOIN scperfil.fatos_dados c ON c.pessoa_id = a.pessoa_id AND c.sistema_id = a.sistema_id AND c.evento_id = a.evento_id
     JOIN scperfil.tempo d ON d.datainfo = c.data_evento
     JOIN scperfil.eventos e ON e.sistema_id = a.sistema_id AND e.id = a.evento_id
     JOIN scperfil.pessoas f ON a.pessoa_id = f.id
     LEFT JOIN scperfil.pessoas_alertas g ON a.pessoa_id = g.pessoa_id AND a.evento_id = g.evento_id AND a.sistema_id = g.sistema_id
  WHERE a.sistema_id = 1 AND a.tipo_id = 9 AND a.valor_dado > '40'::text
  GROUP BY a.pessoa_id, a.sistema_id, a.evento_id, a.tempo_id, d.ano, d.mes_desc, f.nome, f.data_nascimento, f.mae, f.cns, e.descricao, (date_part('year'::text, age(CURRENT_DATE::timestamp with time zone, f.data_nascimento::timestamp with time zone))::integer)
  ORDER BY (count(g.*)) DESC, a.pessoa_id, a.sistema_id) tb1
  group by ano, mes_desc, idade;
";
           $sql4="select ano, mes_desc, concat(mes_desc,'/',ano) mesano, 
       count(*) filter (where idade BETWEEN 0 and 10 ) idade_0_a_10_anos,
       count(*) filter (where idade BETWEEN 11 and 15 ) idade_11_a_15_anos,
	   count(*) filter (where idade BETWEEN 16 and 20 ) idade_16_a_20_anos,
	   count(*) filter (where idade>20 ) mais_20_anos
from scperfil.vw_11_acompanhamento_pre_natal
group by ano, mes_desc, idade 
";    


           /*         
            $sql4="select * from crosstab(
                    'select d.ano, d.mes_desc, count(*)
                     from scperfil.fatos a
                    join scperfil.fatos_dados_tipos b 	on a.tipo_id=b.id
                    join scperfil.fatos_dados c on c.pessoa_id = a.pessoa_id and c.sistema_id=a.sistema_id and c.evento_id=a.evento_id
                    join scperfil.tempo d on d.datainfo=c.data_evento
                    group by d.ano, d.mes_desc
                    order by 1,2'
	                ) as ct (ano int, Janeiro bigint,Fevereiro bigint,Março bigint,Abril bigint,Maio bigint,Junho bigint,Julho bigint,Agosto bigint,Setembro bigint,Outubro bigint,Novembro bigint,Dezembro bigint )";
	                */        
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
            $data[] =['Mês','idade_0_a_10_anos','idade_11_a_15_anos','idade_16_a_20_anos','idade_maior_20_anos'];
            foreach($results4 as $row){
                          
                          $data[]=[$row[2],$row[3],$row[4],$row[5],$row[6]];                
                                          
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
        
        $panel = new TPanelGroup('A11 - Acompanhamento Pré natal');
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
