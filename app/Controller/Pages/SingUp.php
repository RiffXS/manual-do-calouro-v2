<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Models\Entity\User as EntityUser;

class SingUp extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina cadastro
     * @return string 
     */
    public static function getSingUp($request) {
        // CONTEUDO DA PAGINA DE LOGIN
        $content = View::render('pages/singup', [
            'status' => self::getStatus($request)
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Cadastro', $content);
    }

    public static function setSingUp($request) {
        // POST VARS
        $postVars = $request->getPostVars();

        $nome     = $postVars['nome'] ?? '';
        $email    = $postVars['email'] ?? '';
        $password = $postVars['senha'] ?? '';
        $confirm  = $postVars['senhaConfirma'] ?? '';

        // VALIDA O NOME
        if (EntityUser::validateUserName($nome)) {
            $request->getRouter()->redirect('/singup?status=invalidname');
        }

        // VALIDA O EMAIL
        if (EntityUser::validateUserEmail($email)) {
            $request->getRouter()->redirect('/singup?status=invalidemail');
        }

        // VERIFICA A SENHA
        if (EntityUser::verifyUserPassword($password)) {
            $request->getRouter()->redirect('/singup?status=invalidpass');
        }

        // VALIDA A SENHA
        if (EntityUser::validateUserPassword($password, $confirm)) {
            $request->getRouter()->redirect('/singup?status=passnotagree');
        }

        // VERIFICA O EMAIL DO USUÁRIO
        $obUser = EntityUser::getUserByEmail($email);

        // VERIFICA SE O EMAIL ESTA DISPONÍVEL
        if ($obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/singup?status=duplicated');
        }

        // NOVA INSTÂNCIA DE USUARIO
        $obUser = new EntityUser;
        $obUser->nom_usuario = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($password, PASSWORD_DEFAULT);

        // CADASTRA O USUÁRIO
        $obUser->insertUser();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/singin?status=created');
    }

    /**
     * Methodo responsavel por retornar a menagem de status
     * @param \App\Http\Request
     * @return string
     */
    private static function getStatus($request) {
        // DECLARAÇÃO DE VARIÁVEL
        $msg = '';

        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        // VERIFICA SE EXISTE UM STATUS
        if (!isset($queryParams['status'])) return $msg;

        // MENSAGENS DE STATUS
        switch ($queryParams['status']) {
            // NOME INVÁLIDO
            case 'invalidname':
                $erro = 'Nome inválido!';
                break;

            // EMAIL INVÁLIDO
            case 'invalidemail':
                $erro = 'Email inválido!';
                break;

            // SENHAS NÃO IGUAIS
            case 'passnotagree':
                $erro = 'Senhas não indênticas!';
                break;

            // SENHA INVALIDA
            case 'invalidpass':
                $erro = 'Senha inválida!';
                break;
                
            // EMAIL DUPLICADO
            case 'duplicated':
                $erro = 'O e-mail indisponivel!';
                break;
        }
        // EXIBE A MENSAGEM DE ERRO
        if (!empty($erro)) {
            return Alert::getError($erro);
        }
        // EXIBE A MENSAGEM DE SUCESSO
        return Alert::getSucess($msg);        
    }
}