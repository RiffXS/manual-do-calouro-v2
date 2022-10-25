<?php

namespace App\Controller\Pages;

use App\Models\Entity\Hash as EntityHash;
use App\Models\Entity\User as EntityUser;
use App\Utils\Tools\Alert;
use \App\Utils\View;

class Redefine extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina sobre
     * @param \App\Http\Request
     * @return string 
     */
    public static function getRedefine($request) {
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        $chave = $queryParams['chave'];

        // NOVA INSTANCIA
        $obHash = EntityHash::findKey(null, $chave);

        // VALIDA A INSTANCIA, VERIFICANDO SE HOUVE RESULTADO 
        if (!$obHash instanceof EntityHash) {
            // REDIRECIONA PARA HOME
            $request->getRouter()->redirect('/recovery?status=invalid_hash');
        }
        // VIEW DO SOBRE
        $content =  View::render('pages/redefine', [
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
        
        $id = $postVars['id'];
        $password = $postVars['senha'];
        $confirma = $postVars['confirma'];

        // NOVA INSTANCIA DE USUARIO E CHAVE
        $obUser = EntityUser::getUserById($id);
        $obHash = EntityHash::findKey($id, null);

        // VALIDA A SENHA
        if (EntityUser::validateUserPassword($password, $confirma)) {
            $request->getRouter()->redirect("/redefine?status=invalid_pass&chave={$obHash->getKey()}");
        }
        // EXCLUIR A CHAVE DO BANCO
        EntityHash::deleteKey($id);

        // ATUALIZA O USUARIO
        $obUser->setPass($password);
        $obUser->updateUser();
        
        // REDIRECIONA PARA O LOGIN
        $request->getRouter()->redirect('/signin?status=pass_updated');        
    }
}