<?php
/**
 * Vw40AtencaoMedicaReport Report
 * @author  <your name here>
 */
class Vw40AtencaoMedicaReport extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Vw40AtencaoMedicaPrimerissimaInfancia_report');
        $this->form->setFormTitle('Vw40AtencaoMedicaPrimerissimaInfancia Report');
        

        // create the form fields
        $pessoa_id = new TEntry('pessoa_id');
        $nome = new TEntry('nome');
        $mae = new TEntry('mae');
        $cns = new TEntry('cns');
        $idade = new TEntry('idade');
        $qtd = new TEntry('qtd');
        $output_type = new TRadioGroup('output_type');


        // add the fields
        $this->form->addFields( [ new TLabel('Pessoa Id') ], [ $pessoa_id ] );
        $this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Mae') ], [ $mae ] );
        $this->form->addFields( [ new TLabel('Cns') ], [ $cns ] );
        $this->form->addFields( [ new TLabel('Idade') ], [ $idade ] );
        $this->form->addFields( [ new TLabel('Qtd') ], [ $qtd ] );
        $this->form->addFields( [ new TLabel('Output') ], [ $output_type ] );

        $output_type->addValidation('Output', new TRequiredValidator);


        // set sizes
        $pessoa_id->setSize('100%');
        $nome->setSize('100%');
        $mae->setSize('100%');
        $cns->setSize('100%');
        $idade->setSize('100%');
        $qtd->setSize('100%');
        $output_type->setSize('100%');


        
        $output_type->addItems(array('html'=>'HTML', 'pdf'=>'PDF', 'rtf'=>'RTF', 'xls' => 'XLS'));
        $output_type->setLayout('horizontal');
        $output_type->setUseButton();
        $output_type->setValue('pdf');
        $output_type->setSize(70);
        
        // add the action button
        $btn = $this->form->addAction(_t('Generate'), new TAction(array($this, 'onGenerate')), 'fa:cog');
        $btn->class = 'btn btn-sm btn-primary';
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }
    
    /**
     * Generate the report
     */
    function onGenerate()
    {
        try
        {
            // open a transaction with database 'dbpmbv'
            TTransaction::open('dbpmbv');
            
            // get the form data into an active record
            $data = $this->form->getData();
            
            $this->form->validate();
            
            $repository = new TRepository('Vw40AtencaoMedicaPrimerissimaInfancia');
            $criteria   = new TCriteria;
            
            if ($data->pessoa_id)
            {
                $criteria->add(new TFilter('pessoa_id', 'like', "%{$data->pessoa_id}%"));
            }
            if ($data->nome)
            {
                $criteria->add(new TFilter('nome', 'like', "%{$data->nome}%"));
            }
            if ($data->mae)
            {
                $criteria->add(new TFilter('mae', 'like', "%{$data->mae}%"));
            }
            if ($data->cns)
            {
                $criteria->add(new TFilter('cns', 'like', "%{$data->cns}%"));
            }
            if ($data->idade)
            {
                $criteria->add(new TFilter('idade', 'like', "%{$data->idade}%"));
            }
            if ($data->qtd)
            {
                $criteria->add(new TFilter('qtd', 'like', "%{$data->qtd}%"));
            }

           
            $objects = $repository->load($criteria, FALSE);
            $format  = $data->output_type;
            
            if ($objects)
            {
                $widths = array(100,100,100,100,100,100,100,50,100,100,100,100,100);
                
                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths);
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLS($widths);
                        break;
                    case 'rtf':
                        $tr = new TTableWriterRTF($widths);
                        break;
                }
                
                // create the document styles
                $tr->addStyle('title', 'Arial', '10', 'B',   '#ffffff', '#9898EA');
                $tr->addStyle('datap', 'Arial', '10', '',    '#000000', '#EEEEEE');
                $tr->addStyle('datai', 'Arial', '10', '',    '#000000', '#ffffff');
                $tr->addStyle('header', 'Arial', '16', '',   '#ffffff', '#494D90');
                $tr->addStyle('footer', 'Times', '10', 'I',  '#000000', '#B1B1EA');
                
                // add a header row
                $tr->addRow();
                $tr->addCell('Vw40AtencaoMedicaPrimerissimaInfancia', 'center', 'header', 13);
                
                // add titles row
                $tr->addRow();
                $tr->addCell('Pessoa Id', 'right', 'title');
                $tr->addCell('Sistema Id', 'right', 'title');
                $tr->addCell('Evento Id', 'right', 'title');
                $tr->addCell('Tempo Id', 'right', 'title');
                $tr->addCell('Ano', 'right', 'title');
                $tr->addCell('Mes Desc', 'left', 'title');
                $tr->addCell('Nome', 'left', 'title');
                $tr->addCell('Data Nascimento', 'left', 'title');
                $tr->addCell('Mae', 'left', 'title');
                $tr->addCell('Cns', 'left', 'title');
                $tr->addCell('Descricao', 'left', 'title');
                $tr->addCell('Idade', 'right', 'title');
                $tr->addCell('Qtd', 'right', 'title');

                
                // controls the background filling
                $colour= FALSE;
                
                // data rows
                foreach ($objects as $object)
                {
                    $style = $colour ? 'datap' : 'datai';
                    $tr->addRow();
                    $tr->addCell($object->pessoa_id, 'right', $style);
                    $tr->addCell($object->sistema_id, 'right', $style);
                    $tr->addCell($object->evento_id, 'right', $style);
                    $tr->addCell($object->tempo_id, 'right', $style);
                    $tr->addCell($object->ano, 'right', $style);
                    $tr->addCell($object->mes_desc, 'left', $style);
                    $tr->addCell($object->nome, 'left', $style);
                    $tr->addCell($object->data_nascimento, 'left', $style);
                    $tr->addCell($object->mae, 'left', $style);
                    $tr->addCell($object->cns, 'left', $style);
                    $tr->addCell($object->descricao, 'left', $style);
                    $tr->addCell($object->idade, 'right', $style);
                    $tr->addCell($object->qtd, 'right', $style);

                    
                    $colour = !$colour;
                }
                
                // footer row
                $tr->addRow();
                $tr->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 13);
                
                // stores the file
                if (!file_exists("app/output/Vw40AtencaoMedicaPrimerissimaInfancia.{$format}") OR is_writable("app/output/Vw40AtencaoMedicaPrimerissimaInfancia.{$format}"))
                {
                    $tr->save("app/output/Vw40AtencaoMedicaPrimerissimaInfancia.{$format}");
                }
                else
                {
                    throw new Exception(_t('Permission denied') . ': ' . "app/output/Vw40AtencaoMedicaPrimerissimaInfancia.{$format}");
                }
                
                // open the report file
                parent::openFile("app/output/Vw40AtencaoMedicaPrimerissimaInfancia.{$format}");
                
                // shows the success message
                new TMessage('info', 'Report generated. Please, enable popups.');
            }
            else
            {
                new TMessage('error', 'No records found');
            }
    
            // fill the form with the active record data
            $this->form->setData($data);
            
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
