<?php
/**
 * PessoasAlertasFormNew Form
 * @author  <your name here>
 */
class PessoasAlertasFormNew extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        TSession::regenerate();
            
            $id_usuario=TSession::getValue('userid');
            $data_atual = new TDateTime('created');
            $data_atual->setValue(date('Y-m-d H:i'));
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_PessoasAlertas');
        $this->form->setFormTitle('PessoasAlertas');
        

        // create the form fields
        $id = new TEntry('id');
        $pessoa_id = new TEntry('pessoa_id');
        if(isset($_GET['pessoa_id'])){
          $pessoa_id->setValue($_GET['pessoa_id']);
        }else{
          $pessoa_id->setValue($param['pessoa_id']);       
        }
        
        if(isset($_GET['pessoas_alertas_id'])){
          $pessoas_alertas_id=$_GET['pessoas_alertas_id'];
        }else{
          $pessoas_alertas_id=$param['pessoas_alertas_id'];       
        }
        
        $sistema_id = new TDBCombo('sistema_id', 'dbpmbv', 'Sistemas', 'id', 'nome','nome');
        
        $filter = new TCriteria;
        $filter->add(new TFilter('sistema_id', '<', '0'));
        
        $evento_id = new TDBCombo('evento_id', 'dbpmbv', 'Eventos', 'id', 'nome','nome',$filter);
        
        //$evento_id->enableSearch();
        
        
        $usuario_id = new TEntry('usuario_id');
        $usuario_id->setValue($id_usuario);
        $observacao = new TText('observacao');
        $status = new TDBCombo('status', 'dbpmbv', 'AlertaStatus', 'id', 'descricao');
        $tipo = new TEntry('tipo');
        $data_info = new TEntry('data_info');
        $data_info=$data_atual;


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Pessoa Id') ], [ $pessoa_id ] );
        $this->form->addFields( [ new TLabel('Sistema Id') ], [ $sistema_id ] );
        $this->form->addFields( [ new TLabel('Evento Id') ], [ $evento_id ] );
        $this->form->addFields( [ new TLabel('Usuario Id') ], [ $usuario_id ] );
        $this->form->addFields( [ new TLabel('Ação Tomada') ], [ $tipo ] );
        $this->form->addFields( [ new TLabel('Descrição da Ação') ], [ $observacao ] );
        $this->form->addFields( [ new TLabel('Status') ], [ $status ] );        
        $this->form->addFields( [ new TLabel('Data Info') ], [ $data_info ] );



        // set sizes
        $id->setSize('100%');
        $pessoa_id->setSize('100%');
        $sistema_id->setSize('100%');
        $evento_id->setSize('100%');
        $usuario_id->setSize('100%');
        $observacao->setSize('100%');
        $status->setSize('100%');
        $tipo->setSize('100%');
        $data_info->setSize('100%');
        
        $sistema_id->setChangeAction( new TAction( array($this, 'onSistemaChange' )) );
        
        $sistema_id->addValidation('Sistema', new TRequiredValidator());



        if (!empty($id))
        {
            $id->setEditable(FALSE);
            $pessoa_id->setEditable(FALSE);
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
        //$this->form->addAction(_t('Close'),  new TAction(['Painel02View', 'onRetorno']), 'fa:eraser red');
         $actionLink = new TAction( ['Painel02View', 'onRetorno' ] );
        //$actionLink = new TAction( [$this, 'onClose' ] );
        //$actionLink->setParameter('key', $key);
        $actionLink->setParameter('pessoa_id',$_GET['pessoa_id']);
        $actionLink->setParameter('sistema_id',$_GET['sistema_id']);
        $actionLink->setParameter('evento_id',$_GET['evento_id']);
        $actionLink->setParameter('tipo',$_GET['tipo']);
        
        $actionLink->setParameter('pessoas_alertas_id',$pessoas_alertas_id);
        
        $this->form->addAction(_t('Close'),  $actionLink, 'fa:eraser red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }


    public function fireEvents( $object )
    {
        $obj = new stdClass;
        $obj->sistema_id  = $object->sistema_id;
        $obj->eventos_id  = $object->eventos_id;
        //$obj->customer_id = $object->customer_id;
        TForm::sendData('form_PessoasAlertas', $obj);
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
            $param['pessoas_alertas_id']=0;
            //new TMessage('info', 'Alerta vinculado!');
             AdiantiCoreApplication::loadPage('Painel02View','onRetorno',$param);
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
                
                $this->fireEvents( $object );
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
    
    
     public static function onSistemaChange($param)
    {
        try
        {
            TTransaction::open('dbpmbv');
            if (!empty($param['sistema_id']))
            {
                $criteria = TCriteria::create( ['sistema_id' => $param['sistema_id'] ] );
                
                // formname, field, database, model, key, value, ordercolumn = NULL, criteria = NULL, startEmpty = FALSE
                TDBCombo::reloadFromModel('form_PessoasAlertas', 'evento_id', 'dbpmbv', 'Eventos', 'id', '{nome} ({id})', 'nome', $criteria, TRUE);
            }
            else
            {
                TCombo::clearField('form_PessoasAlertas', 'eventos_id');
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
}
