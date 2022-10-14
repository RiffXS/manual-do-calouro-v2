<?php

namespace App\Http;

class Response {

    /**
     * Codigo status HTTP
     * @var integer
     */
    private $httpCode = 200;

    /**
     * Cabeçalho do Response
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de conteudo que esta sendo retornado
     * @var 
     */
    private $contentType = 'text/html';

    /**
     * Conteudo do Response
     * @var mixed
     */
    private $content;

    /**
     * @param integer $httpCode
     * @param mixed   $content
     * @param string  $contentType 
     * 
     */
    public function __construct($httpCode, $content, $contentType = 'text/html') {
        $this->httpCode = $httpCode;
        $this->content  = $content;
        $this->setContentType($contentType);
    }

    /**
     * Methodo responsavel por alterar o content type do Response
     * 
     */
    public function setContentType($contentType) {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Methodo responsavel por adicionar um registro no cabeçalho do Response
     * 
     */
    public function addHeader($key, $value) {
        $this->headers[$key] = $value;
    }

    /**
     * Methodo responsavel por enviar os headers para o navegador
     */
    private function sendHeaders() {
        // STATUS 
        http_response_code($this->httpCode);

        // ENVIAR HEADERS
        foreach($this->headers as $key=>$value) {
            header($key.': '.$value);
        }
    }

    /**
     * Methodo responsavel por enviar a resposta ao usuario
     */
    public function sendResponse() {
        // ENVIANDO OS HEADERS  
        $this->sendHeaders();

        // IMPRIME O CONTEUDO
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
        }   
    }
}
?>