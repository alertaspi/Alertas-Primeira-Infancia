<?php
/**
 * SystemNotificationForm Form
 * @author  <your name here>
 */
class SistemaNotificacaoAlertasForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        
        
        TTransaction::open('dbpmbv');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_SystemNotification');
        $this->form->setFormTitle('SystemNotification');
        
        //TSession::regenerate();
        $id_usuario=TSession::getValue('userid');
        $nome_usuario=TSession::getValue('username');
        $data_atual = new TDateTime('created');
        $data_atual->setValue(date('Y-m-d H:i:s'));

        // create the form fields
        $id = new TEntry('id');
        $system_user_id = new TEntry('system_user_id');//new TDBUniqueSearch('system_user_id', 'communication', 'SystemUser', 'name', $id_usuario);
        $system_user_id->setValue($id_usuario);
        //new TDBUniqueSearch('system_user_to_id', 'communication', 'SystemUser', 'id', 'name');
        $system_user_to_id =new TDBMultiSearch('system_user_to_id', 'permission', 'SystemUser', 'id', 'name'); 
        //new TDBUniqueSearch('system_user_to_id', 'communication', 'SystemUser', 'id', 'name');// new TEntry('system_user_to_id');
        $subject = new TEntry('subject');
        $message = new TText('message');
        
        $dt_message = new TEntry('dt_message');
        $dt_message =$data_atual;
        $action_url = new TEntry('action_url');
        $action_url->setValue('class=SystemDocumentForm&method=onView');
        $action_label = new TEntry('action_label');
        $action_label->setValue('Alerta');
        $icon = new TEntry('icon');
        $icon->setValue('fa fa-pencil-square-o blue');
        //$pessoa_id = new TEntry('pessoa_id');
        //$pessoas_alertas_id = new TEntry('pessoas_alertas_id');
        //$checked = new TEntry('checked');
        /*
        if(!isset($_GET['pessoas_alertas_id'])){
          $pessoas_alertas_id->setValue($_GET['pessoas_alertas_id']);                                          
        }else{
           $pessoas_alertas_id->setValue($param['pessoas_alertas_id']);      
             }
        if(!isset($_GET['pessoa_id'])){
          $pessoa_id->setValue($_GET['pessoa_id']);                                          
        }else{
           $pessoa_id->setValue($param['pessoa_id']);      
             }
             */
        $pessoas_alertas_id=$param['pessoas_alertas_id'];     
        $pessoa_id=$param['pessoa_id'];       
        // TSession::setValue('pessoa_id',$pessoa_id);
        // TSession::setValue('pessoas_alertas_id',$pessoas_alertas_id);    
        $alerta = new PessoasAlertas($pessoas_alertas_id);
        $pessoa = new Pessoas($pessoa_id);
        
         //exit;           
        //TTransaction::close();
        $mensagem='';
        $mensagem='Notificação de Alerta'."\n";
        $mensagem.='Alerta: '.$alerta->tipo."\n";
        $mensagem.='Situação: '.$alerta->observacao."\n";
        $mensagem.='Pessoa: '.$pessoa->nome."\n";
        $mensagem.='CNS: '.$pessoa->cns."\n";
        $mensagem.='Mãe: '.$pessoa->mae."\n";
        $mensagem.='Contato: '.$pessoa->fone.' '.$pessoa->endereco.' '.$pessoa->numero_endereco.' '.$pessoa->bairro.' '.$pessoa->cidade.' '.$pessoa->cep."\n";
        //$mensagem.='Observação: '."\n";
        $message->setValue($mensagem);

        // add the fields
         //$this->form->setFields(array('pessoa_id',$pessoa_id));
         //$table_itens->setFields(array('pessoas_alertas_id',$pessoas_alertas_id));
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Usuário Origem') ], [ $system_user_id ],[$nome_usuario] );
        $this->form->addFields( [ new TLabel('Usuário(s) destino') ], [ $system_user_to_id ] );
        $this->form->addFields( [ new TLabel('Assunto') ], [ $subject ] );
        $this->form->addFields( [ new TLabel('Mensagem') ], [ $message ] );
        $this->form->addFields( [ new TLabel('Dt Message') ], [ $dt_message ] );
        
        $this->form->addFields( [ new TLabel('Action Url') ], [ $action_url ] );
        $this->form->addFields( [ new TLabel('Action Label') ], [ $action_label ] );
        $this->form->addFields( [ new TLabel('Icon') ], [ $icon ] );
        //$this->form->addFields( [ new TLabel('Checked') ], [ $checked ] );
        
        //$this->form-> hideField($action_url);
        //$action_url->
        //$param['pessoas_alertas_id']
        //$this->form->hideField('pessoas_alertas_id',$id_alerta);

        // $this->form->addField('pessoa_id',$pessoa_id);
        // $this->form->addField('pessoas_alertas_id',$pessoas_alertas_id);
        
        

        // set sizes
        $id->setSize('100%');
        $system_user_id->setSize('100%');
        $system_user_to_id->setSize('100%');
        $subject->setSize('100%');
        $message->setSize('100%');
        $dt_message->setSize('100%');
        $action_url->setSize('100%');
        $action_label->setSize('100%');
        $icon->setSize('100%');
        //$checked->setSize('100%');



        if (!empty($id))
        {
            $id->setEditable(FALSE);
            $system_user_id->setEditable(FALSE);
            $dt_message->setEditable(FALSE);
            $action_url->setEditable(FALSE);
            $action_label->setEditable(FALSE);
            $icon->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
        
        $actionLink = new TAction( [$this, 'onSave' ] );
        //$actionLink = new TAction( [$this, 'onClose' ] );
        //$actionLink->setParameter('key', $key);
        $actionLink->setParameter('pessoa_id',$pessoa_id);
        $actionLink->setParameter('pessoas_alertas_id',$pessoas_alertas_id);
        
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), $actionLink, 'fa:floppy-o');
        //$btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('permission'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            $data->pessoas_alertas_id=TSession::getValue('pessoas_alertas_id');
            $data->pessoa_id=TSession::getValue('pessoa_id');
            
            
            
            
            $object = new SystemNotificationAlert;  // create an empty object
            
            $object->fromArray( (array) $data); // load the object with data
            //$object->store(); // save the object
            //var_dump($data);
            $title=$data->subject;
            $message=$data->message;
            $label=$data->action_label;
            $action=$data->action_url;
            $icon=$data->icon;
            if ($data->system_user_to_id)
            {
                foreach ($data->system_user_to_id as $system_user_to_id)
                {
                    $user=new SystemUser($system_user_to_id);
                    $phone=$user->phone;
                    if($phone){
                      $this->envioMensagem($phone,$message,$param);
                    }
                 
                    //print_r($system_user_to_id.' '.$title.' '.$message.' '.$action.' '.$label.' '.$icon.'<br>');
                    SystemNotification::register( $system_user_to_id, $title, $message, $action, $label, $icon );
                    //TTransaction::open('permission');
                    //$system_user = SystemUser::find($user_id);
                    //TTransaction::close();
                    //$object->addSystemUser( $system_user );
                }
            }
            //$pessoas_alertas_id=null;
            //$pessoa_id=null;
            
            // get the generated id
            //$data->id = $object->id;
            //var_dump($object);
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            //exit;
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    function envioMensagem($phone, $mensage,$param){
                                                
    //$p=$phone;
    //$m=$mensage;                                            
    //print_r($p);
    //print_r($m);
    //$phone='95991288207';
                                            
                                
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
      \"numero\": \"55$phone\",
      \"servico\": \"short\",
      \"mensagem\": \"$mensage\",
      \"parceiro_id\": \"5034e65a0c\",
      \"codificacao\": \"0\"
    }
  ]",
  CURLOPT_HTTPHEADER => [
    "authorization: Bearer 91f3eab84edf0b7c3cc24c6246993ee99f28ea5f",
    "content-type: application/json"
  ],
]);


$response = curl_exec($curl);

//$response='';
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
}
/*
 else {
  echo 'Executado: '.$response;          
}
  */  
}
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('communication'); // open a transaction
                $object = new SystemNotificationAlert($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    public function onReload($param) {                                               
        //var_dump($param);                                            
        $key = $param['id'];
        $this->id = $key;
        
        //var_dump($param);
        //exit;
        /*
        $this->pessoas_alertas_id=$param['pessoas_alertas_id'];
        $this->pessoa_id=$param['pessoa_id'];
        $this->sistema_id=$param['sistema_id'];
        $this->evento_id=$param['evento_id'];
        */
        $this->loaded = true;
        
    }
}
