<?php
/**
 * PessoasAlertas Active Record
 * @author  <your-name-here>
 */
class PessoasAlertas extends TRecord
{
    const TABLENAME = 'scperfil.pessoas_alertas';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('sistema_id');
        parent::addAttribute('evento_id');
        parent::addAttribute('usuario_id');
        parent::addAttribute('data_info');
        parent::addAttribute('observacao');
        parent::addAttribute('status');
        parent::addAttribute('tipo');
    }


}
