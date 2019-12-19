<?php
/**
 * PessoasSituacaoTipo Active Record
 * @author  <your-name-here>
 */
class PessoasSituacaoTipo extends TRecord
{
    const TABLENAME = 'scperfil.pessoas_situacao_tipo';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('sistema_id');
    }


}
