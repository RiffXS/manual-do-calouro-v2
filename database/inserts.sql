INSERT INTO acesso (dsc_acesso) VALUES
    ('admin'),
    ('usuario'),
    ('aluno'),
    ('servidor'),
    ('professor');
    
INSERT INTO state (dsc_state) VALUES
    ('Inativo'),
    ('Ativo'),
    ('Confirma');

-- Senha unica! (S3NH@S)
INSERT INTO usuario (nom_usuario, email, senha, fk_acesso_id_acesso, fk_state_id_state) VALUES
    ('admin', 'mdc@ifes.edu.br', '$2y$10$5R0JhvGH7iTEA9HgUqEd0.SywoyROn6.kkgC81UMhCoXHfl.J8hw6', 1, 1);

INSERT INTO usuario (nom_usuario, email, senha, fk_acesso_id_acesso)
VALUES (
        'Henrique',
        'henriquedalmagro@outlook.com',
        '$2y$10$jgcDXod3UkRc3xdcMInbouy.K/qggwkDnNgD8cwjZTlBBrqffO9WG',
        3
    ),
    (
        'Maria',
        'mariaeduarda@gmail.com',
        '$2y$10$eQ1ZixS8rKNWkigRl2VmpusdSkHh19gLujF/fcYlZUOnlOkXsdHmG',
        3
    ),
    (
        'Rafael',
        'rafaelbarros@hotmail.com',
        '$2y$10$YLtWLzRodJzZ1mb31RdDO.1JLqJLoxnl1SBpnXLCbmGZ3bBER1aEm',
        3
    ),
    (
        'Raphael',
        'rbranco@yahoo.com',
        '$2y$10$xUZ9PMKKnLyHQxMrJREmT.gtIpMV785I5SrDq3jCqTgavF9.9F0Ra',
        4
    ),
    (
        'Nauvia Cancelieri',
        'nauvia@gmail.com',
        '$2y$10$//6XkcYo2qlAJWgbbDYiueC5QEEv8kX8zSu70uh34kA7eOnsSlGb6',
        5
    ),
    (
        'Marta Amorim',
        'marta@gmail.com',
        '$2y$10$TvuKBuyUgpdwdo/X8sxhaOuKYOtnk3pvwI7mZucRBExj8mQgsXQ72',
        5
    ),
    (
        'Paulo Cezar',
        'paulo.cesar@gmail.com',
        '$2y$10$kDwbUHz3pZjhikO29eAZp.LKdmy8E1UkN3NUxRLu7uDVL/HoViz4C',
        5
    ),
    (
        'Alessandro Bermudes',
        'bermudes@gmail.com',
        '$2y$10$YhW5QUL.BTJzdmL3qzZQNeJf/FAFULcDbcEy.GAUMu2xKlBKDVPJe',
        5
    ),
    (
        'Ronaldo Marques',
        'ronaldo@gmail.com',
        '$2y$10$09.kh9fHfHtZLwO3dP.SMuiozTdZCMdzqriBbgsv5AjBjYFqsjHp6',
        5
    ),
    (
        'Diego Alves',
        'diego@gmail.com',
        '$2y$10$IceIFIucyB93NcgQw032DO8o/bONWDZwC9QJ/X9VGjOKKAPdqXKcu',
        5
    ),
    (
        'Ana Paula Klauck',
        'ana.paula@gmail.com',
        '$2y$10$0VHPjKuE6f6ev9h2Gdu2kuEA.sXIC0aOos5YRd66w5lJ/DRJ9jb2i',
        5
    ),
    (
        'Geraldo Bello',
        'geraldo@gmail.com',
        '$2y$10$QkPZ1vF8uBX7M1I6bYoEvOKsc2L5tLnORWbHEkTqXl2WZblHB/Q92',
        5
    ),
    (
        'Maikon Chaider',
        'maikon@gmail.com',
        '$2y$10$S0TJ.4x.sRkBgea8YAiq4.e00Ky/BRHyqhbMUZiTGQnvt9Ip3J0Ta',
        5
    ),
    (
        'Carlos Lins',
        'carlos@gmail.com',
        '$2y$10$wmEOlFlx63clUa5VCgiqe.Ysvl3T28LNJXwMvthDEBJocYQrQvYzG',
        5
    ),
    (
        'Daniel Trindade',
        'daniel@gmail.com',
        '$2y$10$kS8dBN46ELFC8IS/eV/Xd.5OuAEsd03p0AsPjdtG2F8wjdIjr3M.q',
        5
    ),
    (
        'Moisés Omena',
        'moisesomena@ifes.edu.br',
        '$2y$10$SU803qITi2b8imDq1R5stO7Amc0SAbzTxPdeh0kklt/IS9IqyYyxG',
        5
    ),
    (
        'Thiago',
        'thiagoneves@gmail.com',
        '$2y$10$5R0JhvGH7iTEA9HgUqEd0.SywoyROn6.kkgC81UMhCoXHfl.J8hw6',
        3
    ),
    (
        'Luís',
        'luisluz@gmail.com',
        '$2y$10$5R0JhvGH7iTEA9HgUqEd0.SywoyROn6.kkgC81UMhCoXHfl.J8hw6',
        3
    ),
    (
        'Eloá',
        'eloa@gmail.com',
        '$2y$10$5R0JhvGH7iTEA9HgUqEd0.SywoyROn6.kkgC81UMhCoXHfl.J8hw6',
        3
    ),
    (
        'Roger',
        'warrick@gmail.com',
        '$2y$10$5R0JhvGH7iTEA9HgUqEd0.SywoyROn6.kkgC81UMhCoXHfl.J8hw6',
        3
    ),
    (
        'Scopel',
        'scopel.ifes@gmail.com',
        '$2y$10$5R0JhvGH7iTEA9HgUqEd0.SywoyROn6.kkgC81UMhCoXHfl.J8hw6',
        4
    );

