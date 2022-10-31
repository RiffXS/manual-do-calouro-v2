<?php

namespace App\Controller\Pages;

use App\Models\User as EntityUser;
use App\Utils\Session;
use App\Utils\View;

class Page {

    /**
     * Paginas disponíveis no navlink
     * @var array
     */
    private static $paginas = [
        'home' => [
            'label' => 'Home',
            'link'  => URL.'/'
        ],
        'about' => [
            'label' => 'Sobre',
            'link'  => URL.'/about'
        ],
        'map' => [
            'label' => 'Mapa',
            'link'  => URL.'/map'
        ],
        'calendar' => [
            'label' => 'Calendário',
            'link'  => URL.'/calendar'
        ],
        'schedule' => [
            'label' => 'Horários',
            'link'  => URL.'/schedule'
        ],
        'contact' => [
            'label' => 'Contatos',
            'link'  => URL.'/contact'
        ],
        'rod' => [
            'label' => 'ROD',
            'link'  => URL.'/rod'
        ],
        'faq' => [
            'label' => 'FAQ',
            'link'  => URL.'/faq'
        ]
    ];

    /**
     * Método responsavel por rendenizar os links do header
     * @param  string $currentModule
     * @return string
     */
    private static function getLinks($current_module) {  
        // LINKS DO MENU
        $links = '';

        // VERIFICA SE O USUÁRIO ESTÁ LOGADO
        if (Session::isLogged()) {
            // OBTEM O ID DA SESSÃO ATUAL
            $id = Session::getSessionId();
            $turma = EntityUser::getUserClass($id);

            if (!empty($turma)) {
                $curso  = $turma['curso'];
                $modulo = $turma['modulo'];

                // ATRIBUI O LINK À PÁGINA DE HORÁRIO
                self::$paginas['schedule']['link'] = URL."/schedule?curso=$curso&modulo=$modulo";  
            }
        }
        // ITERA OS MODULOS
        foreach (self::$paginas as $hash => $module) {
            $links .= View::render('pages/header/link', [
                'label'   => $module['label'],
                'link'    => $module['link'],
                'current' => $hash == $current_module ? 'active' : ''
            ]);
        }
        // RETORNA A RENDENIZAÇÃO DOS LINKS
        return $links;
    }

    /**
     * Método responsável por renderizar a view do menu do login
     * @return string
     */
    private static function getLogin() {
        // RETORNA O DROPDOWN CASO LOGADO
        if (Session::isLogged()) {
            // OBTEM O ID DA SESSÃO ATUAL
            $id = Session::getSessionId();            

            // OBTÊM OS DADOS DO USUARIO
            $obUser = EntityUser::getUserById($id);

            // RETORNA O DROPDOWN DO LOGIN
            return View::render('pages/header/dropdown', [
                'imagem' => $obUser->getImgProfile()
            ]);
        }
        // RETORNA O BOTÃO DO LOGIN
        return View::render('pages/header/button');
    }

    /**
     * Methodo responsavel por rendenizar a view do painel com conteudos dinamicos
     * @param  string $title
     * @param  string $contenct
     * @param  string $currentModule
     * @return string
     */
    public static function getHeader($module) {
        // RENDENIZA A VIEW DO HEADER
        return View::render('pages/header', [
            'links'    => self::getLinks($module),
            'login'   => self::getLogin()
        ]);
    }

    /**
     * Méthodo responsavel por rendenizar o rodapé da pagina
     * @return string
     */
    private static function getFooter() {
        // RENDENIZA A VIEW DO FOOTER
        return View::render('pages/footer');
    }

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina generica
     * @return string 
     */
    public static function getPage($title, $content, $module = '') {
        // RENDENIZA A PAGINA
        return View::render('pages/page',[
            'title'   => $title,
            'header'  => self::getHeader($module),
            'content' => $content,
            'footer'  => self::getFooter()
        ]);
    }
    
    /**
     * Methodo responsavel por rendenizar o layout de paginação
     * @param \App\Http\Request     $request
     * @param \App\Utils\Pagination $obPagination
     * @return string
     */
    public static function getPagination($request, $obPagination) {
        // DECLARAÇÃO DE VARIAVEIS
        $links = '';
        $pages = $obPagination->getPages(); // OBTER AS PAGINAS
        $url = $request->getRouter()->getCurrentUrl(); // URL ATUAL sem GET

        // VERIFICA A QUANTIDADE DE PAGINAS
        if (count($pages) <= 1) return '';

        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        $currentPage = $queryParams['page'] ?? 1; // PAGINA ATUAL
        $limit = getenv('PG_LIMIT');             // LIMITE DE PAGINAS
        $middle = ceil($limit / 2);             // MEIO DA PAGINAÇÃO

        // AJUSTA O INICIO DA PAGINAÇÃO
        $start = $middle > $currentPage ? 0 : $currentPage - $middle; 

        // AJUSTA O FINAL DA PAGINAÇÃO
        $limit += $start; 

        // AJUSTA O INICIO DA PAGINAÇÃO
        if ($limit > count($pages)) {
            $diff = $limit - count($pages);
            $start -= $diff;
        }
        // LINK INICIAL
        if ($start > 0) {
            $links .= self::getPaginationLink($queryParams, reset($pages), $url, '<<'); 
        }
        // RENDENIZA OS LINKS
        foreach ($pages as $page) {
            // VERIFICA O STRAT DA PAGINAÇÃO
            if ($page['page'] <= $start) continue;

            if ($page['page'] > $limit) {
                $links .= self::getPaginationLink($queryParams, end($pages), $url, '>>'); 
                break;
            }
            $links .= self::getPaginationLink($queryParams, $page, $url);   
        }
        // RETORNA BOX DE PAGINAÇÃO
        return View::render('pages/pagination/box',[
            'links' => $links
        ]); 
    } 

    /**
     * Methodo responsavel por retornar um link da paginação
     * @param  array  $queryParams
     * @param  array  $page
     * @param  string $url
     * @return string
     */
    private static function getPaginationLink($queryParams, $page, $url, $label = null) {
        // ALTERA PAGINA    
        $queryParams['page'] = $page['page'];

        // LINK
        $link = $url.'?'.http_build_query($queryParams);

        // VIEW
        return View::render('pages/pagination/link', [
            'page' => $label ?? $page['page'],
            'link' => $link,
            'active' => $page['current'] ? 'text-danger' : ''
        ]);
    }
}