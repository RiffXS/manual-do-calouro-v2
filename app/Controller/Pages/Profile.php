<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Turma as EntityGrade;
use App\Models\Admin as EntityAdmin;
use App\Models\Setor as EntitySector;
use App\Models\Aluno as EntityStudent;
use App\Models\Usuario as EntityUser;
use App\Models\Professor as EntityTeacher;
use App\Models\GrupoAluno as EntityGroupStudent;
use App\Utils\Tools\Alert;
use App\Utils\Sanitize;
use App\Utils\Session;
use App\Utils\Upload;
use App\Utils\View;

class Profile extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página perfil
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    public static function getEditProfile(Request $request): string {
        // OBTEM A IMAGEM DO USUARIO
        $obUser = EntityUser::getUserById(Session::getId());

        $view = self::getTextType($obUser);

        // VIEW DA HOME
        $content = View::render('pages/profile', [
            'status'   => Alert::getStatus($request),
            'foto'     => $obUser->getImg_perfil(),
            'nome'     => $obUser->getNom_usuario(),
            'email'    => $obUser->getEmail(),
            'texto'    => $view['text'],
            'campo'    => $view['column']
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Perfil', $content);
    }

    /**
     * Método responsável por definir o texto de acordo com o tipo de usuário
     * @param \App\Models\Usuario $obUser
     * 
     * @return array
     */
    public static function getTextType(EntityUser $obUser): array {
        // DECLARAÇÃO DE VARIAVEIS
        $text = '';
        $column = '';

        // RELATIVO NIVEL DE ACESSO DO USUARIO
        switch ($obUser->getFk_acesso()) {
            case 2:
                $text = 'Matrícula';
                $column = View::render('pages/components/profile/enrollment');

                break;

            case 3:
                // DECLARAÇÃO DE VARIAVEIS
                $text = 'Turma';
                $hidden = '';

                // CONSULTA A TURMA DO ALUNO
                $class = $obUser->getUserClass($obUser->getId_usuario());

                if (!empty($class)) {
                    $hidden = parent::setHiddens($class);
                }

                $column = View::render('pages/components/profile/class', [
                    'hidden'   => $hidden,
                    'curso'    => self::getCourse(),
                    'modulo'   => self::getModule()
                ]);

                break;

            case 4:
                // CONSULTA O SETOR DO SERVIDOR
                $setor = $obUser->getUserSector($obUser->getId_usuario());

                $text = 'Setor';
                $column = View::render('pages/components/profile/sector', [
                    'h-setor' => $setor['setor'],
                    'setor'   => self::getSector()
                ]);
                
                break;
                
            case 5:
                // CONSULTA OS DADOS DO PROFESSOR
                $obTeacher = EntityTeacher::getTeacherById($obUser->getId_usuario());
                $text = 'Regras';

                $column = View::render('pages/components/profile/rules', [
                    'regras' => $obTeacher->getRules()
                ]);

                break;            
        }
        // RETORNA O TEXTO E A VIEW DA COLUNA
        return [
            'text'  => $text,
            'column' => $column
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
        $obUser = Session::getUser();
    
        // LAMBDA - VERIFICA SE EXISTE UM STATUS DE ERRO
        $isError = function($key) use ($request): void {
            if (!empty($key)) {
                $request->getRouter()->redirect("/profile?status=$key");
            }
        };
        // NOVA INSTANCIA DE UPLOAD
        $obUpload = new Upload($files['foto']);

        // OBTEM O NOME DA IMAGEM DO USUARIO
        $photo = $obUser->getImg_perfil();

        // VERIFICA SE O ARQUIVO EXISTEM NO ARMAZENAMENTO TEMPORARIO
        if (file_exists($obUpload->getTpmName())) {
            // VERIFICA SE É O PRIMEIRO UPLOAD 
            if ($photo == 'user.png') {
                $obUpload->generateNewName();
            } 
            else {
                // OBTEM AS INFORMAÇÕES DO ARQUIVO
                $info = pathinfo($photo);

                if ($obUpload->getExtension() != $info['extension']) {
                    unlink(__DIR__."/../../../public/uploads/".$photo);
                }
                // ATRIBUI O NOME AO JA EXISTENTE DO USUARIO
                $obUpload->setName($photo);
            }
            $isError(Upload::profilePicture($obUpload));
        }
        // ATUALIZA A VARIAVEL COM O NOME DA FOTO     
        $photo = $obUpload->getBasename();

        // ATUALIZA O CAMPO DO TIPO USUARIO
        self::updateProfileUser($request, $obUser, $postVars);

        $nome = $postVars['nome'];
        $email = $postVars['email'];

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
     * Método responsável por realizar uma operação relativo ao tipo de usuário atual
     * @param \App\Http\Request $request
     * @param \App\Models\Usuario  $obUser
     * @param array $postVars
     * 
     * @return void
     */
    private static function updateProfileUser(Request $request, EntityUser $obUser, array $postVars): void {
        // REALIZA UMA AÇÃO DEPENDENDO DO TIPO DE USUÁRIO
        switch ($obUser->getFk_acesso()) {
            // USUÁRIO
            case 2:
                if (!empty($postVars['matricula'])) {
                    // NOVA INSTÂNCIA
                    $obStudent = new EntityStudent;
        
                    $obStudent->setFk_id_usuario($obUser->getId_usuario());
                    $obStudent->setNum_matricula($postVars['matricula']);
                
                    // VERIFICA SE A MATRÍCULA ESTA DISPONIVEL
                    if (!$obStudent->verifyEnrollment()) {
                        $request->getRouter()->redirect('/profile?status=enrollment_duplicated');
                    } 
                    // INSERE O USUÁRIO NA TABELA DE ALUNOS
                    $obStudent->insertStudent();
                }

                // ALTERA O NÍVEL DE ACESSO PARA 3 (ALUNO)
                $obUser->setFk_acesso(3); 
                
                break;
                
            // ALUNO
            case 3:
                // VERIFICA SE O CURSO E O MÓDULO FORAM RECEBIDOS
                if (!empty($postVars['curso']) && !empty($postVars['modulo'])) {
                    // NOVA INSTÂNCIA
                    $obGroupStudent = new EntityGroupStudent;
 
                    $obGroupStudent->setFk_id_usuario($obUser->getId_usuario());
                    $grupo = $obGroupStudent->getFk_id_grupo();

                    if ($grupo != 0) {
                        $obGroupStudent->updateGroupStudent(); // ATUALIZA A TURMA DO ALUNO
                    } else {
                        $obGroupStudent->findGroup((int)$postVars['curso'], (int)$postVars['modulo']);

                        $obGroupStudent->insertGroupStudent();
                    }
                }

                break;

            // SERVIDOR
            case 4:
                if (!empty($postVars['setor'])) {
                    $obAdmin = new EntityAdmin;

                    $obAdmin->setFk_id_usuario($obUser->getId_usuario());
                    $obAdmin->setFk_id_setor($postVars['setor']);

                    $obAdmin->updateAdmin();
                }

                break;

            // PROFESSOR
            case 5:
                // VERIFICA SE O CAMPO ESTA VAZIO
                if (!empty($postVars['regras'])) {
                    // NOVA INSTANCIA
                    $obTeacher = new EntityTeacher();

                    $obTeacher->setFk_id_usuario($obUser->getId_usuario());
                    $obTeacher->setRules($postVars['regras']);

                    $obTeacher->updateRules(); // ATUALIZA AS REGRAS DO PROFESSOR
                }

                break;
        }
    }

    /**
     * Método responsável por retornar a view dos setores
     * @return string
     */
    public static function getSector(): string {
        // DECLARAÇÃO DE VARIÁVEIS
        $content = '';

        // ARRAY COM TODOS OS SETORES CADASTRADOS
        $setores = EntitySector::getSector();

        for ($i = 0; $i < count($setores); $i++) {
            $content .= View::render('pages/components/profile/sector-item', [
                'id'    => $setores[$i]['id_setor'],
                'setor' => $setores[$i]['dsc_setor']
            ]);
        }
        // RETORNA O CONTEÚDO
        return $content;
    }

    /**
     * Método responsável por retornar a view dos cursos
     * @return string
     */
    public static function getCourse(): string {
        // DECLARAÇÃO DE VARIÁVEIS
        $content = '';

        // ARRAY COM TODOS OS CURSOS CADASTRADOS
        $cursos = EntityGrade::getCursos();

        for ($i = 0; $i < count($cursos); $i++) {
            $content .= View::render('pages/components/profile/course', [
                'id'       => $cursos[$i]['id_curso'],
                'curso'    => $cursos[$i]['dsc_curso']
            ]);
        }
        // RETORNA O CONTEÚDO
        return $content;
    }

    /**
     * Método responsável por retornar a view dos módulos
     * @return string
     */
    public static function getModule(): string {
        // DECLARAÇÃO DE VARIÁVEIS
        $content = '';

        for ($i = 1; $i < 7; $i++) {
            $content .= View::render('pages/components/profile/module', [
                'id'       => "$i",
                'modulo'   => "$i"
            ]);
        }
        // RETORNA O CONTEÚDO
        return $content;
    }
}
