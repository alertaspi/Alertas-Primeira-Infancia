<?php
/**
 * PessoasAlertasForm Form
 * @author  <your name here>
 */
class PessoasAlertasForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_PessoasAlertas');
        $this->form->setFormTitle('PessoasAlertas');
        
       
        
        
        TSession::regenerate();
            
            $id_usuario=TSession::getValue('userid');
            $data_atual = new TDateTime('created');
            $data_atual->setValue(date('Y-m-d H:i'));
        

        // create the form fields
        $id = new TEntry('id');
        $pessoa_id = new TEntry('pessoa_id');
        
        $sistema_id = new TEntry('sistema_id');
        
        $evento_id = new TEntry('evento_id');
        
        $usuario_id = new TEntry('usuario_id');
        $usuario_id->setValue($id_usuario);
        $tipo = new TEntry('tipo');
        
        $data_info = new TEntry('data_info');
        $data_info=$data_atual;
        $observacao = new TText('observacao');
        $status = new TDBCombo('status','dbpmbv','AlertaStatus','id','descricao');
        $status->setDefaultOption(FALSE);
        
         if(isset($_GET['tipo'])){
          //$tipo=$_GET['tipo'];
          $tipo->setValue($_GET['tipo']);
        }
        if(isset($_GET['pessoa_id'])){
          //$pessoa_id=$_GET['pessoa_id'];
          $pessoa_id->setValue($_GET['pessoa_id']);
        }
        if(isset($_GET['evento_id'])){
          //$pessoa_id=$_GET['pessoa_id'];
          $evento_id->setValue($_GET['evento_id']);
        }
        if(isset($_GET['sistema_id'])){
          //$sistema=$_GET['sistema_id'];
          $sistema_id->setValue($_GET['sistema_id']);
        }
        
        $itens=array();
        if(!empty($id)){
            $itens=['1'=>'Abertura de Alerta'];
            $status->addItems($itens);
        $status->setDefaultOption(FALSE);                  
        }
        
        /*
        $itens=array();
        if(!empty($id)){
            $itens=['1'=>'Abertura de Alerta'];                  
        }else{                
        $itens=['0'=>'Nenhum',
                '1'=>'Abertura de Alerta',
                '2'=>'Em Atendimento',
                '3'=>'Alerta Fechado'];
        }                
        $status->addItems($itens);
        $status->setDefaultOption(FALSE);
*/

        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Alerta') ], [ $tipo ] );
        $this->form->addFields( [ new TLabel('Pessoa Id') ], [ $pessoa_id ] );
        $this->form->addFields( [ new TLabel('Sistema Id') ], [ $sistema_id ] );
        $this->form->addFields( [ new TLabel('Evento Id') ], [ $evento_id ] );
        $this->form->addFields( [ new TLabel('Usuario Id') ], [ $usuario_id ] );
        $this->form->addFields( [ new TLabel('Data Info') ], [ $data_info ] );
        $this->form->addFields( [ new TLabel('Observações') ], [ $observacao ] );
        $this->form->addFields( [ new TLabel('Status') ], [ $status ] );
        
        /*
        $this->form->addField($sistema_id);
        $this->form->addField($pessoa_id);
        $this->form->addField($evento_id);
        $this->form->addField($usuario_id);
        
        $sistema_id->setInputType('hidden');
        $pessoa_id->setInputType('hidden');
        $evento_id->setInputType('hidden');
        $usuario_id->setInputType('hidden');
        */
        $observacao->placeholder = "Digite aqui as motivações referente a motivação de abertura de Alerta";



        // set sizes
        $id->setSize('100%');
        $tipo->setSize('100%');
        $pessoa_id->setSize('100%');
        $sistema_id->setSize('100%');
        $evento_id->setSize('100%');
        $usuario_id->setSize('100%');
        $data_info->setSize('100%');
        $observacao->setSize('100%');
        $status->setSize('100%');
        
        



        if (!empty($id))
        {
            $id->setEditable(FALSE);
            $tipo->setEditable(FALSE);
            $pessoa_id->setEditable(FALSE);
            $evento_id->setEditable(FALSE);
            $sistema_id->setEditable(FALSE);
            $usuario_id->setEditable(FALSE);
            $data_info->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        //$this->form->addAction(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        $this->form->addAction(_t('Close'),  new TAction(['GestanteForaFqaView', 'onReload']), 'fa:eraser red');
        
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
            TTransaction::open('dbpmbv'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new PessoasAlertas;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            new TMessage('info', 'Alert cadastradp com sucesso!');
            //new TMessage('info', TAdiantiCoreTranslator::translate('Alert registered successfully!'));
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
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('dbpmbv'); // open a transaction
                $object = new PessoasAlertas($key); // instantiates the Active Record
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
}
