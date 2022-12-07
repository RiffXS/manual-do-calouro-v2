<?php

namespace App\Models;

use \App\Utils\Database;

class Aluno {

    /**
     * ID do aluno
     * @var integer
     */
    private $fk_usuario_id_usuario;

    /**
     * Matrícula do aluno
     * @var string
     */
    private $num_matricula;
    
    /**
     * Método responsável por cadastrar um usuário como aluno
     * @return boolean
     */
    public function insertStudent() {
        (new Database('aluno'))->insert([
            'fk_usuario_id_usuario' => $this->fk_usuario_id_usuario,
            'num_matricula'         => $this->num_matricula
        ], false);

        // RETORNA VERDADEIRO
        return true;
    }

    /**
     * Método reponsável por verificar se a matrícula já está cadastrada
     * @return boolean
     */
    public function verifyEnrollment() {
        $result = (new Database('aluno'))->select("num_matricula = '{$this->num_matricula}'");
        
        // VERIFICA SE HOUVE RESULTADO
        if ($result->rowCount() > 0) {
            // MATRÍCULA JÁ UTILIZADA
            return false;
        }
        // MATRÍCULA DISPONÍVEL
        return true;
    }
    
    /*
     * Métodos GETTERS E SETTERS
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
     * Get num_matricula
     * @return string
     */
    public function getNum_matricula(): string {
        return $this->num_matricula;
    }

    /**
     * Set num_matricula
     * @param string $value
     */
    public function setNum_matricula(string $value): void {
        $this->num_matricula = $value;
    }
}