<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Models\Comentario as EntityComment;

class Comment {

    /**
     * Método responsavel por retornar os dados de um comentario
     * @param \App\Http\Request $request
     * @param integer $id
     * 
     * @return array
     */
    public static function getViewComment(Request $request, int $id): array {
        // CONSULTA DO COMENTARIO
        $obComment = EntityComment::getCommentById($id);

        // VALIDA A INSTANCIA
        if (!$obComment instanceof EntityComment) {
            $request->getRouter()->redirect('/admin/comments?status');
        }
        // RETORNA OS DADOS DO COMENTARIO
        return [
            'id_comentario'  => $obComment->getId_comentario(),
            'dsc_comentario' => $obComment->getDsc_comentario(),
            'dt_comentario'  => $obComment->getDt_comentario(),
            'fk_usuario'     => $obComment->getFK_id_usuario()
        ];
    }
}
