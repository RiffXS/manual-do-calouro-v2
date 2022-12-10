<?php

namespace App\Models\Constant;
use App\Utils\Database;

class HorarioAula {

    /**
     * ID SERIAL do horario
     * @var integer
     */
    private $id_horario_aula;

    /**
     * Horario de inicio da aula
     * @var string
     */
    private $hora_aula_inicio;

    /**
     * Horario de fim da aula
     * @var string
     */
    private $hora_aula_fim;

    /**
     * Método responsável por consultar os horários de tempo
     * 
     * @return array
     */
    public static function getTimes(): array {
        return (new Database())->find('horario_aula');
    }
}