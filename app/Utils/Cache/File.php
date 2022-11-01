<?php

namespace App\Utils\Cache;

class File {

    /**
     * Methodo responsavel por retornar o caminho ate o arquivo de cache
     * @param  string $hash
     * @return string
     */
    private static function getFilePath($hash) {
        // DIRETORIO DE CACHE
        $dir = getenv('CACHE_DIR');

        // VERIFICA A EXISTENCIA DO DIRETORIO
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        // RETORNA O CAMINHO ATE O ARQUIVO
        return $dir.'/'.$hash;
    }

    /**
     * Methodo responsavel por guardar informações no cache
     * @param string $hash
     * @param mixed $content
     * @return boolean
     */
    private static function SetStorageCache($hash, $content) {
        // SERIALIZA O RETORNO
        $serialize = serialize($content);

        // OBTEM O CAMINHO ATE O CAMINHO DE CACHE
        $cacheFile = self::getFilePath($hash);

        // GRAVA AS INFORMAÇÕES NO ARQUIVO
        return file_put_contents($cacheFile, $serialize);
    }

    /**
     * Methodo responsavel por retornar o conteudo gravado no cache
     * @param string  $hash
     * @param integer $expiration
     * @return mixed
     */
    private static function getContentCache($hash, $expiration) {
        // OBTEM O CAMINHO DO ARQUIVO
        $cacheFile = self::getFilePath($hash);

        // VERIFICA A EXISTENCIA DO ARQUIVO
        if (!file_exists($cacheFile)) {
            return false;
        }
        // VALIDA A EXPIRAÇÃO DO CACHE
        $createTime = filectime($cacheFile);
        $diffTime = time() - $createTime;

        if ($diffTime > $expiration) {
            return false;
        }
        // RETORNA O DADO REAL
        $serialize = file_get_contents($cacheFile);
        return unserialize($serialize);
    }

    /**
     * Methodo responsavel por obter uma informação do cache
     * @param string $hash
     * @param integer $expiration
     * @param \Closure $function
     * @return mixed
     */
    public static function getCache($hash, $expiration, $function) {
        // VERIFICA O CONTEUDO GRAVADO
        if ($content = self::getContentCache($hash, $expiration)) {
            return $content;
        }
        // EXECUÇÃO DA FUNÇÃO
        $content = $function();

        // GRAVA O RETORNO NO CACHE
        self::SetStorageCache($hash, $content);

        // RETORNA O CONTEUDO
        return $content;
    }
}