<?php
namespace Edufw\services\educar\models\repositorio\interaccion;
use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST para realizar acciones sobre videos.
 *
 * Servicios relacionados a la obtención y descarga de videos.
 * 
 * @name Video
 * @version 20120614
 * @author pgambetta
 */
class Video extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_RECURSO_INEXISTENTE = 1;
    const CODE_SITIO_INEXISTENTE = 2;
    const CODE_VIDEO_NODISPONIBLE = 3;
    const CODE_RECURSO_NODISPONIBLE = 4;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_RECURSO_INEXISTENTE = 'Recurso inexistente';
    const MSG_RECURSO_NODISPONIBLE = 'Recurso no disponible';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_VIDEO_DISPONIBLE = 'Video disponible para descarga';
    const MSG_VIDEO_NODISPONIBLE = 'Video no disponible';

    /**
     * Obtiene la url de descarga de un video
     * @method getVideoOffline
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param string $file_id Es el ID del video
     * @param integer $rec_id Es el ID del recurso
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getVideoOffline($usr_id, $login_token, $file_id, $rec_id, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "rec_id" => $rec_id,
            "file_id" => $file_id,
            "usr_id" => $usr_id,
            "login_token" => $login_token
        );
        $this->uri = ApiCommunication::get_api_uri('URL_DESCARGAR_VIDEO');
        return $this->callRestService();
    }

}
