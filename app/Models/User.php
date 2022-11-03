<?php

namespace App\Models;

use App\Utils\Database;

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
     * Data de criação do usuario
     * @var string
     */
    private $add_data;

    /**
     * Identificador de status do usuario
     * @var string
     */
    private $ativo = 1;

    /**
     * Nivel de acesso do usuario
     * @var integer
     */
    private $fk_acesso_id_acesso = 2;

    /**
     * Metodo responsavel por retornar a turma pelo id
     * @return array
     */
    public static function getUserClass($id) {
        $table  = "turma t JOIN aluno a ON (t.id_turma = a.fk_turma_id_turma)";
        $where  = "a.fk_usuario_id_usuario = $id";
        $fields = "t.fk_curso_id_curso AS curso, t.num_modulo AS modulo";

        return (new Database($table))->select($where, null, null, $fields)->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Metodo responsavel por retornar um contato de um usuario pelo id
     * @return array
     */
    public static function getUserContact($id) {
        $table = "usuario u JOIN servidor s ON (u.id_usuario = s.fk_usuario_id_usuario) JOIN contato c ON (s.fk_usuario_id_usuario = c.fk_servidor_fk_usuario_id_usuario) JOIN tipo_contato tc ON (c.fk_tipo_contato_id_tipo = tc.id_tipo)";
        $where = "id_usuario = $id";
        $fields = "nom_usuario, dsc_contato, dsc_tipo";

        return (new Database($table))->select($where, null, null, $fields)->fetchAll(\PDO::FETCH_ASSOC);
    }
 
    /**
     * Methodo responsavel por cadastrar a istancia atual no banco de dados
     * @return boolean
     */
    public function insertUser() {
        $this->setAdd_data();

        // INSERE A ISTANCIA NO BANCO
        $this->setId_usuario((new Database('usuario'))->insert([
            'nom_usuario'         => $this->nom_usuario,
            'email'               => $this->email,
            'senha'               => $this->senha,
            'add_data'            => $this->add_data,
            'img_perfil'          => $this->img_perfil,
            'ativo'               => $this->ativo,
            'fk_acesso_id_acesso' => $this->fk_acesso_id_acesso
        ]));
        // SUCESSO
        return true;
    }

    /**
     * Methodo responsavel por atualizar os dados no banco
     * @return boolean
     */
    public function updateUser() {
        return (new Database('usuario'))->update("id_usuario = {$this->id_usuario}", [
            'nom_usuario'         => $this->nom_usuario,
            'email'               => $this->email,
            'senha'               => $this->senha,
            'add_data'            => $this->add_data,
            'img_perfil'          => $this->img_perfil,
            'ativo'               => $this->ativo,
            'fk_acesso_id_acesso' => $this->fk_acesso_id_acesso
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
     * Obtem o id do usuario
     * @return integer
     */
    public function getId_usuario() {
        return $this->id_usuario;
    }

    /**
     * Atribui um id ao usuario manualmente
     * @param integer $id
     */
    private function setId_usuario($id) {
        $this->id_usuario = $id;
    }

    /**
     * Obtem o nome do usuario
     * @return string
     */
    public function getNom_usuario() { 
        return $this->nom_usuario;
    }

    /**
     * Atribui o nome do usuario
     * @param string $value
     */
    public function setNom_usuario($name) { 
        $this->nom_usuario = $name;
    }

    /**
     * Obtem o email do usuario
     * @return string
     */
    public function getEmail() { 
        return $this->email;
    }

    /**
     * Atribui o email do usuario
     * @param string $email
     */
    public function setEmail($email) { 
        $this->email = $email;
    }

    /**
     * Obtem a senha do usuario
     * @return string
     */
    public function getSenha() { 
        return $this->senha;
    }

    /**
     * Atribui a senha do usuario
     * @param string $senha
     */
    public function setSenha($senha) { 
        !empty($senha) ? $this->senha = password_hash($senha, PASSWORD_DEFAULT) : $this->senha; 
    }

    /**
     * Obtem a imagem de perfil do usuario
     * @return string
     */
    public function getImg_perfil() {
        return !empty($this->img_perfil) ? $this->img_perfil : 'user.png';
    }

    /**
     * Atribui a imagem de perfil do usuario
     * @param string $hash
     */
    public function setImg_perfil($img) { 
        $this->img_perfil = $img;
    }

    /**
     * Obtem o status do usuario
     * @return integer
     */
    public function getAtivo() { 
        return $this->ativo;
    }

    /**
     * Atribui o status de atividade do usuario
     * @param string $ativo
     */
    public function setAtivo($ativo) {
        $ativo = $ativo == 't' ? 1 : 0;
        $this->ativo = $ativo;
    }

    /**
     * Obtem a data de criação do usuario legivel
     * @return string 
     */
    public function getAdd_data() { 
        return date('d/m/Y H:i:s', strtotime($this->add_data));
    }

    /**
     * Atribui a data de criação do usuario em timestamp
     * @param string $data
     */
    private function setAdd_data() { 
        //$this->add_data = $data;
        $this->add_data = date('Y-m-d H:i:s');
    }
 
    /**
     * Obtem o nivel de acesso do usuario
     * @return integer
     */
    public function getFk_acesso() { 
        return $this->fk_acesso_id_acesso;
    }

    /**
     * Atribui o nivel de acesso do usuario
     * @param integer
     */
    public function setFk_acesso($acesso = 2) { 
        $this->fk_acesso_id_acesso = $acesso;
    }  
}