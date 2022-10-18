<?php
namespace App\Models\Entity;

use \App\Utils\Database;

class User {

    /**
     * ID do usuario
     * @var integer
     */
    public $id_usuario;

    /**
     * Nome do usuario
     * @var string
     */
    public $nom_usuario;

    /**
     * Email do usuario
     * @var string
     */
    public $email;

    /**
     * Senha do usuario
     * @var string
     */
    public $senha;

    /**
     * Codigo da foto do usuario
     * @var string
     */
    public $img_perfil;

    /**
     * Identificador de status do usuario
     * @var string
     */
    public $ativo;

    /**
     * Data de criação do usuario
     * @var string
     */
    public $add_data;

    /**
     * Nivel de acesso do usuario
     * @var integer
     */
    public $fk_acesso_id_acesso;

    /**
     * Metodo responsavel por verificar verificar se o nome de entrada esta nos parametros do site
     * @param  string $nome
     * @return boolean
     */
    public static function validateUserName($nome) {
        // VERIFICA SE A STRING POSSUI NUMEROS OU CARACTER ESPECIAIS
        if (preg_match('/^[a-zA-Z\s]+$/', $nome)){
            return false;
        }
        return true;
    }

    /**
     * Metodo responsavel por verificar se o email de entrada é válido
     * @param  string $email
     * @return boolean
     */
    public static function validateUserEmail($email) {
        // SANITIZA O EMAIL
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // VALIDA O FORMATO DO EMAIL
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    /**
     * Metodo responsavel por verificar se a senha atende os requisitos de segurança
     * @param  string $password
     * @return boolean
     */
    public static function verifyUserPassword($password) {
        // MÍNIMO DE SEIS CARACTERES, PELO MENOS UMA LETRA, UM NÚMERO E UM CARACTERE ESPECIAL
        $parameters = "^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?])[A-Za-z\d@$!%*#?]{6,36}$";

        if (preg_match($parameters, $password)) {
            return false;
        }
        return true;
    }

    /**
     * Metodo responsavel por verificar se as senhas conicidem
     * @param  string $password
     * @param  string $confirm
     * @return boolean
     */
    public static function validateUserPassword($password, $confirm) {
        // VERIFICA SE AS SENHAS SÃO IGUAIS
        if ($password !== $confirm) {
            return false;
        }
        return true;
    }

    /**
     * Metodo responsavel por verificar se o usuario esta ativo
     * @return boolean
     */
    public function verifyUserIsActive() {
        // VERIFICA SE O USUARIO ESTA ATIVO
        if ($this->ativo == 0) {
            return false;
        }
        return true;
    }

    /**
     * Methodo responsavel por cadastrar a istancia atual no banco de dados
     * @return boolean
     */
    public function insertUser() {
        // INSERE A ISTANCIA NO BANCO
        $this->id_usuario = (new Database('usuario'))->insert([
            'nom_usuario' => $this->nom_usuario,
            'email'       => $this->email,
            'senha'       => $this->senha
        ]);
        // SUCESSO
        return true;
    }

    /**
     * Methodo responsavel por atualizar os dados no banco
     * @return boolean
     */
    public function updateUser() {
        return (new Database('usuario'))->update("id_usuario = {$this->id_usuario}", [
            'nom_usuario' => $this->nom_usuario,
            'email'       => $this->email,
            'senha'       => $this->senha
        ]);
    }

    /**
     * Methodo responsavel por excluir um usuario do banco
     * @return boolean
     */
    public function deleteUser() {
        return (new Database('usuario'))->delete("id_usuario = {$this->id_usuario}");
    }

    /**
     * Méthodo responsavel por retornar usuario
     * @param  string $where
     * @param  string $order
     * @param  string $limit
     * @param  string $fields
     * @return mixed
     */
    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*') {
        return (new Database('usuario'))->select($where, $order, $limit, $fields);
    }

    /**
     * Methodo responsavel por retornar uma istancia com base no ID
     * @param  integer $id
     * @return User
     */
    public static function getUserById($id) {
        return self::getUsers("id_usuario = $id")->fetchObject(self::class);
    }

    /**
     * Methodo responsavel por retornar um usuario com base em seu email
     * @param  string $email
     * @return User
     */
    public static function getUserByEmail($email) {
        return self::getUsers("email = '$email'")->fetchObject(self::class);
    }   
}