<?php

namespace App\Models;

use \App\Utils\Database;

class Servidor {

    /**
     * ID do servidor
     * @var integer
     */
    private $fk_usuario_id_usuario;

    /**
     * Sala do servidor
     * @var integer
     */
    private $fk_sala_id_sala;

}