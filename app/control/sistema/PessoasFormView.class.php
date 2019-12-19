<?php
/**
 * PessoasFormView Form
 * @author  <your name here>
 */
class PessoasFormView extends TPage
{
    /**
     * Show data
     */
    public function onEdit( $param )
    {
        try
        {
            // convert parameter to object
            $data = (object) $param;
            
            // load the html template
            $html = new THtmlRenderer('app/resources/pessoasformview.html');
            
            TTransaction::open('dbpmbv');
            if (isset($data->id))
            {
                // load customer identified in the form
                $object = Pessoas::find( $data->id );
                if ($object)
                {
                    // create one array with the customer data
                    $array_object = $object->toArray();
                    
                    // replace variables from the main section with the object data
                    $html->enableSection('main',  $array_object);
                }
                else
                {
                    throw new Exception('Pessoas not found');
                }
            }
            else
            {
                throw new Exception('<b>id</b> not detected in parameters');
            }
            
            TTransaction::close();
            
            // vertical box container
            $container = new TVBox;
            $container->style = 'width: 90%';
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
            $container->add($html);
            parent::add($container);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
