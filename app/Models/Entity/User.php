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
     * Methodo responsavel por cadastrar a istancia atual no banco de dados
     * @return boolean
     */
    public function cadastrarUser() {
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
    public function atualizarUser() {
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
    public function excluirUser() {
        return (new Database('usuario'))->delete("id_usuario = {$this->id_usuario}");
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
    
}