<?php

require('APIPeople.php');

class APIAlerts extends AdiantiRecordService {
    const DATABASE = "dbpmbv";
    const ACTIVE_RECORD = "PessoasAlertas";
    
    /*
        Esta é um afunção provisória para simular um InnerJoin. COmo estava com pressa e não tinha
        no momento os conhecimentos necessários para fazer um innerjoin com o adianti eu fiz de forma
        iterativa (É eu sei, isso é muito mais lento e tudo mais...);
        ~ Fabio Vitor
    */
   function loadAllProv($param){
       $all = AdiantiRecordService::loadAll($param);
       for($i = 0; $i < sizeof($all); $i++){

           $param["id"] = $all[$i]['pessoa_id'];
           
           //$all[$i]['pessoa_id'] = (new APIPeople())->load($param);
           $all[$i] = (new APIPeople())->load($param);
           
       }
       return ($all);
   }

}
