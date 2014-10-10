<?php
namespace Edufw\services\educar\models\repositorio\interaccion;
use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST para realizar acciones sobre votaciones.
 *
 * Servicios dedicados a la votación de recursos por parte de los usuarios.
 * "Requieren autenticación previa"
 * 
 * @name Votacion
 * @version 20120614
 * @author pgambetta
 */
class Votacion extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_USUARIO_INEXISTENTE = 1;
    const CODE_RECURSO_INEXISTENTE = 3;
    const CODE_SITIO_INEXISTENTE = 2;
    const CODE_RECURSO_VOTADO = 5;

    const PUNTAJE_MENOR_PERMITIDO = 0;
    const PUNTAJE_MAYOR_PERMITIDO = 10;
    const PUNTAJE_ME_GUSTA = 1;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';
    const MSG_RECURSO_INEXISTENTE = 'Recurso inexistente';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_RECURSO_VOTADO = 'El usuario ya ha votado este recurso';

    public function votar() {
        
    }

    /**
     * Realiza una votación en un recurso
     * @method meGusta
     * 
     * @param string $usr_id Es el ID del usuario
     * @param string $login_token Es el token de login
     * @param integer $rec_id Es el ID del recurso
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function meGusta($usr_id, $login_token, $rec_id, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "rec_id" => $rec_id
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_MEGUSTA');
        return $this->callRestService();
    }

    /**
     * Realiza un chequeo de la votación para ver si puede o no hacerlo
     * @method puedeVotar
     * 
     * @param string $usr_id Es el ID del usuario
     * @param integer $rec_id Es el ID del recurso
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function puedeVotar($usr_id, $recursos, $login_token, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "recursos" => $recursos
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_CHEK_VOTAR');
        return $this->callRestService();
    }

}
