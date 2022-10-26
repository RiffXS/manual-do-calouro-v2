<?php

namespace App\Controller\Pages;

use App\Models\Entity\Hash as EntityHash;
use App\Models\Entity\User as EntityUser;
use App\Utils\Tools\Alert;
use App\Utils\View;

class Redefine extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina redefinir senha
     * @param \App\Http\Request
     * @return string 
     */
    public static function getRedefine($request) {
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
        return parent::getHeader('Redefinir', $content);
    }

    /**
     * Método responsavel por atualizar
     * @param \App\Http\Request
     */
    public static function setRedefine($request) {
        // POST VARS
        $postVars = $request->getPostVars();    
        
        $password = $postVars['senha'];
        $confirma = $postVars['confirma'];
        $id_user  = $postVars['id'];

        // NOVA INSTANCIA CHAVE
        $obHash = EntityHash::findHash($id_user, null);

        // NOVA INSTANCIA DE USUARIO
        $obUser = EntityUser::getUserById($id_user);

        // VALIDA A SENHA
        if (EntityUser::validateUserPassword($password, $confirma)) {
            $request->getRouter()->redirect("/redefine?chave={$obHash->getHash()}&status=invalid_pass");
        }
        // ATUALIZA O USUARIO
        $obUser->setPass($password);
        $obUser->updateUser();
        
        // EXCLUI A CHAVE
        $obHash->deleteHash();

        // REDIRECIONA PARA O LOGIN
        $request->getRouter()->redirect('/signin?status=pass_updated');        
    }
}