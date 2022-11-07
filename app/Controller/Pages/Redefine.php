<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Hash as EntityHash;
use App\Models\User as EntityUser;
use App\Utils\Tools\Alert;
use App\Utils\Sanitize;
use App\Utils\View;

class Redefine extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página redefinir senha
     * @param \App\Http\Request
     * 
     * @return string 
     * 
     * @author @SimpleR1ick
     */
    public static function getRedefine(Request $request): string {
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        $chave = $queryParams['chave'];

        // NOVA INSTANCIA
        $obHash = EntityHash::findHash(null, $chave);

        // VALIDA A INSTANCIA, VERIFICANDO SE HOUVE RESULTADO 
        if (!$obHash instanceof EntityHash) {
            // REDIRECIONA PARA HOME
            $request->getRouter()->redirect('/recovery?status=invalid_hash');
        }
        // VIEW DO SOBRE
        $content = View::render('pages/redefine', [
            'id' => $obHash->getFkId(),
            'status' => Alert::getStatus($request)
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Redefinir Senha', $content);
    }

    /**
     * Método responsável por atualizar
     * @param \App\Http\Request
     * 
     * @return void
     * 
     * @author @SimpleR1ick
     */
    public static function setRedefine(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();    
        
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