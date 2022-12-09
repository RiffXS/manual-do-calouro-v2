<?php

namespace App\Models\Constant;

use App\Utils\Database;

class Curso {

    /**
     * SERIAL ID do curso
     * @var integer
     */
    private $id_curso;

    /**
     * Nome do curso
     * @var string
     */
    private $dsc_curso;

    /**
     * Método responsável por obter o curso de um usuário
     * @param integer $id
     * 
     * @return array
     */
    public static function getCursoById(int $id): array {
        // RETORNA O NOME DO CURSO
        return (new Database('curso'))->select("id_curso = $id", null, null, 'sigla_curso')->fetch(\PDO::FETCH_ASSOC);
    }
}