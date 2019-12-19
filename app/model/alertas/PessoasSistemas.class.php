<?php
/**
 * PessoasSistemas Active Record
 * @author  <your-name-here>
 */
class PessoasSistemas extends TRecord
{
    const TABLENAME = 'scperfil.pessoas_sistemas';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('sistema_id');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('id_origem_pessoa');
        parent::addAttribute('data_info');
    }


}
