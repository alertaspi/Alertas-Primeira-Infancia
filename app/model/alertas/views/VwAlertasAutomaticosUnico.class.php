<?php
/**
 * VwAlertasAutomaticosUnico Active Record
 * @author  <your-name-here>
 */
class VwAlertasAutomaticosUnico extends TRecord
{
    const TABLENAME = 'scperfil.vw_alertas_automaticos_unico';
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
        parent::addAttribute('usuario_id');
        parent::addAttribute('observacao');
        parent::addAttribute('status');
        parent::addAttribute('tipo');
        parent::addAttribute('data_info');
    }


}
