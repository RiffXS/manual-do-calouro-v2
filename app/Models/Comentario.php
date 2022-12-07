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
    private $dt_comentario;

    /**
     * FK do usuario que fez o comentário
     * @var integer
     */
    private $fk_usuario_id_usuario;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     */
    public function insertComment() {
        // DEFINE A DATA    
        $this->setdt_comentario();

        // INSERE O DEPOIMENTO NO BANCO DE DADOS
        $this->setId_comentario((new Database('comentario'))->insert([
            'dsc_comentario'        => $this->dsc_comentario,
            'dt_comentario'         => $this->dt_comentario,
            'fk_usuario_id_usuario' => $this->fk_usuario_id_usuario
        ]));
        // SUCESSO
        return true;
    }

    /**
     * Método responsável por atualizar do banco com a instância atual
     */
    public function updateComment() {
        // DEFINE A DATA    
        $this->setDt_comentario();

        // ATUALIZA O DEPOIMENTO NO BANCO DE DADOS
        return (new Database('comentario'))->update("id_comentario = {$this->id_comentario}", [
            'dsc_comentario' => $this->dsc_comentario,
            'dt_comentario'  => $this->dt_comentario
        ]);
    }

    /**
     * Método responsável por excluir um depoimento do banco de dados
     */
    public function deleteComment() {
        // EXCLUI O DEPOIMENTO DO BANCO DE DADOS
        return (new Database('comentario'))->delete("id_comentario = {$this->id_comentario}");
    }

    /**
     * Método responsável por retornar depoimentos
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
     * Método responsável por retornar um depoimento com base no seu id
     * @param  integer $id
     * 
     * @return self|bool
     */
    public static function getCommentById($id): mixed {
        return self::getComments("id_comentario = $id")->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os comentários
     * @param string $order
     * @param string $limit
     * 
     * @return \PDOStatement
     */
    public static function getDscComments(string $order, string $limit): \PDOStatement {
        $sql =  "SELECT id_comentario,
                    dsc_comentario,
                    dt_comentario,
                    nom_usuario,
                    img_perfil
                FROM comentario c
                    JOIN usuario u ON (
                        c.fk_usuario_id_usuario = u.id_usuario
                    ) ORDER BY $order LIMIT $limit";
                    
        return (new Database())->execute($sql);
    }

    /*
     * Métodos GETTERS E SETTERS
     */

    /**
     * Get id_comentario
     * @return int
     */
    public function getId_comentario(): int {
        return $this->id_comentario;
    }   

    /**
     * Set id_comentario
     * @param mixed $id
     */
    public function setId_comentario($id): void {
        $this->id_comentario = $id;
    }

    /**
     * Get dsc_comentario
     * @return string
     */
    public function getDsc_comentario(): string {
        return $this->dsc_comentario;
    }

    /**
     * Set dsc_comentario
     * @param mixed $dsc
     */
    public function setDsc_comentario($dsc): void {
        $this->dsc_comentario = $dsc;
    }

    /**
     * Get dt_comentario
     * @return string
     */
    public function getDt_comentario(): string {
        return date('d/m/Y H:i:s', strtotime($this->dt_comentario));
    }

    /**
     * Set dt_comentario
     * @return void
     */
    public function setDt_comentario(): void {
        $this->dt_comentario = date('Y-m-d H:i:s');
    }

    /**
     * Get fk_usuario_id_usuario
     * @return string
     */
    public function getFK_id_usuario(): string {
        return $this->fk_usuario_id_usuario;
    }

    /**
     * Set fk_usuario_id_usuario
     * @param mixed $fk
     */
    public function setFK_id_usuario($fk): void {
        $this->fk_usuario_id_usuario = $fk;
    }
}