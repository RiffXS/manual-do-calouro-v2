/**
 * Função para carregar os dados do usuario no modal
 * @param {int} 
 */
 async function editContact(id) {
    // REQUISIÇÃO A ROTA DE CONSULTA
    const dados = await fetch("contact/edit/"+id);
    const resposta = await dados.json();

    // Obtendo os id dos inputs e adicionando os dados recebidos a eles
    document.getElementById('edit-id-contato').value    = resposta['dados'].id_contato;
    document.getElementById('edit-fk-usuario').value    = resposta['dados'].fk_servidor_fk_usuario_id_usuario;
    document.getElementById('edit-tipo-contato').value  = resposta['dados'].fk_tipo_contato_id_tipo;
    document.getElementById('edit-input-contato').value = resposta['dados'].dsc_contato;

}

/**
 * Função para carregar o id do usuario a ser excluido
 * @param {int} id
 */
 function delContact(id) {
    // Obtendo os id dos inputs e adicionando os dados recebidos a eles
    document.getElementById('id_delete').value = id;
}
