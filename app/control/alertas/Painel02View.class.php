<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Painel02View
 *
 * @author Luiz Fernandes
 */
class Painel02View extends \Adianti\Control\TPage {

    //put your code here
    var $form = "";

    function __construct() {
        parent::__construct($param = NULL);


        $html1 = new THtmlRenderer('app/resources/alertas/Painel02.html');
        //$html1->enableSection('main', array());
        //$html2->enableSection('main', array());
        //$html3->enableSection('main', array());

        try {

            TTransaction::open('dbpmbv');

            $key = $_GET['pessoa_id'];
            $pessoa = new Pessoas($key);
            $replaces = $pessoa->toArray();
            $replaces['nome'] = $pessoa->nome;
            $replaces['cns'] = $pessoa->cns;
            $replaces['cpf'] = $pessoa->cpf;
            $replaces['fone'] = $pessoa->fone;
            //$replaces['cep'] = $pessoa->cep;
            $replaces['endereco'] = $pessoa->cep . ' ' . $pessoa->endereco . ' ' . $pessoa->numero_endereco . ' ' . $pessoa->bairro;
            //$replaces['numero_endereco'] = $pessoa->numero_endereco;
            //echo $key;
            $conn = TTransaction::get();

            $sql = "select id, tipo, qtd, qtdtipo, ceil(estatistica*100) escala, total
                from (
                select 1 id, tipo, total, 7 qtdtipo , ((v1+v2+v3+v4+v5+v6+v7)) qtd, (((v1+v2+v3+v4+v5+v6+v7))/total::float)::float estatistica from (
                select 'Principal' tipo,
                    sum(case when nome is not null then 1 else 0 end) v1, 
                    sum(case when data_nascimento is not null then 1 else 0 end) v2,
                    sum(case when mae is not null then 1 else 0 end) v3,
                    sum(case when cpf is not null then 1 else 0 end) v4,
                    sum(case when rg is not null then 1 else 0 end) v5,
                    sum(case when pis is not null then 1 else 0 end) v6,
                    sum(case when cns is not null then 1 else 0 end) v7,
                    7 total
                    from scperfil.pessoas p
                    where id=$key
                        group by tipo
                ) tb1
                union
                select 2 id, tipo, total, 2 qtdtipo, ((v1+v2)) qtd, (((v1+v2))/total::float)::float estatistica from (
                  select 'Contatos' tipo,
                  sum(case when fone is not null then 1 else 0 end) v1, 
                  sum(case when email is not null then 1 else 0 end) v2,
                  2  total
                from scperfil.pessoas p
                where id=$key
                    group by tipo
                ) tb2
                union
                select 3 id, tipo, total, 7 qtdtipo, ((v1+v2+v3+v4+v5+v6+v7)) qtd, (((v1+v2+v3+v4+v5+v6+v7))/total::float)::float estatistica from (
                select 'Endereço' tipo,
                  sum(case when endereco is not null then 1 else 0 end) v1, 
                  sum(case when numero_endereco is not null then 1 else 0 end) v2,
                  sum(case when cep is not null then 1 else 0 end) v3,
                  sum(case when referencia_endereco is not null then 1 else 0 end) v4,
                  sum(case when bairro is not null then 1 else 0 end) v5,
                  sum(case when cidade is not null then 1 else 0 end) v6,
                  sum(case when uf is not null then 1 else 0 end) v7, 7 total
                from scperfil.pessoas p
                where id=$key
                    group by tipo
                ) tb3
            ) tb26 order by 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $results2 = $stmt->fetchAll();
            //$conn->close();
            $data2 = array();
            $tipo = array();
            $tipo = ['bg-primary', 'bg-danger', 'bg-success'];
            //$data2[] = [ 'tipo', 'qtd','escala' ];
            $x = 0;
            foreach ($results2 as $row) {
                //$data2[] = [ (float)$row[1]." - ".$row[0],  (float)$row[1]];
                $data2[] = [$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $tipo[$x]];
                $x++;
            }

            /*
              $html2='';
              $html2->enableSection('GrfUser', array('data'   => json_encode($data2),
              'width'  => '100%',
              'height'  => '400px',
              'code' => 'grap3',
              'title'  => 'Atendimentos Concluídos por Usuário',
              'ytitle' => 'Atendimentos',

              'xtitle' => 'Dia'));
             */
            $replaces['GrfUser'] = $data2;
            $sqls="select a.nome pessoa, c.nome sistema, a.cns, 
                          b.id_origem_pessoa, b.data_cadastro_sistema dt_cad, b.data_ultima_atualizacao dt_alt 
                from scperfil.pessoas a
                join scperfil.pessoas_sistemas b on a.id=b.pessoa_id
                join scperfil.sistemas c on b.sistema_id=c.id
                where a.nome ilike (select concat(nome,'%') from scperfil.pessoas where id=$key)
                order by a.nome";
            $sqls="select a.nome pessoa, c.nome sistema, a.cns, 
                          b.id_origem_pessoa, b.data_cadastro_sistema dt_cad, b.data_ultima_atualizacao dt_alt 
                from scperfil.pessoas a
                join scperfil.pessoas_sistemas b on a.id=b.pessoa_id
                join scperfil.sistemas c on b.sistema_id=c.id
                where a.nome = (select d.nome from scperfil.pessoas d where d.id=$key and d.data_nascimento=a.data_nascimento)
                order by a.nome";
            $stmt3 = $conn->prepare($sqls);
            $stmt3->execute();
            $results3 = $stmt3->fetchAll();
            $data3 = array();
            foreach ($results3 as $row3) {
                //$data2[] = [ (float)$row[1]." - ".$row[0],  (float)$row[1]];
                $data3[] = [$row3[0], $row3[1], $row3[2], $row3[3],  TDate::date2br($row3[4]),  TDate::date2br($row3[5])];                
            }
            $replaces['GrfSistema'] = $data3;
            //$replaces
            //var_dump($data3);
            TTransaction::close();

            //$this->loaded = true;
        } catch (Exception $e) { // in case of exception
            // shows the exception error message
            new TMessage('error', $e->getMessage());

            // undo all pending operations
            TTransaction::rollback();
        }

        $div = new TElement('div');  
               
        //$div->add( $a = new OrigemDadosPessoaPieChartView(false) );
        $a = new OrigemDadosPessoaPieChartView(false);
        
        
        $actionLink = new TAction( ['PessoasAlertasForm', 'onEdit' ] );
        //$actionLink->setParameter('key', $key);
        $actionLink->setParameter('pessoa_id',$_GET['pessoa_id']);
        $actionLink->setParameter('sistema_id',$_GET['sistema_id']);
        $actionLink->setParameter('evento_id',$_GET['evento_id']);
        $actionLink->setParameter('tipo',$_GET['tipo']);
        
        if(!$_GET['sistema_id']==0){
           $actionLink = new TAction( ['PessoasAlertasForm', 'onEdit' ] );
        //$actionLink->setParameter('key', $key);
        $actionLink->setParameter('pessoa_id',$_GET['pessoa_id']);
        $actionLink->setParameter('sistema_id',$_GET['sistema_id']);
        $actionLink->setParameter('evento_id',$_GET['evento_id']);
        $actionLink->setParameter('tipo',$_GET['tipo']);                            
                                   
           $b2 = new TActionLink('Criar Novo Alerta', $actionLink, 'white', 10, '', 'fa:check-square-o #FEFF00');
           $b2->class='btn btn-success';
         }else{
           $actionLink = new TAction( ['PessoasAlertasFormNew', 'onEdit' ] );
        //$actionLink->setParameter('key', $key);
        $actionLink->setParameter('pessoa_id',$_GET['pessoa_id']);
        $actionLink->setParameter('pessoas_alertas_id',0);
        $actionLink->setParameter('sistema_id',$_GET['sistema_id']);
        $actionLink->setParameter('evento_id',$_GET['evento_id']);
        $actionLink->setParameter('tipo',$_GET['tipo']);       
         $b2 = new TActionLink('Criar Novo Alerta', $actionLink, 'white', 10, '', 'fa:check-square-o #FEFF00');
           $b2->class='btn btn-success';
           //$b2='';
         }
        //$div->add($b2);
        
        
        $replaces['grafpessoa'] =$a;
        $replaces['btngrisco']=$b2;
        $html1->enableSection('main', $replaces);
        $html1->enableTranslation();
        
        //$g=new GravidaGestaoAtividadeView(false);
        $g=new PessoaAlertaBootstrapView();
        $div->add($g);
        //$html2=new LineChartView(false);
        $panel1 = new TPanelGroup('Perfil!');
        //$panel1->add($b2);
        $panel1->add($html1);
        $panel1->add($div);
        //$panel2 = new TPanelGroup('Bem-vindo!');
        //$panel2->add($html2);
        //$panel3 = new TPanelGroup('Bienvenido!');
        //$panel3->add($html3);
        //$vbox = TVBox::pack($panel1, $panel2, $panel3);
        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';

        // add the template to the page
        parent::add($vbox);
    }

