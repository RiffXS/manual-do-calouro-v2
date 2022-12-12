// URL do projeto
const url = 'http://localhost/manual-do-calouro';

/**
 * Função para rendenizar os dados do modal contato
 * @param {int} id 
 */
async function viewComment(id) {
    // REQUISIÇÃO A ROTA DE CONSULTA
    const data = await getData(url+'/api/v1/comments/view/'+id);

    // ADICIONANDO VALORES AO MODAL DE EDIÇÃO
    document.getElementById('usuario').value = data.fk_usuario;
    document.getElementById('data').value = data.dt_comentario;
    document.getElementById('texto').value  = data.dsc_comentario;
}