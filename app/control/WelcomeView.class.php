<?php
/**
 * WelcomeView
 *
 * @version    1.0
 * @package    control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class WelcomeView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        $html1 = new THtmlRenderer('app/resources/system_welcome_alertas.html');
        $html2 = new THtmlRenderer('app/resources/system_welcome_pt.html');
        $html3 = new THtmlRenderer('app/resources/system_welcome_es.html');
        
        
        




        // replace the main section variables
        $html1->enableSection('main', array());
        //$html2->enableSection('main', array());
        //$html3->enableSection('main', array());
        
        $panel1 = new TPanelGroup('Bem Vindos!');
        $panel1->add($html1);
        
        //$panel2 = new TPanelGroup('Bem-vindo!');
        //$panel2->add($html2);
		
        //$panel3 = new TPanelGroup('Bienvenido!');
        //$panel3->add($html3);
        
        //$vbox = TVBox::pack($panel1, $panel2, $panel3);
        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';
        
        // add the template to the page
        parent::add( $vbox );
    }
    
    public function atualizaAlertas(){
       try{
        TTransaction::open('dbpmbv');
        $conn=TTransaction::get();
        $sql="select count(*) qtd from scperfil.vw_alertas_automaticos_unico";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch();
        if($results['qtd']>0){
          $sqlu="insert into scperfil.pessoas_alertas
                       (pessoa_id,sistema_id, evento_id, usuario_id, observacao, status, tipo, data_info)
                 select pessoa_id, sistema_id, evento_id, usuario_id, observacao, status, tipo, data_info 
                 from scperfil.vw_alertas_automaticos_unico";
          $stmtu = $conn->prepare($sqlu);
          $stmtu->execute();                       
        }
        
        TTransaction::close();
        } catch (Exception $e) {
          echo $e->getMessage();
        }                                      
    }
}
