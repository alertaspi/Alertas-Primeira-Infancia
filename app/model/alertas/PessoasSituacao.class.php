<?php
/**
 * PessoasSituacao Active Record
 * @author  <your-name-here>
 */
class PessoasSituacao extends TRecord
{
    const TABLENAME = 'scperfil.pessoas_situacao';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    private $pessoas_situacao_tipo;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('evento_id');
        parent::addAttribute('situacao_id');
        parent::addAttribute('obs_situacao');
        parent::addAttribute('usuario_id');
        parent::addAttribute('ativo');
        parent::addAttribute('data_info');
    }
    
    /*
    public function get_pessoas_situacao_tipo()
    {
        // loads the associated object
        if (empty($this->pessoas_situacao_tipo))
            $this->pessoas_situacao_tipo = new PessoasSituacaoTipo($this->pessoas_situacao_tipo_id);
    
        // returns the associated object
        return $this->pessoas_situacao_tipo;
    }
*/

}
