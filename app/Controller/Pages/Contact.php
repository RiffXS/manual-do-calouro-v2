<?php

namespace App\Controller\Pages;

use App\Models\Contact as EntityContact;
use App\Models\User as EntityUser;
use App\Utils\View;

class Contact extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página contatos
     * @return string 
     * 
     * @author @SimpleR1ick @RiffXS 
     */
    public static function getContact(): string {
        // CRIANDO NOVA INSTÂNCIA DE CONTATO
        $obContact = new EntityContact;

        // OBTEM A VIEWS DOS CARDS CONTATOS
        $views = self::getContacts($obContact);

        // REDENIZA AS COLUNAS DE CONTATOS
        $content = View::render('pages/contacts', [
            'professores' => $views['professores'],
            'servidores'  => $views['servidores']
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Contatos', $content, 'contact');
    }
    
    /**
     * Método responsável por rendenizar os contatos
     * @param EntityContact $obContacat
     * @return array
     * 
     * @author @SimpleR1ick @RiffXS 
     */
    private static function getContacts(EntityContact $obContact): array {
        // DECLARAÇÃO DE VARIÁVEIS
        $contentTeacher = '';
        $contentServers = '';
        
        // OBTENDO OS ARRAYS DOS CONTATOS
        $teacher = $obContact->getProfessor();
        $servers = $obContact->getServidor();

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
     * 
     * @author @SimpleR1ick @RiffXS
     */ 
    private static function getContactType(int $id): string {
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
     * 
     * @author @SimpleR1ick @RiffXS 
     */
    private static function cardTeacher(array $contact): string {
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
     * 
     * @author @SimpleR1ick @RiffXS 
     */
    private static function cardServer(array $contact): string {
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