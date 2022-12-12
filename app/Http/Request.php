<?php

namespace App\Http;

class Request {

    /**
     * Instância do Router
     * @var Router 
     */
    private $router;

    /**
     * Método HTTP da requisição
     * @var string 
     */
    private $httpMethod;

    /**
     * Parâmetros da URL ($_GET)
     * @var array
     */
    private $queryParams = [];

    /**
     * Arquivos recebidos no POST DA PAGINA ($_FILES)
     * @var array
     */
    private $uploadFiles = [];

    /**
     * Cabeçalho da requisição
     * @var array
     */
    private $headers = [];

    /**
     * Variáveis recebidas no POST da página ($_POST)
     * @var array
     */
    private $postVars = [];

     /**
     * URI da página
     * @var string
     */
    private $uri;

    /**
     * Instância de Usuario na request
     * @var \App\Models\Usuario
     */
    public $user;

    /**
     * Construtor da classe
     */
    public function __construct($router) {
        $this->router      = $router;
        $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->queryParams = $_GET ?? [];
        $this->uploadFiles = $_FILES ?? [];
        $this->headers     = getallheaders();
        $this->setPostVars(); // POST
        $this->setUri();      // URI
    }

    /**
     * Método GETTERS e SETTERS
     */

    /**
     * Método responsável por retornar a instância de router
     * @return Router
     */
    public function getRouter(): Router {
        return $this->router;
    }

    /**
     * Método responsável por retornar  método HTTP
     * @return string
     */
    public function getHttpMethod(): string {
        return $this->httpMethod;
    }

    /**
     * Método responsável por retornar os parâmetros da url da requisição
     * @return array
     */
    public function getQueryParams(): array {
        return $this->queryParams;
    }

    /**
     * Método responsável por retornar os arquivos recebidos no post
     * @return array
     */
    public function getUploadFiles(): array {
        return $this->uploadFiles;
    }

    /**
     * Método responsável por retornar os headers da requisição
     * @return array
     */
    public function getHeaders(): array {
        return $this->headers;
    }

    /**
     * Método responsável por definir as variáveis do post
     * @return boolean
     */
    private function setPostVars(): bool {
        // VERIFICA O METHODO DA REQUISIÇÃO
        if ($this->httpMethod == 'GET') return false;
    
        // POST PADRÃO
        $this->postVars = $_POST ?? [];

        // POST JSON
        $inputRaw = file_get_contents('php://input');
        
        // VERIFICA SE E JSON
        $this->postVars = strlen($inputRaw) && empty($_POST) ? 
            json_decode($inputRaw, true) : $this->postVars;

        // RETORNA SUCESSO
        return true;
    }

    /**
     * Método responsável por retornar as variáveis POST da requisição
     * @return array
     */
    public function getPostVars(): array {
        return $this->postVars;
    }

    /**
     * Método responsável por definir a URI
     * @return void
     */
    private function setUri(): void {
        // URI COMPLETA COM GETS    
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';

        // REMOVE GETS DA URI
        $xUri = explode('?', $this->uri);
        $this->uri = $xUri[0];
    }

    /**
     * Método responsável por retornar a URI da requisição
     * @return string
     */
    public function getUri(): string {
        return $this->uri;
    }
}       