INSERT INTO sala (num_sala) VALUES
    ('101'),
    ('701'),
    ('702'),
    ('703'),
    ('704'),
    ('705'),
    ('706'),
    ('708'),
    ('709'),
    ('710'),
    ('711'),
    ('712'),
    ('707'),
    ('900t');

INSERT INTO servidor (fk_usuario_id_usuario, fk_sala_id_sala) VALUES
    (5, 1), -- CAE
    (6, 2),
    (7, 3),
    (8, 4),
    (9, 5),
    (10, 6),
    (11, 7),
    (12, 8),
    (13, 9),
    (14, 10),
    (15, 13),
    (16, 12),
    (17, 13), -- Moisés
    (22, 14); -- Scopel

INSERT INTO tipo_contato (dsc_tipo) VALUES
    ('Telefone'),
    ('E-mail'),
    ('WhatsApp');

INSERT INTO contato (dsc_contato, fk_servidor_fk_usuario_id_usuario, fk_tipo_contato_id_tipo) VALUES
    ('(27) 99966-0410', 5, 1),
    ('(27) 99872-1412', 6, 1),
    ('wagnerscopel@ifes.edu.br', 23, 2),
    ('(27) 3182-9480', 23, 3);

INSERT INTO servidor_horario (fk_servidor_fk_usuario_id_usuario, fk_horario_id_horario) VALUES
    (5, 1),
    (6, 1),
    (22, 2);

INSERT INTO setor (dsc_setor) VALUES
    ('Coordenadoria de Apoio ao Ensino'),
    ('Pedagógico');

INSERT INTO administrativo (fk_servidor_fk_usuario_id_usuario, fk_setor_id_setor) VALUES
    (5, 1),
    (23, 2);

INSERT INTO campus (dsc_campus) VALUES
    ('Serra');

