<?php

/**
 * ContainerWindowView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ContainerWindowView extends TWindow {

    private $form;
      
    // trait with onSave, onClear, onEdit, ...
    use Adianti\Base\AdiantiStandardFormTrait;
    
    // trait with saveFile, saveFiles, ...
    use Adianti\Base\AdiantiFileSaveTrait;

    /**
     * Class constructor
     * Creates the page
     */
    function __construct() {
        parent::__construct();
        parent::setTitle('Importando Cadastro!');

        // with: 500, height: automatic
        parent::setSize(500, null); // use 0.6, 0.4 (for relative sizes 60%, 40%)

        $close_action = new TAction([$this, 'onBeforeClose']);
        $close_action->setParameter('id', parent::getId());
        parent::setCloseAction($close_action);

        // create the form
        $this->form = new BootstrapFormBuilder('window_form');
        $this->form->setProperty('style', 'margin-bottom:0');

        // create the form fields
        //$id = new TEntry('id');
        $sistema_id = new TDBCombo('sistema_id', 'dbpmbv', 'Sistemas', 'id', 'nome');
        $data_atual = new TDateTime('created');
        $data_atual->setValue(date('Y-m-d H:i'));

        // add the fields inside the form
        $this->form->addFields(['#Sistema'], [$sistema_id]);
        $this->form->addFields(['Data da Importação'], [$data_atual]);
        $file = new TFile('file');

        //$text->setSize(300,100);
        $data_atual->setEditable(FALSE);
        //$file->setCompleteAction(new TAction(array($this, 'onImportar')));
        //$file->setSize(400);

        $this->form->addFields([new TLabel('File:')], [$file]);

        // define the form action 
        $this->form->addAction('Save', new TAction(array($this, 'onImportar')), 'fa:check-circle-o green');

        // add the form inside the page
        parent::add($this->form);
    }

    /**
     * on close event
     */
    public static function onBeforeClose($param) {
        $action = new TAction(['ContainerWindowView', 'onClose']);
        $action->setParameter('id', $param['id']);
        new TQuestion('Want to close?', $action);
    }

    public static function onClose($param) {
        
        if(empty($param['id'])){
                                   $id=0;
                               }
        parent::closeWindow($id);                       
        AdiantiCoreApplication::loadPage('Painel01View');
    }

    /**
     * Simulates an save button
     * Show the form content
     */
    public function onSave($param) {
        $data = $this->form->getData(); // optional parameter: active record class
        // put the data back to the form
        $this->form->setData($data);

        new TMessage('info', $data->text);
    }

    public function onImportar($param) {
        try {
            //var_dump($param);
            TSession::regenerate();
            
            
            //$data = $this->form->getData();
            //Obtém o nome do arquivo
            $fileName = $param['file'];
            $sistema_id=$param['sistema_id'];
            $id_usuario=TSession::getValue('userid');
            $data_atual = new TDateTime('created');
            $data_atual->setValue(date('Y-m-d H:i'));
            
            $output = '';
            $data = $this->form->getData();
            $nomeArquivo= explode('.',$fileName);
            //echo $nomeArquivo[1]." ";
            //var_dump($id_usuario);
            //exit;
            $fileName2="cadastros_import.".$nomeArquivo[1];
                    
            //echo $fileName." e 2 ".$fileName2;
            copy ( 'tmp/'.$fileName , 'files/cadastros/'.$fileName2 );
            //$pessoas = new Pessoas();
            //$this->saveFile($pessoas, $param,'file', 'files/cadastros');
            $upfile = json_decode(urldecode($data->file));
            TTransaction::open('dbpmbv');
            $rp = new RegistroDasImportacoes();
            $rp->id_sistema=$sistema_id;
            $rp->id_usuario=$id_usuario;
            $rp->data_import=date('Y-m-d H:i');
            //var_dump($rp);
            $rp->store(); // save the object
            TTransaction::close();
            
            //$path_parts = pathinfo($upfile->fileName);
            //echo "id: ".$sistema_id;
            //var_dump($sistema_id);
            echo shell_exec('call C:\VertrigoServ\www\data-integration\Pan.bat /file:C:\VertrigoServ\www\appalertas\files\spoon\import_cadastros_pessoas.ktr');
            echo shell_exec('call C:\VertrigoServ\www\data-integration\Pan.bat /file:C:\VertrigoServ\www\appalertas\files\spoon\import_cadastros_pessoas_sys.ktr');
            new TMessage('info', "Cadastros foram importados!");
            TApplication::loadPage('Painel01View');
            
            exit();
            foreach ($param as $key => $value) {
                if (is_string($value)) {
                    $output .= "<b>$key</b> => $value <br>";
                } else {
                    $svalue = json_encode($value);
                    $output .= "<b>$key</b> => $svalue <br>";
                }
            }
            var_dump($output);
            //$fileName = json_decode(urldecode($param['file']))->fileName;
            //Abre o arquivo
            $handle = fopen($fileName, "r");

            //Abre uma transação com o banco de dados
            TTransaction::open(self::$database);

            //Contador de registros inseridos
            $count = 0;

            //Separador das colunas do arquivo CSV
            $separador = ';';

            //Limite de caracteres que uma linha pode ter, 0 = sem limite
            $limite_da_linha = 0;
            /*
              //Percorre todas as linhas do arquivos
              while (($dados = fgetcsv($handle, $limite_da_linha, $separador)) !== FALSE)
              {
              //Monta o objeto cliente com as colunas do arquivo CSV
              $cliente           = new AcCliente;
              $cliente->nome     = $dados[0];
              $cliente->email    = $dados[1];
              $cliente->sexo     = $dados[2];
              $cliente->celular  = $dados[3];
              $cliente->endereco = $dados[4];

              //Insere um novo cliente
              $cliente->store();
              $count++;
              }
             */
            //Fecha a transação
            TTransaction::close();

            //Fecha o arquivo
            fclose($handle);

            //Ação a ser executada quando a mensagem de sucesso for fechada
            $closeAction = new TAction(['AcClienteList', 'onReload']);

            //Mensagem de sucesse
            new TMessage('info', "{$count} clientes foram importados!", $closeAction);
        } catch (Exception $e) { // in case of exception
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    public function show() {
        // check if the datagrid is already loaded
        /*
        if (!$this->loaded AND ( !isset($_GET['method']) OR $_GET['method'] !== 'onReload')) {
            $this->onReload(func_get_arg(0));
        }*/
        parent::show();
    }

}
