<?php 

namespace App\Controller\Api;

use App\Http\Request;
use App\Models\Aluno as EntityStudent;
use App\Models\Usuario as EntityUser;
use App\Utils\Sanitize;
use App\Utils\Upload;

Class Android {

    /**
     * Campos do cadastro
     * @var array
     */
    private static $p_cadastro = [
        'nome',
        'email',
        'senha',
        'matricula'
    ];

    /**
     * Método responsavel por processar o cadastro dé um usuario API-Android
     * @param \App\Http\Request $request
     * 
     * @return array
     */
    public static function cadastroActivity(Request $request): array {
        // GET POSTVARS & FILES
        $postVars = $request->getPostVars();
        $files = $request->getUploadFiles();

        // VERIFICA SE TODOS OS PARAMETROS EXISTEM
        if (!Sanitize::verifyParams(self::$p_cadastro, $postVars)) {
            return [
                'sucesso' => 0,
                'erro'    => "Campo requerido não preenchido"
            ];
        }
        // VERIFICA SE A IMAGEM FOI ENVIADA
        if (!isset($files['img'])) {
            return [
                'sucesso' => 0,
                'erro'    => "Imagem não enviada!"
            ];
        }


        // NOVA INSTANCIA DE UPLOAD
        $obUpload = new Upload($files['img']);
    
        $obUpload->generateNewName();
        
        // RELIZZA O PROCESSO DE UPLOAD
        $status = Upload::profilePicture($obUpload);

        // VERIFICA SE RETORNOU ALGUM STATUS DE ERRO
        if (!empty($status)) {
            return [
                'sucesso' => 0,
                'erro'    => $status
            ];
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
            return [
                'sucesso' => 0,
                'erro'    => 'Erro ao cadastrar o usuário no BD'
            ];
        }
        // NOVA INSTANCIA DE ESTUDANTE
        $obStudent = new EntityStudent;

        // SET ATRIBUTOS
        $obStudent->setFk_id_usuario($obUser->getId_usuario());
        $obStudent->setNum_matricula($postVars['matricula']);

        // VERIFICA A DISPONIBILIDADE DA MATRICULA
        if (!$obStudent->verifyEnrollment()) {
            return [
                'sucesso' => 0,
                'erro'    => 'Matrícula já cadastrada'
            ];
        }

        // RETORNA SUCESSO
        return [
            'sucesso' => 1,
            'erro'    => 'Usuário cadastrado com sucesso!'
        ];
    }

    public static function loginActivity($request) {

    }
}