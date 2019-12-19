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
class GravidaGestaoAtividadeView extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TQuickGrid);
        $this->datagrid->width = '100%';
        
        // add the columns
        $this->datagrid->addQuickColumn('Ação tomada',    'obs_situacao',    'left');        
        $this->datagrid->addQuickColumn('Situação', 'nome', 'left');
        $this->datagrid->addQuickColumn('Data',    'data_info',    'left');
        //$this->datagrid->addQuickColumn('Phone',   'usu',    'left');
        
        //$this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        
        //$action1 = new TDataGridAction(array($this, 'onView'));
        //$action2 = new TDataGridAction(array($this, 'onDelete'));
        
        // add the actions
        //$this->datagrid->addQuickAction('View',   $action1, 'name', 'ico_find.png');
        //$this->datagrid->addQuickAction('Delete', $action2, 'code', 'ico_delete.png');
        
        //$action1->setUseButton(TRUE);
        //$action1->setButtonClass('btn btn-default');
        //$action1->setImage('fa:search blue');
        
        //$action2->setUseButton(TRUE);
        //$action2->setButtonClass('btn btn-default');
        //$action2->setImage('fa:remove red');
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        $panel = new TPanelGroup('Ações realizadas');
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        //$panel->addFooter('footer');
        
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
    function onReload($param=null)
    {
        $this->datagrid->clear();
        $key=$_GET['key'];
        $sql="select a.obs_situacao, a.data_info, b.nome, a.usuario_id
from scperfil.pessoas_situacao a
join scperfil.pessoas_situacao_tipo b on a.situacao_id=b.id
where a.pessoa_id=$key";
         TTransaction::open('dbpmbv');
         $conn=TTransaction::get();
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         $results = $stmt->fetchAll();
         
         foreach ($results as $row)
            {
                $item = new StdClass;
                $item->obs_situacao=$row[0];
                $item->data_info=$row[1];
                $item->nome=$row[2];
                $item->usuario_id=$row[3];
                $this->datagrid->addItem($item);
            }
        TTransaction::close();

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
        $key=$param['key'];
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
