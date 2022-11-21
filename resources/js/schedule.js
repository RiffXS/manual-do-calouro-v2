// NOVA INSTANCIA DE DATA
const date = new Date();

const ano = date.getFullYear();
const mes = date.getMonth();

// DECLARAÇÃO DE VARIAVEIS
var link = "https://serra.ifes.edu.br/geral/geral/horarios-de-aula-";
var semestre = 0;


// VERIFICA O PERIODO DO ANO
if (mes <= 5) {
    // ATRIBUI 1º SEMESTRE
    semestre = 1;
} else {
    // ATRIBUI 2º SEMESTRE
    semestre = 2;
}

// CONCATENA O ANUAL ATUAL + SEMESTRE
link += `${ano}-${semestre}`;

// ALTERA O LINK NA PAGINA HORARIOS
document.getElementById("link").setAttribute("href", link);