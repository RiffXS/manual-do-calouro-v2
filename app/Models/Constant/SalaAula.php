<?php

namespace App\Models\Constant;
use App\Utils\Database;

class SalaAula {

    private $id_sala_aula;

    private $dsc_dia_semana;

    /**
     * Método responsavel por consultar as aulas por uma sala
     * @param string $sala
     * 
     * @return array
     */
    public static function getOccupiedClass(string $sala): array {
        $sql = "SELECT sa.dsc_sala_aula AS sala,
                    ds.dsc_dia_semana AS dia,
                    ha.hora_aula_inicio AS hora_inicio,
                    ha.hora_aula_fim AS hora_fim
                FROM sala_aula sa
                    JOIN aula a ON (sa.id_sala_aula = a.fk_sala_aula_id_sala_aula)
                    JOIN horario_aula ha ON (
                        a.fk_horario_aula_id_horario_aula = ha.id_horario_aula
                    )
                    JOIN dia_semana ds ON (a.fk_dia_semana_id_dia_semana = ds.id_dia_semana)
                WHERE dsc_sala_aula LIKE UPPER('%$sala%')";
        
        return (new Database())->execute($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}