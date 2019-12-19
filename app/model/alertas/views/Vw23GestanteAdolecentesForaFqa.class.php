<?php
/**
 * Vw23GestanteAdolecentesForaFqa Active Record
 * @author  <your-name-here>
 */
class Vw23GestanteAdolecentesForaFqa extends TRecord
{
    const TABLENAME = 'scperfil.vw_23_gestante_adolecentes_fora_fqa';
    const PRIMARYKEY= 'pessoa_id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('sistema_id');
        parent::addAttribute('evento_id');
        parent::addAttribute('tempo_id');
        parent::addAttribute('ano');
        parent::addAttribute('mes_desc');
        parent::addAttribute('nome');
        parent::addAttribute('data_nascimento');
        parent::addAttribute('mae');
        parent::addAttribute('cns');
        parent::addAttribute('descricao');
        parent::addAttribute('idade');
        parent::addAttribute('qtd');
    }


}
