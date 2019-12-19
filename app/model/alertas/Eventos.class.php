<?php
/**
 * Eventos Active Record
 * @author  <your-name-here>
 */
class Eventos extends TRecord
{
    const TABLENAME = 'scperfil.eventos';
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
        parent::addAttribute('sistema_id');
        parent::addAttribute('origem_id');
        parent::addAttribute('data_info');
        parent::addAttribute('evento_pai');
    }


}
