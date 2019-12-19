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
class Painel01View extends TPage {

    //put your code here

    var $form = "";

    function __construct() {
        parent::__construct();


        $html1 = new THtmlRenderer('app/resources/alertas/painel01.html');

        try {
            TTransaction::open('dbpmbv');
            $conn = TTransaction::get();

            $sql = "select nome, qtd, (qtd*100)/qtdtotal::float8 qtd_porcento, qtdtotal 
                 from (select  c.nome, count(*) qtd, (select count(*)  from scperfil.pessoas) qtdtotal            
                 from scperfil.pessoas a
                 join scperfil.pessoas_sistemas b on a.id=b.pessoa_id
                 join scperfil.sistemas c on b.sistema_id=c.id
                 group by c.nome
             )tb1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll();
            $data = array();
            $total1 = 0;
            foreach ($results as $row) {
                //$data2[] = [ (float)$row[1]." - ".$row[0],  (float)$row[1]];
                $data[] = [$row[0], $row[1], number_format($row[2], 2), $row[3]];
                $total1 += $row[1];
            }
            $replaces['GrpSistemas'] = $data;
            $replaces['total1'] = $total1;

            $sql2 = "select sum(cont_nome) qtd_name, (sum(cont_nome)*100)/count(*)::float8  qtde_name,
                    sum(cont_dtnas) qtd_dtnas, 100-(sum(cont_dtnas)*100)/count(*)::float8 qtde_dtnas,
                    sum(cont_cpf) qtd_cpf, 100-(sum(cont_cpf)*100)/count(*)::float8 qtde_cpf,
                    sum(cont_cns) qtd_cns, 100-(sum(cont_cns)*100)/count(*)::float8 qtde_cns,
                    sum(cont_mother) qtd_mae, 100-(sum(cont_mother)*100)/count(*)::float8 qtde_mae,
                    sum(cont_father) qtd_pai, 100-(sum(cont_father)*100)/count(*)::float8 qtde_pai,
                    sum(cont_fone)  qtd_fone, 100-(sum(cont_fone)*100)/count(*)::float8 qtde_fone,
                    count(*) total
                   from (
                    select  case when nome is null then 0 else 1 end cont_nome, 
                    case when data_nascimento is null then 0 else 1 end cont_dtnas,
                    case when cpf is null then 0 else 1 end cont_cpf,
                    case when cns is null then 0 else 1 end cont_cns,
                    case when mae is null then 0 else 1 end cont_mother,
                    case when pai is null then 0 else 1 end cont_father,
                    case when fone is null then 0 else 1 end cont_fone
                    from scperfil.pessoas
            ) tmest";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->execute();
            //$results2 = $stmt2->fetchAll();
            $results2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $qtdlinha=$stmt2->rowCount();
            
            //var_dump($results2);
            $data2 = array();
            if($results2['qtd_name']>0){
             $resta_nome = $results2['qtd_name'] - ($results2['qtde_name'] / ($results2['qtde_name']) * $results2['qtd_name']);
            $data2[] = [$results2['qtd_name'], $results2['qtde_name'],
                $resta_nome, $results2['qtd_dtnas'],
                number_format(100 - $results2['qtde_dtnas'], 4, ',', ','),
                number_format(100 * $results2['qtde_dtnas'], 3, ',', ','),
                $results2['qtd_mae'], number_format(100 - $results2['qtde_mae'], 2, ',', ','), number_format($results2['qtde_mae'], 2, ',', ','),
                $results2['qtd_pai'], number_format(100 - $results2['qtde_pai'], 2, ',', ','), number_format($results2['qtde_pai'], 2, ',', ','),
                $results2['qtd_cpf'], number_format(100 - $results2['qtde_cpf'], 2, ',', ','), number_format($results2['qtde_cpf'], 2, ',', ','),
                $results2['qtd_cns'], number_format(100 - $results2['qtde_cns'], 2, ',', ','), number_format($results2['qtde_cns'], 2, ',', ','),
                $results2['qtd_fone'], number_format(100 - $results2['qtde_fone'], 2, ',', ','), number_format($results2['qtde_fone'], 2, ',', ',')];
                }
            ////,$results2['cont_dtnas'],$results2['cont_cpf'],$results2['cont_cns'],$results2['cont_mother'],$results2['cont_father'],$results2['cont_pis'],$results2['cont_rg']];
            //var_dump($data2);            
            //$replaces['resta_nome'] = $resta_nome;
            $replaces['GrpPerfil'] = $data2; //$results2;

            $sql3 = "select id, tipo, qtd, qtdtipo, ceil(estatistica*100) escala, total
                from (
                select 1 id, tipo, total, 7 qtdtipo , ((v1+v2+v3+v4+v5+v6+v7)) qtd, (((v1+v2+v3+v4+v5+v6+v7))/total::float)::float estatistica from (
                select 'Principal' tipo,
                    (count(*) filter(where nome is not null))/count(*)::float v1, 
                    (count(*) filter(where data_nascimento is not null))/count(*)::float v2,
                    (count(*) filter(where mae is not null))/count(*)::float v3,
                    (count(*) filter(where cpf is not null))/count(*)::float v4,
                    (count(*) filter(where rg is not null))/count(*)::float v5,
                    (count(*) filter(where pis is not null))/count(*)::float v6,
                    (count(*) filter(where cns is not null))/count(*)::float v7,
                    7 total
                    from scperfil.pessoas p
                        group by tipo
                ) tb1
                union
                select 2 id, tipo, total, 2 qtdtipo, ((v1+v2)) qtd, (((v1+v2))/total::float)::float estatistica from (
                  select 'Contatos' tipo,
                  (count(*) filter(where fone is not null))/count(*)::float v1, 
                  (count(*) filter(where email is not null))/count(*)::float v2,
                  2  total
                from scperfil.pessoas p
                    group by tipo
                ) tb2
                union
                select 3 id, tipo, total, 7 qtdtipo, ((v1+v2+v3+v4+v5+v6+v7)) qtd, (((v1+v2+v3+v4+v5+v6+v7))/total::float)::float estatistica from (
                select 'Endereço' tipo,
                  (count(*) filter(where endereco is not null))/count(*)::float v1, 
                  (count(*) filter(where numero_endereco is not null))/count(*)::float v2,
                  (count(*) filter(where cep is not null))/count(*)::float v3,
                  (count(*) filter(where referencia_endereco is not null))/count(*)::float v4,
                  (count(*) filter(where bairro is not null))/count(*)::float v5,
                  (count(*) filter(where cidade is not null))/count(*)::float v6,
                  (count(*) filter(where uf is not null))/count(*)::float v7, 7 total
                from scperfil.pessoas p
                    group by tipo
                ) tb3
            ) tb26 order by 1";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->execute();
            $results3 = $stmt3->fetchAll();
            //$results3 = $stmt3->fetch(PDO::FETCH_ASSOC);
            $replaces['GrpCadastro'] = $results3;
            
            
            //var_dump($results4);

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());

            TTransaction::rollback();
        }

        //$html1->enableSection('main', array());

        $div = new TElement('div');
        $html1->enableSection('main', $replaces);
        $html1->enableTranslation();

        $div->add($c = new CoberturaBarChartView(false));
        $div->add($d = new GravidasBarChartView(false));
        $div->add($e = new OrigemDadosPieChartView(false));
        $panel1 = new TPanelGroup('Olá, seja bem vindo');
        $panel1->add($html1);
        $panel1->add($div);

        $vbox = TVBox::pack($panel1);
        $vbox->style = 'display:block; width: 90%';

        // add the template to the page
        parent::add($vbox);
    }

}
