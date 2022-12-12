<?php

namespace App\Utils;

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
     * Coonstrutor da classe
     * @param array $file $_FILE['arquivo']
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
     * Metodo responsavel por gerar um novo nome aleatorio
     * @return void
     */
    public function generateNewName(): void {
        $this->name = time().'-'.uniqid();
    }

    /**
     * Metodo responsavel por retornar o nome do arquivo com sua extensão
     * @return string
     */
    public function getBasename(): string  {
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
     * 
     * @return string 
     */
    private function getPossibleBasename(string $dir, bool $overwrite): string {
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
     * 
     * @return boolean
     */
    public function upload(string $dir, bool $overwrite = true): bool {
        // VERIFICAR ERRO
        if ($this->error != 0) return false;

        // CAMINHO COMPLETO DE DESTINO
        $path = $dir.'/'.$this->getPossibleBasename($dir, $overwrite);

        // MOVE O ARQUIVO PARA PASTA DE DESTINO
        return move_uploaded_file($this->tmpName, $path);
    }

    /**
     * Metodo responsavel por criar instancias de ulpload para multiplos arquivos
     * @param  array $files $_FILES['arquivo']
     * 
     * @return array
     */
    public static function createMultUpload(array $files): array {
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

    /**
     * Método responsavel por realizar o upload da imagem enviada pelo usuario
     * @param \App\Utils\Upload $obUpload
     * 
     * @return string
     */
    public static function profilePicture(Upload $obUpload): string {
        // EXTENÇÕES ACEITAS
        $extensions = array("png", "jpg", "jpeg");

        // MENSAGEM DE STATUS
        $status = '';

        // VALIDA SE A EXTENSÃO DO ARQUIVO É PERMITIDA
        if (!in_array($obUpload->getExtension(), $extensions)) {
            $status = 'image_type';
        }
        // VALIDA SE O TAMANHO DA IMAGEM EXCEDEU O LIMITE
        else if ($obUpload->getsize() > 33554432) {
            $status = 'image_size';
        }
        // VALIDA SE O UPLOAD OCORREU CORRETAMENTE
        else if ((!$obUpload->upload(__DIR__.'/../../public/uploads/'))){    
            $status = 'image_erro';
        } 
        // RETORNA O STATUS
        return $status;
    } 

    /*
     * Metodos GETTERS E SETTERS
     */
    
    /**
     * Get name
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Set name
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * Get extension
     * @return string
     */
    public function getExtension(): string {
        return $this->extension;
    }

    /**
     * Set extension
     * @param mixed $name
     */
    public function setExtension(mixed $ext): void {
        $this->extension = $ext;
    }

    /**
     * Get tpmName
     * @return string
     */
    public function getTpmName(): string {
        return $this->tmpName;
    }

    /**
     * Get type
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * Get size
     * @return integer
     */
    public function getsize(): int {
        return $this->size;
    }

    /**
     * Get error
     * @return integer
     */
    public function getUploadError(): int {
        return $this->error;
    }
}