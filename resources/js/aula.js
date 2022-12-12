window.onload = function () {

    const semana = document.getElementById('fk_dia_semana_id_dia_semana').value;
    const horario = document.getElementById('fk_horario_aula_id_horario_aula').value;
    const sala = document.getElementById('fk_sala_aula_id_sala_aula').value;
    const disciplina = document.getElementById('fk_disciplina_id_disciplina').value;
    const professor = document.getElementById('fk_professor_fk_servidor_fk_usuario_id_usuario').value;

    document.getElementById('dia_semana').value = semana;
    document.getElementById('horario').value = horario;
    document.getElementById('sala_aula').value = sala;
    document.getElementById('disciplina').value = disciplina;
    document.getElementById('professor'). value = professor;
}