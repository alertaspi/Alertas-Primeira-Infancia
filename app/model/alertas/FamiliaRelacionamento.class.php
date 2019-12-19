<?php
/**
 * FamiliaRelacionamento Active Record
 * @author  <your-name-here>
 */
class FamiliaRelacionamento extends TRecord
{
    const TABLENAME = 'scperfil.familia_relacionamento';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id_1');
        parent::addAttribute('tipo_relacao_pessoa_id_1_com_pessoa_id_2');
        parent::addAttribute('pessoa_id_2');
        parent::addAttribute('tipo_relacao_pessoa_id_2_com_pessoa_id_1');
        parent::addAttribute('id_origem');
    }


}
