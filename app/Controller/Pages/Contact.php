<?php

namespace App\Controller\Pages;

use App\Models\Entities\Contact as EntityContact;
use App\Models\Entities\User as EntityUser;
use App\Utils\View;

class Contact extends Page {

    /**
     * Método responsavel por retornar o contéudo (view) da página contatos
     * @return string 
     */
    public static function getContact() {
        // CRIANDO NOVA INSTÂNCIA DE CONTATO
        $obContact = new EntityContact;

        // 
        $views = self::getContacts($obContact);

        // VIEW DOS CONTATOS
        $content = View::render('pages/contacts', [
            'professores' => $views['professores'],
            'servidores'  => $views['servidores']
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Contatos', $content, 'contact');
    }
    
    /**
     * Metodo responsavel por rendenizar os contatos
     * @param EntityContact $obContacat
     */
    public static function getContacts($obContact) {
        // DECLARAÇÃO DE VARIÁVEIS
        $contentTeacher = '';
        $contentServers = '';
        
        // OBTENDO OS ARRAYS DOS CONTATOS
        $teacher = $obContact->professor;
        $servers = $obContact->servidor;

        // LOOP PARA OBTER AS VIEWS DOS CARDS DOS PROFESSORES
        for ($p = 0; $p < count($teacher); $p++) {
            $contentTeacher .= self::cardTeacher($teacher[$p]);
        }

        // LOOP PARA OBTER AS VIEWS DOS CARDS DOS SERVIDORES
        for ($s = 0; $s < count($servers); $s++) {
            $contentServers .= self::cardServer($servers[$s]);
        }

        // RETORNA UM ARRAY COM AS VIEWS DOS PROFESSORES E SERVIORES
        return [
            'professores' => $contentTeacher,
            'servidores'  => $contentServers
        ];
    }

    /**
     * Método responsável por renderizar o contato
     * @param  integer $id
     * @return string
     */
    public static function getContactType($id) {
        // DECLARAÇÃO DE VARIÁVEIS
        $content = '';
        
        // OBTENDO AS INFORMAÇÕES DE CONTATO DO SERVIDOR
        $typeContacts = EntityUser::getUserContact($id);

        // LOOP PARA IMPRIMIR OS CONTATOS
        for ($i = 0; $i < count($typeContacts); $i++) {
            if ($typeContacts[$i]['dsc_tipo'] == 'Telefone') {
                $icone = 'fa-solid fa-phone';

            } else if ($typeContacts[$i]['dsc_tipo'] == 'E-mail') {
                $icone = 'fa-solid fa-envelope';

            } else if ($typeContacts[$i]['dsc_tipo'] == 'WhatsApp') {
                $icone = 'fa-brands fa-whatsapp';
            }
            
            // RENDENIZA A VIEW
            $content .= View::render('pages/contacts/type_contact', [
                'icone' => $icone,
                'contato' => $typeContacts[$i]['dsc_contato']
            ]);
        }
        // RETORNA A VIEW DOS TIPOS DE CONTATO
        return $content;
    }

    /**
     * Método responsável por renderizar os cards de contato dos professores
     * @param  array $contact
     * @return string
     */
    public static function cardTeacher($contact) {
        // ATRIBUIÇÃO DE VARIÁVEIS
        $id = $contact['id_usuario'];
        $imagem = !empty($contact['img_perfil']) ? "{$contact['img_perfil']}" : 'user.png';

        // VIEW DOS CONTATOS DE PROFESSORES
        return View::render('pages/contacts/teacher', [
            'contato_id'  => $id,
            'contato'     => self::getContactType($id),
            'nome'        => $contact['nom_usuario'],
            'regras'      => $contact['regras'],
            'imagem'      => $imagem,
            'hora_inicio' => $contact['hora_inicio'],
            'hora_fim'    => $contact['hora_fim'],
            'sala'        => $contact['num_sala']
        ]);
    }

    /**
     * Método responsável por renderizar os cards de contato dos servidores
     * @param  string $contact
     * @return string
     */
    public static function cardServer($contact) {
        // ATRIBUIÇÃO DE VARIÁVEIS
        $id = $contact['id_usuario'];
        $imagem = !empty($contact['img_perfil']) ? "{$contact['img_perfil']}" : 'user.png';

        // VIEW DOS CONTATOS DE SERVIDORES
        return View::render('pages/contacts/server', [
            'contato_id'  => $id,
            'contato'     => self::getContactType($id),
            'setor'       => $contact['dsc_setor'],
            'imagem'      => $imagem,
            'hora_inicio' => $contact['hora_inicio'],
            'hora_fim'    => $contact['hora_fim'],
            'sala'        => $contact['num_sala']
        ]);
    }
}