<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Chave as EntityHash;
use App\Models\Usuario as EntityUser;
use App\Utils\Tools\Alert;
use App\Utils\Sanitize;
use App\Utils\View;

class Redefine extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página redefinir senha
     * @param \App\Http\Request
     * 
     * @return string
     */
    public static function getRedefine(Request $request): string {
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        $chave = $queryParams['chave'];

        // NOVA INSTANCIA
        $obHash = EntityHash::findHash(null, $chave);

        // VALIDA A INSTÂNCIA, VERIFICANDO SE HOUVE RESULTADO 
        if (!$obHash instanceof EntityHash) {
            // REDIRECIONA PARA HOME
            $request->getRouter()->redirect('/recovery?status=invalid_hash');
        }
        // VIEW DO SOBRE
        $content = View::render('pages/redefine', [
            'id' => $obHash->getFkId(),
            'status' => Alert::getStatus($request)
        ]);

        // RETORNA A VIEW DA PÁGINA
        return parent::getPage('Redefinir Senha', $content);
    }

    /**
     * Método responsável por redefinir a senha
     * @param \App\Http\Request
     * 
     * @return void
     */
    public static function setRedefine(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // VERIFICA HTML INJECT
        if (Sanitize::validateForm($postVars)) {
            $request->getRouter()->redirect('/signup?status=invalid_chars');
        }
        // SANITIZA O ARRAY
        $postVars = Sanitize::sanitizeForm($postVars); 
        
        $password = $postVars['senha'];
        $confirma = $postVars['confirma'];
        $id_user  = $postVars['id'];

        // NOVA INSTANCIA CHAVE
        $obHash = EntityHash::findHash($id_user, null);

        // NOVA INSTANCIA DE USUARIO
        $obUser = EntityUser::getUserById($id_user);

        // VERIFICA SE AS SENHAS CONICIDEM
        if (Sanitize::validatePassword($password, $confirma)) {
            $request->getRouter()->redirect("/redefine?chave={$obHash->getHash()}&status=invalid_pass");
        }
        // EXCLUI A CHAVE
        $obHash->deleteHash();

        // ATUALIZA A SENHA DO USUARIO
        $obUser->setSenha($password);
        $obUser->updateUser();
        
        // REDIRECIONA PARA O LOGIN
        $request->getRouter()->redirect('/signin?status=pass_updated');        
    }
}