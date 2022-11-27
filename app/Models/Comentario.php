<?php

namespace App\Models;

use \App\Utils\Database;

class Comentario {

    /**
     * ID do depoimento
     * @var integer
     */
    private $id_comentario;

    /**
     * Mensagem do depoimento
     * @var string
     */
    private $dsc_comentario;

    /**
     * Data de publicação
     * @var string
     */
    private $add_data;

    /**
     * FK do usuario que fez o comentario
     * @var integer
     */
    private $fk_usuario_id_usuario;

    /**
     * Methodo responsavel por cadastrar a instancia atual no banco de dados
     * @return boolean
     */
    public function insertComment() {
        // DEFINE A DATA    
        $this->setAdd_data();

        // INSERE O DEPOIMENTO NO BANCO DE DADOS
        $this->setId_comentario((new Database('comentario'))->insert([
            'dsc_comentario'        => $this->dsc_comentario,
            'add_data'              => $this->add_data,
            'fk_usuario_id_usuario' => $this->fk_usuario_id_usuario
        ]));
        // SUCESSO
        return true;
    }

    /**
     * Methodo responsavel por atualizar do banco com a instancia atual
     */
    public function updateComment() {
        // DEFINE A DATA    
        $this->setAdd_data();

        // ATUALIZA O DEPOIMENTO NO BANCO DE DADOS
        return (new Database('comentario'))->update("id_comentario = {$this->id_comentario}", [
            'dsc_comentario' => $this->dsc_comentario,
            'add_data'       => $this->add_data
        ]);
    }

    /**
     * Methodo responsavel por excluir um depoimento do banco de dados
     */
    public function deleteComment() {
        // EXCLUI O DEPOIMENTO DO BANCO DE DADOS
        return (new Database('comentario'))->delete("id_comentario = {$this->id_comentario}");
    }

    /**
     * Méthodo responsavel por retornar depoimentos
     * @param  string $where
     * @param  string $order
     * @param  string $limit
     * @param  string $fields
     * 
     * @return \PDOStatement
     */
    public static function getComments($where = null, $order = null, $limit = null, $fields = '*') {
        return (new Database('comentario'))->select($where, $order, $limit, $fields);
    }  

    /**
     * Methodo responsavel por retornar um depoimento com base no seu id
     * @param  integer $id
     * 
     * @return self|bool
     */
    public static function getCommentById($id): mixed {
        return self::getComments("id_comentario = $id")->fetchObject(self::class);
    }

    public static function getDscComments($order, $limit) {
        $sql =  "SELECT id_comentario,
                    dsc_comentario,
                    c.add_data,
                    nom_usuario,
                    img_perfil
                FROM comentario c
                    JOIN usuario u ON (
                        c.fk_usuario_id_usuario = u.id_usuario
                    ) ORDER BY $order LIMIT $limit";
                    
        return (new Database())->execute($sql);
    }

    /*
     * Metodos GETTERS E SETTERS
     */

    public function getId_comentario(): int {
        return $this->id_comentario;
    }

    public function setId_comentario($id): void {
        $this->id_comentario = $id;
    }

    public function getDsc_comentario(): string {
        return $this->dsc_comentario;
    }

    public function setDsc_comentario($dsc): void {
        $this->dsc_comentario = $dsc;
    }

    public function getAdd_data(): string {
        return date('d/m/Y H:i:s', strtotime($this->add_data));
    }

    public function setAdd_data(): void {
        $this->add_data = date('Y-m-d H:i:s');
    }

    public function getFK_id_usuario(): string {
        return $this->fk_usuario_id_usuario;
    }

    public function setFK_id_usuario($fk): void {
        $this->fk_usuario_id_usuario = $fk;
    }
}