    public function onRetorno($param = NULL) {
        $key =$param['pessoa_id'];//NULL;
       
        $this->id = $key;
        $this->pessoas_alertas_id=$param['pessoas_alertas_id'];
        $this->pessoa_id=$param['pessoa_id'];
        $this->sistema_id=$param['sistema_id'];
        $this->evento_id=$param['evento_id'];
         $this->tipo=$param['tipo'];
        $this->loaded = true;
    }

    public function onReload($param = NULL) {
        $key = $param['key'];
        $this->id = $key;
        $this->loaded = true;
        /*
          try
          {

          TTransaction::open('dbpmbv');

          $key = $param['key'];
          $pessoa= new Pessoas($key);
          $replaces = $pessoa->toArray();
          $replaces['nome'] = $pessoa->nome;
          $replaces['cns'] = $pessoa->cns;

          TTransaction::close();

          $this->loaded = true;
          }
          catch (Exception $e) // in case of exception
          {
          // shows the exception error message
          new TMessage('error', $e->getMessage());

          // undo all pending operations
          TTransaction::rollback();
          }
         */
    }
    
    public function monitorarInserir($param){
         try {
            //var_dump($param);
            TSession::regenerate();
            
            $id_usuario=TSession::getValue('userid');
            $data_atual = new TDateTime('created');
            $data_atual->setValue(date('Y-m-d H:i'));
             $closeAction='';
             new TMessage('info', "{$count} clientes foram importados!", $closeAction);
        } catch (Exception $e) { // in case of exception
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }                                          
    }

}
