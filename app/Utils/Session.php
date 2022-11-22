<?php

namespace App\Utils;

use App\Models\Usuario as EntityUser; 

class Session {

    /**
     * Método responsável por iniciar a sessão
     * @return void
     */
    private static function init(): void {
        // VERIFICA SE A SESSÃO NÃO ESTÁ ATIVA
        if (session_status() != PHP_SESSION_ACTIVE) {
            // INICIA A SESSÃO
            session_start();
        }
    }

    /**
     * Método responsavel por retornar um objeto usuario
     * @return \App\Models\Usuario
     */
    public static function getUser(): object {
        // RETORNA OS DADOS DE UM USUÁRIO NA SESSÃO
        return EntityUser::getUserById(self::getId());
    }

    /**
     * Método responsével por criar o login do usuário
     * @param \App\Models\Usuario @obUser
     * 
     * @return boolean
     */
    public static function login(EntityUser $obUser): bool {
        // INICIA A SESSÃO
        self::init();

        // DEFINE A SESSÃO DO ADMIN
        $_SESSION['usuario'] = [
            'id_usuario' => $obUser->getId_usuario(),
            'lv_acesso'  => $obUser->getFk_acesso()
        ];
        // SUCESSO
        return true;
    }

    /**
     * Método responsável por devolver o ID do usuário logado
     * @return integer
     */
    public static function getId(): int {
        // INICIA A SESSÃO
        self::init();

        // RETORNA O ID DO USUÁRIO NA SESSÃO
        return $_SESSION['usuario']['id_usuario'];
    }

    /**
     * Método responsavel por devolve o LV de acesso do usuario
     * @return integer
     */
    public static function getLv(): int {
        // INICIA A SESSÃO
        self::init();

        // RETORNA O ID DO USUÁRIO NA SESSÃO
        return $_SESSION['usuario']['lv_acesso'];
    }

    /**
     * Método responsável por verificar se o usuário está logado
     * @return boolean
     */
    public static function isLogged(): bool {
        // INICIA A SESSÃO
        self::init();

        // RETORNA A VERIFICAÇÃO
        return isset($_SESSION['usuario']['id_usuario']);
    }

    /**
     * Método responsável por executar o logout do usuário
     * @return boolean
     */
    public static function logout(): bool {
        // INICIA A SESSÃO
        self::init();

        // DESLOGA O USUÁRIO
        unset($_SESSION['usuario']);

        // SUCESSO
        return true;
    }
}