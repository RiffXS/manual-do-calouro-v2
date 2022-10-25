<?php 

namespace App\Models\Entity;

use App\Utils\Database;

class Hash {

    /**
     * ID do usuario da chave
     * @var integer
     */
    private $fk_usuario_id_usuario;

    /**
     * Valor do hash da chave
     * @var string
     */
    private $chave_confirma;

    /**
     * Método responsavel por buscar uma chave por id
     */
    public static function verifyKey($id) {
        if ((new Database('chave'))->select("fk_usuario_id_usuario = $id")->rowCount() == 0) {
            return false;
        }
        return true;
    }

     /**
     * Metodo responsavel por verificar se uma chave existe
     * @return mixed
     */
    public static function findKey($id = null, $chave = null) {
        if (!is_null($id)) {
            return (new Database('chave'))->select("fk_usuario_id_usuario = $id")->fetchObject(self::class);
        } 
        return (new Database('chave'))->select("chave_confirma = '$chave'")->fetchObject(self::class);
    }
    
    /**
     * Metodo responsavel por inserir uma chave na tabela
     */
    public function insertKey() {
        (new Database('chave'))->insert([
            'fk_usuario_id_usuario' => $this->fk_usuario_id_usuario,
            'chave_confirma' => $this->chave_confirma
        ]);
    }

    /**
     * Metodo responsavel por atualizar uma chave na tabela
     */
    public function updateKey() {
        return (new Database('chave'))->update("fk_usuario_id_usuario = {$this->fk_usuario_id_usuario}", [
            'chave_confirma' => $this->chave_confirma
        ]);
    }

    /**
     * Metodo responsavel por deleltar a chave
     */
    public static function deleteKey($id) {
        return (new Database('chave'))->delete("fk_usuario_id_usuario = $id");
    }

    /**
     * Metodo responsavel por gerar uma chave unica
     */
    public function generateKey() {
        $this->chave_confirma = sha1(uniqid(mt_rand()));
    }

    /**
     * Set iD do usuario da chave
     * @param  integer  $fk_usuario_id_usuario
     */ 
    public function setFkId($id) {
        $this->fk_usuario_id_usuario = $id;
    }

    /**
     * Get iD do usuario da chave
     * @return  integer
     */ 
    public function getFkId() {
        return $this->fk_usuario_id_usuario;
    }

    /**
     * Método responsavel por retornar o atributo chave
     * @return string
     */
    public function getKey() {
        return $this->chave_confirma;
    }
}