INSERT INTO evento (dat_evento, dsc_evento, fk_campus_id_campus)
VALUES (
        '2022-11-01 11:10:00', 
        'Prova de Biologia', 
        1
    ),
    (
        '2022-10-09 08:20:00', 
        'OBMEP', 
        1
    ),
    (
        '2022-10-30 13:00:00', 
        'Prova de Matemática', 
        1
    ),
    (
        '2022-11-27 07:30:00', 
        'Expedição IFES', 
        1
    ),
    (
        '2022-10-11 10:20:00',
        'Laboratório de Química',
        1
    ),
    (
        '2022-10-29 10:20:00', 
        'Festa Cultural', 
        1
    ),
    (
        '2022-09-11 09:10:00', 
        'Setembro Verde', 
        1
    ),
    (
        '2022-08-07 08:00:00', 
        'Exposição de Hip Hop', 
        1
    ),
    (
        '2022-08-15 10:20:00', 
        'Teatro Info 2', 
        1
    ),
    (
        '2022-07-10 11:00:00', 
        'SIGI', 
        1
    ),
    (
        '2022-08-11 13:00:00',
        'Reunião do Comitê Estudantil',
        1
    );

INSERT INTO campus_curso(fk_campus_id_campus, fk_curso_id_curso) VALUES
    (1,1),
    (1,2),
    (1,3);

INSERT INTO horario (hora_inicio, hora_fim) VALUES
    ('10:30:00', '11:30:00'),
    ('13:00:00', '17:00:00');

INSERT INTO curso (dsc_curso) VALUES
    ('Info'),
    ('Mec'),
    ('Iot');

INSERT INTO turma (num_modulo, fk_curso_id_curso) VALUES
    (1, 1),
    (2, 1),
    (3, 1),
    (4, 1),
    (5, 1),
    (6, 1),
    (1, 2),
    (2, 2),
    (3, 2),
    (4, 2),
    (5, 2),
    (6, 2),
    (1, 3),
    (2, 3),
    (3, 3),
    (4, 3),
    (5, 3),
    (6, 3);

INSERT INTO aluno (num_matricula, fk_usuario_id_usuario, fk_turma_id_turma) VALUES
    ('20201tiimi0365', 2, 6),   -- Henrique
    ('20201tiimi0152', 3, 6),   -- Duda
    ('20201tiimi0160', 4, 6),   -- Rafael
    ('20201tiimi0161', 18, 7),  -- Thiago
    ('20201tiimi0162', 19, 10), -- Luís
    ('20201tiimi0163', 20, 14), -- Eloá
    ('20201tiimi0164', 21, 15); -- Roger

INSERT INTO grupo (dsc_grupo, fk_turma_id_turma) VALUES
	('A', 1),
    ('B', 1);

INSERT INTO grupo_aluno (fk_aluno_fk_usuario_id_usuario, fk_grupo_id_grupo) VALUES
	(2, 2),
    (3, 2),
    (4, 2),
    (18, 1);

INSERT INTO dia_semana (dsc_dia_semana) VALUES
    ('Domingo'),
    ('Segunda-feira'),
    ('Terça-feira'),
    ('Quarta-feira'),
    ('Quinta-feira'),
    ('Sexta-feira'),
    ('Sábado');

INSERT INTO horario_aula (hora_aula_inicio, hora_aula_fim) VALUES
    ('07:30:00', '08:20:00'), -- 1
    ('08:20:00', '09:10:00'), -- 2
    ('09:30:00', '10:20:00'), -- 3
    ('10:20:00', '11:10:00'), -- 4
    ('11:20:00', '12:10:00'), -- 5
    ('12:10:00', '13:00:00'), -- 6
    ('13:00:00', '13:50:00'), -- 7
    ('13:50:00', '14:40:00'), -- 8
    ('15:00:00', '15:50:00'), -- 9
    ('15:50:00', '16:40:00'), -- 10
    ('16:50:00', '17:40:00'), -- 11
    ('17:40:00', '18:30:00'), -- 12
    ('18:30:00', '19:20:00'), -- 13
    ('19:20:00', '20:10:00'), -- 14
    ('20:20:00', '21:10:00'), -- 15
    ('21:10:00', '22:00:00'); -- 16

