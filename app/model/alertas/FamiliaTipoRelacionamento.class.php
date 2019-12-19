<?php
/**
 * FamiliaTipoRelacionamento Active Record
 * @author  <your-name-here>
 */
class FamiliaTipoRelacionamento extends TRecord
{
    const TABLENAME = 'scperfil.familia_tipo_relacionamento';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('sexo');
        parent::addAttribute('relacao_id_sexo_f');
        parent::addAttribute('relacao_id_sexo_m');
    }


}
