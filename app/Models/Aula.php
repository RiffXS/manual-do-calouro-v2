<?php

namespace App\Models;

use App\Utils\Database;

class Aula {

    /**
     * ID da aula
     * @var integer
     */
    private $id_aula;

    /**
     * Fk do dia da semana
     * @var integer
     */
    private $fk_dia_semana_id_dia_semana;

    /**
     * Fk do horario da aula
     * @var integer
     */
    private $fk_horario_aula_id_horario_aula;

    /**
     * Fk da sala de aula
     * @var integer
     */
    private $fk_sala_aula_id_sala_aula;

    /**
     * Fk disciplina
     * @var integer
     */
    private $fk_disciplina_id_disciplina;

    /**
     * Fk do professor
     * @var integer
     */
    private $fk_professor_fk_servidor_fk_usuario_id_usuario;

    /**
     * FK do grupo
     * @var mixed
     */
    private $fk_grupo_id_grupo;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     */
    public function insertSchedule(): bool {
        // INSERE A INSTÂNCIA NO BANCO
        $this->setId_aula((new Database('aula'))->insert([
            'fk_dia_semana_id_dia_semana' => $this->fk_dia_semana_id_dia_semana,
            'fk_horario_aula_id_horario_aula' => $this->fk_horario_aula_id_horario_aula,
            'fk_sala_aula_id_sala_aula' => $this->fk_sala_aula_id_sala_aula,
            'fk_disciplina_id_disciplina' => $this->fk_disciplina_id_disciplina,
            'fk_professor_fk_servidor_fk_usuario_id_usuario' => $this->fk_professor_fk_servidor_fk_usuario_id_usuario,
            'fk_grupo_id_grupo' => $this->fk_grupo_id_grupo,
            
        ]));
        // SUCESSO
        return true;
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     */
    public function updateSchedule(): bool {
        return (new Database('aula'))->update("id_aula = {$this->id_aula}", [
            'fk_dia_semana_id_dia_semana' => $this->fk_dia_semana_id_dia_semana,
            'fk_horario_aula_id_horario_aula' => $this->fk_horario_aula_id_horario_aula,
            'fk_sala_aula_id_sala_aula' => $this->fk_sala_aula_id_sala_aula,
            'fk_disciplina_id_disciplina' => $this->fk_disciplina_id_disciplina,
            'fk_professor_fk_servidor_fk_usuario_id_usuario' => $this->fk_professor_fk_servidor_fk_usuario_id_usuario,
            'fk_grupo_id_grupo' => $this->fk_grupo_id_grupo,
        ]);
    }

    /**
     * Método responsável por excluir uma aula do banco
     * @return boolean
     */
    public function deleteSchedule(): bool {
        return (new Database('aula'))->delete("id_aula = {$this->id_aula}");
    }

    /**
     * Método responsável por retornar todos os horários de uma turma
     * @param integer $curso
     * @param integer $modulo
     * 
     * @return array
     */
    public static function getScheduleClass(int $curso, int $modulo): array {
        // Seleciona todas as aulas, mesmo aquelas que não existem, e as coloca em um array
        $sql = "SELECT
                    *
                FROM
                (
                    (
                    SELECT
                        fk_dia_semana_id_dia_semana AS id_dia_semana,
                        fk_horario_aula_id_horario_aula AS id_horario_aula,
                        g.fk_turma_id_turma AS id_turma,
                        g.dsc_grupo AS grupo,
                        ha.hora_aula_inicio AS hora_aula_inicio,
                        ha.hora_aula_fim AS hora_aula_fim,
                        s.dsc_sala_aula AS sala,
                        d.dsc_disciplina AS materia,
                        u.nom_usuario AS professor
                    FROM aula au
                        JOIN sala_aula s ON (au.fk_sala_aula_id_sala_aula = s.id_sala_aula)
                        JOIN disciplina d ON (au.fk_disciplina_id_disciplina = d.id_disciplina)
                        LEFT JOIN horario_aula ha ON (ha.id_horario_aula = au.fk_horario_aula_id_horario_aula)
                        JOIN grupo g ON (au.fk_grupo_id_grupo = g.id_grupo)
                        JOIN turma t ON (g.fk_turma_id_turma = t.id_turma)
                        JOIN professor p ON (au.fk_professor_fk_servidor_fk_usuario_id_usuario = fk_servidor_fk_usuario_id_usuario)
                        JOIN servidor se ON (p.fk_servidor_fk_usuario_id_usuario = fk_usuario_id_usuario)
                        JOIN usuario u ON (se.fk_usuario_id_usuario = u.id_usuario)
                    WHERE au.fk_horario_aula_id_horario_aula IN (1, 2, 3, 4, 5, 6) 
                        AND au.fk_dia_semana_id_dia_semana IN (2, 3, 4, 5, 6, 7)
                        AND t.fk_curso_id_curso = $curso
                        AND t.num_modulo = $modulo
                    )
                    
                    UNION
                    
                    (
                    SELECT
                        id_dia_semana,
                        table3.id_horario_aula,
                        table3.id_turma,
                        dsc_grupo,
                        hora_aula_inicio,
                        hora_aula_fim,
                        dsc_sala_aula,
                        dsc_disciplina,
                        nom_usuario
                    FROM 
                    (
                        SELECT
                        *,
                        'C' AS dsc_grupo,
                        '-' AS dsc_sala_aula,
                        '-' AS dsc_disciplina,
                        '-' AS nom_usuario
                        FROM
                        (
                        SELECT
                            *
                        FROM
                        (
                            SELECT
                            id_dia_semana,
                            id_horario_aula,
                            id_turma
                            FROM
                            dia_semana,
                            horario_aula,
                            turma
                            WHERE turma.fk_curso_id_curso = $curso
                            AND turma.num_modulo = $modulo
                            except
                            SELECT
                            fk_dia_semana_id_dia_semana AS id_dia_semana,
                            fk_horario_aula_id_horario_aula AS id_horario_aula,
                            fk_turma_id_turma
                            FROM aula au
                            JOIN grupo g ON (au.fk_grupo_id_grupo = g.id_grupo)
                            WHERE fk_dia_semana_id_dia_semana IN (2, 3, 4, 5, 6, 7)
                            ORDER BY id_dia_semana
                        ) AS table1
                        WHERE table1.id_dia_semana IN (2, 3, 4, 5, 6, 7)
                        ) AS table2
                    ) AS table3
                    INNER JOIN horario_aula ha ON (ha.id_horario_aula = table3.id_horario_aula)
                    JOIN turma t ON (t.id_turma = table3.id_turma)
                    )
                ) AS COMPLETE_TABLE
                ORDER BY id_horario_aula, id_dia_semana, grupo";

        // RETORNA OS DEPOIMENTOS
        return (new Database('aula'))->execute($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Método responsável por consultar todas as aulas com a descrição dos items
     * @param string  $order 
     * @param integer $limit 
     * 
     * @return \PDOStatement
     */
    public static function getDscSchedules(string $order, int $limit): \PDOStatement {
        $sql = "SELECT id_aula,
                    dsc_dia_semana,
                    hora_aula_inicio,
                    dsc_sala_aula,
                    dsc_disciplina,
                    nom_usuario
                FROM aula au
                    JOIN dia_semana ds ON (
                        au.fk_dia_semana_id_dia_semana = ds.id_dia_semana
                    )
                    JOIN horario_aula ha ON (
                        au.fk_horario_aula_id_horario_aula = ha.id_horario_aula
                    )
                    JOIN sala_aula sa ON (au.fk_sala_aula_id_sala_aula = sa.id_sala_aula)
                    JOIN disciplina d ON (au.fk_disciplina_id_disciplina = d.id_disciplina)
                    JOIN professor p ON (
                        au.fk_professor_fk_servidor_fk_usuario_id_usuario = p.fk_servidor_fk_usuario_id_usuario
                    )
                    JOIN servidor s ON (
                        p.fk_servidor_fk_usuario_id_usuario = s.fk_usuario_id_usuario
                    )
                    JOIN usuario u ON (s.fk_usuario_id_usuario = u.id_usuario) ORDER BY $order LIMIT $limit";

        return (new Database)->execute($sql);
    }

    /**
     * Método responsável por retornar aula
     * @param  string $where
     * @param  string $order
     * @param  string $limit
     * @param  string $fields
     * 
     * @return mixed
     */
    public static function getSchedules($where = null, $order = null, $limit = null, $fields = '*'): mixed {
        return (new Database('aula'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsavel por retornar os dados de uma aula pelo ID
     * @param int $id
     * 
     * @return self|bool
     */
    public static function getScheduleById(int $id): mixed {
        return self::getSchedules("id_aula = $id")->fetchObject(self::class);
    }

    /*
     * Métodos GETTERS e SETTERS
     */

    /**
     * Get id_aula
     * @return integer
     */
    public function getId_aula(): int {
        return $this->id_aula;
    }

    /**
     * Set id_aula
     * @param integer $id
     */
    private function setId_aula(int $id): void {
        $this->id_aula = $id;
    }

    /**
     * Get fk_dia_semana_id_dia_semana
     * @return integer
     */
    public function getFk_dia_semana(): int {
        return $this->fk_dia_semana_id_dia_semana;
    }

    /**
     * Set fk_dia_semana_id_dia_semana
     * @param integer $fk
     */
    public function setFk_dia_semana(int $fk): void {
        $this->fk_dia_semana_id_dia_semana = $fk;
    }

    /**
     * Get fk_horario_aula_id_horario_aula
     * @return integer
     */
    public function getFk_horario_aula(): int {
        return $this->fk_horario_aula_id_horario_aula;
    }   

    /**
     * Set fk_horario_aula_id_horario_aula
     * @param integer $fk
     */
    public function setFk_horario_aula(int $fk): void {
        $this->fk_horario_aula_id_horario_aula = $fk;
    }

    /**
     * Get fk_sala_aula_id_sala_aula
     * @return integer
     */
    public function getFk_sala_aula(): int {
        return $this->fk_sala_aula_id_sala_aula;
    }

    /**
     * Set fk_sala_aula_id_sala_aula
     * @param integer $fk
     */
    public function setFk_sala_aula(int $fk): void {
        $this->fk_sala_aula_id_sala_aula = $fk;
    }
    
    /**
     * Get fk_disciplina_id_disciplina
     * @return integer
     */
    public function getFk_disciplina(): int {
        return $this->fk_disciplina_id_disciplina;
    }

    /**
     * Set fk_disciplina_id_disciplina
     * @param integer $fk
     */
    public function setFk_disciplina(int $fk): void {
        $this->fk_disciplina_id_disciplina = $fk;
    }

    /**
     * Get fk_professor_fk_servidor_fk_usuario_id_usuario
     * @return integer
     */
    public function getFk_professor(): int {
        return $this->fk_professor_fk_servidor_fk_usuario_id_usuario;
    }

    /**
     * Set fk_professor_fk_servidor_fk_usuario_id_usuario
     * @param integer $fk
     */
    public function setFk_professor(int $fk): void {
        $this->fk_professor_fk_servidor_fk_usuario_id_usuario = $fk;
    }

    /**
     * Get fk_grupo_id_grupo
     * @return int
     */
    public function getFk_grupo(): int {
        return $this->fk_grupo_id_grupo;
    }

    /**
     * Set fk_grupo_id_grupo
     * @param integer $fk
     */
    public function setFk_grupo(int $fk): void {
        $this->fk_grupo_id_grupo = $fk;
    }
} 