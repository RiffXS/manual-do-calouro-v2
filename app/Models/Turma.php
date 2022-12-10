<?php

namespace App\Models;

use \App\Utils\Database;

class Turma {

    /**
     * Método responsável por id da turma 
     * 
     * @return array
     */
    public static function getGradeId(int $curso, int $modulo): array {
        $table = "grupo g JOIN turma t ON (g.fk_turma_id_turma = t.id_turma)";
        $where = "g.dsc_grupo = 'C' AND t.fk_curso_id_curso = $curso AND t.num_modulo = $modulo";

        return (new Database($table))->select($where, null, null, 'id_grupo')->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Método responsável por retornar as descrições dos cursos
     * 
     * @return array
     */
    public static function getCursos() {
        return (new Database('curso'))->select()->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Método responsável por retornar os grupos dependendo da turma do usuário
     * @param integer $curso
     * @param integer $modulo
     * 
     * @return array
     */
    public static function getGroupsByClass($curso, $modulo): array {
        $table  = "grupo g JOIN turma t ON (g.fk_turma_id_turma = t.id_turma)";
        $where  = "g.dsc_grupo IN ('A', 'B') AND t.fk_curso_id_curso = $curso AND t.num_modulo = $modulo";
        $fields = "id_grupo, dsc_grupo";

        return (new Database($table))->select($where, null, null, $fields)->fetchAll(\PDO::FETCH_ASSOC);
    }

}