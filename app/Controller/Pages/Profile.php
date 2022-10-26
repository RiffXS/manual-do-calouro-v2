<?php

namespace App\Controller\Pages;

use App\Models\Entity\Grade as EntityGrade;
use App\Models\Entity\Student as EntityStudent;
use App\Models\Entity\Teacher as EntityTeacher;
use App\Models\Entity\User as EntityUser;
use App\Utils\View;
use App\Utils\Session;
use App\Utils\Upload;
use App\Utils\Tools\Alert;

class Profile extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina perfil
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
     * Método responsavel por atualizar o perfil do usuario
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
        $photo = $obUser->getImgProfile();

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
                $obUpload->upload(getenv('DIR').'/public/uploads/');
            }
        }  
        // REALIZA UMA AÇÃO DEPENDENDO DO TIPO DE USUARIO
        switch ($acesso) {
            case 2:
                $matricula = $postVars['matricula'] ?? '';

                // VERIFICA SE A MATRICULA ESTA VAZIA
                if (!empty($matricula)) {
                    // NOVA INSTANCIA
                    $obStudent = new EntityStudent($obUser->getUserId(), $matricula);
                
                    // VERIFICA SE A MATRICULA ESTA DISPONIVEL
                    if ($obStudent->verifyEnrollment()) {
                        // INSERE O USUARIO NA TABELA DE ALUNOS
                        $obStudent->insertStudent();
                        $obUser->setAcess(3); // ALTERA O NIVEL DE ACESSO PARA 3 (ALUNO)
                    }
                }
                break;

            case 3:
                $curso  = $postVars['curso'] ?? '';
                $modulo = $postVars['modulo'] ?? '';

                // VERIFICA SE O CURSO E O MODULO FORAM RECEBIDOS
                if (!empty($modulo) && !empty($curso)) {
                    // BUSCA O ID DA TURMA POR CURSO E MODULO
                    $gradeId = EntityGrade::getGradeId($curso, $modulo);

                    // NOVA INSTANCIA
                    $obStudent = new EntityStudent($obUser->getUserId());
                    $obStudent->fk_turma_id_turma = $gradeId['id_turma'];

                    $obStudent->updateStudent(); // ATUALIZA A TURMA DO ALUNO
                }
                break;
                
            case 4:
                $regras = $postVars['regras'] ?? '';

                // VERIFICA SE O CAMPO ESTA VAZIO
                if (!empty($regras)) {
                    // NOVA INSTANCIA
                    $obTeacher = new EntityTeacher($obUser->getUserId(), $regras);

                    $obTeacher->updateRules(); // ATUALIZA AS REGRAS DO PROFESSOR
                }                
                break;
        }
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
        $obUser->setImgProfile($photo);
        
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
