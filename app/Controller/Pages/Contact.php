<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Contact as EntityContact;
use App\Models\User as EntityUser;
use App\Utils\Sanitize;
use App\Utils\Session;
use App\Utils\Tools\Alert;
use App\Utils\View;

class Contact extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página contatos
     * @param \App\Http\Request $request
     * 
     * @return string  
     */
    public static function getContact(Request $request): string {
        // DECLARAÇÃO DE VARIAVEIS
        $crud = '';

        $contacts = [
            'professor' => EntityContact::getContactTeacher(),
            'servidor'  => EntityContact::getContactServer()
        ];
        // OBTEM A VIEWS DOS CARDS CONTATOS
        $views = self::getContacts($contacts);

        // VERIFICA SE EXISTE UMA SESSÃO
        if (Session::isLogged()) {
            $crud = self::getCrud(Session::getLv());
        }
        // REDENIZA AS COLUNAS DE CONTATOS
        $content = View::render('pages/contacts', [
            'status'      => Alert::getStatus($request),
            'my_contacts' => $crud,
            'professores' => $views['professores'],
            'servidores'  => $views['servidores']
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Contatos', $content, 'contact');
    }

    /**
     * Methodo responsavel por obter a rendenização dos items de usuarios para página
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    private static function getContactItems($fk) {
        // USUARIOS
        $itens = '';

        // RESULTADOS DA PAGINA
        $results = EntityContact::getContactsInfo($fk);

        // RENDENIZA O ITEM
        while ($contact = $results->fetch(\PDO::FETCH_ASSOC)) {
            // VIEW De DEPOIMENTOSS
            $id = $contact['id_contato'];
            
            $itens .= View::render('pages/contacts/item',[
                'tipo' => $contact['dsc_tipo'],
                'dado' => $contact['dsc_contato'],
                'edit' => "onclick=editContact($fk)",
                'del'  => "onclick=delContact($id)"
            ]);
        }
        // RETORNA OS DEPOIMENTOS
        return $itens;
    }

    /**
     * Método responsavel por rendenizar o CRUD de contatos
     * @param integer $acess
     * 
     * @return string
     */
    private static function getCrud($acess): string {
        // DECLARAÇÃO DE VARIAVEIS
        $auth = [4, 5]; // tipos de usuarios aceitos
        $view = '';

        // VERIFICA SE O ACESSO E AUTORIZADO
        if (in_array($acess, $auth)) {
            $view .= View::render('pages/contacts/crud', [
                'items' => self::getContactItems(Session::getId()),
            ]);
        }
        // RETORNA VAZIO
        return $view;
    }

    /**
     * Método responsável por rendenizar os contatos
     * @param  array $obContacat
     * 
     * @return array
     */
    private static function getContacts(array $contacts): array {
        // DECLARAÇÃO DE VARIÁVEIS
        $contentTeacher = '';
        $contentServers = '';
        
        // OBTENDO OS ARRAYS DOS CONTATOS
        $teacher = $contacts['professor'];
        $servers = $contacts['servidor'];

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
     * 
     * @return string
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
            $content .= View::render('pages/contacts/type', [
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
     * 
     * @return string
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
     * 
     * @return string
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

    /**
     * Método responsavel por cadastrar um contato
     * @param Request $request
     * 
     * @return void
     */
    public static function setNewContact(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // VERIFICA HTML INJECT
        if (Sanitize::validateForm($postVars)) {
            $request->getRouter()->redirect('/signup?status=invalid_chars');
        }
        // SANITIZA O ARRAY
        $postVars = Sanitize::sanitizeForm($postVars);

        $type = $postVars['tipo-contato'];
        $data = $postVars['input-contato'];

        // NOVA INSTANCIA
        $obContact = new EntityContact;

        // INSERE DADOS NA INSTANCIA
        $obContact->setFk_usuario(Session::getId());
        $obContact->setFk_tipo($type);
        $obContact->setDsc_contato($data);

        // INSERE OBJETO NO BANCO
        $obContact->insertContact();

        // REDIRECIONA PARA CONTATOS
        $request->getRouter()->redirect('/contact?status=contact_registered');
    }
    
    /**
     * Método responsavel por consultar os dados de um contato
     * @param \App\Http\Request $request
     * @param string $id
     * 
     * @return void
     */
    public static function getEditContact(Request $request, string $id): void {
        // ARRAY COM AS INFORMAÇÕES DO CONTATO
        $return = [
           'dados' => EntityContact::getContactByFk($id)
        ];
        // IMPRIMI O JSON NA PAGINA
        echo json_encode($return, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }

    /**
     * Método responsavel por editar os dados de um contato
     * @param Request $request
     * 
     * @return void
     */
    public static function setEditContact(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // VERIFICA HTML INJECT
        if (Sanitize::validateForm($postVars)) {
            $request->getRouter()->redirect('/signup?status=invalid_chars');
        }
        // SANITIZA O ARRAY
        $postVars = Sanitize::sanitizeForm($postVars);

        $id   = $postVars['id_contato'];
        $fk   = $postVars['fk_usuario'];
        $tipo = $postVars['tp_contato'];
        $dsc  = $postVars['dsc_contato'];

        // SANITIZAÇÕES
        $obContact = new EntityContact;

        $obContact->setId_contato($id);
        $obContact->setFk_usuario($fk);
        $obContact->setFk_tipo($tipo);
        $obContact->setDsc_contato($dsc);

        $obContact->updateContact();

        $request->getRouter()->redirect('/contact?status=contact_updated');

    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public static function setDeleteContact(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // OBTENDO O USUARIO DO BANCO DE DADOS
        $obContact = EntityContact::getContactById($postVars['id']);
  
        // VALIDA A INSTANCIA
        if (!$obContact instanceof EntityContact) {
            $request->getRouter()->redirect('/contact');
        }
        // EXCLUIR DEPOIMENTO
        $obContact->deleteContact();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/contact?status=contact_deleted');
    }
}