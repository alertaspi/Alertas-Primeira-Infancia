<?php
/**
 * Pessoas Active Record
 * @author  <your-name-here>
 */
class Pessoas extends TRecord
{
    const TABLENAME = 'scperfil.pessoas';
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
        parent::addAttribute('data_nascimento');
        parent::addAttribute('data_falecimento');
        parent::addAttribute('pai');
        parent::addAttribute('mae');
        parent::addAttribute('rg');
        parent::addAttribute('cpf');
        parent::addAttribute('cns');
        parent::addAttribute('fone');
        parent::addAttribute('email');
        parent::addAttribute('escolaridade');
        parent::addAttribute('profissao');
        parent::addAttribute('cep');
        parent::addAttribute('endereco');
        parent::addAttribute('numero_endereco');
        parent::addAttribute('referencia_endereco');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('uf');
        parent::addAttribute('cidade_nascimneto');
        parent::addAttribute('uf_nascimento');
        parent::addAttribute('nacionalidade');
        parent::addAttribute('tipo_familia_id');
        parent::addAttribute('id_origem');
        parent::addAttribute('data_info');
        parent::addAttribute('latitude');
        parent::addAttribute('longitude');
        parent::addAttribute('id_origem2');
        parent::addAttribute('pis');
    }


}
