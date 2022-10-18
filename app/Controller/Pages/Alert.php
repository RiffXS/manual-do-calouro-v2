<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Alert {

    /**
     * Methodo responsavel por retornar uma mensagem de sucesso
     * @param  string $message
     * @return string
     */
    public static function getSucess($message) {
        return View::render('admin/alert/status', [
            'tipo'     => 'success',
            'mensagem' => $message
        ]);
    }

    /**
     * Methodo responsavel por retornar uma mensagem de erro
     * @param  string $message
     * @return string
     */
    public static function getError($message) {
        return View::render('admin/alert/status', [
            'tipo'     => 'danger',
            'mensagem' => $message
        ]);
    }
}