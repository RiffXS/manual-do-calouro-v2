<?php
// LOAD COMPOSER
require __DIR__.'/../vendor/autoload.php';

use App\Http\Middleware\Queue as MiddlewareQueue;
use App\Utils\View;
use App\Utils\Database;
use App\Utils\Environment;


// CARREGA VARIAVEIS DE AMBIENTE
Environment::load(__DIR__.'/../');

// DADOS DE CONEXÃO COM O BANCO
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

// DEFINE A CONSTANTE DE URL DO PROJETO
define('URL', getenv('URL'));

// DEFINE O VALOR PADRÃO DAS VARIAVEIS
View::init([
    'URL' => URL
]);

// DEFINE O MAPEAMENTO DE MIDDLEWARES DISPONIVEIS
MiddlewareQueue::setMap([
    'maintenence'  => \App\Http\Middleware\Maintenence::class,
    'admin-logout' => \App\Http\Middleware\AdminLogout::class,
    'admin-login'  => \App\Http\Middleware\AdminLogin::class,
    'user-login'   => \App\Http\Middleware\UserLogin::class,
    'user-logout'  => \App\Http\Middleware\UserLogout::class,
    'basic-auth'   => \App\Http\Middleware\BasicAuth::class,
    'jwt-auth'     => \App\Http\Middleware\JWTAuth::class,
    'cache'        => \App\Http\Middleware\Cache::class,
    'api'          => \App\Http\Middleware\Api::class,
]);

// DEFINE O MAPEAMENTO DE MIDDLEWARES EM TODAS AS ROTAS
MiddlewareQueue::setDefault([
    'maintenence'
]);