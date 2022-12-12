<?php

namespace App\Utils;

class Sanitize {

    /**
     * Metodo responsavel por sanitizar todos os indices de um array
     * @param  array $array
     * 
     * @return array
     */
    public static function sanitizeForm(array $array): array {
        // PERCORRER OS PARES DE CHAVE VALOR
        foreach ($array as $key => $value) {
            // REMOVE CONTRA BARRA E ESPAÇO EM BRANCO
            $value = stripslashes($value);
            $value = trim($value);

            // SOBRESCREVE O VALOR ORIGINAL
            $array[$key] = $value;
        }
        // RETORNA O ARRAY SANITIZADO
        return $array;
    }

    /**
     * Metodo responsavel por verificar injeção de HTML em um array
     * @param array $array a ser percorrido
     * 
     * @return boolean false se encontrar, true se passar
     */
    public static function validateForm(array $array): bool {
        // VARIAVEL DE CONTROLE
        $ok = false;

        // PERCOCRRE O ARRAY
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
     * Metodo responsavel por verificar se o nome de entrada esta nos parametros do site
     * @param  string $nome
     * 
     * @return boolean
     */
    public static function validateName(string $name): bool {
        // PARAMETROS REGEX
        $parameters = '/^[a-zA-Z\s]+$/';

        // VERIFICA SE A STRING POSSUI NUMEROS OU CARACTER ESPECIAIS
        if (preg_match($parameters, $name)){
            return false;
        }
        return true;
    }

    /**
     * Metodo responsavel por verificar se o email de entrada é válido
     * @param  string $email
     * 
     * @return boolean
     */
    public static function validateEmail(string $email): bool {
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
     * 
     * @return boolean
     */
    public static function validatePassword(string $password, string $confirm): bool {
        // VERIFICA SE AS SENHAS SÃO IGUAIS
        if ($password != $confirm) {
            return true;
        }
        return false;
    }

    /**
     * Método responsavel por verificar sé um telefone esta no formato requisitado
     * @param string $number
     * 
     * @return boolean
     */
    function validatePhone($number): bool {
        // PARAMETROS REGEX
        $parametros = '/^\(+[0-9]{2,3}\) [0-9]{4}-[0-9]{4}$^/';

        // VERIFICA SE E UM TELEFONE
        if (preg_match($parametros, $number)) {
            return false;
        }
        return true;
    }


    /**
     * Metodo responsavel por verificar se a senha atende os requisitos de segurança
     * @param  string $password
     * 
     * @return boolean
     */
    public static function verifyPassword(string $password): bool {
        // PARAMETROS REGEX
        $parameters = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d].\S{6,36}$/';

        // MÍNIMO DE SEIS CARACTERES, PELO MENOS UMA LETRA, UM NÚMERO E UM CARACTERE ESPECIAL
        if (preg_match($parameters, $password)) {
            return false;
        }
        return true;
    }

    /**
     * Método responsavel por verificar sé os parametros necessarios da $request existem
     * @param array $keys
     * @param array $array
     * 
     * @return boolean
     */
    public static function verifyParams(array $keys, array $array): bool {
        // VERIFICA SE TODOS OS PARAMETROS EXISTEM
        foreach ($keys as $key) {
            // VERIFICA SE A CHAVE EXISTE
            if (!array_key_exists($key, $array)) {
                return false;
            }
        }
        return true;
    }
}