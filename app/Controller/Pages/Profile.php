<?php

namespace App\Controller\Pages;

use App\Models\Grade as EntityGrade;
use App\Models\Student as EntityStudent;
use App\Models\Teacher as EntityTeacher;
use App\Models\User as EntityUser;
use App\Utils\Tools\Alert;
use App\Utils\Sanitize;
use App\Utils\View;
use App\Utils\Session;
use App\Utils\Upload;

class Profile extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página perfil
     * @param  \App\Http\Request $request
     * @return string 
     */
    public static function getEditProfile($request) {
        // OBTEM A IMAGEM DO USUARIO
        $id = Session::getSessionId();
        $obUser = EntityUser::getUserById($id);

        $view = self::getTextType($obUser);

        // VIEW DA HOME
        $content = View::render('pages/profile', [
            'status' => Alert::getStatus($request),
            'foto'   => $obUser->getImg_perfil(),
            'nome'   => $obUser->getNom_usuario(),
            'email'  => $obUser->getEmail(),
            'texto'  => $view['text'],
            'campo'  => $view['colum']
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Perfil', $content);
    }

    /**
     * Método responsável por definir o texto de acordo com o tipo de usuário
     * @param EntityUser $obUser
     */
    public static function getTextType($obUser) {
        $text = '';
        $colum = '';

        switch ($obUser->getFk_acesso()) {
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

    /**
     * Método responsável por atualizar o perfil do usuário
     * @param \App\Http\Request $request
     */
    public static function setEditProfile($request) {
        // OBTEM O USUARIO E O NIVEL DE ACESSO DA SESSÃO
        $obUser = Session::getSessionUser();
        $acesso = Session::getSessionLv();

        // POST VARS    
        $postVars = $request->getPostVars(); 
        $files    = $request->getUploadFiles();

        $nome = $postVars['nome'];
        $email = $postVars['email'];
        $photo = $obUser->getImg_perfil();

        // NOVA INSTANCIA 
        $obUpload = new Upload($files['foto']);

        // VERIFICA SE HOUVE UPLOAD DE FOTO
        if (is_uploaded_file($obUpload->tmpName) && $obUpload->error != 4) { 
            // VERIFICA SE O ARQUIVO E MENOR DO QUE O ACEITO
            if ($postVars['MAX_FILE_SIZE'] > $obUpload->size) {
                // VARIFICA SE O USUARIO POSSUI UMA FOTO
                if ($photo == 'user.png') {
                    $obUpload->generateNewName();      // GERA UM NOME NOVO
                    $photo = $obUpload->getBasename(); // OBTEM O NOME NOVO
                } 
                else {
                    // ATRIBUI O NOME AO JA EXISTENTE DO USUARIO
                    $obUpload->name = pathinfo($photo, PATHINFO_FILENAME);
                }
                // FAZ O UPLOAD DA FOTO PARA PASTA DE UPLOADS
                if (!$obUpload->upload(__DIR__.'/../../../public/uploads/')) {
                    $request->getRouter()->redirect('/profile?status=upload_error');
                }
            }
        }  
        // REALIZA UMA AÇÃO DEPENDENDO DO TIPO DE USUARIO
        switch ($acesso) {
            case 2:
                self::registerStudent($request, $obUser, $postVars);
                
                break;

            case 3:
                self::updateStudent($obUser, $postVars);

                break;
                
            case 4:
                self::updateTeacher($obUser, $postVars);
                              
                break;
        }
        // VALIDA O NOME
        if (Sanitize::validateName($nome)) {
            $request->getRouter()->redirect('/profile?status=invalid_name');
        }
        // VALIDA O EMAIL
        if (Sanitize::validateEmail($email)) {
            $request->getRouter()->redirect('/profile?status=invalid_email');
        }
        // VALIDA O EMAIL DO USUARIO
        $obUserEmail = EntityUser::getUserByEmail($email);

        if ($obUserEmail instanceof EntityUser && $obUserEmail->getId_usuario() != $obUser->getId_usuario()) {
            $request->getRouter()->redirect('/profile?status=duplicated_email');
        }
        // ATUALIZA A INSTÂNCIA
        $obUser->setNom_usuario($nome);
        $obUser->setEmail($email);
        $obUser->setImg_perfil($photo);
        
        // ATUALIZA O USUÁRIO
        $obUser->updateUser();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/profile?status=profile_updated');
    }

    

    /**
     * Método responsável por atualizar os usuário comum para o tipo aluno
     * @param \App\Http\Request 
     * @param EntityUser $obUser
     * @param array $PostVars
     */
    private static function registerStudent($request, $obUser, $postVars) {
        $matricula = $postVars['matricula'] ?? '';

        // VERIFICA SE A MATRICULA ESTA VAZIA
        if (!empty($matricula)) {
            // NOVA INSTANCIA
            $obStudent = new EntityStudent($obUser->getId_usuario(), $matricula);
        
            // VERIFICA SE A MATRICULA ESTA DISPONIVEL
            if ($obStudent->verifyEnrollment()) {
                // INSERE O USUARIO NA TABELA DE ALUNOS
                $obStudent->insertStudent();
                $obUser->setFk_acesso(3); // ALTERA O NIVEL DE ACESSO PARA 3 (ALUNO)
            } else {
                $request->getRouter()->redirect('/profile?status=enrollment_duplicated');
            }
        }
    }

    /**
     * Método responsável por atualizar a turma do usuário
     * @param EntityUser $obUser
     * @param array $postVars
     */
    private static function updateStudent($obUser, $postVars) {
        $curso  = $postVars['curso'] ?? '';
        $modulo = $postVars['modulo'] ?? '';

        // VERIFICA SE O CURSO E O MODULO FORAM RECEBIDOS
        if (!empty($modulo) && !empty($curso)) {
            // BUSCA O ID DA TURMA POR CURSO E MODULO
            $gradeId = EntityGrade::getGradeId($curso, $modulo);

            // NOVA INSTANCIA
            $obStudent = new EntityStudent($obUser->getId_usuario());
            $obStudent->setFk_turma($gradeId['id_turma']);

            $obStudent->updateStudent(); // ATUALIZA A TURMA DO ALUNO
        }
    }

    /**
     * Método responsável por atualizar as regras do professor
     * @param EntityUser $obUser
     * @param array $postVars
     */
    private static function updateTeacher($obUser, $postVars) {
        $regras = $postVars['regras'] ?? '';

        // VERIFICA SE O CAMPO ESTA VAZIO
        if (!empty($regras)) {
            // NOVA INSTANCIA
            $obTeacher = new EntityTeacher($obUser->getId_usuario(), $regras);

            $obTeacher->updateRules(); // ATUALIZA AS REGRAS DO PROFESSOR
        }  
    }
}
