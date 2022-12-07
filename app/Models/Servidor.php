<?php

namespace App\Models;

use \App\Utils\Database;

class Servidor {

    /**
     * ID do servidor
     * @var integer
     */
    private $fk_usuario_id_usuario;

    /**
     * Sala do servidor
     * @var integer
     */
    private $fk_sala_id_sala;

    /**
     * Método responsável por cadastrar um usuário como servidor
     * 
     * @return boolean
     */
    public function insertServer() {
        (new Database('servidor'))->insert([
            'fk_usuario_id_usuario' => $this->fk_usuario_id_usuario,
            'fk_sala_id_sala'       => $this->fk_sala_id_sala
        ], false);

        // RETORNA VERDADEIRO
        return true;
    }

    /**
     * Método responsável por atualizar a sala de um servidor
     * 
     * @return boolean
     */
    public function updateRoom(): bool {
        $where = "fk_usuario_id_usuario = {$this->fk_usuario_id_usuario}";
        
        return (new Database('servdiro'))->update($where, [
            'fk_sala_id_sala' => $this->fk_sala_id_sala
        ]);
    }

    /*
     * Métodos GETTERS e SETTERS
     */

    /**
     * Get fk_usuario_id_usuario
     * @return integer
     */
    public function getFk_id_usuario(): int {
        return $this->fk_usuario_id_usuario;
    }

    /**
     * Set fk_usuario_id_usuario
     * @param integer $fk
     */
    public function setFk_id_usuario(int $fk): void {
        $this->fk_usuario_id_usuario = $fk;
    }
    
    /**
     * Get fk_sala_id_sala
     * @return integer
     */
    public function getFk_id_sala(): int {
        return $this->fk_sala_id_sala;
    }

    /**
     * Set fk_sala_id_sala
     * @param integer $room
     */
    public function setFk_id_sala(int $room): void {
        $this->fk_sala_id_sala = $room;
    }

}