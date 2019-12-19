<?php
/**
 * FormHierarchicalComboView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormImportEventosView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // create the form
        $this->form = new BootstrapFormBuilder('form_hierarchical');
        $this->form->setFormTitle(_t('Importando Eventos e Programas'));
        
        $id = new TEntry('id');
        //$name = new TEntry('name');
        $data_atual = new TDateTime('created');
        $data_atual->setValue(date('Y-m-d H:i'));
        $sistema_id = new TDBCombo('sistema_id', 'dbpmbv', 'Sistemas', 'id', 'nome', 'nome');
        //$sistema_id->enableSearch();
        
        // filter to avoid preload items
        $filter = new TCriteria;
        $filter->add(new TFilter('id', '<', '0'));
        $eventos_id = new TDBCombo('eventos_id', 'dbpmbv', 'Eventos', 'id', 'nome', 'nome', $filter);
        //$customer_id = new TDBCombo('customer_id', 'samples', 'Customer', 'id', 'name', 'name', $filter);
        
        $eventos_id->enableSearch();
        //$customer_id->enableSearch();
        
        // add the fields inside the form
        $this->form->addFields( [new TLabel('Id')],    [$id] );
        $this->form->addFields( [new TLabel('Data da Importação')],  [$data_atual] );
        $this->form->addFields( [new TLabel('Sistema')], [$sistema_id] );
        $this->form->addFields( [new TLabel('Eventos/Programa')],  [$eventos_id] );
        $file = new TFile('file');
        $id->setEditable(FALSE);
        $data_atual->setEditable(FALSE);
        $id->setSize('30%');
        
        $this->form->addFields([new TLabel('File:')], [$file]);
        
        $sistema_id->setChangeAction( new TAction( array($this, 'onSistemaChange' )) );
        
        $this->form->addAction('Save', new TAction(array($this, 'onSave')));
        
        $sistema_id->addValidation('Sistema', new TRequiredValidator());
        $sistema_id->addValidation('Codigo', new TRequiredValidator());
        $file->addValidation('Arquivo de Importação', new TRequiredValidator());  
          
        
        
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style='width:100%';
        //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
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
            $this->form->validate(); // validate form data
            
            $fileName = $param['file'];
            $sistema_id=$param['sistema_id'];
            $evento_id=$param['eventos_id'];
            $id_usuario=TSession::getValue('userid');
            $data_atual = new TDateTime('created');
            $data_atual->setValue(date('Y-m-d H:i'));            
            $nomeArquivo= explode('.',$fileName);            
            $fileName2="eventos_import.".$nomeArquivo[1];
            copy ( 'tmp/'.$fileName , 'files/eventos/'.$fileName2 );
            //$upfile = json_decode(urldecode($data->file));
            $object = new RegistroDasImportacoesEvento();  // create an empty object
            //$data = $this->form->getData(); // get form data as array
            //$object->fromArray( (array) $data); // load the object with data
            $object->id_sistema=$sistema_id;
            $object->id_evento=$evento_id;
            $object->id_usuario=$id_usuario;
            $object->data_import=date('Y-m-d H:i');            
            //var_dump($object);
            $object->store(); // save the object
            
            // get the generated id
            //$data->id = $object->id;
            
            //$this->form->setData($data); // fill form data
            
            //$this->fireEvents( $object );
            
            TTransaction::close(); // close the transaction
            //echo shell_exec('call D:\VertrigoServ\www\prontuariocidadao-appalertas\files\spoon\import_eventos.bat');
            //echo shell_exec('call D:\Aplicativos\data-integration\Pan.bat /file:D:\VertrigoServ\www\prontuariocidadao-appalertas\files\spoon\import_eventos_programas.ktr');
            //echo shell_exec('call D:\pmdv\data-integration\Kitchen.bat /file:D:\VertrigoServ\www\prontuariocidadao-appalertas\files\spoon\job_eventos.kjb');
            echo shell_exec('call C:\VertrigoServ\www\data-integration\Pan.bat /file:C:\VertrigoServ\www\appalertas\files\spoon\import_pessoas_eventos_programas.ktr');
            //time_sleep_until(time()+0.2);
            echo shell_exec('call C:\VertrigoServ\www\data-integration\Pan.bat /file:C:\VertrigoServ\www\appalertas\files\spoon\import_eventos_pessoas_sys.ktr');
            //time_sleep_until(time()+0.2);   
            echo shell_exec('call C:\VertrigoServ\www\data-integration\Pan.bat /file:C:\VertrigoServ\www\appalertas\files\spoon\import_eventos_programas_novo.ktr');
            //time_sleep_until(time()+0.2); 
            new TMessage('info', "Cadastros foram importados!, verificando alertas!");
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
            TApplication::loadPage('Painel01View');
            
            exit();
            
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Fire form events
     * @param $param Request
     */
    public function fireEvents( $object )
    {
        $obj = new stdClass;
        $obj->sistema_id  = $object->sistema_id;
        $obj->eventos_id  = $object->eventos_id;
        //$obj->customer_id = $object->customer_id;
        TForm::sendData('form_hierarchical', $obj);
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
                TTransaction::open('samples'); // open a transaction
                $object = new Test($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
                
                $this->fireEvents( $object );
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Action to be executed when the user changes the state
     * @param $param Action parameters
     */
    public static function onSistemaChange($param)
    {
        try
        {
            TTransaction::open('dbpmbv');
            if (!empty($param['sistema_id']))
            {
                $criteria = TCriteria::create( ['sistema_id' => $param['sistema_id'] ] );
                
                // formname, field, database, model, key, value, ordercolumn = NULL, criteria = NULL, startEmpty = FALSE
                TDBCombo::reloadFromModel('form_hierarchical', 'eventos_id', 'dbpmbv', 'Eventos', 'id', '{nome} ({id})', 'nome', $criteria, TRUE);
            }
            else
            {
                TCombo::clearField('form_hierarchical', 'eventos_id');
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Action to be executed when the user changes the city
     * @param $param Action parameters
     */
    public static function onCityChange($param)
    {
        try
        {
            TTransaction::open('samples');
            if (!empty($param['city_id']))
            {
                $criteria = TCriteria::create( ['city_id' => $param['city_id'] ] );
                
                // formname, field, database, model, key, value, ordercolumn = NULL, criteria = NULL, startEmpty = FALSE
                TDBCombo::reloadFromModel('form_hierarchical', 'customer_id', 'samples', 'Customer', 'id', '{name} ({id})', 'name', $criteria, TRUE);
            }
            else
            {
                TCombo::clearField('form_hierarchical', 'customer_id');
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
