<?php
/**
 * PessoasAlertasAcoesForm Form
 * @author  <your name here>
 */
class PessoasAlertasAcoesForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        //var_dump($param);
        
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_PessoasAlertasAcoes');
        $this->form->setFormTitle($param['tipo']);
        
        TSession::regenerate();
        $id_usuario=TSession::getValue('userid');
        $data_atual = new TDateTime('created');
        $data_atual->setValue(date('Y-m-d H:i:s'));
        

        // create the form fields
        $id = new TEntry('id');
        //$pessoas_alertas_id = new TDBUniqueSearch('pessoas_alertas_id', 'dbpmbv', 'PessoasAlertas', 'id', 'pessoa_id');
        $pessoas_alertas_id = new TEntry('pessoas_alertas_id');
        $usuario_id = new TEntry('usuario_id');
        $usuario_id->setValue($id_usuario);
        $acao_tomada = new TEntry('acao_tomada');
        $descricao_acao = new TText('descricao_acao');
        $data_info = new TEntry('data_info');
        $data_info = $data_atual;        
        $status = new TDBCombo('status','dbpmbv','AlertaStatus','id','descricao');
        
        if($param['method']=='onNew'){
        $filter = new TCriteria;
        $filter->add(new TFilter('pessoas_alertas_id', '=', $param['id']));
        
        $id_pai = new TDBCombo('id_pai','dbpmbv','PessoasAlertasAcoes','id','acao_tomada','acao_tomada',$filter);
        }else{
        $id_pai = new TDBCombo('id_pai','dbpmbv','PessoasAlertasAcoes','id','acao_tomada','id');
        }
        //var_dump($param);
        if($param['method']=='onNew'){
           $pessoas_alertas_id->setValue($param['pessoas_alertas_id']);                              
        }
        
        /*
        if(!isset($param['pessoas_alertas_id'])){
           $pessoas_alertas_id->setValue($_GET['pessoas_alertas_id']);               
        }else{
           $pessoas_alertas_id->setValue($param['pessoas_alertas_id']);        
        }
        
        if($pessoas_alertas_id->getValue==null){
          $pessoas_alertas_id->setValue(TSession::getValue('pessoas_alertas_id'));                                         
                                               
          //var_dump($pessoas_alertas_id);                                   
        }
        */
        if($param['method']=='onEdit'){
           //$pessoas_alertas_id->setValue(TSession::getValue('pessoas_alertas_id'));
           $pessoas_alertas_id->setValue($_GET['pessoas_alertas_id']);
           //var_dump($pessoas_alertas_id);                              
        }
        
        /*
        if(!empty($pessoas_alertas_id)){
           $pessoas_alertas_id->setValue($param['key']);
        }
        */

        $acao_tomada->placeholder='Informe aqui o tipo da ação tomada';
        $descricao_acao->placeholder='Descreva a ação tomada';

        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Pessoas Alertas Id') ], [ $pessoas_alertas_id ] );
        $this->form->addFields( [ new TLabel('Usuario Id') ], [ $usuario_id ] );
        $this->form->addFields( [ new TLabel('Ação Tomada') ], [ $acao_tomada ] );
        $this->form->addFields( [ new TLabel('Descrição da Ação') ], [ $descricao_acao ] );
        $this->form->addFields( [ new TLabel('Status') ], [ $status ] );
        $this->form->addFields( [ new TLabel('Ligada a uma ação anterior') ], [ $id_pai ] );
        $this->form->addFields( [ new TLabel('Data Info') ], [ $data_info ] );
        

        $acao_tomada->addValidation('Acao Tomada', new TRequiredValidator);
        
        


        // set sizes
        $id->setSize('100%');
        $pessoas_alertas_id->setSize('100%');
        $usuario_id->setSize('100%');
        $acao_tomada->setSize('100%');
        $descricao_acao->setSize('100%');
        $data_info->setSize('100%');
        



        if (!empty($id))
        {
            $id->setEditable(FALSE);
            $pessoas_alertas_id->setEditable(FALSE);
            $usuario_id->setEditable(FALSE);
            
            $data_info->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $actionLink = new TAction( ['Painel02View', 'onRetorno' ] );
        //$actionLink = new TAction( [$this, 'onClose' ] );
        //$actionLink->setParameter('key', $key);
        $actionLink->setParameter('pessoa_id',$_GET['pessoa_id']);
        $actionLink->setParameter('sistema_id',$_GET['sistema_id']);
        $actionLink->setParameter('evento_id',$_GET['evento_id']);
        $actionLink->setParameter('tipo',$_GET['tipo']);
        
        $actionLink->setParameter('pessoas_alertas_id',$pessoas_alertas_id);
       
        $actionLink2 = new TAction( [$this, 'onSave' ] );
        //$actionLink->setParameter('key', $key);
        $actionLink2->setParameter('pessoa_id',$_GET['pessoa_id']);
        $actionLink2->setParameter('sistema_id',$_GET['sistema_id']);
        $actionLink2->setParameter('evento_id',$_GET['evento_id']);
        $actionLink2->setParameter('tipo',$_GET['tipo']);
        $actionLink2->setParameter('data_info',$data_atual);
        
        $notificationLink = new TAction([$this, 'onNotification']);
        //$notificationLink->setParameter('key', $key);
        $notificationLink->setParameter('pessoa_id',$_GET['pessoa_id']);
        $notificationLink->setParameter('sistema_id',$_GET['sistema_id']);
        $notificationLink->setParameter('evento_id',$_GET['evento_id']);
        $notificationLink->setParameter('tipo',$_GET['tipo']);
        $notificationLink->setParameter('data_info',$data_atual);
        $notificationLink->setParameter('id_usuario',$id_usuario);
        
        //$btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:floppy-o');
        $btn = $this->form->addAction(_t('Save'),$actionLink2 , 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        //$this->form->addAction(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        $this->form->addAction(_t('Close'),  $actionLink, 'fa:eraser red');
        //$this->form->addAction(_t('Close'),  new TAction([$actionLink, 'onClose']), 'fa:eraser red');
        //$this->form->addAction(_t('Notification'),  new TAction(['SystemNotificationForm', 'onReload']), 'fa:user');
        $this->form->addAction(_t('Notification'), $notificationLink, 'fa:user');
        
        
        $div = new TElement('div'); 
        $a=new PessoasAlertasAcoesList();
        $div->add($a);
        $panel1 = new TPanelGroup('Ações tomadas para: '.$param['tipo']);
        $panel1->add($div);
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel1);
        
        parent::add($container);
    }

    public function onNotification($param){
       //var_dump($param);
       $param['key']=$param['pessoa_id'];    
       $param['id']=$param['pessoa_id'];   
       $pessoa_id=$param['pessoa_id'];
       $sistema_id=$param['sistema_id'];
       $evento_id=$param['evento_id'];
       $tipo=$param['tipo'];
       $data_info=$param['created'];
       $id_usuario=$param['id_usuario'];
       //var_dump($param);
       //$id_usuario=3;   
       //$action='class=SystemDocumentForm&method=onView';
       //$label='Verificar';
       //$icon='fa fa-pencil-square-o blue';
       AdiantiCoreApplication::loadPage('SistemaNotificacaoAlertasForm','onReload',$param);
       //SystemNotification::register( $user, $title, $message, $action, $label, $icon );                          
       //SystemNotification::register( $id_usuario, 'Alerta', $tipo, $action, $label, $icon );                                 
       exit;                                 
    }

    /**
     * Save form data
     * @param $param Request
     */
    public static function onClose($param) {
        
        if(empty($param['id'])){
                                   $id=0;
                               }
                               
        $param['id']=$param['pessoa_id'];    
        $param['key']=$param['pessoa_id'];                     
        //$this->pessoas_alertas_id=$param['pessoas_alertas_id'];
        //$this->pessoa_id=$param['pessoa_id'];
        //$this->sistema_id=$param['sistema_id'];
        //$this->evento_id=$param['evento_id'];
        //parent::closeWindow($id);  
        var_dump($param);                     
        AdiantiCoreApplication::loadPage('Painel02View','onRetorno',$param);
    }
     
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('dbpmbv'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new PessoasAlertasAcoes;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->data_info=new TDateTime('created');
            //var_dump($object);
            $object->store(); // save the object
            
            //var_dump($this);
            
            // get the generated id
            $data->id = $object->id;
            $this->pessoas_alertas_id=$param['pessoas_alertas_id'];
            $this->pessoa_id=$param['pessoa_id'];
            $this->sistema_id=$param['sistema_id'];
            $this->evento_id=$param['evento_id'];
            $this->tipo=$param['tipo'];
            
            $this->form->setData($data); // fill form data
            $this->form->setData(TSession::setValue('pessoas_alertas_id',   $this->pessoas_alertas_id)); // stores the filter in the session
            //$this->pessoas_alertas_id=$param['pessoas_alertas_id'];
            
            TTransaction::close(); // close the transaction
            
            new TMessage('info', 'Ação cadastrada/alterada!');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
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
            if (isset($param['id']))
            {
                $key = $param['id'];  // get the parameter $key
                TTransaction::open('dbpmbv'); // open a transaction
                $object = new PessoasAlertasAcoes($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                
                $this->pessoas_alertas_id=$param['pessoas_alertas_id'];
        $this->pessoa_id=$param['pessoa_id'];
        $this->sistema_id=$param['sistema_id'];
        $this->evento_id=$param['evento_id'];
                
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
    
    public function onReload($param = NULL) {                                               
        //var_dump($param);                                            
        $key = $param['id'];
        $this->id = $key;
        /*
        $this->pessoas_alertas_id=$param['pessoas_alertas_id'];
        $this->pessoa_id=$param['pessoa_id'];
        $this->sistema_id=$param['sistema_id'];
        $this->evento_id=$param['evento_id'];
        */
        $this->loaded = true;
        
    }
    
    public function onNew($param = NULL) {                                               
        //var_dump($param);                                            
        $key = NULL;
        $this->id = $key;
        $this->pessoas_alertas_id=$param['pessoas_alertas_id'];
        $this->pessoa_id=$param['pessoa_id'];
        $this->sistema_id=$param['sistema_id'];
        $this->evento_id=$param['evento_id'];
        $this->loaded = true;
        
    }
    
    
}
