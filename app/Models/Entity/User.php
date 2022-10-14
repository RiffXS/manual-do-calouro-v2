<?php
namespace App\Models\Entity;

use \App\Utils\Database;

class User {

    /**
     * ID do usuario
     * @var integer
     */
    public $id;

    /**
     * Nome do usuario
     * @var string
     */
    public $nome;

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
        $this->id = (new Database('usuarios'))->insert([
            'nome'  => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
        ]);
        // SUCESSO
        return true;
    }

    /**
     * Methodo responsavel por atualizar os dados no banco
     * @return boolean
     */
    public function atualizarUser() {
        return (new Database('usuarios'))->update('id = '.$this->id, [
            'nome'  => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
        ]);
    }

    /**
     * Methodo responsavel por excluir um usuario do banco
     * @return boolean
     */
    public function excluirUser() {
        return (new Database('usuarios'))->delete('id = '.$this->id);
    }

    /**
     * Methodo responsavel por retornar uma istancia com base no ID
     * @param integer $id
     * @return User
     */
    public static function getUserById($id) {
        return self::getUsers('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Methodo responsavel por retornar um usuario com base em seu email
     * @param string $email
     * @return User
     */
    public static function getUserByEmail($email) {
        return self::getUsers('email = "'.$email.'"')->fetchObject(self::class);
    }

    /**
     * Méthodo responsavel por retornar usuarios
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return mixed
     */
    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*') {
        return (new Database('usuarios'))->select($where, $order, $limit, $fields);
    } 
}