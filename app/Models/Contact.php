<?php 

namespace App\Models;

use App\Utils\Database;

class Contact {

    /**
     * Array com os dados dos professores
     * @var array
     */
    private $professor;

    /**
     * Array com os dados dos servidores
     * @var array
     */
    private $servidor;
    
    /**
     * Método responsavel por construir o objeto
     */
    public function __construct() {
        $this->professor = self::getContactTeacher();
        $this->servidor  = self::getContactServer();
    }

    /**
     * Método responsável por retornar os contatos dos professores
     * @return array
     * 
     * @author @SimpleR1ick @RiffXS
     */
    public static function getContactTeacher(): array {
        $sql = "SELECT id_usuario,
                    nom_usuario,
                    regras,
                    img_perfil,
                    hora_inicio,
                    hora_fim,
                    num_sala
                    FROM usuario u
                    JOIN servidor s ON (u.id_usuario = s.fk_usuario_id_usuario)
                    JOIN sala sa ON (s.fk_sala_id_sala = sa.id_sala)
                    JOIN servidor_horario sh ON (s.fk_usuario_id_usuario = sh.fk_servidor_fk_usuario_id_usuario) 
                    JOIN horario h ON (sh.fk_horario_id_horario = h.id_horario)
                    JOIN professor p ON (s.fk_usuario_id_usuario = p.fk_servidor_fk_usuario_id_usuario)
                    JOIN contato c ON (s.fk_usuario_id_usuario = c.fk_servidor_fk_usuario_id_usuario)";

        // RETORNA OS DEPOIMENTOS
        return (new Database())->execute($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Método responsável por retornar os contatos dos servidores
     * @return array
     * 
     * @author @SimpleR1ick @RiffXS
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
                    JOIN servidor_horario sh ON (s.fk_usuario_id_usuario = sh.fk_servidor_fk_usuario_id_usuario) 
                    JOIN horario h ON (sh.fk_horario_id_horario = h.id_horario)
                    JOIN administrativo a ON (s.fk_usuario_id_usuario = a.fk_servidor_fk_usuario_id_usuario)
                    JOIN setor se ON (a.fk_setor_id_setor = se.id_setor)";

        // RETORNA OS DEPOIMENTOS
        return (new Database())->execute($sql)->fetchAll(\PDO::FETCH_ASSOC); 
    }

    /*
     * Metodos GETTERS E SETTERS
     */

    public function getProfessor(){
        return $this->professor;
    }

    public function getServidor(){
        return $this->servidor;
    }
}