<?php

namespace App\Models;

use \App\Utils\Database;

class Setor {

    /**
     * Método responsável por retornar todos os setores
     *
     * @return array
     */
    public static function getSector(): array {
        return (new Database('setor'))->select()->fetchAll(\PDO::FETCH_ASSOC);
    }

}