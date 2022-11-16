<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Grade   as EntityGrade;
use App\Models\Student as EntityStudent;
use App\Models\Teacher as EntityTeacher;
use App\Models\User    as EntityUser;
use App\Utils\Tools\Alert;
use App\Utils\Sanitize;
use App\Utils\View;
use App\Utils\Session;
use App\Utils\Upload;

class Profile extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página perfil
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    public static function getEditProfile(Request $request) {
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
     * @param \App\Models\User $obUser
     * 
     * @return array
     */
    public static function getTextType(EntityUser $obUser): array {
        // DECLARAÇÃO DE VARIAVEIS
        $text = '';
        $colum = '';

        // RELATIVO NIVEL DE ACESSO DO USUARIO
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
                $text = 'Setor';
                $colum = 'sector';
                break;
                
            case 5:
                $text = 'Regras';
                $colum = 'rules';
                break;            
        }
        // RETORNA O TEXTO E A VIEW DA COLUNA
        return [
            'text' => $text,
            'colum' => View::render("pages/profile/$colum")
        ];
    }

    /**
     * Método responsável por atualizar o perfil do usuário
     * @param \App\Http\Request $request
     * 
     * @return void
     */
    public static function setEditProfile(Request $request): void {
        // POST VARS    
        $postVars = $request->getPostVars();

        // VERIFICA HTML INJECT
        if (Sanitize::validateForm($postVars)) {
            $request->getRouter()->redirect('/signup?status=invalid_chars');
        }
        // SANITIZA O ARRAY
        $postVars = Sanitize::sanitizeForm($postVars);
        $files = $request->getUploadFiles();

        // OBTEM O USUARIO E O NIVEL DE ACESSO DA SESSÃO
        $obUser = Session::getSessionUser();
        $acesso = Session::getSessionLv();

        $photo = $obUser->getImg_perfil();
        $nome = $postVars['nome'];
        $email = $postVars['email'];

        // NOVA INSTANCIA 
        $obUpload = new Upload($files['foto']);

        // VERIFICA SE HOUVE UPLOAD DE FOTO
        if (is_uploaded_file($obUpload->tmpName) && $obUpload->error != 4) { 
            // VERIFICA SE O PROCESSO DE UPLOAD FOI REALIZADO
            if (!self::uploadProfilePicture($obUpload, $photo)) {
                $request->getRouter()->redirect('/profile?status=upload_error');
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
     * Método responsavel por realizar o upload da imagem enviada pelo usuario
     * @param \App\Utils\Upload $obUpload
     * @param string $photo
     * 
     * @return bool e alteração de referencia da $photo
     */
    private static function uploadProfilePicture(Upload $obUpload, string &$photo): bool {
        // VERIFICA SE O ARQUIVO E MENOR DO QUE O ACEITO
        if ($_POST['MAX_FILE_SIZE'] > $obUpload->size) {
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
            if ($obUpload->upload(__DIR__.'/../../../public/uploads/')) {
                return true;
            }
            return false;
        }
        return false;
    }  

    /**
     * Método responsável por atualizar os usuário comum para o tipo aluno
     * @param \App\Http\Request $request
     * @param \App\Models\User  $obUser
     * @param array $postVars
     * 
     * @return void
     */
    private static function registerStudent(Request $request, EntityUser $obUser, array $postVars): void {
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
     * @param \App\Models\User $obUser
     * @param array $postVars
     * 
     * @return void
     */
    private static function updateStudent(EntityUser $obUser, array $postVars): void {
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
     * @param \App\Models\User $obUser
     * @param array $postVars
     * 
     * @return void
     */
    private static function updateTeacher(EntityUser $obUser, array $postVars): void {
        $regras = $postVars['regras'] ?? '';

        // VERIFICA SE O CAMPO ESTA VAZIO
        if (!empty($regras)) {
            // NOVA INSTANCIA
            $obTeacher = new EntityTeacher($obUser->getId_usuario(), $regras);

            $obTeacher->updateRules(); // ATUALIZA AS REGRAS DO PROFESSOR
        }  
    }
}
