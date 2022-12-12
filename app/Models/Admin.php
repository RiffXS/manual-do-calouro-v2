<?php

namespace App\Models;

use \App\Utils\Database;

class Admin {

    /**
     * ID do administrativo
     * @var integer
     */
    private $fk_servidor_fk_usuario_id_usuario;

    /**
     * Setor do administrativo
     * @var integer
     */
    private $fk_setor_id_setor;

    /**
     * Método responsável por cadastrar um usuário como servidor
     * @return boolean
     */
    public function insertAdmin(): bool {
        (new Database('administrativo'))->insert([
            'fk_servidor_fk_usuario_id_usuario' => $this->fk_servidor_fk_usuario_id_usuario,
            'fk_setor_id_setor'                 => $this->fk_setor_id_setor
        ], false);

        // RETORNA VERDADEIRO
        return true;
    }

    /**
     * Método responsável por atualizar o setor do servidor
     * 
     * @return bool
     */
    public function updateAdmin(): bool {
        return (new Database('administrativo'))->update("fk_servidor_fk_usuario_id_usuario = {$this->fk_servidor_fk_usuario_id_usuario}", [
            'fk_setor_id_setor' => $this->fk_setor_id_setor
        ]);
    }

    /*
     * Métodos GETTERS e SETTERS
     */

    /**
     * Get fk_servidor_fk_usuario_id_usuario
     * @return integer
     */
    public function getFk_id_usuario(): int {
        return $this->fk_servidor_fk_usuario_id_usuario;
    }

    /**
     * Set fk_servidor_fk_usuario_id_usuario
     * @param integer $fk
     */
    public function setFk_id_usuario(int $fk): void {
        $this->fk_servidor_fk_usuario_id_usuario = $fk;
    }
    
    /**
     * Get fk_setor_id_setor
     * @return integer
     */
    public function getFk_id_setor(): int {
        return $this->fk_setor_id_setor;
    }

    /**
     * Set fk_setor_id_setor
     * @param integer $setor
     */
    public function setFk_id_setor(int $setor): void {
        $this->fk_setor_id_setor = $setor;
    }

}