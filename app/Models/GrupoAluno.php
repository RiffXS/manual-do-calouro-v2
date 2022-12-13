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
    private $fk_grupo_id_grupo = 0;

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
     * @return bool
     */
    public function updateGroupStudent(): bool {
        return (new Database('grupo_aluno'))->update("fk_aluno_fk_usuario_id_usuario = {$this->fk_aluno_fk_usuario_id_usuario}", [
            'fk_grupo_id_grupo' => $this->fk_grupo_id_grupo
        ]);
    }

    /**
     * Método responsavel por definir a fk_grupo do objeto
     * @param integer $curso
     * @param integer $modulo
     * 
     * @return void
     */
    public function findGroup(int $curso, int $modulo): void {
        // PARAMETROS SQL
        $where = "t.fk_curso_id_curso = $curso AND t.num_modulo = $modulo AND g.dsc_grupo = 'C'";
        $table = "grupo g JOIN turma t ON (g.fk_turma_id_turma = t.id_turma)";

        // CONSULTA O GRUPO
        $grupo = (new Database($table))->select($where, null, null, 'id_grupo')->fetch(\PDO::FETCH_ASSOC);

        // SET DO ATRIBUTO
        $this->fk_grupo_id_grupo = $grupo['id_grupo'];
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
    public function getFk_id_grupo(): int {
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