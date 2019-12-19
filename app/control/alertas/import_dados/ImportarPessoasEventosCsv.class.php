<?php
/**
 * SinglePageView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ImportarPessoasEventosCsv extends TPage
{
    /**
     * Constructor method
     */
    private $form;
     
    public function __construct()
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
    
    
    public function fireEvents( $object )
    {
        $obj = new stdClass;
        $obj->sistema_id  = $object->sistema_id;
        $obj->eventos_id  = $object->eventos_id;
        //$obj->customer_id = $object->customer_id;
        TForm::sendData('form_hierarchical', $obj);
    }
    
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
            
            $csv = new TReadCsv('files/eventos/'.$fileName2);
            $conteudo = $csv->abre();
            
            foreach($conteudo as $row){
            $pimport = new ImportDados();
            $pimport->pessoa_id=$row['pessoa_id']
            
            }
            $pimport->fromArray( (array) $conteudo);
            
            var_dump($pimport);
            exit;
            
            // get the generated id
            //$data->id = $object->id;
            
            //$this->form->setData($data); // fill form data
            
            //$this->fireEvents( $object );
            
            TTransaction::close(); // close the transaction
            //echo shell_exec('call D:\Aplicativos\data-integration\Pan.bat /file:D:\VertrigoServ\www\prontuariocidadao-appalertas\files\spoon\import_pessoas_eventos_programas.ktr');
            //echo shell_exec('call D:\Aplicativos\data-integration\Pan.bat /file:D:\VertrigoServ\www\prontuariocidadao-appalertas\files\spoon\import_eventos_pessoas_sys.ktr');   
            //echo shell_exec('call D:\Aplicativos\data-integration\Pan.bat /file:D:\VertrigoServ\www\prontuariocidadao-appalertas\files\spoon\import_eventos_programas_novo.ktr');
           
            new TMessage('info', "Cadastros foram importados!");
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
