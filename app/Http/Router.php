<?php 

namespace App\Http;

use App\Http\Middleware\Queue as MiddlewareQueue;
use \Closure;
use \Exception;
use \ReflectionFunction;

class Router {

    /**
     * URL Completa do projeto
     * @var string
     */
    private $url = '';
    
    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Índice de rotas
     * @var array 
     */
    private $routes = [];

    /**
     * Instância de Request
     * @var Request
     */
    private $request;

    /**
     * Content type padrão do Response
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Método responsável por iniciar a classe
     * @param string $uri
     */
    public function __construct(string $url) {
        $this->request = new Request($this);
        $this->url     = $url;
        $this->setPrefix();
    }

    /**
     * Método responsável por alterar o valor do content type
     * @param string $contentType
     * 
     * @return void
     */
    public function setContentType(string $contentType): void {
        $this->contentType = $contentType;
    }

    /**
     * Método responsável por definir o prefixo das rotas
     * @return void
     */
    private function setPrefix(): void {
        // INFORMAÇÕES DA URL
        $parseUrl = parse_url($this->url);

        // DEFINE O PREFIXO
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adicionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array  $params
     * 
     * @return array
     */
    public function addRoute(string $method, string $route, array $params = []): array {
        // VALIDAÇÃO DOS PARAMETROS
        foreach ($params as $key=>$value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }
        // MIDDLEWARES DA ROTA
        $params['middlewares'] = $params['middlewares'] ?? [];

        //  VARIAVEIS DA ROTA
        $params['variables'] = [];

        // PADRÃO DE VALIDAÇÃO DAS VARIVEIS DAS ROTAS
        $patternVariable = '/{(.*?)}/';

        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }
        // REMOVE BARRA NO FINAL DA ROTA
        $route = rtrim($route, '/');

        // PADRAO DE VALIDAÇÃO DA URL
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

        // ADICIONA A ROTA DENTRO DA CLASSE
        return $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método responsável por definir uma rota de GET
     * @param string $router
     * @param array  $params
     * 
     * @return array
     */
    public function get(string $route, array $params = []): array {
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de POST
     * @param string $router
     * @param array  $params
     * 
     * @return array
     */
    public function post(string $route, array $params = []): array {
        return $this->addRoute('POST', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de PUT
     * @param string $router
     * @param array  $params
     * 
     * @return array
     */
    public function put(string $route, array $params = []): array {
        return $this->addRoute('PUT', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de DELETE
     * @param string $router
     * @param array  $params
     * 
     * @return array
     */
    public function delete(string $route, array $params = []): array {
        return $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Método responsável por retornar a  URI desconsiderando o prefixo
     * @return string
     */
    public function getUri(): string {
        // URI DA REQUEST
        $uri = $this->request->getUri();

        // FATIA A URI COM O PREFIXO
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        // RETORNA A URI SEM PREFIXO
        return rtrim(end($xUri), '/');
    }

    /**
     * Método responsável por retornar os dados da rota atual
     * @return array 
     */
    private function getRoute(): array {
        // URI
        $uri = $this->getUri();
        
        // METHOD
        $httpMethod = $this->request->getHttpMethod();

        // VALIDA AS ROTAS
        foreach ($this->routes as $patternRoute => $methods) {
            // VERIFICA SE A URI BATE COM O PADRÃO
            if (preg_match($patternRoute, $uri, $matches)) {
                // VERIFICA O METHODO
                if (isset($methods[$httpMethod])) {
                    // REMOVE A PRIMEIRA POSIÇÃO
                    unset($matches[0]);

                    // VARIAVEIS PROCESSADAS
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    // RETORNO DO PARAMETROS DA ROTA
                    return $methods[$httpMethod];
                }
                // MÉTODO NÃO PERMITIDO/DEFINIDO
                throw new Exception("Méthodo não permitido", 405);
            }
        }
        // URL NÃO ENCONTRADA
        throw new Exception("URL não encontrada", 404);
    }

    /**
     * Método responsável por executar a rota atual
     * @return Response
     */
    public function run(): Response {
        try {
            // OBTEM A ROTA ATUAL
            $route = $this->getRoute();

            // VERIFICA O CONTROLADOR
            if (!isset($route['controller'])) {
                throw new Exception("A URL não pode ser processada", 500);
            }
            // ARGUMENTOS DA FUNÇÃO
            $args = [];

            // REFLECTION
            $reflection = new ReflectionFunction($route['controller']);

            foreach($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            // RETORNA A EXECUÇÃO DA FILA DE MIDDLEWARES
            return (new MiddlewareQueue($route['middlewares'], $route['controller'], $args))->next($this->request);

        } catch(Exception $e) {
            // OBTEM A MENSAGEM DE ERRO
            $erro = $e->getMessage();

            return new Response($e->getCode(), $this->getErrorMesasage($erro), $this->contentType);
        }
    }

    /**
     * Método responsável por retornar a mensagem de erro de acordo com o content type
     * @param string $message
     * 
     * @return mixed
     */
    private function getErrorMesasage(string $message): mixed {
       switch ($this->contentType) {
        case 'application/json':
            return [
                'error' => $message
            ];        
        default:
            return $message;
       }
    }

    /**
     * Método responsável por retornar a URL atual
     * @return string
     */
    public function getCurrentUrl(): string {
        return $this->url.$this->getUri();
    }

    /**
     * Método responsável por redirecionar a URL
     * @param string $route
     * 
     * @return void
     */
    public function redirect(string $route): void {
        $url = $this->url.$route;

        // EXECUTA O REDIRECT
        header('Location: '.$url);
        exit;
    }
}
