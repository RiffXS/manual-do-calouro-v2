<?php 

namespace App\Models\Entity;

use App\Utils\Database;

class Contact {

    /**
     * Método responsável por retornar os contatos dos professores
     * @return array
     */
    public static function getContactTeacher() {
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
        return (new Database())->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Método responsável por retornar os contatos dos servidores
     * @return array
     */
    public static function getContactServer() {
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
        return (new Database())->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        
    }
}