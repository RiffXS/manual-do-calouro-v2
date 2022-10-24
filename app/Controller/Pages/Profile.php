<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Utils\Session;
use \App\Utils\Tools\Alert;
use \App\Utils\Tools\Upload;
use \App\Models\Entity\Grade;
use \App\Models\Entity\Student;
use \App\Models\Entity\Teacher;
use \App\Models\Entity\User as EntityUser;

class Profile extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina perfil
     * @param  Request $request
     * @return string 
     */
    public static function getEditProfile($request) {
        // OBTEM A IMAGEM DO USUARIO
        $id = Session::getSessionId();
        $obUser = EntityUser::getUserById($id);

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
     * @param Request $request
     */
    public static function setEditProfile($request) {
        // OBTEM A IMAGEM DO USUARIO
        $obUser = Session::getSessionUser();

        // POST VARS    
        $postVars = $request->getPostVars(); 
        $files    = $request->getUploadFiles();

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
        
        $acesso = Session::getSessionLv();

        switch ($acesso) {
            case 2:
                $matricula = $postVars['matricula'] ?? '';

                if (!empty($matricula)) {

                    $obStudent = new Student($obUser->getUserId(), $matricula);
                
                    if ($obStudent->verifyEnrollment()) {
                        $obStudent->insertStudent();
                        $obUser->setAcess(3);
                    }
                }
                break;

            case 3:
                $curso  = $postVars['curso'] ?? '';
                $modulo = $postVars['modulo'] ?? '';

                if (!empty($modulo) && !empty($curso)) {
                    $gradeId = Grade::getGradeId($curso, $modulo);

                   
                    $obStudent = new Student($obUser->getUserId());
                    $obStudent->fk_turma_id_turma = $gradeId['id_turma'];

                    $obStudent->updateStudent();
                }
                break;
                
            case 4:
                $regras = $postVars['regras'] ?? '';

                if (!empty($regras)) {
                    $obTeacher = new Teacher($obUser->getUserId(), $regras);
                    $obTeacher->updateRules();
                }
                
                break;
        }

        $nome = $postVars['nome'];
        $email = $postVars['email'];

        // VALIDA O NOME
        if (EntityUser::validateUserName($nome)) {
            $request->getRouter()->redirect('/profile?status=invalid_name');
        }
        // VALIDA O EMAIL
        if (EntityUser::validateUserEmail($email)) {
            $request->getRouter()->redirect('/profile?status=invalid_email');
        }
        // VALIDA O EMAIL DO USUARIO
        $obUserEmail = EntityUser::getUserByEmail($email);

        if ($obUserEmail instanceof EntityUser && $obUserEmail->getUserId() != $obUser->getUserId()) {
            $request->getRouter()->redirect('/profile?status=duplicated_email');
        }
        
        // ATUALIZA A INSTÂNCIA
        $obUser->setNomUser($nome);
        $obUser->setEmail($email);
        
        // ATUALIZA O USUÁRIO
        $obUser->updateUser();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/profile?status=profile_updated');
    }

    /**
     * Método responsável por definir o texto de acordo com o tipo de usuário
     * @param EntityUser $obUser
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
