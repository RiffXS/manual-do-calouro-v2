<?php 

namespace App\Models;
use App\Utils\Database;

class Calendar {

    /**
     * Array de eventos do calendario
     * @var Array
     */
    private $events = [];

    /**
     * Método construtor da classe
     */
    public function __construct() {
        $this->events = self::getCalendar();
    }

    /**
     * Método responsavel por obter os eventos do banco de dados
     * @return array
     * 
     * @author @SimpleR1ick
     */
    public static function getCalendar(): array {
        return (new Database('evento'))->select(null, null, null, 'dsc_evento, dat_evento')->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Obtem o array de eventos
     * @return array
     */
    public function getEvents() {
        return $this->events;
    }
}