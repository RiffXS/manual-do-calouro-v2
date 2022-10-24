<?php

namespace App\Controller\Pages;

use App\Models\Entity\User;
use App\Utils\Session;
use App\Utils\Tools\Alert;
use App\Utils\Tools\Upload;
use \App\Utils\View;

class Profile extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina perfil
     * @param \App\Http\Request
     * @return string 
     */
    public static function getEditProfile($request) {
        // OBTEM A IMAGEM DO USUARIO
        $id = Session::getSessionId();
        $obUser = User::getUserById($id);

        $view = self::getTextType($obUser);

        // VIEW DA HOME
        $content =  View::render('pages/profile', [
            'status' => Alert::getStatus($request),
            'foto'   => $obUser->getImgProfile(),
            'nome'   => $obUser->getNomUser(),
            'email'  => $obUser->getEmail(),
            'texto'  => $view['text'],
            'campo'  => $view['colum']
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Perfil', $content);
    }

    /**
     * @param \App\Http\Request
     */
    public static function setEditProfile($request) {
        // OBTEM A IMAGEM DO USUARIO
        $obUser = Session::getSessionUser();

        // POST VARS    
        $postVars = $request->getPostVars(); 
        $files = $request->getUploadFiles();

        $obUpload = new Upload($files['foto']);

        if (is_uploaded_file($obUpload->tmpName)) { 

            if ($postVars['MAX_FILE_SIZE'] > $obUpload->size) {
                
                $name = $obUser->getImgProfile();

                if ($name == 'images/user.png') {
                    $obUpload->generateNewName();
                    $name = $obUpload->getBasename();
                }

                if ($obUpload->upload(getenv('DIR').'/public/uploads/')) {
                    $obUser->setImgProfile($name);
                }
            }
        }  
        echo '<pre>'; print_r($obUser); echo '</pre>'; exit;
    }

    /**
     * Metodo responsavel por definir o texto de acordo com o tipo de usuario
     * @param User
     */
    public static function getTextType($obUser) {
        $text = '';
        $colum = '';

        switch ($obUser->getAcess()) {
            case 2:
                $text = 'Matricula';
                $colum = 'enrollment';

                break;
            case 3:

                $text = 'Turma';
                $colum = 'class';
                break;
            case 4:
                $text = 'Regras';
                $colum = 'rules';
                break;

            case 5:
                $text = 'Setor';
                $colum = 'sector';
                break;
        }
        return [
            'text' => $text,
            'colum' => View::render("pages/profile/$colum")
        ];
    }
}
