<?php
/**
 * RegistroDasImportacoesEvento Active Record
 * @author  <your-name-here>
 */
class RegistroDasImportacoesEvento extends TRecord
{
    const TABLENAME = 'scperfil.registro_das_importacoes_evento';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_sistema');
        parent::addAttribute('id_evento');
        parent::addAttribute('data_import');
        parent::addAttribute('id_usuario');
    }


}
