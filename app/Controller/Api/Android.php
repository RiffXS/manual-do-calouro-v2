<?php 

namespace App\Controller\Api;

use App\Http\Request;
use App\Models\Constant\HorarioAula as EntityTime;
use App\Models\Contato as EntityContact;
use App\Models\Aula as EntitySchedule;
use App\Models\Aluno as EntityStudent;
use App\Models\Usuario as EntityUser;
use App\Utils\Sanitize;
use App\Utils\Upload;

Class Android {

    /**
     * Método responsável por retorna o código & mensagem da API
     * @param integer $code
     * @param string  $message
     * @param array   $dados
     * 
     * @return array
     */
    private static function sendResponse(int $code, string $message = '', array $dados = []): array {
        // RETORNA A RESPONSE
        return array(
            'success' => $code,
            'message' => $message,
            'content' => $dados
        );
    }

    /**
     * Método responsável por processar o cadastro de um usuário API-Android
     * @param \App\Http\Request $request
     * 
     * @return array
     */
    public static function signUpActivity(Request $request): array {
        // PARAMETROS NECESSARIOS
        $params = array('nome', 'email', 'senha', 'matricula');

        // GET POSTVARS & FILES
        $postVars = $request->getPostVars();
        $files = $request->getUploadFiles();

        // VERIFICA SE TODOS OS PARAMETROS EXISTEM
        if (!Sanitize::verifyParams($params, $postVars)) {
            return self::sendResponse(0, "Campo requerido não preenchido");
        }
        // VERIFICA SE A IMAGEM FOI ENVIADA
        if (!isset($files['img'])) {
            return self::sendResponse(0, "Imagem não enviada!");
        }
        // NOVA INSTANCIA DE UPLOAD
        $obUpload = new Upload($files['img']);
    
        $obUpload->generateNewName();
        
        // RELIZZA O PROCESSO DE UPLOAD
        $status = Upload::profilePicture($obUpload);

        // VERIFICA SE RETORNOU ALGUM STATUS DE ERRO
        if (!empty($status)) {
            return self::sendResponse(0, $status);
        }
        // VALIDA O NOME
        if (Sanitize::validateName($postVars['nome'])) {
            return self::sendResponse(0, 'Nome inválido!');
        }
        // VALIDA O EMAIL
        if (Sanitize::validateEmail($postVars['email'])) {
            return self::sendResponse(0, 'E-mail inválido!');
        }
        // CONSULTA O USUARIO UTILIZANDO O EMAIL
        $obUser = EntityUser::getUserByEmail($postVars['email']);

        // VALIDA A INSTANCIA, VERIFICANDO SE O EMAIL JÁ ESTA SENDO UTILIZADO
        if ($obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/signup?status=duplicated_email');
        }
        // NOVA INSTANCIA DE USUARIO
        $obUser = new EntityUser; 

        $obUser->setNom_usuario($postVars['nome']);
        $obUser->setEmail($postVars['email']);
        $obUser->setSenha($postVars['senha']);
        $obUser->setImg_perfil($obUpload->getBasename());
        $obUser->setFk_acesso(3);

        // VERIFICA SE O USUARIO FOI INSERIDO COM SUCESSO
        if (!$obUser->insertUser()) {
            return self::sendResponse(0, "Erro ao cadastrar o usuário no BD");
        }
        // NOVA INSTANCIA DE ESTUDANTE
        $obStudent = new EntityStudent;

        // SET ATRIBUTOS
        $obStudent->setFk_id_usuario($obUser->getId_usuario());
        $obStudent->setNum_matricula($postVars['matricula']);

        // VERIFICA A DISPONIBILIDADE DA MATRICULA
        if (!$obStudent->verifyEnrollment()) {
            return self::sendResponse(0, 'Matrícula já cadastrada');
        }
        // RETORNA SUCESSO
        return self::sendResponse(1, 'Usuário cadastrado com sucesso!');
    }

    /**
     * Método responsável por processar o login de um usuário API-Android
     * @param \App\Http\Request $request
     * 
     * @return array|bool
     */
    public static function singInActivity(Request $request): mixed {
        // VERIFICA A EXISTENCIA DOS DADOS DE ACESSO
        if (!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW'])) {
            return false;
        }
        // BUSCA USUARIO PELO EMAIL
        $obUser = EntityUser::getUserByEmail($_SERVER['PHP_AUTH_USER']);

        // VALIDA A INSTÂNCIA
        if (!$obUser instanceof EntityUser) {
            // senha ou usuario nao confere
            return self::sendResponse(0, 'Usuário ou senha não conferem');
        }
        // VERIFICA A SENHA DO USUARIO
        if (!password_verify($_SERVER['PHP_AUTH_PW'], $obUser->getSenha())) {
            return self::sendResponse(0, 'Usuário ou senha não conferem');
        }
        // RETORNA SUCESSO
        return self::sendResponse(1, 'Sessão iniciada :)');
    }

    /**
     * Método responsavel por consultar as aulas de uma turma
     * @param \App\Http\Request $request
     * 
     * @return array
     */
    public static function scheduleActivity(Request $request): array {
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        // VERIFICA SE OS PARAMETROS ESTÃO DEFINIDOS
        if (!isset($queryParams['curso']) or !isset($queryParams['modulo'])) {
            return self::sendResponse(0, 'Campo requerido não preenchido');
        }
        // ATRIBUINDO VARIAVEIS
        $curso  = $queryParams['curso'];
        $modulo = $queryParams['modulo'];

        // RETORNA SUCESSO
        return self::sendResponse(1, '', [
            'aulas' => EntitySchedule::getScheduleClass($curso, $modulo),
            'horas' => EntityTime::getTimes()
        ]);
    }

    /**
     * Método responsavel por consultar os dados de contatos
     * @param \App\Http\Request $request
     * 
     * @return array
     */
    public static function contactsActivity(Request $request): array {
        // RETORNA SUCESSO
        return self::sendResponse(1, '', [
            'professor' => EntityContact::getContactTeacher(),
            'servidor'  => EntityContact::getContactServer()
        ]);
    }
}