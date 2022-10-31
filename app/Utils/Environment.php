<?php

namespace App\Utils;

class Environment {

	/**
	 * Método responsável por carregar as variáveis de ambiente do projeto
	 * @param  string $dir Caminho absoluto da pasta onde encontra-se o arquivo .env
	 */
	public static function load($dir) {
		//VERIFICA SE O ARQUIVO .ENV EXISTE
		if (!file_exists($dir . '/.env')) {
			return false;
		}
		// OBTEM AS VARIÁVEIS DE AMBIENTE
		$lines = file($dir.'/.env');

		// REMOVE AS LINHAS EM BRANCO
		foreach ($lines as $key => $value) {
			// VERIFICA O TAMANHO DA LINHA ('\n')
			if (strlen($value) == 2) {
				unset($lines[$key]);
			}
		}
		// DEFINE AS VARIAVEIS DE AMBIENTE
		foreach ($lines as $line) {
			putenv(trim($line));
		}	
	}
}