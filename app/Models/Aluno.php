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
     * ID da turma do aluno
     * @var integer
     */
    private $fk_turma_id_turma;
    
    /**
     * Método responsável por cadastrar um usuário como aluno
     * @return boolean
     */
    public function insertStudent() {
        (new Database('aluno'))->insert([
            'fk_usuario_id_usuario' => $this->fk_usuario_id_usuario,
            'num_matricula'         => $this->num_matricula
        ]);

        // RETORNA VERDADEIRO
        return true;
    }

    /**
     * Método responsável por atualizar a turma do aluno
     *
     */
    public function updateStudent() {
        return (new Database('aluno'))->update("fk_usuario_id_usuario = {$this->fk_usuario_id_usuario}", [
            'fk_turma_id_turma' => $this->fk_turma_id_turma
        ]);
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
     * Metodos GETTERS E SETTERS
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

    /**
     * Get fk_turma_id_turma
     * @return integer
     */
    public function getFk_id_turma(): int {
        return $this->fk_turma_id_turma;
    }

    /**
     * Set fk_turma_id_turma
     * @param integer $fk
     */
    public function setFk_id_turma(int $fk): void {
        $this->fk_turma_id_turma = $fk;
    }
}