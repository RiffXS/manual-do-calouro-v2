<?php

namespace App\Utils;

class Session {

    /**
     * Methodo responsavel por iniciar a sessão
     */
    private static function init() {
        // VERIFICA SE A SESSÃO NÃO ESTA ATIVA
        if (session_status() != PHP_SESSION_ACTIVE) {
            // INICIA A SESSÃO
            session_start();
        }
    }

    /**
     * Methodo responsavel por criar o login do usuario
     * @param \App\Models\Entity\User @obUser
     * @param boolean
     */
    public static function login($obUser) {
        // INICIA A SESSÃO
        self::init();

        // DEFINE A SESSÃO DO ADMIN
        $_SESSION['usuario'] = [
            'id_usuario' => $obUser->id_usuario
        ];
        // SUCESSO
        return true;
    }

    /**
     * Methodo responsavel por verificar se o usuario esta logado
     * @return boolean
     */
    public static function isLogged() {
        // INICIA A SESSÃO
        self::init();

        // RETORNA A VERIFICAÇÃO
        return isset($_SESSION['usuario']['id_usuario']);
    }

    /**
     * Methodo responsavel por executar o logout do usuario
     * @return boolean
     */
    public static function logout() {
        // INICIA A SESSÃO
        self::init();

        // DESLOGA O USUARIO
        unset($_SESSION['usuario']);

        // SUCESSO
        return true;
    }
}