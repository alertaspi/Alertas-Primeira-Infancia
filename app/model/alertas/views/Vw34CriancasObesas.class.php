<?php
/**
 * Vw34CriancasObesas Active Record
 * @author  <your-name-here>
 */
class Vw34CriancasObesas extends TRecord
{
    const TABLENAME = 'scperfil.vw_34_criancas_obesas';
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
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('mes_ano');
        parent::addAttribute('nome');
        parent::addAttribute('idade');
        parent::addAttribute('peso');
        parent::addAttribute('altura');
        parent::addAttribute('imcv');
        parent::addAttribute('baixo_peso');
        parent::addAttribute('adequado');
        parent::addAttribute('sobrepeso');
        parent::addAttribute('tipo_imc');
    }


}
