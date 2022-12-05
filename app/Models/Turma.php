<?php

namespace App\Models;

use \App\Utils\Database;

class Turma {

    /**
     * Método responsável por id da turma 
     * @return array
     * 
     * @author @SimpleR1ick @RiffXS
     */
    public static function getGradeId(int $curso, int $modulo): array {
        $where = "num_modulo = $modulo AND fk_curso_id_curso = $curso";

        return (new Database('turma'))->select($where, null, null, 'id_turma')->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Método responsável por retornar as descrições dos cursos
     * @return array
     * 
     * @author @RiffXS
     */
    public static function getCursos() {
        return (new Database('curso'))->select()->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getGroupByClass($curso, $modulo) {
        $table  = "grupo g JOIN turma t ON (g.fk_turma_id_turma = t.id_turma)";
        $where  = "t.fk_curso_id_curso = $curso AND t.num_modulo = $modulo";
        $fields = "id_grupo, dsc_grupo";

        return (new Database($table))->select($where, null, null, $fields)->fetchAll(\PDO::FETCH_ASSOC);
    }

}