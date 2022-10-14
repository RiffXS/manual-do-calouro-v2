<?php

namespace App\Models\Entity;

use \App\Utils\Database;

class Testimony {

    /**
     * ID do depoimento
     * @var integer
     */
    public $id;

    /**
     * Nome do usuario que fez o Depoimento
     * @var string
     */
    public $nome;

    /**
     * Mensagem do depoimento
     * @var string
     */
    public $mensagem;

    /**
     * Data de publicação
     * @var string
     */
    public $data;

    /**
     * Methodo responsavel por cadastrar a instancia atual no banco de dados
     * @return boolean
     */
    public function cadastrarTestimony() {
        // DEFINE A DATA    
        $this->data = date('Y-m-d   H:i:s');

        // INSERE O DEPOIMENTO NO BANCO DE DADOS
        $this->id = (new Database('depoimentos'))->insert([
            'nome'     => $this->nome,
            'mensagem' => $this->mensagem,
            'data'     => $this->data
        ]);
        // SUCESSO
        return true;
    }

    /**
     * Methodo responsavel por atualizar do banco com a instancia atual
     */
    public function atualizarTestimony() {

        // ATUALIZA O DEPOIMENTO NO BANCO DE DADOS
        return (new Database('depoimentos'))->update('id ='.$this->id, [
            'nome'     => $this->nome,
            'mensagem' => $this->mensagem
        ]);
    }

    /**
     * Methodo responsavel por retornar um depoimento com base no seu id
     * @param integer $id
     * @return Testimony
     */
    public static function getTestimonyById($id) {
        return self::getTestimonies('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Méthodo responsavel por retornar depoimentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return mixed
     */
    public static function getTestimonies($where = null, $order = null, $limit = null, $fields = '*') {
        return (new Database('depoimentos'))->select($where, $order, $limit, $fields);
    }  
    
    /**
     * Methodo responsavel por excluir um depoimento do banco de dados
     */
    public function excluirTestimony() {
        // EXCLUI O DEPOIMENTO DO BANCO DE DADOS
        return (new Database('depoimentos'))->delete('id ='.$this->id);
    }
}