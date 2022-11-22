<?php 

namespace App\Models;

use App\Utils\Database;

class Chave {

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
     * @return mixed
     * 
     * @author @SimpleR1ick
     */
    public static function findHash(int $id = null, string $chave = null): mixed {
        // VERIFICA SE O ID ESTA VAZIO
        if (!is_null($id)) {
            return (new Database('chave'))->select("fk_usuario_id_usuario = $id")->fetchObject(self::class);
        } else {
            return (new Database('chave'))->select("chave_confirma = '$chave'")->fetchObject(self::class);
        }
    }
    
    /**
     * Metodo responsavel por inserir uma chave na tabela
     * @return mixed
     * 
     * @author @SimpleR1ick
     */
    public function insertHash(): mixed {
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

    /**
     * Get fk_usuario_id_usuario
     * @return integer
     */
    public function getFkId(): int {
        return $this->fk_usuario_id_usuario;
    }

    /**
     * Set fk_usuario_id_usuario
     * @param integer $id
     */
    public function setFkId($id): void {
        $this->fk_usuario_id_usuario = $id;
    }

    /**
     * Get chave_confirma
     * @return string
     */
    public function getHash(): string {
        return $this->chave_confirma;
    }

    /**
     * Set chave_confirma
     * @return void
     */
    public function setHash(): void {
        $this->chave_confirma = sha1(uniqid(mt_rand()));
    }
}