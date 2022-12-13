<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\View;
use App\Utils\Pagination;
use App\Models\Admin as EntityAdmin;
use App\Models\Usuario as EntityUser;
use App\Models\Servidor as EntityServer;
use App\Models\Professor as EntityTeacher;
use App\Utils\Tools\Alert;

class User extends Page {

    /**
     * Método responsável por obter a renderização dos items de usuários para página
     * @param \App\Http\Request $request
     * @param \App\Utils\Pagination $obPagination
     * 
     * @return string
     */
    private static function getUsersItems(Request $request, &$obPagination): string {
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

        // RENDERIZA O ITEM
        while ($obUser = $results->fetchObject(EntityUser::class)) {
            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/users/item',[
                'click' => "onclick=deleteItem({$obUser->getId_usuario()})",
                'id'    => $obUser->getId_usuario(),
                'nome'  => $obUser->getNom_usuario(),
                'email' => $obUser->getEmail(),
            ]);
        }

        // RETORNA OS DEPOIMENTOS
        return $itens;
    }

    /**
     * Método responsável por renderizar a view de listagem de usuários
     * @param \App\Http\Request
     * 
     * @return string
     */
    public static function getUsers(Request $request): string {
        // CONTEUDO DA HOME
        $content = View::render('admin/modules/users/index', [
            'itens'      => self::getUsersItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => Alert::getStatus($request)
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Usuários > MDC', $content, 'users');
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo usuário
     * @param \App\Http\Request
     * 
     * @return string
     */
    public static function getNewUser(Request $request): string {
        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/users/form', [
            'tittle'   => 'Cadastrar Usuário',
            'status'   => Alert::getStatus($request),
            'nome'     => '',
            'email'    => '',
            'ativo'    => 'checked',
            'inativo'  => '',
            'acesso'   => '2',
            'botao'    => 'Cadastrar'
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Cadastrar Usuário > MDC', $content, 'users');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param \App\Http\Request
     * 
     * @return void
     */
    public static function setNewUser(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        $nome   = $postVars['nome'] ?? '';
        $email  = $postVars['email'] ?? '';
        $senha  = $postVars['senha'] ?? '';
        $status = $postVars['status'] ?? '';
        $ativo  = $postVars['active'] ?? '';

        // VALIDA O EMAIL DO USUÁRIO
        $obUser = EntityUser::getUserByEmail($email);

        if ($obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users/new?status=duplicated_email');
        }
        // NOVA INSTANCIA DE USUÁRIO
        $obUser = new EntityUser;
        $obUser->setNom_usuario($nome);
        $obUser->setEmail($email);
        $obUser->setSenha($senha);
        $obUser->setFk_acesso($status);
        $obUser->setAtivo($ativo);

        $obUser->insertUser();

        self::registerByUserType($obUser);

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/users/edit/'.$obUser->getId_usuario().'?status=user_registered');
    }

    /**
     * Método responsável por retornar o formulário de edição de um usuário
     * @param \App\Http\Request
     * @param integer $id
     * 
     * @return string
     */
    public static function getEditUser(Request $request, int $id): string {
        // DECLARAÇÃO DE VARIÁVEIS
        $status = [
            'ativo'   => '',
            'inativo' => ''
        ];
        
        // OBTENDO O USUÁRIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }
        $obUser->getAtivo() == 1 ? $status['ativo'] = 'checked' : $status['inativo'] = 'checked';

        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/users/form', [
            'status'  => Alert::getStatus($request),
            'tittle'  => 'Editar Usuário',
            'botao'   => 'Atualizar',
            'nome'    => $obUser->getNom_usuario(),
            'email'   => $obUser->getEmail(),
            'acesso'  => $obUser->getFk_acesso(),
            'ativo'   => $status['ativo'],
            'inativo' => $status['inativo'],
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Editar Usuário  > WDEV', $content, 'users');
    }

    /**
     * Método responsável por gravar a atualização de um usuário
     * @param \App\Http\Request
     * @param integer $id
     * 
     * @return void
     */
    public static function setEditUser(Request $request, int $id): void {
        // OBTENDO O USUÁRIO DO BANCO DE DADOS
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

        // VALIDA O EMAIL DO USUÁRIO
        $obUserEmail = EntityUser::getUserByEmail($email);

        if ($obUserEmail instanceof EntityUser && $obUserEmail->getId_usuario() != $id) {
            $request->getRouter()->redirect('/admin/users/edit/'.$id.'?status=duplicated_email');
        }
        
        // ATUALIZA A INSTANCIA
        $obUser->setNom_usuario($nome);
        $obUser->setEmail($email);
        $obUser->setSenha($senha);
        $obUser->setAtivo($active);
        $obUser->setFk_acesso($status);

        $obUser->updateUser();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/users/edit/'.$id.'?status=user_updated');
    }

    /**
     * Método responsável por excluir um usuário
     * @param \App\Http\Request
     * 
     * @return void
     */
    public static function setDeleteUser(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();
       
        // OBTENDO O USUÁRIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($postVars['id']);

        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }
        // EXCLUIR DEPOIMENTO
        $obUser->deleteUser();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/users?status=user_deleted');
    }

    /**
     * Método responsável por inserir um usuário professor e administrador nas tabelas de herança
     * @param \App\Models\Usuario $obUser
     * 
     * @return void
     */
    private static function registerByUserType(EntityUser $obUser): void {
        // OBTEM O ID DO USUÁRIO
        $id = $obUser->getId_usuario();

        // NOVA INSTANCIA DE SERVIDOR
        $obServer = new EntityServer;
        $obServer->setFk_id_usuario($id);
        $obServer->setFk_id_sala(1);
        $obServer->insertServer();

        switch($obUser->getFk_acesso()) {
            case 4: // ADMINISTRATIVO
                $obAdmin = new EntityAdmin;
                $obAdmin->setFk_id_usuario($id);
                $obAdmin->setFk_id_setor(1);
                $obAdmin->insertAdmin();

                break;

            case 5: // PROFESSOR
                $obTeacher = new EntityTeacher;
                $obTeacher->setFk_id_usuario($id);
                $obTeacher->insertTeacher();

                break;
        }
    }
}