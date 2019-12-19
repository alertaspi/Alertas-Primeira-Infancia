<?php
/**
 * SistemasForm Form
 * @author  <your name here>
 */
class SistemasForm extends TPage
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
        $this->form = new BootstrapFormBuilder('form_Sistemas');
        $this->form->setFormTitle('Sistemas');
        
        //$dttime = date('Y-m-d H:i:s');
        $dttime = date('d/m/Y H:i:s');
        
        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $descricao = new TText('descricao');
        $id_origem = new TEntry('id_origem');
        $data_info = new TDate('data_info');
        $entidade_id = new TEntry('entidade_id');
        $icon_html = new TEntry('icon_html');
        $url = new TEntry('url');
        $img = new TEntry('img');
        
        $data_info->setValue($dttime);
        $data_info->setMask('dd/mm/yyyy');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Descricao') ], [ $descricao ] );
        $this->form->addFields( [ new TLabel('Id Origem') ], [ $id_origem ] );
        $this->form->addFields( [ new TLabel('Data Info') ], [ $data_info ] );
        $this->form->addFields( [ new TLabel('Entidade Id') ], [ $entidade_id ] );
        $this->form->addFields( [ new TLabel('Icon Html') ], [ $icon_html ] );
        $this->form->addFields( [ new TLabel('Url') ], [ $url ] );
        $this->form->addFields( [ new TLabel('Img') ], [ $img ] );



        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $descricao->setSize('100%');
        $id_origem->setSize('100%');
        $data_info->setSize('100%');
        $entidade_id->setSize('100%');
        $icon_html->setSize('100%');
        $url->setSize('100%');
        $img->setSize('100%');



        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:floppy-o');
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
            TTransaction::open('dbpmbv'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Sistemas;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
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
                $object = new Sistemas($key); // instantiates the Active Record
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
