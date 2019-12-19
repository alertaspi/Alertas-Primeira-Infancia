<?php
/**
 * Fatos Active Record
 * @author  <your-name-here>
 */
class Fatos extends TRecord
{
    const TABLENAME = 'scperfil.fatos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tempo_id');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('sistema_id');
        parent::addAttribute('evento_id');
    }


}
