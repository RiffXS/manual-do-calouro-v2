<?php

namespace App\Utils;

use App\Models\User as EntityUser; 

class Session {

    /**
     * Método responsável por iniciar a sessão
     */
    private static function init() {
        // VERIFICA SE A SESSÃO NÃO ESTÁ ATIVA
        if (session_status() != PHP_SESSION_ACTIVE) {
            // INICIA A SESSÃO
            session_start();
        }
    }

    /**
     * Método responsavel por retornar um objeto usuario
     * @return EntityUser
     */
    public static function getSessionUser() {
        // RETORNA OS DADOS DE UM USUÁRIO NA SESSÃO
        return EntityUser::getUserById(self::getSessionId());
    }


    /**
     * Método responsével por criar o login do usuário
     * @param \App\Models\User @obUser
     * @param boolean
     */
    public static function login($obUser) {
        // INICIA A SESSÃO
        self::init();

        // DEFINE A SESSÃO DO ADMIN
        $_SESSION['usuario'] = [
            'id_usuario' => $obUser->getUserId(),
            'lv_acesso'  => $obUser->getAcess()
        ];
        // SUCESSO
        return true;
    }

    /**
     * Método responsável por devolver o ID do usuário logado
     * @return integer
     */
    public static function getSessionId() {
        // INICIA A SESSÃO
        self::init();

        // RETORNA O ID DO USUÁRIO NA SESSÃO
        return $_SESSION['usuario']['id_usuario'];
    }

    /**
     * Método responsavel por devolve o LV de acesso do usuario
     * @return integer
     */
    public static function getSessionLv() {
        // INICIA A SESSÃO
        self::init();

        // RETORNA O ID DO USUÁRIO NA SESSÃO
        return $_SESSION['usuario']['lv_acesso'];
    }

    /**
     * Método responsável por verificar se o usuário está logado
     * @return boolean
     */
    public static function isLogged() {
        // INICIA A SESSÃO
        self::init();

        // RETORNA A VERIFICAÇÃO
        return isset($_SESSION['usuario']['id_usuario']);
    }

    /**
     * Método responsável por executar o logout do usuário
     * @return boolean
     */
    public static function logout() {
        // INICIA A SESSÃO
        self::init();

        // DESLOGA O USUÁRIO
        unset($_SESSION['usuario']);

        // SUCESSO
        return true;
    }
}