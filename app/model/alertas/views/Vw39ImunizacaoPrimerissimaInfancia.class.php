<?php
/**
 * Vw39ImunizacaoPrimerissimaInfancia Active Record
 * @author  <your-name-here>
 */
class Vw39ImunizacaoPrimerissimaInfancia extends TRecord
{
    const TABLENAME = 'scperfil.vw_39_imunizacao_primerissima_infancia';
    const PRIMARYKEY= 'pessoa_id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
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
        parent::addAttribute('vacina');
        parent::addAttribute('qtd');
    }


}
