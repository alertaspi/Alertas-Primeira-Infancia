<?php

class envioSms{

 

function __construct($param){
                                
                                
  var_dump($param);                          
                            
                                
  $curl = curl_init();                          

  curl_setopt_array($curl, [
  CURLOPT_URL => "http://api.optjuntos.com.br/mt",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "[
    {
      \"numero\": \"5595991288207\",
      \"servico\": \"short\",
      \"mensagem\": \"Texto da mensagem\",
      \"parceiro_id\": \"5034e65a0c\",
      \"codificacao\": \"0\"
    }
  ]",
  CURLOPT_HTTPHEADER => [
    "authorization: Bearer 91f3eab84edf0b7c3cc24c6246993ee99f28ea5f",
    "content-type: application/json"
  ],
]);

//$response = curl_exec($curl);
$response='';
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
          
}
}//fim contrutor

public function show(){
 
   new TMessage('error','Mensagem Enviada com Sucesso!');                            

}



}