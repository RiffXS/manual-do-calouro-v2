<?php 

namespace App\Models\Entities;

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
     * Metodo responsavel por verificar se uma chave existe
     * @return mixed
     */
    public static function findHash($id = null, $chave = null) {
        if (!is_null($id)) {
            return (new Database('chave'))->select("fk_usuario_id_usuario = $id")->fetchObject(self::class);
        } 
        return (new Database('chave'))->select("chave_confirma = '$chave'")->fetchObject(self::class);
    }
    
    /**
     * Metodo responsavel por inserir uma chave na tabela
     */
    public function insertHash() {
        return (new Database())->execute("INSERT INTO chave (fk_usuario_id_usuario, chave_confirma) VALUES ({$this->fk_usuario_id_usuario}, '{$this->chave_confirma}')");
    }

    /**
     * Metodo responsavel por atualizar uma chave na tabela
     */
    public function updateHash() {
        return (new Database('chave'))->update("fk_usuario_id_usuario = {$this->fk_usuario_id_usuario}", [
            'chave_confirma' => $this->chave_confirma
        ]);
    }

    /**
     * Metodo responsavel por deleltar a chave
     */
    public function deleteHash() {
        return (new Database('chave'))->delete("fk_usuario_id_usuario = {$this->fk_usuario_id_usuario}");
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
     * Metodo responsavel por gerar uma chave unica
     */
    public function setHash() {
        $this->chave_confirma = sha1(uniqid(mt_rand()));
    }

    /**
     * Método responsavel por retornar o atributo chave
     * @return string
     */
    public function getHash() {
        return $this->chave_confirma;
    }
}