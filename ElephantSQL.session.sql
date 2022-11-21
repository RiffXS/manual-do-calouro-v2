SELECT *
FROM (
        (
            SELECT fk_dia_semana_id_dia_semana AS id_dia_semana,
                fk_horario_aula_id_horario_aula AS id_horario_aula,
                g.fk_turma_id_turma AS id_turma,
                g.dsc_grupo AS grupo,
                ha.hora_aula_inicio AS hora_aula_inicio,
                ha.hora_aula_fim AS hora_aula_fim,
                s.num_sala_aula AS sala,
                d.dsc_disciplina AS materia,
                u.nom_usuario AS professor
            FROM aula au
                JOIN sala_aula s ON (au.fk_sala_aula_id_sala_aula = s.id_sala_aula)
                JOIN disciplina d ON (au.fk_disciplina_id_disciplina = d.id_disciplina)
                LEFT JOIN horario_aula ha ON (
                    ha.id_horario_aula = au.fk_horario_aula_id_horario_aula
                )
                JOIN grupo_aula ga ON (au.id_aula = ga.fk_aula_id_aula)
                JOIN grupo g ON (ga.fk_grupo_id_grupo = g.id_grupo)
                JOIN turma t ON (g.fk_turma_id_turma = t.id_turma)
                JOIN professor p ON (
                    au.fk_professor_fk_servidor_fk_usuario_id_usuario = fk_servidor_fk_usuario_id_usuario
                )
                JOIN servidor se ON (
                    p.fk_servidor_fk_usuario_id_usuario = fk_usuario_id_usuario
                )
                JOIN usuario u ON (se.fk_usuario_id_usuario = u.id_usuario)
            WHERE au.fk_horario_aula_id_horario_aula in (1, 2, 3, 4, 5, 6)
                AND au.fk_dia_semana_id_dia_semana in (2, 3, 4, 5, 6, 7)
                AND g.dsc_grupo = 'C'
                AND t.fk_curso_id_curso = 1
                AND t.num_modulo = 6
        )
        UNION
        (
            SELECT id_dia_semana,
                table3.id_horario_aula,
                table3.id_turma,
                dsc_grupo,
                hora_aula_inicio,
                hora_aula_fim,
                num_sala_aula,
                dsc_disciplina,
                nom_usuario
            FROM (
                    SELECT *,
                        'C' AS dsc_grupo,
                        '-' AS num_sala_aula,
                        '-' AS dsc_disciplina,
                        '-' AS nom_usuario
                    FROM (
                            SELECT *
                            FROM (
                                    SELECT id_dia_semana,
                                        id_horario_aula,
                                        id_turma
                                    FROM dia_semana,
                                        horario_aula,
                                        turma
                                    WHERE turma.fk_curso_id_curso = 1
                                        AND turma.num_modulo = 6
                                    except
                                    SELECT fk_dia_semana_id_dia_semana AS id_dia_semana,
                                        fk_horario_aula_id_horario_aula AS id_horario_aula,
                                        fk_turma_id_turma
                                    FROM aula au
                                        JOIN grupo_aula ga ON (au.id_aula = ga.fk_aula_id_aula)
                                        JOIN grupo g ON (ga.fk_grupo_id_grupo = g.id_grupo)
                                    WHERE fk_dia_semana_id_dia_semana IN (2, 3, 4, 5, 7)
                                    ORDER BY id_dia_semana
                                ) AS table1
                            WHERE table1.id_dia_semana IN (2, 3, 4, 5, 6, 7)
                        ) AS table2
                ) AS table3
                INNER JOIN horario_aula ha ON (ha.id_horario_aula = table3.id_horario_aula)
                JOIN turma t ON (t.id_turma = table3.id_turma)
        )
    ) AS COMPLETE_TABLE
ORDER BY id_horario_aula,
    id_dia_semana;