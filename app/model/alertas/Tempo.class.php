<?php
/**
 * Tempo Active Record
 * @author  <your-name-here>
 */
class Tempo extends TRecord
{
    const TABLENAME = 'scperfil.tempo';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('datainfo');
        parent::addAttribute('dia');
        parent::addAttribute('mes');
        parent::addAttribute('mes_desc');
        parent::addAttribute('ano');
        parent::addAttribute('bimestre');
        parent::addAttribute('trimestre');
        parent::addAttribute('quadrimestre');
        parent::addAttribute('semestre');
        parent::addAttribute('dia_da_semana');
    }


}
