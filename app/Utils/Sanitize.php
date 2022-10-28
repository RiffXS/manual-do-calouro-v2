<?php

namespace App\Utils;

class Sanitize {

    /**
     * Metodo responsavel por verificar injeção de HTML em um array
     * @param array $array a ser percorrido
     * @return bool false se encontrar, true se passar
     */
    public static function validateForm($array) {
        // VARIAVEL DE CONTROLE
        $ok = false;

        foreach ($array as $string) {
            // TRANSFORMA OS SEGUINTES CARACTERS (<>, "', &)
            $f_string = htmlspecialchars($string, ENT_QUOTES);

            // VERIFICA SE A STRING SANITIZADA E IGUAL A ORIGINAL
            if ($f_string != $string) {
                $ok = true;
                break;
            }
        }
        // RETORNA O CONTROLE
        return $ok;
    }

    /**
     * Metodo responsavel por sanitizar todos os indices de um array
     * @param  array $array
     * @return array
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
        // RETORNA O ARRAY SANITIZADO
        return $array;
    }
}
