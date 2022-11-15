/**
 * Função para 
 * 
 * @param {int} id da tarefa
 * 
 * @author Henrique Dalmagro
 */
 async function editContact(id) {
    // REQUISIÇÃO A ROTA DE CONSULTA
    const dados = await fetch("contact/edit/"+id);
    const resposta = await dados.json();

    console.log(resposta["dados"]);

    // Obtendo os id dos inputs e adicionando os dados recebidos a eles
    document.getElementById('edit-id-contato').value    = resposta['dados'].id_contato;
    document.getElementById('edit-fk-usuario').value    = resposta['dados'].fk_servidor_fk_usuario_id_usuario;
    document.getElementById('edit-tipo-contato').value  = resposta['dados'].fk_tipo_contato_id_tipo;
    document.getElementById('edit-input-contato').value = resposta['dados'].dsc_contato;

}
