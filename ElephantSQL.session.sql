SELECT id_usuario,
    nom_usuario,
    dsc_setor,
    img_perfil,
    hora_inicio,
    hora_fim,
    num_sala
FROM usuario u
    JOIN servidor s ON (u.id_usuario = s.fk_usuario_id_usuario)
    JOIN sala sa ON (s.fk_sala_id_sala = sa.id_sala)
    JOIN servidor_horario sh ON (
        s.fk_usuario_id_usuario = sh.fk_servidor_fk_usuario_id_usuario
    )
    JOIN horario h ON (sh.fk_horario_id_horario = h.id_horario)
    JOIN administrativo a ON (
        s.fk_usuario_id_usuario = a.fk_servidor_fk_usuario_id_usuario
    )
    JOIN setor se ON (a.fk_setor_id_setor = se.id_setor)