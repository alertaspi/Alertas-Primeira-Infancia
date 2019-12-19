<?php
/**
 * Sistemas Active Record
 * @author  <your-name-here>
 */
class Sistemas extends TRecord
{
    const TABLENAME = 'scperfil.sistemas';
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
        parent::addAttribute('data_info');
        parent::addAttribute('entidade_id');
        parent::addAttribute('icon_html');
        parent::addAttribute('url');
        parent::addAttribute('img');
    }


}