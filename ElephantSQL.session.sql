SELECT id_aula,
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
    JOIN usuario u ON (s.fk_usuario_id_usuario = u.id_usuario);