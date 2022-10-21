<?php

namespace App\Controller\Pages;

use App\Models\Entity\User;
use App\Utils\Session;
use App\Utils\Tools\Alert;
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
            'foto'   => $obUser->getImgPrfoile(),
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
     * 
     */
    public static function setEditProfile($request) {
        $postVars = $request->getPostVars();
        $files = $request->getUploadFiles();

        // Verifica a a imagem existe
        if (is_uploaded_file(($files['foto']['tmp_name']))) { 
            // Nome da foto, tamanho da foto, nome temporario no servidor
            $photo_name = $files['foto']['name'];
            $photo_size = $files['foto']['size'];
            $path_temp  = $files['foto']['tmp_name'];

            if ($photo_size < $postVars['MAX_FILE_SIZE'])
                self::uploadProfileImage($photo_name, $path_temp);

            echo '<pre>'; print_r($files['foto']); echo '</pre>'; exit;
        }

    }

    /**
     * Metodo responsavel por definir o texto de acordo com o tipo de usuario
     * @param User
     */
    public static function getTextType($obUser) {
        $text = '';
        $colum = '';

        switch($obUser->getAcess()) {
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

    public static function uploadProfileImage($photoName, $pathTemp) {
        // Declaração de variavel - pasta destino
        $dir = '../../assets/uploads/';

        // Atualiza o nome da foto do usuario
        $sql = "UPDATE usuario SET img_perfil ='$foto_nome' 
                WHERE id_usuario = {$_SESSION['id_usuario']}";

        // Verifica se o update ocorreu e armazena a foto na pasta de uploads
        if (pg_query(CONNECT, $sql)) {
            // Concatenando o caminho
            $path = $dir.$foto_nome;

            if (move_uploaded_file($path_temp, $path)) {
                return true;
            }
        } else {
            return false
        }   
    }
}