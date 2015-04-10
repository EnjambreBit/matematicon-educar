<?php
namespace Edufw\services\educar\models\repositorio\interaccion;
use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST para realizar acciones sobre comentarios.
 *
 * Servicios que permiten a un usuario realizar un comentario sobre un recurso educativo. Los comentarios son propios de cada sitio.
 * 
 * @name Comentarios
 * @version 20120614
 * @author pgambetta
 */
class Comentarios extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_USUARIO_INEXISTENTE = 1;
    const CODE_SITIO_INEXISTENTE = 2;
    const CODE_RECURSO_INEXISTENTE = 3;
    const CODE_COMENTARIO_INVALIDO = 4;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';
    const MSG_USUARIO_INEXISTENTE = 'Usuario inexistente';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_RECURSO_INEXISTENTE = 'Recurso inexistente';
    const MSG_COMENTARIO_INVALIDO = 'Comentario invalido';

    /**
     * Crea un nuevo comentario en un recurso dado
     * @method comentarRecurso
     * 
     * @param integer $rec_id Es el ID del recurso
     * @param string $user Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param string $texto Es el texto del comentario
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function comentarRecurso($rec_id, $user, $login_token, $texto, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "rec_id" => $rec_id,
            "usr_id" => $user,
            "login_token" => $login_token,
            "comentario" => $texto
        );
        $this->uri = ApiCommunication::get_api_uri('URI_SET_COMENTARIOS');
        return $this->callRestService();
    }

    /**
     * Obtiene los comentarios de un recurso dado un ID
     * @method getComentariosDeRecurso
     * 
     * @param integer $rec_id Es el ID del recurso
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getComentariosDeRecurso($rec_id, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "rec_id" => $rec_id
        );
        $this->uri = ApiCommunication::get_api_uri('URI_GET_COMENTARIOS');
        return $this->callRestService();
    }

    
    /**
     * Obtiene los comentarios de un recurso dado un ID
     * @method getComentarios
     * 
     * @param integer $rec_id Es el ID del recurso
     * @param integer $sitio_id [opcional]
     * 
     * @return array
     */
    public function getComentariosPagindos($rec_id, $limit, $offset, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "rec_id" => $rec_id,
            "limit" => $limit,
            "offset" => $offset
        );
        $this->uri = ApiCommunication::get_api_uri('URI_GET_COMENTARIOS_PAGINADOS');
        return $this->callRestService();
    }
    
    /**
     * Crea un nuevo comentario en un recurso dado
     * @method setComentarios
     * 
     * @param integer $rec_id Es el ID del recurso
     * @param string $user Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param string $texto Es el texto del comentario
     * @param integer $sitio_id [opcional]
     * 
     * @return array
     */
    public function setComentarios($rec_id, $user, $login_token, $texto, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "rec_id" => $rec_id,
            "usr_id" => $user,
            "login_token" => $login_token,
            "comentario" => $texto
        );
        $this->uri = ApiCommunication::get_api_uri('URI_SET_COMENTARIOS');
        return $this->callRestService();
    }
    
    /**
     * 
     * @todo Implementar método
     */
    public function getUltimosComentariosDeUsuario() {
        
    }

    /**
     * 
     * @todo Implementar método
     */
    public function getComentariosList() {
        
    }

}
