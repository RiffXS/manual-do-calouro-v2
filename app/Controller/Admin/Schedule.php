<?php

namespace App\Controller\Admin;

use App\Http\Request;
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

    /**
     * Método responsavel por rendenizar o formulario de cadastro de aula
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    public static function getNewSchedule(Request $request): string {
        $content = View::render('admin/modules/schedules/form');

        return parent::getPanel('Cadastrar aula > MDC', $content, 'horario');
    }
}