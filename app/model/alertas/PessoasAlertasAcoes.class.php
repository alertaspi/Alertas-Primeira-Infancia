<?php
/**
 * PessoasAlertasAcoes Active Record
 * @author  <your-name-here>
 */
class PessoasAlertasAcoes extends TRecord
{
    const TABLENAME = 'scperfil.pessoas_alertas_acoes';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoas_alertas_id');
        parent::addAttribute('usuario_id');
        parent::addAttribute('acao_tomada');
        parent::addAttribute('descricao_acao');
        parent::addAttribute('id_pai');
        parent::addAttribute('data_info');
        parent::addAttribute('status');
        
    }


}
