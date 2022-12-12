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
 * Função para alterar o conteudo do input delete
 * @param {int} id 
 */
function deleteItem(id) {
    document.getElementById('id').value = id;
}