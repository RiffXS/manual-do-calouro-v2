<?php 

namespace App\Controller\Api;

use App\Http\Request;
use App\Models\Usuario as EntityUser;
use App\Utils\Pagination;
use Exception;

class User extends Api {

    /**
     * Método responsavel por retornar os detalhes do usuario
     * @param \App\Models\Usuario $obUser
     * 
     * @return array
     */
    private static function detailsUser(EntityUser $obUser): array {
        return [
            'id'    => $obUser->getId_usuario(),
            'nome'  => $obUser->getNom_usuario(),
            'email' => $obUser->getEmail(),
        ];
    }

    /**
     * Método responsavel por retornar o usuario atualmente conectado
     * @param Request $request
     * 
     * @return array
     */
    public static function getCurrentUser(Request $request) {
        // RETORNA OS DETALHES DO USUARIO
        return self::detailsUser($request->user);
    }

    /**
     * Methodo responsavel por obter a rendenização dos items de usuarios para página
     * @param \App\Http\Request $request
     * @param \App\Utils\Pagination $obPagination
     * 
     * @return array
     */
    private static function getUsersItems(Request $request, Pagination &$obPagination): array {
        // DEPOIMENTOS
        $itens = [];

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) AS qtd')->fetchObject()->qtd;

        // PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        // RESULTADOS DA PAGINA
        $results = EntityUser::getUsers(null, 'id_usuario ASC', $obPagination->getLimit());

        // RENDENIZA O ITEM
        while ($obUser = $results->fetchObject(EntityUser::class)) {
            $itens[] = self::detailsUser($obUser);
        }
        // RETORNA OS USUARIOS
        return $itens;
    }

    /**
     * Methodo responsavel retornar os detalhes da API
     * @param \App\Http\Request
     * @return array
     */
    public static function getUsers(Request $request): array {
        return [
            'usuarios'  => self::getUsersItems($request, $obPagination),
            'paginacao' => parent::getPagination($request, $obPagination)
        ];
    }

    /**
     * Methodo responsavel por retornar os detalhes de um usuario
     * @param \App\Http\Request
     * @param integer $id
     * 
     * @return array
     */
    public static function getUser(Request $request, int $id) {
        // VALIDA O ID DO USUARIO
        if (!is_numeric($id)) {
            throw new Exception("O id '".$id."'não e valido", 400);
        }
        // BUSCA USUARIO
        $obUser = EntityUser::getUserById($id);

        // VALIDA SE O DEPOIMENTO EXISTE
        if (!$obUser instanceof EntityUser) {
            throw new Exception("O usuario ".$id." não foi encontrado", 404);
        }
        // RETORNA OS DETALHES DO USUARIO
        return self::detailsUser($obUser);
    }

    /**
     * Metodo responsavel por cadastrar um novo usuario
     * @param \App\Http\Request $request
     * 
     * @return array
     */
    public static function setNewUser(Request $request): array {
        // POST VARS
        $postVars = $request->getPostVars();
    
        // VALIDA OS CAMPOS OBRIGATORIOS
        if (!isset($postVars['nome']) or !isset($postVars['email']) or !isset($postVars['senha'])) {
            throw new Exception("Os campos 'nome', 'email' e 'senha' são obrigatorios", 400);
        }
        // VALIDA O EMAIL DO USUARIO
        $obUser = EntityUser::getUserByEmail($postVars[]);

        if ($obUser instanceof EntityUser) {
            throw new Exception("O email já está em uso.", 400);
        }
        // NOVA INSTANCIA DE USUARIO
        $obUser = new EntityUser;

        $obUser->setNom_usuario($postVars['nome']);
        $obUser->setEmail($postVars['email']);
        $obUser->setSenha($postVars['senha']);

        $obUser->insertUser();

        // RETORNA OS DETALHES DO USUARIO CADASTRADO
        return self::detailsUser($obUser);
    }

    /**
     * Methodo responsavel por atualizar um usuario
     * @param \App\Http\Request $request
     * @param integer $id
     * 
     * @return array
     */
    public static function setEditUser(Request $request, int $id) {
        // POST VARS
        $postVars = $request->getPostVars();
    
        // VALIDA OS CAMPOS OBRIGATORIOS
        if (!isset($postVars['nome']) or !isset($postVars['email']) or !isset($postVars['senha'])) {
            throw new Exception("Os campos 'nome', 'email' e 'senha' são obrigatorios", 400);
        }
        $nome  = $postVars['nome'];
        $email = $postVars['email'];
        $senha = $postVars['senha'];

        // BUSCA O DEPOIMENTO
        $obUser = EntityUser::getUserById($id);

        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            throw new Exception("O usuario ".$id." não foi encontrado", 404);
        }
        // VALIDA DISPONIBILIDADE DO EMAIL
        $obUserEmail = EntityUser::getUserByEmail($email);
        if ($obUserEmail instanceof EntityUser and $obUserEmail->getId_usuario() != $obUser->getId_usuario()) {
            throw new Exception("O email já está em uso.", 400);
        }
        // ATUALIZA O DEPOIMENTO
        $obUser->setNom_usuario($nome);
        $obUser->setEmail($email);
        $obUser->setSenha($senha);

        $obUser->updateUser();

        // RETORNA OS DETALHES DO DEPOIMENTO ATUALIZADO
        return self::detailsUser($obUser);
    }

    /**
     * Methodo responsavel por excluir um depoimento
     * @param \App\Http\Request $request
     * 
     * @return array
     */
    public static function setDeleteUser(Request $request, int $id): array {
        // BUSCA O DEPOIMENTO
        $obUser = EntityUser::getUserById($id);

        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            throw new Exception("O depoimento ".$id." não foi encontrado", 404);
        }
        // EXCLUI O DEPOIMENTO
        $obUser->deleteUser();

        // RETORNA O SUCESSO DA EXCLUSÃO
        return [
            'sucesso' => true 
        ];
    }
}