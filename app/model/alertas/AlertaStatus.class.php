<?php
/**
 * AlertaStatus Active Record
 * @author  <your-name-here>
 */
class AlertaStatus extends TRecord
{
    const TABLENAME = 'scperfil.alerta_status';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
    }


}
