<?php
require __DIR__.'/../vendor/autoload.php';

use App\Utils\View;
use App\Utils\Environment;
use App\Utils\Database;
use App\Http\Middleware\Queue as MiddlewareQueue;

// CARREGA VARIAVEIS DE AMBIENTE
Environment::load(__DIR__.'/../');

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

// DEFINE O MAPEAMENTO DE MIDDLEWARES
MiddlewareQueue::setMap([
    'maintenence'          => \App\Http\Middleware\Maintenence::class,
    'require-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
    'require-admin-login'  => \App\Http\Middleware\RequireAdminLogin::class,
    'api'                  => \App\Http\Middleware\Api::class,
    'user-basic-auth'      => \App\Http\Middleware\UserBasicAuth::class,
    'cache'                => \App\Http\Middleware\Cache::class,
]);

// DEFINE O MAPEAMENTO DE MIDDLEWARES EM TODAS AS ROTAS
MiddlewareQueue::setDefault([
    'maintenence'
]);