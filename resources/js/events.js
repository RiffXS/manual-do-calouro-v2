window.onload = function () {
    // OBTEM O VALOR DO HIDDEN INPUT
    const campus = document.getElementById("h-campus").value;

    // ALTERA O VALOR DO SELECT
    document.getElementById("campus").value = campus; 
}