<?php

namespace App\Controller\Admin;

use App\Models\User as EntityUser;
use App\Utils\Tools\Alert;
use App\Utils\View;
use App\Utils\Pagination;

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
        $results = EntityUser::getUsers(null, 'id_usuario DESC', $obPagination->getLimit());

        // RENDENIZA O ITEM
        while ($obUser = $results->fetchObject(EntityUser::class)) {
            $modal = View::render('admin/modules/users/delete',[
                'id' => $obUser->getId_usuario()
            ]);

            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/users/item',[
                'id'    => $obUser->getId_usuario(),
                'nome'  => $obUser->getNom_usuario(),
                'email' => $obUser->getEmail(),
                'modal' => $modal
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
            'status'     => Alert::getStatus($request)
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Usuarios > MDC', $content, 'users');
    }

    /**
     * Methodo responsavel por excluir um usuario
     * @param \App\Http\Request
     */
    public static function setDeleteUser($request) {
        // POST VARS
        $postVars = $request->getPostVars();
       
        // OBTENDO O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($postVars['id']);

        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }

        // EXCLUIR DEPOIMENTO
        $obUser->deleteUser();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/users?status=user_deleted');
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
            'botao'    => 'Cadastrar',
            'status'   => Alert::getStatus($request),
            'ativo'    => 'checked',
            'inativo'  => '',
            'acesso'   => '2'
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Cadastrar usuario > MDC', $content, 'users');
    }

    /**
     * Methodo responsavel por cadastrar um usuario no banco
     * @param \App\Http\Request
     */
    public static function setNewUser($request) {
        // POST VARS
        $postVars = $request->getPostVars();

        $nome   = $postVars['nome'] ?? '';
        $email  = $postVars['email'] ?? '';
        $senha  = $postVars['senha'] ?? '';
        $status = $postVars['status'] ?? '';
        $ativo  = $postVars['ativo'] ?? '';

        // VALIDA O EMAIL DO USUARIO
        $obUser = EntityUser::getUserByEmail($email);

        if ($obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users/new?status=duplicated_email');
        }
        // NOVA INSTANCIA DE USUARIO
        $obUser = new EntityUser;
        $obUser->setNom_usuario($nome);
        $obUser->setEmail($email);
        $obUser->setSenha($senha);
        $obUser->setFk_acesso($status);
        $obUser->setAtivo($ativo);

        $obUser->insertUser();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/users/'.$obUser->getId_usuario().'/edit?status=user_registered');
    }

    /**
     * Methodo responsavel por retornar o formulario edição de um usuario
     * @param \App\Http\Request
     * @param integer $id
     * @return string
     */
    public static function getEditUser($request, $id) {
        // DECLARAÇÃO DE VARIÁVEIS
        $status = [
            'ativo'   => '',
            'inativo' => ''
        ];
        
        // OBTENDO O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }
        $obUser->getAtivo() == 1 ? $status['ativo'] = 'checked' : $status['inativo'] = 'checked';

        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/users/form', [
            'tittle'  => 'Editar usuario',
            'nome'    => $obUser->getNom_usuario(),
            'email'   => $obUser->getEmail(),
            'botao'   => 'Atualizar',
            'status'  => Alert::getStatus($request),
            'ativo'   => $status['ativo'],
            'inativo' => $status['inativo'],
            'acesso'  => $obUser->getFk_acesso()
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

        $nome   = $postVars['nome'] ?? '';
        $email  = $postVars['email'] ?? '';
        $senha  = $postVars['senha'] ?? '';
        $active = $postVars['active'] ?? '';
        $status = $postVars['status'] ?? '';

        // VALIDA O EMAIL DO USUARIO
        $obUserEmail = EntityUser::getUserByEmail($email);

        if ($obUserEmail instanceof EntityUser && $obUserEmail->getId_usuario() != $id) {
            $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated_email');
        }
        
        // ATUALIZA A INSTANCIA
        $obUser->setNom_usuario($nome);
        $obUser->setEmail($email);
        $obUser->setSenha($senha);
        $obUser->setAtivo($active);
        $obUser->setFk_acesso($status);

        $obUser->updateUser();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=user_updated');
    }
}