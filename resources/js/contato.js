const url = 'http://localhost/mvc-mdc';

/**
 * Função para realizar request a uma rota e obter os dados
 * @param {string} route 
 * 
 * @returns 
 */
async function getData(route) {
    const response = await fetch(route);

    const result = await response.json();

    return await result['dados'];
}

/**
 * Função para rendenizar os dados do modal contato
 * @param {int} id 
 */
async function editContact(id) {
    // REQUISIÇÃO A ROTA DE CONSULTA
    const data = await getData(url+'/api/v1/contact/data/'+id);

    console.log(data);

    // ADICIONANDO VALORES AO MODAL DE EDIÇÃO
    document.getElementById('edit-id-contato').value    = data.id_contato;
    document.getElementById('edit-fk-usuario').value    = data.fk_servidor_fk_usuario_id_usuario;
    document.getElementById('edit-tipo-contato').value  = data.fk_tipo_contato_id_tipo;
    document.getElementById('edit-input-contato').value = data.dsc_contato;
}
