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
     * Metodo responsavel por cadastrar a istancia atual no banco de dados
     * @return boolean
     * 
     * @author @SimpleR1ick @RiffXS
     */
    public function insertUser(): bool {
        // ATRIBUI AO OBJETO A HORA ATUAL
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
     * Metodo responsavel por atualizar os dados no banco
     * @return boolean
     * 
     * @author @SimpleR1ick @RiffXS
     */
    public function updateUser(): bool {
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
     * Metodo responsavel por excluir um usuario do banco
     * @return boolean
     * 
     * @author @RiffXS
     */
    public function deleteUser(): bool {
        return (new Database('usuario'))->delete("id_usuario = {$this->id_usuario}");
    }

    /**
     * Metodo responsavel por retornar a turma pelo id
     * @return array
     * 
     * @author @SimpleR1ick @RiffXS
     */
    public static function getUserClass(int $id): array {
        $table  = "turma t JOIN aluno a ON (t.id_turma = a.fk_turma_id_turma)";
        $where  = "a.fk_usuario_id_usuario = $id";
        $fields = "t.fk_curso_id_curso AS curso, t.num_modulo AS modulo";

        // RETORNA UM ARRAY ASSOCIATIVO
        return (new Database($table))->select($where, null, null, $fields)->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Metodo responsavel por retornar um contato de um usuario pelo id
     * @return array
     * 
     * @author @SimpleR1ick @RiffXS
     */
    public static function getUserContact(int $id): array {
        $table = "usuario u JOIN servidor s ON (u.id_usuario = s.fk_usuario_id_usuario) JOIN contato c ON (s.fk_usuario_id_usuario = c.fk_servidor_fk_usuario_id_usuario) JOIN tipo_contato tc ON (c.fk_tipo_contato_id_tipo = tc.id_tipo)";
        $where = "id_usuario = $id";
        $fields = "nom_usuario, dsc_contato, dsc_tipo";

        // RETORNA UM ARRAY ASSOCIATIVO
        return (new Database($table))->select($where, null, null, $fields)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Méthodo responsavel por retornar usuario
     * @param  string $where
     * @param  string $order
     * @param  string $limit
     * @param  string $fields
     * 
     * @return mixed
     * 
     * @author @SimpleR1ick @RiffXS
     */
    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*'): mixed {
        return (new Database('usuario'))->select($where, $order, $limit, $fields);
    }

    /**
     * Methodo responsavel por retornar uma istancia com base no ID
     * @param  integer $id
     * 
     * @return User
     * 
     * @author @SimpleR1ick
     */
    public static function getUserById($id): object {
        return self::getUsers("id_usuario = $id")->fetchObject(self::class);
    }

    /**
     * Methodo responsavel por retornar um usuario com base em seu email
     * @param  string $email
     * 
     * @return User
     * 
     * @author @SimpleR1ick
     */
    public static function getUserByEmail(string $email): object {
        return self::getUsers("email = '$email'")->fetchObject(self::class);
    }

    /*
     * Metodos GETTERS E SETTERS
     */

    public function getId_usuario() {
        return $this->id_usuario;
    }

    private function setId_usuario(int $id){
        $this->id_usuario = $id;
    }

    public function getNom_usuario() { 
        return $this->nom_usuario;
    }

    public function setNom_usuario(string $name) { 
        $this->nom_usuario = $name;
    }

    public function getEmail() { 
        return $this->email;
    }

    public function setEmail(string $email) { 
        $this->email = $email;
    }

    public function getSenha() { 
        return $this->senha;
    }

    public function setSenha(string $senha) { 
        !empty($senha) ? $this->senha = password_hash($senha, PASSWORD_DEFAULT) : $this->senha; 
    }

    public function getImg_perfil() {
        return !empty($this->img_perfil) ? $this->img_perfil : 'user.png';
    }

    public function setImg_perfil(string $img) { 
        $this->img_perfil = $img;
    }

    public function getAtivo() { 
        return $this->ativo;
    }

    public function setAtivo(int $ativo) {
        $ativo = $ativo == 't' ? 1 : 0;
        $this->ativo = $ativo;
    }

    public function getAdd_data() { 
        return date('d/m/Y H:i:s', strtotime($this->add_data));
    }

    private function setAdd_data() { 
        //$this->add_data = $data;
        $this->add_data = date('Y-m-d H:i:s');
    }
 
    public function getFk_acesso() { 
        return $this->fk_acesso_id_acesso;
    }

    public function setFk_acesso(int $acesso = 2) { 
        $this->fk_acesso_id_acesso = $acesso;
    }  
}