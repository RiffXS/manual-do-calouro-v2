<?php 

namespace App\Models;

use App\Utils\Database;

class Evento {

    /**
     * ID serial do evento
     * @var integer
     */
    private $id_evento;

    /**
     * Descrição do evento
     * @var string
     */
    private $dsc_evento;

    /**
     * Data timestamp do evento
     * @var string
     */
    private $dat_evento;

    /**
     * FK do campus relacionado
     * @var integer
     */
    private $fk_campus_id_campus;

     /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     */
    public function insertEvent(): bool {
        // INSERE A INSTÂNCIA NO BANCO
        $this->setId_evento((new Database('evento'))->insert([
            'dsc_evento'          => $this->dsc_evento,
            'dat_evento'          => $this->dat_evento,
            'fk_campus_id_campus' => $this->fk_campus_id_campus,
        ]));
        // SUCESSO
        return true;
    }

    /**
     * Método responsável por atualizar os dados de um evento no banco
     * @return boolean
     */
    public function updateEvent(): bool {
        return (new Database('evento'))->update("id_evento = {$this->id_evento}", [
            'dsc_evento'          => $this->dsc_evento,
            'dat_evento'          => $this->dat_evento,
            'fk_campus_id_campus' => $this->fk_campus_id_campus,
        ]);
    }

    /**
     * Método responsável por excluir um evento do banco
     * @return boolean
     */
    public function deleteEvent(): bool {
        return (new Database('evento'))->delete("id_evento = {$this->id_evento}");
    }

    /**
     * Método responsável por obter os eventos do banco de dados
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * 
     * @return \PDOStatement
     */
    public static function getEvents($where = null, $order = null, $limit = null, $fields = '*'): mixed {
        return (new Database('evento'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsavel por obter um evento pelo ID
     * @param integer $id
     * 
     * @return self|bool
     */
    public static function getEventById(int $id): mixed {
        return self::getEvents("id_evento = $id")->fetchObject(self::class);
    }

    /**
     * Método responsável por obter a descrição dos eventos
     * @param string $order
     * @param string $limit
     * 
     * @return \PDOStatement|bool
     */
    public static function getDscEvents($order, $limit): mixed {
        $sql = "SELECT id_evento,
                    dsc_campus,
                    dat_evento,
                    dsc_evento
                FROM evento e
                    JOIN campus c ON (e.fk_campus_id_campus = c.id_campus)
                ORDER BY $order LIMIT $limit";

        return (new Database)->execute($sql);
    }

    /*
     * Métodos GETTERS E SETTERS
     */
    
    /**
     * Get id_evento
     * @return integer
     */ 
    public function getId_evento(): int {
        return $this->id_evento;
    }

    /**
     * Set id_evento
     * @param integer $id
     */
    public function setId_evento(int $id): void {
        $this->id_evento = $id;
    }

    /**
     * Get dsc_evento
     * @return string
     */
    public function getDsc_evento(): string {
        return $this->dsc_evento;
    }

    /**
     * Set dsc_evento
     * @param string $value
     */
    public function setDsc_evento(string $value): void {
        $this->dsc_evento = $value;
    }

    /**
     * Get dat_evento
     * @return string 
     */
    public function getDat_evento(): string {
        return date('Y-m-d', strtotime($this->dat_evento));
    }

    /**
     * Set dat_evento
     * @param string $date
     */
    public function setDat_evento(string $date): void {
        $this->dat_evento = $date;
    }

    /**
     * Get fk_campus_id_campus
     * @return integer
     */
    public function getFk_campus(): int {
        return $this->fk_campus_id_campus;
    }

    /**
     * Set fk_campus_id_campus
     * @param integer
     */
    public function setFk_campus(int $fk): void {
        $this->fk_campus_id_campus = $fk;
    }
}