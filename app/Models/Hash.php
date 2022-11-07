<?php 

namespace App\Models;

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
     * Metodo responsavel buscar uma chave pelo id ou hash
     * @return self
     */
    public static function findHash($id = null, $chave = null): object {
        // VERIFICA SE O ID ESTA VAZIO
        if (!is_null($id)) {
            return (new Database('chave'))->select("fk_usuario_id_usuario = $id")->fetchObject(self::class);
        } 
        return (new Database('chave'))->select("chave_confirma = '$chave'")->fetchObject(self::class);
    }
    
    /**
     * Metodo responsavel por inserir uma chave na tabela
     * @return boolean
     * 
     * @author @SimpleR1ick
     */
    public function insertHash(): bool {
        return (new Database())->execute("INSERT INTO chave (fk_usuario_id_usuario, chave_confirma) VALUES ({$this->fk_usuario_id_usuario}, '{$this->chave_confirma}')");
    }

    /**
     * Metodo responsavel por atualizar uma chave na tabela
     * @return boolean
     * 
     * @author @SimpleR1ick
     */
    public function updateHash(): bool {
        return (new Database('chave'))->update("fk_usuario_id_usuario = {$this->fk_usuario_id_usuario}", [
            'chave_confirma' => $this->chave_confirma
        ]);
    }

    /**
     * Metodo responsavel por deleltar a chave
     * @return boolean
     * 
     * @author @SimpleR1ick
     */
    public function deleteHash(): bool {
        return (new Database('chave'))->delete("fk_usuario_id_usuario = {$this->fk_usuario_id_usuario}");
    }

    /*
     * Metodos GETTERS E SETTERS
     */

    public function getFkId(): int {
        return $this->fk_usuario_id_usuario;
    }

    public function setFkId($id): void {
        $this->fk_usuario_id_usuario = $id;
    }

    public function getHash(): string {
        return $this->chave_confirma;
    }

    public function setHash(): void {
        $this->chave_confirma = sha1(uniqid(mt_rand()));
    }
}