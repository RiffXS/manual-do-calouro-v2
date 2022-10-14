<?php

namespace App\Http;

class Request {

    /**
     * Instancia do Router
     * @var Router 
     */
    private $router;

    /**
     * Méthodo HTTP da requisição
     * @var string 
     */
    private $httpMethod;

    /**
     * URI da pagina
     * @var string
     */
    private $uri;

    /**
     * Parametros da URL ($_GET)
     * @var array
     */
    private $queryParams = [];

    /**
     * Váriaveis recebidas no POST da pagina ($_POST)
     * @var array
     */
    private $postVars = [];

    /**
     * Cabeçalho da requisição
     * @var array
     */
    private $headers = [];

    /**
     * Instancia de User
     * @var \App\Models\Entity\User
     */
    public $user;

    /**
     * Construtor da classe
     */
    public function __construct($router) {
        $this->router      = $router;
        $this->queryParams = $_GET ?? [];
        $this->headers     = getallheaders();
        $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->setPostVars(); 
    }

    /**
     * Methodo responsavel por definir as variaveis do post
     */
    private function setPostVars() {
        // VERIFICA O METHODO DA REQUISIÇÃO
        if ($this->httpMethod == 'GET') return false;

        // POST PADRÃO
        $this->postVars = $_POST ?? [];

        // POST JSON
        $inputRaw = file_get_contents('php://input');

        // POST RAW
        $this->postVars = strlen($inputRaw) && empty($_POST) ? 
            json_decode($inputRaw, true) : $this->postVars;
    }
    
    /**
     * Methodo responsavel por definir a URI
     */
    private function setUri() {
        // URI COMPLETA COM GETS    
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';

        // REMOVE GETS DA URI
        $xUri = explode('?', $this->uri);
        $this->uri = $xUri[0];
    }

    /**
     * Methodo responsavel por retornar a instancia de router
     * @return Router
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * Méthodo responsavel por retornar ó methodo HTTP
     * @return string
     */
    public function getHttpMethod() {
        return $this->httpMethod;
    }

    /**
     * Méthodo responsavel por retornar a URI da requisição
     * @return string
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * Méthodo responsavel por retornar os headers da requisição
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * Méthodo responsavel por retornar os parametros da url da requisição
     * @return array
     */
    public function getQueryParams() {
        return $this->queryParams;
    }

    /**
     * Méthodo responsavel por retornar as variaveis POST da requisição
     * @return array
     */
    public function getPostVars() {
        return $this->postVars;
    }
}       