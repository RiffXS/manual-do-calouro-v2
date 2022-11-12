<?php 

namespace App\Models;
use App\Utils\Database;

class Calendar {


    /**
     * Método responsavel por obter os eventos do banco de dados
     * @return array
     * 
     * @author @SimpleR1ick
     */
    public static function getCalendar(): array {
        return (new Database('evento'))->select(null, null, null, 'dsc_evento, dat_evento')->fetchAll(\PDO::FETCH_ASSOC);
    }
}