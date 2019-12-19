<?php
/**
 * Entidades Active Record
 * @author  <your-name-here>
 */
class Entidades extends TRecord
{
    const TABLENAME = 'scperfil.entidades';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('id_origem');
    }


}
