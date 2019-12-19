<?php
/**
 * DatagridBootstrapView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class PessoaAlertaBootstrapView extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TQuickGrid);
        $this->datagrid->width = '100%';
        
        // add the columns
        $this->datagrid->addQuickColumn('id',    'id',    'left');
        $this->datagrid->addQuickColumn('Name',    'nome',    'left');
        $this->datagrid->addQuickColumn('Alerta', 'tipo', 'left');
        $this->datagrid->addQuickColumn('Motivação', 'observacao', 'left');
        $this->datagrid->addQuickColumn('Sistema', 'sistema', 'left');
        $this->datagrid->addQuickColumn('Evento', 'evento', 'left');
        $this->datagrid->addQuickColumn('Dt Cad',   'data_info',    'left');
        
        
        //$this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        
        //$action1 = new TDataGridAction(array($this, 'onView'));
        $action1 = new TDataGridAction(array('PessoasAlertasAcoesForm', 'onNew'));
        
        
        //$action2 = new TDataGridAction(array($this, 'onDelete'));
        $v=0;
        $this->datagrid->addQuickAction('View',   $action1,'id', 'ico_find.png');
        $action1->setUseButton(TRUE);
        $action1->setButtonClass('btn btn-default');
        $action1->setImage('fa:search blue');
        
        
        $action1->setParameter('pessoas_alertas_id',$action1->getParameter('id'));
        $action1->setField('pessoa_id');
        $action1->setField('sistema_id');
        $action1->setField('evento_id');
        $action1->setField('tipo');
        //$action1->setParameter('pessoa_id',$_GET['pessoa_id']);
        //$action1->setParameter('sistema_id',$_GET['sistema_id']);
        //$action1->setParameter('evento_id',$_GET['evento_id']);
        //$action1->setParameter('tipo',$_GET['tipo']);
        
        // add the actions
        //$this->datagrid->addQuickAction('View',   $action1, 'name', 'ico_find.png');
        //$this->datagrid->addQuickAction('Delete', $action2, 'code', 'ico_delete.png');
        /*
        $action1->setUseButton(TRUE);
        $action1->setButtonClass('btn btn-default');
        $action1->setImage('fa:search blue');
        
        $action2->setUseButton(TRUE);
        $action2->setButtonClass('btn btn-default');
        $action2->setImage('fa:remove red');
        */
        // creates the datagrid model
        $this->datagrid->createModel();
        
        $panel = new TPanelGroup('Alerta identificados');
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        $panel->addFooter('footer');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($panel);

        parent::add($vbox);
    }
    
    /**
     * Load the data into the datagrid
     */
    function onReload()
    {
        $this->datagrid->clear();
        
        $key=$_GET['pessoa_id'];
        $sql="SELECT a.id, b.nome, c.nome sistema, d.nome evento, a.tipo, a.observacao, 
        a.data_info, case when a.status = 1 then 'Abertura de Alerta'
	        when a.status = 2 then 'Em Atendimento'
			else 'Alerta Fechado' end status, a.pessoa_id, a.sistema_id, a.evento_id
FROM scperfil.pessoas_alertas a
JOIN scperfil.pessoas b on a.pessoa_id=b.id
JOIN scperfil.sistemas c on a.sistema_id=c.id
JOIN scperfil.eventos d on a.evento_id=d.id
where a.pessoa_id=$key";
         TTransaction::open('dbpmbv');
         $conn=TTransaction::get();
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         $results = $stmt->fetchAll();
         
         foreach ($results as $row)
            {
                $item = new StdClass;
                $item->id=$row[0];
                $item->nome=$row[1];
                $item->sistema=$row[2];
                $item->evento=$row[3];
                $item->tipo=$row[4];
                $item->observacao=$row[5];
                $item->data_info=$row[6];
                $item->status=$row[7];
                $item->pessoa_id=$row[8];
                $item->sistema_id=$row[9];
                $item->evento_id=$row[10];
                $this->datagrid->addItem($item);
            }
        TTransaction::close();
        
        /*
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '1';
        $item->name     = 'Fábio Locatelli';
        $item->address  = 'Rua Expedicionario';
        $item->fone     = '1111-1111';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '2';
        $item->name     = 'Julia Haubert';
        $item->address  = 'Rua Expedicionarios';
        $item->fone     = '2222-2222';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '3';
        $item->name     = 'Carlos Ranzi';
        $item->address  = 'Rua Oliveira';
        $item->fone     = '3333-3333';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '4';
        $item->name     = 'Daline DallOglio';
        $item->address  = 'Rua Oliveira';
        $item->fone     = '4444-4444';
        $this->datagrid->addItem($item);
        */
    }

    /**
     * method onDelete()
     * Executed when the user clicks at the delete button
     */
    function onDelete($param)
    {
        // get the parameter and shows the message
        $key=$param['key'];
        new TMessage('error', "The register $key may not be deleted");
    }
    
    /**
     * method onView()
     * Executed when the user clicks at the view button
     */
    function onView($param)
    {
        // get the parameter and shows the message
        $key=$param['id'];
        
        new TMessage('info', "The name is : $key");
    }
    
    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}
