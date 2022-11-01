<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Rod extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina rod
     * @param \App\Http\Request $request
     * @return string 
     */
    public static function getRod(): string {
        // VIEW DA HOME
        $content = View::render('pages/rod');

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('ROD', $content, 'rod');
    }
}