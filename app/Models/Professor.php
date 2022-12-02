<?php

namespace App\Models;

use \App\Utils\Database;

class Professor {

    /**
     * ID do professor
     * @var integer
     */
    private $fk_servidor_fk_usuario_id_usuario;

    /**
     * Regras do professor
     * @var $regras
     */
    private $regras;

    /**
     * Método responsável por cadastrar um usuário como aluno
     * @return boolean
     */
    public function insertTeacher() {
        (new Database('professor'))->insert([
            'fk_servidor_fk_usuario_id_usuario' => $this->fk_servidor_fk_usuario_id_usuario,
            'regras'                            => $this->regras
        ], false);

        // RETORNA VERDADEIRO
        return true;
    }

    /**
     * Método responsável por atualizar as regras de um usuário professor
     * @return boolean
     */
    public function updateRules(): bool {
        $where = "fk_servidor_fk_usuario_id_usuario = {$this->fk_servidor_fk_usuario_id_usuario}";
        
        return (new Database('professor'))->update($where, [
            'regras' => $this->regras
        ]);
    }

    /**
     * Método responsável por retornar usuário
     * @param  string $where
     * @param  string $order
     * @param  string $limit
     * @param  string $fields
     * 
     * @return mixed
     */
    public static function getTeachers($where = null, $order = null, $limit = null, $fields = '*'): mixed {
        return (new Database('professor'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsável por retornar uma istância com base no ID
     * @param  integer $id
     * 
     * @return self|bool
     */
    public static function getTeacherById($id): mixed {
        return self::getTeachers("fk_servidor_fk_usuario_id_usuario = $id")->fetchObject(self::class);
    }

    /*
     * Métodos GETTERS e SETTERS
     */

    /**
     * Get fk_servidor_fk_usuario_id_usuario
     * @return integer
     */
    public function getFk_id_usuario(): int {
        return $this->fk_servidor_fk_usuario_id_usuario;
    }

    /**
     * Set fk_servidor_fk_usuario_id_usuario
     * @param integer $fk
     */
    public function setFk_id_usuario(int $fk): void {
        $this->fk_servidor_fk_usuario_id_usuario = $fk;
    }
    
    /**
     * Get regras
     * @return string|null
     */
    public function getRules(): mixed {
        return $this->regras;
    }

    /**
     * Set regras
     * @param string $rules
     */
    public function setRules(string $rules): void {
        $this->regras = $rules;
    }

}