<?php

namespace App\Models\Entity;

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

    public function __construct($id, $regras) {
        $this->fk_servidor_fk_usuario_id_usuario = $id;
        $this->regras = $regras;
    }

    public function updateRules() {
        $where = "fk_servidor_fk_usuario_id_usuario = {$this->fk_servidor_fk_usuario_id_usuario}";
        
        return (new Database('professor'))->update($where, [
            'regras' => $this->regras
        ]);
    }

}