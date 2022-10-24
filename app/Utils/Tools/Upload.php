<?php

namespace App\Utils\Tools;

class Upload {

    /**
     * Nome do arquivo (sem extensão)
     * @var string 
     */
    private $name;

    /**
     * Extensão do arquivo (sem ponto)
     * @var string
     */
    private $extension;

    /**
     * Type do arquivo
     * @var string
     */
    private $type;

    /**
     * Nome temporario/caminho do arquivo
     * @var string
     */
    private $tmpName;

    /**
     * Tamanho do arquivo
     * @var integer
     */
    private $size;

    /**
     * Codigo de erro do upload
     * @var integer
     */
    private $error;

    /**
     * Contador de duplicação de arquivo
     * @var integer
     */
    private $duplicates = 0;

    /**
     * Coonstrutor da calsse
     * @param array file $_FILE['campo']
     */
    public function __construct($file) {
        $info = pathinfo($file['name']);

        $this->name      = $info['filename'];
        $this->extension = $info['extension'] ?? '';
        $this->type      = $file['type'];
        $this->tmpName   = $file['tmp_name'];
        $this->error     = $file['error'];
        $this->size      = $file['size'];
    }

    /**
     * Metodo responsavel por obter o valor atributo do objeto
     */
    public function __get($name) {
        return $this->{$name};
    }

    /**
     * Metodo responsavel por setar um atributo do objeto
     */
    public function __set($name, $value) {
        $this->{$name} = $value;
    }

    /**
     * Metodo responsavel por gerar um novo nome aleatorio
     */
    public function generateNewName() {
        $this->name = time().'-'.uniqid();
    }

    /**
     * Metodo responsavel por retornar o nome do arquivo com sua extensão
     * @return string
     */
    public function getBasename() {
        // VALIDA EXTENSÃO
        $extension = strlen($this->extension) ? '.'.$this->extension : '';

        // VALIDA DUPLICAÇÃO
        $duplicates = $this->duplicates > 0 ? '-'.$this->duplicates : '';

        // RETORNA O NOME COMPLETO
        return $this->name.$duplicates.$extension; 
    }

    /**
     * Metodo responsavel por obter um nome possivel para o arquivo
     * @param  string  $dir
     * @param  boolean $overwrite
     * @return string 
     */
    private function getPossibleBasename($dir, $overwrite) {
        // SOBRESCREVER ARQUIVO
        if ($overwrite) return $this->getBasename();

        // NÃO PODE SEBRESCER ARQYUVI
        $basename = $this->getBasename();

        // VERIFICAR DUPLICAÇÃO
        if (!file_exists($dir.'/'.$basename)) {
            return $basename;
        }
        // INCRIMENTAR DUPLICAÇÕES
        $this->duplicates++;

        // RETORNO O PROPRIO METODO
        return $this->getPossibleBasename($dir, $overwrite);
    }

    /**
     * Metodo responsavel por mover o arquivo de upload
     * @param  string  $dir
     * @param  boolean $overwrite 
     * @return boolean
     */
    public function upload($dir, $overwrite = true) {
        // VERIFICAR ERRO
        if ($this->error != 0) return false;

        // CAMINHO COMPLETO DE DESTINO
        $path = $dir.'/'.$this->getPossibleBasename($dir, $overwrite);

        // MOVE O ARQUIVO PARA PASTA DE DESTINO
        return move_uploaded_file($this->tmpName, $path);
    }

    /**
     * Metodo responsavel por criar instancias de ulpload para multiplos arquivos
     * @param  array $files $_FILES['campo']
     * @return array
     */
    public static function createMultUpload($files) {
        // DECLARAÇÃO DE VARIAVEIS
        $uploads = [];

        // CRIA UM ARRAY DE INSTANCIAS DA CLASSE
        foreach ($files['name'] as $key => $values) {
            // ARRRAY DE ARQUIVO
            $file = [
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
                'tpm_name' => $files['tpm_name'][$key],
                'error'    => $files['error'][$key],
                'size'     => $files['size'][$key],
            ];
            // NOVA INSTANCIA
            $uploads[] = new Upload($file);
        }
        // RETORNA OS ARQUIVOS
        return $uploads;
    }
}