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

    /**
     * Metodo responsavel por verificar se o nome de entrada esta nos parametros do site
     * @param  string $nome
     * @return boolean
     */
    public static function validateName($nome) {
        $parameters = '/^[a-zA-Z\s]+$/';

        // VERIFICA SE A STRING POSSUI NUMEROS OU CARACTER ESPECIAIS
        if (preg_match($parameters, $nome)){
            return false;
        }
        return true;
    }

    /**
     * Metodo responsavel por verificar se o email de entrada é válido
     * @param  string $email
     * @return boolean
     */
    public static function validateEmail($email) {
        // SANITIZA O EMAIL
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // VALIDA O FORMATO DO EMAIL
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    /**
     * Metodo responsavel por verificar se as senhas conicidem
     * @param  string $password
     * @param  string $confirm
     * @return boolean
     */
    public static function validatePassword($password, $confirm) {
        // VERIFICA SE AS SENHAS SÃO IGUAIS
        if ($password != $confirm) {
            return true;
        }
        return false;
    }


    /**
     * Metodo responsavel por verificar se a senha atende os requisitos de segurança
     * @param  string $password
     * @return boolean
     */
    public static function verifyPassword($password) {
        $parameters = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d].\S{6,36}$/';

        // MÍNIMO DE SEIS CARACTERES, PELO MENOS UMA LETRA, UM NÚMERO E UM CARACTERE ESPECIAL
        if (preg_match($parameters, $password)) {
            return false;
        }
        return true;
    }

    
    /**
     * Metodo responsavel por verificar se o usuario esta ativo
     * @return boolean
     */
    public function verifyIsActive($code) {
        // VERIFICA SE O USUARIO ESTA ATIVO
        if ($code == 0) {
            return false;
        }
        return true;
    }

}
