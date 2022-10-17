// Elementos na barra de navegação
const home = document.querySelector("#home");
const sobre = document.querySelector("#sobre");
const calendario = document.querySelector("#calendario");
const mapa = document.querySelector("#mapa");
const horarios = document.querySelector("#horarios");
const contatos = document.querySelector("#contatos");
const rod = document.querySelector("#rod");
const faq = document.querySelector("#faq");

// URL da página
const path = window.location.href;

// Verificação da localização no site
if (path.includes('')) {
    home.classList.toggle('active');

} else if (path.includes('sobre')) {
    sobre.classList.toggle('active');

} else if (path.includes('calendario')) {
    calendario.classList.toggle('active');

} else if (path.includes('mapa')) {
    mapa.classList.toggle('active');
    
} else if (path.includes('horarios')) {
    horarios.classList.toggle('active');

} else if (path.includes('contatos')) {
    contatos.classList.toggle('active');

} else if (path.includes('rod')) {
    rod.classList.toggle('active');

} else if (path.includes('faq')) {
    faq.classList.toggle('active');

}