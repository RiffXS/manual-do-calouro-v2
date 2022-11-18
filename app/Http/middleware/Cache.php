<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Utils\Cache\File as CacheFile;
use Closure;

class Cache {

    /**
     * Metodo responsavel por verificar se a request atual pode ser cacheada
     * @param \App\Http\Request $request
     * 
     * @return boolean
     */
    private function isCacheable(Request $request): bool {
        // DECLARAÇÃO DE VARIAVEL
        $cache = true;

        // VALIDA O TEMPO DE CACHE
        if (getenv('CACHE_TIME') <= 0) {
            $cache = false;
        } 
        // VALIDA O METODO DA REQUISIÇÃO
        if ($request->getHttpMethod() != 'GET') {
            $cache = false;
        }
        // VALIDA O HEADER DE CACHE
        $headers = $request->getHeaders(); 

        if (isset($headers['Cache-Control']) and $headers['Cache-Control'] == 'no-cache') {
            $cache = false;
        }
        // CACHEAVEL
        return $cache;
    }

    /**
     * Methodo responsavel por retornar a hash do cache
     * @param \App\Http\Request
     * 
     * @return string
     */
    private function getHash(Request $request): string {
        // URI DA ROTA
        $uri = $request->getRouter()->getUri();

        // QUERY PARAMS
        $queryParams = $request->getQueryParams();
        $uri .= !empty($queryParams) ? '?'.http_build_query($queryParams) : '';

        // REMOVE AS BARRAS E RETORNAR A HASH
        return rtrim('route-'.preg_replace('/[^0-9a-zA-Z]/', '-', ltrim($uri, '/')), '-');
    }

    /**
     * Methodo responsavel por executar o middleware
     * @param \App\Http\Request $request
     * @param \Closure $next
     * 
     * @return \App\Http\Response
     */
    public function handle(Request $request, Closure $next): Response { 
        // VARIFICA SE A REQUEST ATUAL E CACHEAVEL
        if (!$this->isCacheable($request)) {
            // EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
            return $next($request);
        }
        // HASH DO CACHE
        $hash = $this->getHash($request);

        // RETORNA OS DADOS DO CACHE
        return CacheFile::getCache($hash, getenv('CACHE_TIME'), function() use($request, $next) {
            return $next($request);
        });
    }
}
