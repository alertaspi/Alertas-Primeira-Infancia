<?php
/**
 * ImportDados Active Record
 * @author  <your-name-here>
 */
class ImportDados extends TRecord
{
    const TABLENAME = 'scperfil.import_dados';
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
        parent::addAttribute('data_importacao');
        parent::addAttribute('nome');
        parent::addAttribute('cns');
        parent::addAttribute('cpf');
        parent::addAttribute('pis');
        parent::addAttribute('data_nascimento');
        parent::addAttribute('mae');
        parent::addAttribute('id_pessoa_origem');
        parent::addAttribute('data_evento');
        parent::addAttribute('competencia');
        parent::addAttribute('id_do_evento_na_origem');
        parent::addAttribute('status');
        parent::addAttribute('gestacao');
        parent::addAttribute('peso');
        parent::addAttribute('altura');
        parent::addAttribute('dum');
        parent::addAttribute('dpp');
        parent::addAttribute('semana_gestacional');
        parent::addAttribute('local_nome');
        parent::addAttribute('profissional');
        parent::addAttribute('foi_processado');
    }


}
