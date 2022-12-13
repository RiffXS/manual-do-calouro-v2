<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Usuario as EntityUser;
use App\Utils\Pagination;
use App\Utils\Session;
use App\Utils\View;

class Page {

    /**
     * Páginas disponíveis no navlink
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
     * Método responsável por renderizar os links do header
     * @param  string $currentModule
     * 
     * @return string
     */
    private static function getLinks(string $current_module): string { 
        // LINKS DO MENU
        $links = ''; 
        
        // VERIFICA SE O USUÁRIO ESTÁ LOGADO
        if (Session::isLogged()) {
            // OBTEM O ID DA SESSÃO ATUAL
            $turma = EntityUser::getUserClass(Session::getId()); // CONSULTA

            if (!empty($turma)) {
                // ATRIBUI O LINK À PÁGINA DE HORÁRIO
                self::$paginas['schedule']['link'] = URL."/schedule?curso={$turma['curso']}&modulo={$turma['modulo']}";  
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
        // RETORNA A RENDERIZAÇÃO DOS LINKS
        return $links;
    }

    /**
     * Método responsável por renderizar a view do menu do login
     * @return string
     */
    private static function getLogin(): string {
        // RETORNA O DROPDOWN CASO LOGADO
        if (Session::isLogged()) {
            // OBTÊM OS DADOS DO USUÁRIO
            $obUser = EntityUser::getUserById(Session::getId());

            // LAMBDA - RENDERIZA LINK SE USUÁRIO FOR ADMINISTRADOR
            $isAdmin = function($lv) {
                if ($lv == 1) {
                    return View::render('pages/header/admin');
                }
                return '';
            };
            // RETORNA O DROPDOWN DO LOGIN
            return View::render('pages/header/dropdown', [
                'imagem' => $obUser->getImg_perfil(),
                'admin'  => $isAdmin($obUser->getFk_acesso())
            ]);
        }
        // RETORNA O BOTÃO DO LOGIN
        return View::render('pages/header/button');
    }

    /**
     * Método responsável por renderizar a view do painel com conteúdos dinâmicos
     * @param  string $module
     * 
     * @return string
     */
    private static function getHeader(string $module): string {
        // RENDERIZA A VIEW DO HEADER
        return View::render('pages/header', [
            'links' => self::getLinks($module),
            'login' => self::getLogin()
        ]);
    }

    /**
     * Método responsável por renderizar o rodapé da pagina
     * @return string
     */
    private static function getFooter(): string {
        // RENDERIZA A VIEW DO FOOTER
        return View::render('pages/footer');
    }

    /**
     * Método responsável por retornar o contéudo (view) da página genérica
     * @return string
     */
    protected static function getPage(string $title, string $content, string $module = ''): string {
        // RENDERIZA A PAGINA
        return View::render('pages/page',[
            'title'   => $title,
            'header'  => self::getHeader($module),
            'content' => $content,
            'footer'  => self::getFooter()
        ]);
    }

    /**
     * Método responsavel por renderizar os input hidden
     * @param array $array
     * 
     * @return string
     */
    protected static function setHiddens(array $array): string {
        // INICIALIZAÇÃO DE VARIAVEL
        $content = '';

        for ($i = 0; $i < count($array); $i++) {
            $keys = array_keys($array);

            $content .= View::render('/shared/hidden', [
                'id'    => 'h-'.$keys[$i],
                'value' => $array[$keys[$i]]
            ]);
        }
        // RETORNA O CONTEUDO
        return $content;
    }
    
    /**
     * Método responsável por renderizar o layout de paginação
     * @param \App\Http\Request     $request
     * @param \App\Utils\Pagination $obPagination
     * 
     * @return string
     */
    protected static function getPagination(Request $request, Pagination $obPagination): string {
        // DECLARAÇÃO DE VARIÁVEIS
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
        // RENDERIZA OS LINKS
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
     * Método responsável por retornar um link da paginação
     * @param  array  $queryParams
     * @param  array  $page
     * @param  string $url
     * 
     * @return string
     */
    private static function getPaginationLink(array $queryParams, array $page, string $url, string $label = null): string {
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