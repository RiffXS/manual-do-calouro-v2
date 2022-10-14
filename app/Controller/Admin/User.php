<?php

namespace App\Controller\Admin;

use App\Utils\View;
use \App\Utils\Pagination;
use App\Models\Entity\User as EntityUser;

class User extends Page {

    /**
     * Methodo responsavel por obter a rendenização dos items de usuarios para página
     * @param \App\Http\Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getUsersItems($request, &$obPagination) {
        // USUARIOS
        $itens = '';

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) AS qtd')->fetchObject()->qtd;

        // PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        // RESULTADOS DA PAGINA
        $results = EntityUser::getUsers(null, 'id DESC', $obPagination->getLimit());

        // RENDENIZA O ITEM
        while ($obUser = $results->fetchObject(EntityUser::class)) {
            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/users/item',[
                'id'    => $obUser->id,
                'nome'  => $obUser->nome,
                'email' => $obUser->email
                
            ]);
        }
        // RETORNA OS DEPOIMENTOS
        return $itens;
    }


    /**
     * Methodo responsavel por rendenizar a view de listagem de usuarios
     * @param \App\Http\Request
     * @return string
     */
    public static function getUsers($request) {
        // CONTEUDO DA HOME
        $content = View::render('admin/modules/users/index', [
            'itens'      => self::getUsersItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => self::getStatus($request)
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Usuarios > WDEV', $content, 'users');
    }

    /**
     * Methodo responsavel por retornar o formulario de cadastro de um novo usuario
     * @param \App\Http\Request
     * @return string
     */
    public static function getNewUser($request) {
        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/users/form', [
            'tittle'   => 'Cadastrar usuario',
            'nome'     => '',
            'email'    => '',
            'status'   => self::getStatus($request)
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Cadastrar usuario  > WDEV', $content, 'users');
    }

    /**
     * Methodo responsavel por cadastrar um usuario no banco
     * @param \App\Http\Request
     */
    public static function setNewUser($request) {
        // POST VARS
        $postVars = $request->getPostVars();

        $nome  = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        // VALIDA O EMAIL DO USUARIO
        $obUser = EntityUser::getUserByEmail($email);

        if ($obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }
        
        // NOVA INSTANCIA DE USUARIO
        $obUser = new EntityUser;
        $obUser->nome  = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);

        $obUser->cadastrarUser();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=created');
    }

    /**
     * Methodo responsavel por retornar a menagem de status
     * @param \App\Http\Request
     * @return string
     */
    private static function getStatus($request) {
        // DECLARAÇÃO DE VARIAVEL
        $msg = '';

        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        if (!isset($queryParams['status'])) return $msg;

        // MENSAGENS DE STATUS
        switch ($queryParams['status']) {
            case 'created':
                $msg = 'Usuario criado com sucesso!';
            
            case 'updated':
                $msg = 'Usuario atualizado com sucesso';
                
            case 'deleted':
                $msg = 'Usuario excluido com sucesso';   

            case 'duplicated':
                $erro = 'O e-mail digitado já esta sendo utilizado por outro usuario';    
        }
        // EXIBE A MENSAGEM DE ERRO
        if (!empty($erro)) {
            return Alert::getError($erro);
        }
        // EXIBE A MENSAGEM DE SUCESSO
        return Alert::getSucess($msg);        
    }

    /**
     * Methodo responsavel por retornar o formulario edição de um usuario
     * @param \App\Http\Request
     * @param integer $id
     * @return string
     */
    public static function getEditUser($request, $id) {
        // OBTENDO O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }

        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/users/form', [
            'tittle' => 'Editar usuario', 
            'nome'   => $obUser->nome,
            'email'  => $obUser->email,
            'status' => self::getStatus($request)
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Editar usuario  > WDEV', $content, 'users');
    }

    /**
     * Methodo responsavel por gravar a atualização de um usuario
     * @param \App\Http\Request
     * @param integer $id
     */
    public static function setEditUser($request, $id) {
        // OBTENDO O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }

        // POST VARS
        $postVars = $request->getPostVars();

        $nome  = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        // VALIDA O EMAIL DO USUARIO
        $obUserEmail = EntityUser::getUserByEmail($email);

        if ($obUserEmail instanceof EntityUser && $obUserEmail->id != $id) {
            $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
        }
        // ATUALIZA A INSTANCIA
        $obUser->nome  = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
        $obUser->atualizarUser();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=updated');
    }

    /**
     * Methodo responsavel por retornar o formulario exclusão de um usuario
     * @param \App\Http\Request
     * @param integer $id
     * @return string
     */
    public static function getDeleteUser($request, $id) {
        // OBTENDO O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }

        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/users/delete', [
            'nome'  => $obUser->nome,
            'email' => $obUser->email,
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Excluir usuario  > WDEV', $content, 'users');
    }

    /**
     * Methodo responsavel por excluir um usuario
     * @param \App\Http\Request
     * @param integer $id
     */
    public static function setDeleteUser($request, $id) {
        // OBTENDO O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }

        // EXCLUIR DEPOIMENTO
        $obUser->excluirUser();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/users?status=deleted');
    }
}