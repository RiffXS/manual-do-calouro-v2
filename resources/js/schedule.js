const date = new Date();
const ano = date.getFullYear();
const mes = date.getMonth();
var semestre;
var link = "https://serra.ifes.edu.br/geral/geral/horarios-de-aula-";

if (mes <= 5) {
    semestre = 1;
} else {
    semestre = 2;
}

link += `${ano}-${semestre}`;
document.getElementById("link").setAttribute("href", link);