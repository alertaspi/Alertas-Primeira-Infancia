<?php
/**
 * VwImc Active Record
 * @author  <your-name-here>
 */
class VwImc extends TRecord
{
    const TABLENAME = 'scperfil.vw_imc_novo';
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
        parent::addAttribute('nome');
        parent::addAttribute('data_nascimento');
        parent::addAttribute('fator');
        parent::addAttribute('idade_pessoa');
        parent::addAttribute('peso');
        parent::addAttribute('altura');
        parent::addAttribute('baixo_peso');
        parent::addAttribute('adequado');
        parent::addAttribute('sobrepeso');
        parent::addAttribute('tipo_imc');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('mes_ano');
    }


}
