<?php

namespace App\Models;

use \App\Utils\Database;

class GrupoAluno {

    /**
     * FK da tabela usuario
     * @var integer
     */
    private $fk_aluno_fk_usuario_id_usuario;

    /**
     * FK da tabela grupo
     * @var integer
     */
    private $fk_grupo_id_grupo;

    /**
     * Método responsável por inserir o aluno no grupo padrão
     * 
     * @return \PDOStatement|bool
     */
    public function insertGroupStudent(): mixed {
        return (new Database('grupo_aluno'))->insert([
            'fk_aluno_fk_usuario_id_usuario' => $this->fk_aluno_fk_usuario_id_usuario,
            'fk_grupo_id_grupo'              => $this->fk_grupo_id_grupo
        ], false);
    }

    /**
     * Método responsável por atualizar a turma do aluno
     * 
     * @return \PDOStatement|bool
     */
    public function updateGroupStudent(): mixed {
        return (new Database('grupo_aluno'))->update("fk_aluno_fk_usuario_id_usuario = {$this->fk_aluno_fk_usuario_id_usuario}", [
            'fk_grupo_id_grupo' => $this->fk_grupo_id_grupo
        ]);
    }

    /*
     * Métodos GETTERS E SETTERS
     */

    /**
     * Get fk_aluno_fk_usuario_id_usuario
     * @return integer
     */
    public function getFk_id_usuario(): int {
        return $this->fk_aluno_fk_usuario_id_usuario;
    }

    /**
     * Set fk_aluno_fk_usuario_id_usuario
     * @param integer $fk
     */
    public function setFk_id_usuario(int $fk): void {
        $this->fk_aluno_fk_usuario_id_usuario = $fk;
    }

    /**
     * Get fk_grupo_id_grupo
     * @return integer
     */
    public function getFk_id_grupo(): string {
        return $this->fk_grupo_id_grupo;
    }

    /**
     * Set fk_grupo_id_grupo
     * @param integer $value
     */
    public function setFk_id_grupo(string $value): void {
        $this->fk_grupo_id_grupo = $value;
    }

}