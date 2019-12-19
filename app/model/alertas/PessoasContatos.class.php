<?php
/**
 * PessoasContatos Active Record
 * @author  <your-name-here>
 */
class PessoasContatos extends TRecord
{
    const TABLENAME = 'scperfil.pessoas_contatos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('tipo');
        parent::addAttribute('conteudo');
        parent::addAttribute('ativo');
        parent::addAttribute('data_info');
    }


}
