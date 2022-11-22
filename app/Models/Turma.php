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
}