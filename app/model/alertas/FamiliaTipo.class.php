<?php
/**
 * FamiliaTipo Active Record
 * @author  <your-name-here>
 */
class FamiliaTipo extends TRecord
{
    const TABLENAME = 'scperfil.familia_tipo';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('id_origem');
    }


}
