<?php

namespace App\Utils\Tools;

class Sanitize {

    /**
     * Metodo responsavel por verificar injeção de HTML em um array
     * @param array $array a ser percorrido
     * @return bool false se encontrar, true se passar
     */
    public static function validateForm($array) {
        // Iniciando a variavel de controle
        $ok = false;

        // Percorre cada indice do array
        foreach ($array as $string) {
            // Utiliza a esta função dectar caracters (<>, "', &)
            $f_string = htmlspecialchars($string, ENT_QUOTES);

            // Verifica se a string sanitizada e diferente da original
            if ($f_string != $string) {
                $ok = true;
                break;
            }
        }
        // Retorno da função
        return $ok;
    }

    /**
     * Metodo responsavel por sanitizar todos os indices de um array
     * @param array $array a ser percorrido
     */
    public static function sanitizeForm($array) {
        // Percorre cada indice do array
        foreach ($array as $key => $value) {
            // Remover as tags HTML, contrabarras e espaços em branco de uma.
            $value = filter_var($value, FILTER_SANITIZE_STRING);
            $value = stripslashes($value);
            $value = trim($value);

            // Sobreescreve o valor original
            $array[$key] = $value;
        }
        // Retornando o array sanitizado
        return $array;
    }
}
