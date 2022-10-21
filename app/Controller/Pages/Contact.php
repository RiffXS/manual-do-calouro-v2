<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Models\Entity\Contact as EntityContact;

class Contact extends Page {

    /**
     * Método responsavel por retornar o contéudo (view) da página contatos
     * @return string 
     */
    public static function getContact() {
        // VIEW DOS CONTATOS
        $content =  View::render('pages/contact', [
            'professores' => self::teste(),
            'servidores'  => self::cardServer()
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Contatos', $content, 'contact');
    }
    
    /**
     * 
     */
    public static function teste() {
        $obContactTeacher = EntityContact::getContactTeacher();

        echo '<pre>'; print_r($obContactTeacher); echo '</pre>'; exit;

    }

    /**
     * Método responsável por renderizar os cards de contato dos professores
     * @return string $content
     */
    public static function cardTeacher($contact) {
        // VIEW DOS CONTATOS DE PROFESSORES
        $content =  View::render('pages/contacts/teacher', [
            'contato_id'  => $contact['id_usuario'],
            'nome'        => $contact['nom_usuario'],
            'regras'      => $contact['regras'],
            'imagem'      => $contact['img_perfil'],
            'contato'     => '',
            'hora_inicio' => $contact['hora_inicio'],
            'hora_fim'    => $contact['hora_fim'],
            'sala'        => $contact['num_sala']
        ]);

        // RETORNA A VIEW RENDERIZADA
        return $content;
    }

    /**
     * Método responsável por renderizar os cards de contato dos servidores
     * @return string $content
     */
    public static function cardServer($contact) {
        // VIEW DOS CONTATOS DE SERVIDORES
        $content =  View::render('pages/contacts/server', [
            'contato_id' => '',
            'imagem'     => '',
            'setor'      => '',
            'contato'    => '',
            'sala'       => '',
            'horario'    => ''
        ]);

        // RETORNA A VIEW RENDERIZADA
        return $content;
    }

}