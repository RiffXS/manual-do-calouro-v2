<?php

namespace App\Models;

use \App\Utils\Database;

class Teacher {

    /**
     * ID do professor
     * @var integer
     */
    private $fk_servidor_fk_usuario_id_usuario;

    /**
     * Regras do professor
     * @var $regras
     */
    private $regras;

    /**
     * Método construtor da classe
     * @param integer $id
     * @param string  $regras
     * 
     * @author @SimpleR1ick
     */
    public function __construct($id, $regras) {
        $this->fk_servidor_fk_usuario_id_usuario = $id;
        $this->regras = $regras;
    }

    /**
     * Método responsavel por atualizar as regras de um usuario professor
     * @return boolean
     * 
     * @author @SimpleR1ick
     */
    public function updateRules(): bool {
        $where = "fk_servidor_fk_usuario_id_usuario = {$this->fk_servidor_fk_usuario_id_usuario}";
        
        return (new Database('professor'))->update($where, [
            'regras' => $this->regras
        ]);
    }
}