<?php
/**
 * VwGravidezAdolecenciaAlerta Active Record
 * @author  <your-name-here>
 */
class VwGravidezAdolecenciaAlerta extends TRecord
{
    const TABLENAME = 'scperfil.vw_gravidez_adolecencia_alerta';
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
        parent::addAttribute('idade');
        parent::addAttribute('descricao');
        parent::addAttribute('qtd');
    }


}
