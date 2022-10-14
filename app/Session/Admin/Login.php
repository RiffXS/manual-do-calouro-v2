<?php

namespace App\Session\Admin;

class Login {

    /**
     * Methodo responsavel por iniciar a sessão
     */
    private static function init() {
        // VERIFICA SE A SESSÃO NÃO ESTA ATIVA
        if (session_status() != PHP_SESSION_ACTIVE) {
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
        $_SESSION['admin']['usuario'] = [
            'id'    => $obUser->id,
            'nome'  => $obUser->nome,
            'email' => $obUser->email
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
        return isset($_SESSION['admin']['usuario']['id']);
    }

    /**
     * Methodo responsavel por executar o logout do usuario
     * @return boolean
     */
    public static function logout() {
        // INICIA A SESSÃO
        self::init();

        // DESLOGA O USUARIO
        unset($_SESSION['admin']['usuario']);

        // SUCESSO
        return true;
    }
}