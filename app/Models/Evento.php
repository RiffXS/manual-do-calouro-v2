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
     * Método responsavel por obter os eventos do banco de dados
     * @return mixed
     * 
     * @author @SimpleR1ick
     */
    public static function getEvents($where = null, $order = null, $limit = null, $fields = '*'): mixed {
        return (new Database('evento'))->select($where, $order, $limit, $fields);
    }

    /*
     * Metodos GETTERS E SETTERS
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
        return $this->dat_evento;
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