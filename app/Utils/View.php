<?php

namespace App\Utils;

class View {

    /**
     * Variaveis  padrãoes da View
     * @var array
     */
    private static $vars = [];

    /**
     * Methodo responsavel por definir os dados inicias da classe
     * @param array $vars
     * 
     * @return void
     * 
     * @author @SimpleR1ick
     */
    public static function init(array $vars = []): void {
        self::$vars = $vars;
    }

    /**
     * Methodo responsavel por retornar o conteudo de uma view
     * @param  string $view
     * 
     * @return string
     * 
     * @author @SimpleR1ick
     */
    private static function getContentView($view): string {
        // OBTEM O CAMINHO DO ARQUIVO
        $file = __DIR__.'/../../resources/view/'.$view.'.html';

        // RETORNA O CONTEUDO DO ARQUIVO SE ELE EXISTIR
        if (!file_exists($file)) {
            die("O arquivo: $file não existe!");
        }
        return file_get_contents($file);
    }

    /**
     * Método responsavel por retornar o conteudo rendenizado de uma view
     * @param  string $view
     * @param  array  $vars (strings/numeric)
     * 
     * @return string 
     * 
     * @author @SimpleR1ick
     */
    public static function render(string $view, array $vars = []): string {
        // CONTEUDO DA VIEW 
        $contentView = self::getContentView($view);

        // MERGE DE VARIAVEIS DA VIEW
        $vars = array_merge(self::$vars, $vars);

        // CHAVES DO ARRAY DE VARIAVEIS
        $keys = array_keys($vars);
        $keys = array_map(function($item) {
            return '{{'.$item.'}}';
        },$keys);

        // RETORNA O CONTEUDO RENDENIZADO
        return str_replace($keys, array_values($vars), $contentView);
    }
}