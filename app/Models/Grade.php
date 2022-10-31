<?php

namespace App\Models;

use \App\Utils\Database;

class Grade {

    /**
     * Método responsável por 
     * @return array
     */
    public static function getGradeId($curso, $modulo) {
        $where = "num_modulo = $modulo AND fk_curso_id_curso = $curso";

        return (new Database('turma'))->select($where, null, null, 'id_turma')->fetch(\PDO::FETCH_ASSOC);
    }
}