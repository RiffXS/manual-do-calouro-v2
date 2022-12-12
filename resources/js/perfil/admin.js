window.onload = function() {
    // OBTEM OS VALORES DOS HIDDEN INPUT
    const setor = document.getElementById('h-setor').value;

    // ALTERA OS VALORES DOS SELECTS
    document.getElementById('setor').value = setor;
}