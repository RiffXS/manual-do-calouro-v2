<?php

namespace App\Models\Entities;

use \App\Utils\Database;

class Student {

    /**
     * ID do aluno
     * @var integer
     */
    private $fk_usuario_id_usuario;

    /**
     * Matrícula do aluno
     * @var string
     */
    private $num_matricula;

    /**
     * ID da turma do aluno
     * @var integer
     */
    private $fk_turma_id_turma;
    
    /**
     * Metodo responsavel por construir o objeto aluno
     * @param string $matricula
     */
    public function __construct($id, $matricula = null) {
        $this->fk_usuario_id_usuario = $id;
        $this->num_matricula         = $matricula;
    }

    /**
     * Método responsável por mudar os atributos da classe
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value) {
        $this->{$name} = $value;
    }

    /**
     * Método responsável por obter o valor de um atributo da classe
     * @return mixed
     */
    public function __get($name) {
        return $this->{$name};    
    }

    /**
     * Método responsável por cadastrar um usuário como aluno
     * @return boolean
     */
    public function insertStudent() {
        (new Database('aluno'))->insert([
            'fk_usuario_id_usuario' => $this->fk_usuario_id_usuario,
            'num_matricula'         => $this->num_matricula
        ]);

        // RETORNA VERDADEIRO
        return true;
    }

    /**
     * Método responsável por atualizar a turma do aluno
     *
     */
    public function updateStudent() {
        $where = "fk_usuario_id_usuario = {$this->fk_usuario_id_usuario}";

        return (new Database('aluno'))->update($where, [
            'fk_turma_id_turma' => $this->fk_turma_id_turma
        ]);
    }

    /**
     * Método reponsável por verificar se a matrícula já está cadastrada
     * @return boolean
     */
    public function verifyEnrollment() {
        $result = (new Database('aluno'))->select("num_matricula = '{$this->num_matricula}'");
        
        // VERIFICA SE HOUVE RESULTADO
        if ($result->rowCount() > 0) {
            // MATRÍCULA JÁ UTILIZADA
            return false;
        }
        // MATRÍCULA DISPONÍVEL
        return true;
    }
    
}

?>