INSERT INTO sala_aula (num_sala_aula) VALUES
	('SAL 103'),  -- 1
    ('SAL 104'),  -- 2
    ('SAL 105'),  -- 3
	('SAL 106'),  -- 4
    ('SAL 107'),  -- 5
	('SAL 108'),  -- 6
    ('LAB 208'),  -- 7
    ('LAB 901T'), -- 8
    ('LAB 903T'); -- 9
    
INSERT INTO disciplina (dsc_disciplina) VALUES
	('BIO'),         -- 1
    ('DES SIST'),    -- 2
    ('DISP MOV'),    -- 3
    ('ELET BAS'),    -- 4 
    ('EMPREEND'),    -- 5
    ('FILOS'),       -- 6
    ('MAT'),         -- 7
    ('PORTUG'),      -- 8
    ('PROG WEB II'), -- 9
    ('PROJ INTEG'),  -- 10
    ('QUI'),         -- 11
    ('SOCIOL');      -- 12

INSERT INTO professor (regras, fk_servidor_fk_usuario_id_usuario) VALUES
    (null, 6),                 -- Nauvia Cancelieri
    (null, 7),                 -- Marta Amorim
    (null, 8),                 -- Paulo Cezar
    (null, 9),                 -- Alessandro Bermudes
    (null, 10),                -- Ronaldo Marques
    (null, 11),                -- Diego Alves
    (null, 12),                -- Ana Paula Klauck
    (null, 13),                -- Geraldo Bello
    (null, 14),                -- Maikon Chaider
    (null, 15),                -- Carlos Lins
    (null, 16),                -- Daniel Trindade
    ('Não xingar porra!', 17); -- Moisés Omena

INSERT INTO aula (
        fk_dia_semana_id_dia_semana,
        fk_horario_aula_id_horario_aula,
        fk_sala_aula_id_sala_aula,
        fk_disciplina_id_disciplina,
        fk_professor_fk_servidor_fk_usuario_id_usuario
    )
VALUES -- Segunda-feira Info 6
    (2, 1, 3, 11, 6),
    (2, 2, 3, 11, 6),
    (2, 3, 7, 9, 7),
    (2, 4, 7, 9, 7),
    (2, 5, 7, 9, 7),
    (2, 6, 3, 7, 8),
    -- Terça-feira Info 6
    (3, 1, 3, 1, 9),
    (3, 2, 3, 1, 9),
    (3, 3, 3, 1, 9),
    (3, 4, 3, 5, 10),
    (3, 5, 3, 5, 10),
    (3, 6, 3, 12, 11),
    -- Quarta-feira Info 6
    (4, 1, 3, 8, 12),
    (4, 2, 3, 11, 6),
    (4, 3, 3, 7, 8),
    (4, 4, 3, 7, 8),
    (4, 5, 3, 4, 13),
    (4, 6, 3, 4, 13),
    -- Quinta-feira Info 6
    (5, 2, 3, 6, 14),
    (5, 3, 3, 8, 12),
    (5, 4, 3, 8, 12),
    (5, 5, 3, 2, 15),
    (5, 6, 3, 2, 15),
    -- Sexta-feira Info 6 A
    (6, 1, 8, 10, 17),
    (6, 2, 8, 10, 17),
    (6, 3, 8, 10, 17),
    (6, 4, 9, 3, 16),
    (6, 5, 9, 3, 16),
    -- Sexta-feira Info 6 B
    (6, 2, 9, 3, 16),
    (6, 3, 9, 3, 16),
    (6, 4, 8, 10, 17),
    (6, 5, 8, 10, 17),
    (6, 6, 8, 10, 17);
        
INSERT INTO grupo_aula (fk_grupo_id_grupo, fk_aula_id_aula) VALUES
	(1, 1),
    (1, 2),
    (1, 3),
    (1, 4),
    (1, 5),
    (2, 1),
    (2, 2),
    (2, 3),
    (2, 4),
    (2, 6);
