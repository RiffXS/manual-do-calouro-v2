<?php

namespace App\Models\Entity;

use App\Utils\Database;

class Schedule {

    /**
     * 
     * @var string
     */
    public $sala;

    /**
     * 
     * @var string
     */
    public $materia;

    /**
     * 
     * @var string
     */
    public $professor;

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $hora_inicio;

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $hora_fim;

    /**
     * Metodo responsavel por retornar todos os horarios de uma turma
     * @param integer $curso
     * @param integer $modulo
     */
    public static function getSchedules($curso, $modulo) {
        // Seleciona todas as aulas, mesmo aquelas que não existem, e as coloca em um array
        $sql = "SELECT * FROM (
            (SELECT fk_dia_semana_id_dia_semana AS id_dia_semana,
            fk_horario_aula_id_horario_aula AS id_horario_aula,
            fk_turma_id_turma AS id_turma,
            ha.hora_aula_inicio as hora_inicio,
            ha.hora_aula_fim as hora_fim,
            s.num_sala_aula AS sala,
            d.dsc_disciplina AS materia,
            u.nom_usuario AS professor
            FROM aula au
            JOIN sala_aula s ON (au.fk_sala_aula_id_sala_aula = s.id_sala_aula)
            JOIN professor_disciplina pd ON (au.fk_professor_disciplina_id_professor_disciplina = pd.id_professor_disciplina)
            LEFT JOIN disciplina d ON (d.id_disciplina = pd.fk_disciplina_id_disciplina)
            LEFT JOIN professor p ON (pd.fk_professor_fk_servidor_fk_usuario_id_usuario = p.fk_servidor_fk_usuario_id_usuario)
            LEFT JOIN servidor se ON (p.fk_servidor_fk_usuario_id_usuario = se.fk_usuario_id_usuario)
            LEFT JOIN usuario u ON (se.fk_usuario_id_usuario = u.id_usuario)
            JOIN turma t ON (au.fk_turma_id_turma = t.id_turma)
            LEFT JOIN horario_aula ha ON (ha.id_horario_aula = au.fk_horario_aula_id_horario_aula)
            WHERE au.fk_dia_semana_id_dia_semana IN (2, 3, 4, 5, 6, 7)
            AND t.fk_curso_id_curso = $curso
            AND t.num_modulo = $modulo)

            UNION

            (SELECT
            id_dia_semana,
            table3.id_horario_aula,
            table3.id_turma,
            hora_aula_inicio,
            hora_aula_fim,
            num_sala_aula,
            dsc_disciplina,
            nom_usuario
            FROM (SELECT * , '-' AS num_sala_aula, '-' AS dsc_disciplina, '-' AS nom_usuario FROM (SELECT * FROM
            (SELECT id_dia_semana, id_horario_aula, id_turma FROM dia_semana, horario_aula, turma
            WHERE turma.fk_curso_id_curso = $curso
            AND turma.num_modulo = $modulo
            except
            SELECT fk_dia_semana_id_dia_semana AS id_dia_semana, fk_horario_aula_id_horario_aula AS id_horario_aula, fk_turma_id_turma FROM aula ORDER BY id_dia_semana)
            AS table1
            WHERE table1.id_dia_semana IN (2, 3, 4, 5, 6, 7)) AS table2) AS table3
            INNER JOIN horario_aula ha ON (ha.id_horario_aula = table3.id_horario_aula)
            JOIN turma t ON (t.id_turma = table3.id_turma)))
            AS COMPLETE_TABLE
            ORDER BY id_horario_aula, id_dia_semana";

       
        // RETORNA OS DEPOIMENTOS
        return (new Database('aula'))->selectBySql($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}