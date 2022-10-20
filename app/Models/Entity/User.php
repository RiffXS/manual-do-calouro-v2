<?php
namespace App\Models\Entity;

use \App\Utils\Database;

class User {

    /**
     * ID do usuario
     * @var integer
     */
    private $id_usuario;

    /**
     * Nome do usuario
     * @var string
     */
    private $nom_usuario;

    /**
     * Email do usuario
     * @var string
     */
    private $email;

    /**
     * Senha do usuario
     * @var string
     */
    private $senha;

    /**
     * Codigo da foto do usuario
     * @var string
     */
    private $img_perfil;

    /**
     * Identificador de status do usuario
     * @var string
     */
    private $ativo;

    /**
     * Data de criação do usuario
     * @var string
     */
    private $add_data;

    /**
     * Nivel de acesso do usuario
     * @var integer
     */
    private $fk_acesso_id_acesso;

    /**
     * Atribui o nome do usuario
     * @param string $value
     */
    public function setNomUser($name) { 
        $this->nom_usuario = $name;
    }

    /**
     * Obtem o nome do usuario
     * @return string
     */
    public function getNomUser() { 
        return $this->nom_usuario;
    }

    /**
     * Atribui o email do usuario
     * @param string $email
     */
    public function setEmail($email) { 
        $this->email = $email;
    }

    /**
     * Obtem o email do usuario
     * @return string
     */
    public function getEmail() { 
        return $this->email;
    }

    /**
     * Atribui a senha do usuario
     * @param string $senha
     */
    public function setPass($senha) { 
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
    }

    /**
     * Obtem a senha do usuario
     * @return string
     */
    public function getPass() { 
        return $this->senha;
    }

    /**
     * Atribui a imagem de perfil do usuario
     * @param string $hash
     */
    public function setImgProfile($hash) { 
        $this->img_perfil = $hash;
    }

    /**
     * Obtem a imagem de perfil do usuario
     * @return string
     */
    public function getImgPrfoile() { 
        return $this->img_perfil;
    }

    /**
     * Atribui o status de atividade do usuario
     * @param integer
     */
    public function setActive($boleano) { 
        $this->ativo = $boleano;
    }

    /**
     * Obtem o status do usuario
     * @return integer
     */
    public function getActive() { 
        return $this->ativo;
    }

    /**
     * Atribui a data de criação do usuario em timestamp
     * @param string $data
     */
    public function setAddData($data) { 
        $this->add_data = $data;
    }

    /**
     * Obtem a data de criação do usuario legivel
     * @return string 
     */
    public function getAddData() { 
        return date('d/m/Y H:i:s', strtotime($this->add_data));
    }

    /**
     * Atribui o nivel de acesso do usuario
     * @param integer
     */
    public function setAcess($acesso = 2) { 
        $this->fk_acesso_id_acesso = $acesso;
    }

    /**
     * Obtem o nivel de acesso do usuario
     * @return integer
     */
    public function getAcess() { 
        return $this->fk_acesso_id_acesso;
    }

    /**
     * Methodo responsavel por cadastrar a istancia atual no banco de dados
     * @return boolean
     */
    public function insertUser() {
        $this->add_data = date('Y-m-d H:i:s');
        $this->ativo = 1; //1 true : 0 false 

        // INSERE A ISTANCIA NO BANCO
        $this->id_usuario = (new Database('usuario'))->insert([
            'nom_usuario' => $this->nom_usuario,
            'email'       => $this->email,
            'senha'       => $this->senha,
            'add_data'    => $this->add_data,
            'ativo'       => $this->ativo,
            'fk_acesso_id_acesso' => 2
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
    
    /**
     * Metodo responsavel por retornar o nivel de acesso do usuario 
     * @param integer $id
     * @return User
     */
    public static function getUserAcess($id) {
        return self::getUsers("id_usuario = $id", null, 1, 'fk_acesso_id_acesso')->fetchObject(self::class);
    }

    /**
     * 
     * 
     */
    public static function getUserClass($id) {
        $sql = "SELECT t.fk_curso_id_curso AS curso, t.num_modulo AS modulo 
                FROM turma t JOIN aluno a
                ON (t.id_turma = a.fk_turma_id_turma)
                WHERE a.fk_usuario_id_usuario = $id";

        $dados = (new Database('turma t'))->query($sql)->fetch(\PDO::FETCH_ASSOC);

        return URL."/schedule?curso={$dados['curso']}&modulo={$dados['modulo']}";  
    }

    /**
     * Metodo responsavel por verificar verificar se o nome de entrada esta nos parametros do site
     * @param  string $nome
     * @return boolean
     */
    public static function validateUserName($nome) {
        $parameters = '/^[a-zA-Z\s]+$/';

        // VERIFICA SE A STRING POSSUI NUMEROS OU CARACTER ESPECIAIS
        if (preg_match($parameters, $nome)){
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
        $parameters = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d].\S{6,36}$/';

        // MÍNIMO DE SEIS CARACTERES, PELO MENOS UMA LETRA, UM NÚMERO E UM CARACTERE ESPECIAL
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
        if (!$password !== $confirm) {
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
}