/**
 * Função para 
 * 
 * @param {int} id da tarefa
 * 
 * @author Henrique Dalmagro
 */
 async function editContact(id) {
    // REQUISIÇÃO A ROTA DE CONSULTA
    const dados = await fetch("edit/"+id);
    const resposta = await dados.json();

    console.log(resposta["dados"]);

    // Obtendo os id dos inputs e adicionando os dados recebidos a eles
    //document.getElementById('edit_id').value = resposta['dados'].id;
}
