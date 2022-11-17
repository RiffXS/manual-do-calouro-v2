<?php

namespace App\Controller\Admin;

use App\Utils\View;

class Schedule extends Page {

    /**
     * Método responsavel por rendenizar a view de horario no painel
     * @return string
     */
    public static function getSchedule(): string {
        // CONTEUDO DA HOME
        $content = View::render('admin/modules/schedules/index');

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Horario > MDC', $content, 'horario');
    }
}