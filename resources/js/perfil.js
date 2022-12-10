window.onload = function() {
    // OBTEM OS VALORES DOS HIDDEN INPUT
    const curso  = document.getElementById('h-curso').value;
    const modulo = document.getElementById('h-modulo').value;
    const grupo  = document.getElementById('h-grupo').value;

    // ALTERA OS VALORES DOS SELECTS
    document.getElementById('curso').value  = curso;
    document.getElementById('modulo').value = modulo;
    document.getElementById('grupo').value  = grupo;
}