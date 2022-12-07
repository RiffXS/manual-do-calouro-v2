<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Models\Contato as EntityContact;

class Contact {
    
    /**
     * Método responsável por consultar os dados de um contato
     * @param \App\Http\Request $request
     * @param int $id
     * 
     * @return array
     */
    public static function getDataContact(Request $request, int $id): array {
        // ARRAY COM AS INFORMAÇÕES DO CONTATO
        return [
           'dados' => EntityContact::getContactByFk($id)
        ];

    }
}