<?php 

namespace App\Models;

use App\Utils\Database;

class Contato {

    /**
     * ID do contato
     * @var integer
     */
    private $id_contato;

    /**
     * ID do servidor
     * @var integer
     */
    private $fk_servidor_fk_usuario_id_usuario;

    /**
     * ID do tipo do contato
     * @var integer
     */
    private $fk_tipo_contato_id_tipo;

    /**
     * Descrição do contato (email, telefone, whatsapp)
     * @var string
     */
    private $dsc_contato;

    /**
     * Método responsavel por inserir o contato no banco de dados
     * @return boolean
     */
    public function insertContact(): bool {
        // INSERE A ISTANCIA NO BANCO
        $this->setId_contato((new Database('contato'))->insert([
            'fk_servidor_fk_usuario_id_usuario' => $this->fk_servidor_fk_usuario_id_usuario,
            'fk_tipo_contato_id_tipo'           => $this->fk_tipo_contato_id_tipo,
            'dsc_contato'                       => $this->dsc_contato  
        ]));
        // SUCESSO
        return true;
    }

    /**
     * Método responsavel por atualizar o contato no banco de dados
     * @return boolean
     */
    public function updateContact(): bool {
        // INSERE A ISTANCIA NO BANCO
        return (new Database('contato'))->update("id_contato = {$this->id_contato}", [
            'fk_servidor_fk_usuario_id_usuario' => $this->fk_servidor_fk_usuario_id_usuario,
            'fk_tipo_contato_id_tipo'           => $this->fk_tipo_contato_id_tipo,
            'dsc_contato'                       => $this->dsc_contato  
        ]);
    }

    /**
     * Metodo responsavel por excluir um contato do banco de dados
     * @return boolean
     */
    public function deleteContact(): bool {
        return (new Database('contato'))->delete("id_contato = {$this->id_contato}");
    }

    /**
     * Método responsável por retornar os contatos dos professores
     * @return array
     */
    public static function getContactTeacher(): array {
        $sql = "SELECT DISTINCT id_usuario,
                    nom_usuario,
                    regras,
                    img_perfil,
                    hora_inicio,
                    hora_fim,
                    num_sala
                FROM usuario u
                    JOIN servidor s ON (u.id_usuario = s.fk_usuario_id_usuario)
                    JOIN sala sa ON (s.fk_sala_id_sala = sa.id_sala)
                    LEFT JOIN servidor_horario sh ON (
                        s.fk_usuario_id_usuario = sh.fk_servidor_fk_usuario_id_usuario
                    )
                    LEFT JOIN horario h ON (sh.fk_horario_id_horario = h.id_horario)
                    JOIN professor p ON (
                        s.fk_usuario_id_usuario = p.fk_servidor_fk_usuario_id_usuario
                    )
                    JOIN contato c ON (
                        s.fk_usuario_id_usuario = c.fk_servidor_fk_usuario_id_usuario
                    )";

        // RETORNA OS DEPOIMENTOS
        return (new Database())->execute($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Método responsável por retornar os contatos dos servidores
     * @return array
     */
    public static function getContactServer(): array {
        $sql = "SELECT id_usuario,
                    nom_usuario,
                    dsc_setor,
                    img_perfil,
                    hora_inicio,
                    hora_fim,
                    num_sala
                FROM usuario u
                    JOIN servidor s ON (u.id_usuario = s.fk_usuario_id_usuario)
                    JOIN sala sa ON (s.fk_sala_id_sala = sa.id_sala)
                    JOIN servidor_horario sh ON (
                        s.fk_usuario_id_usuario = sh.fk_servidor_fk_usuario_id_usuario
                    )
                    JOIN horario h ON (sh.fk_horario_id_horario = h.id_horario)
                    JOIN administrativo a ON (
                        s.fk_usuario_id_usuario = a.fk_servidor_fk_usuario_id_usuario
                    )
                    JOIN setor se ON (a.fk_setor_id_setor = se.id_setor)";

        // RETORNA OS DEPOIMENTOS
        return (new Database())->execute($sql)->fetchAll(\PDO::FETCH_ASSOC); 
    }

    /**
     * Méthodo responsavel por retornar usuario
     * @param  string $where
     * @param  string $order
     * @param  string $limit
     * @param  string $fields
     * 
     * @return mixed
     */
    public static function getContacts($where = null, $order = null, $limit = null, $fields = '*'): mixed {
        return (new Database('contato'))->select($where, $order, $limit, $fields);
    }

    public static function getContactsInfo($id) {
        $sql = "SELECT c.id_contato,
                    tc.dsc_tipo,
                    c.dsc_contato
                FROM contato c
                    JOIN tipo_contato tc ON (c.fk_tipo_contato_id_tipo = tc.id_tipo)
                WHERE c.fk_servidor_fk_usuario_id_usuario = $id";

        return (new Database)->execute($sql);
    }

    /**
     * Método responsavel por retornar os contatos de um usuario
     * @param integer $id
     * 
     * @return self|bool
     */
    public static function getContactById(int $id): mixed {
        return self::getContacts("id_contato = $id")->fetchObject(self::class);
    }

    /**
     * Método responsavel por retornar os contatos de um usuario
     * @param integer $fk
     * 
     * @return array|bool
     */
    public static function getContactByFk(int $fk): mixed {
        return self::getContacts("fk_servidor_fk_usuario_id_usuario = $fk")->fetch(\PDO::FETCH_ASSOC);
    }

    /*
     * Metodos GETTERS E SETTERS
     */

    /**
     * Get id_usuario
     * @return integer
     */
     public function getId_contato(): int {
        return $this->id_contato;
     }
     
     /**
      * Set id_usuario
      * @param integer $id
      */
     public function setId_contato(int $id): void {
        $this->id_contato = $id;
     }

    /**
     * Get fk_servidor_fk_id_usuario
     * @return integer
     */
     public function getFk_usuario(): int {
        return $this->fk_servidor_fk_usuario_id_usuario;
     }

     /**
      * Set fk_servidor_fk_id_usuario
      * @param integer $id
      */
     public function setFk_usuario(int $id): void {
        $this->fk_servidor_fk_usuario_id_usuario = $id;
     }

    /**
     * Get fk_tipo_contato_id_tipo
     * @return integer
     */
    public function getFk_tipo(): int {
        return $this->fk_tipo_contato_id_tipo;
     }

     /**
      * Set fk_tipo_contato_id_tipo
      * @param integer $id
      */
     public function setFk_tipo(int $id): void {
        $this->fk_tipo_contato_id_tipo = $id;
     }

     /**
     * Get fk_tipo_contato_id_tipo
     * @return string
     */
    public function getDsc_contato(): string {
        return $this->dsc_contato;
     }

     /**
      * Set fk_tipo_contato_id_tipo
      * @param string $value
      */
     public function setDsc_contato(string $value): void {
        $this->dsc_contato = $value;
     }
}