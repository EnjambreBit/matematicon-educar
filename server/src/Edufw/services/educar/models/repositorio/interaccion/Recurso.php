<?php

namespace Edufw\services\educar\models\repositorio\interaccion;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST para realizar acciones sobre recursos.
 *
 * Servicios relacionados a la obtención de información de un recurso, en base a su ID.
 * 
 * @name Recurso
 * @version 20120614
 * @author pgambetta
 */
class Recurso extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_RECURSO_INEXISTENTE = 1;
    const CODE_SITIO_INEXISTENTE = 2;
    const CODE_RECURSO_NODISPONIBLE = 3;
    const CODE_RECURSO_OVERFLOW = 3;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_RECURSO_INEXISTENTE = 'Recurso inexistente';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_RECURSO_NODISPONIBLE = 'Recurso no disponible';
    const MSG_RECURSO_DISPONIBLE = 'Recurso disponible para descarga';
    const MSG_RECURSO_OVERFLOW = 'Se supera la cantidad máxima de recursos a pedir';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';
    const MSG_USER_UNAUTHENTICATED = 'Usuario no autenticado';
    const MAX_RECURSOS = 100;

    /**
     * Obtiene un recurso "full" dado un ID
     * @method getRecursoFull
     * 
     * @param integer $rec_id Es el ID del recurso
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getRecursoFull($rec_id, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "rec_id" => $rec_id
        );
        $this->uri = ApiCommunication::get_api_uri('URI_GET_RECURSO_FULL');
        return $this->callRestService();
    }

     /**
     * Obtiene un listado de recurso dado un array de ID'S
     * @method getRecursosListLite
     *
     * @param array $recursos Es el ID del recurso
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getRecursosListLite($recursos, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "recursos" => $recursos
        );
        $this->uri = ApiCommunication::get_api_uri('URL_LISTADO_RECURSOS');
        return $this->callRestService();
    }

    /**
     * Obtiene un recurso "lite" dado un ID
     * @method getRecursoLite
     * 
     * @param integer $rec_id Es el ID del recurso
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getRecursoLite($rec_id, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "rec_id" => $rec_id
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_RECURSO_LITE');
        return $this->callRestService();
    }

    /**
     * Obtiene los datos para descargar un recurso
     * @method getRecursoOffline
     * 
     * @param integer $rec_id Es el ID del recurso
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getRecursoOffline($rec_id, $usr_id, $login_token, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "rec_id" => $rec_id,
            "usr_id" => $usr_id,
            "login_token" => $login_token
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_RECURSO_OFFLINE');
        return $this->callRestService();
    }

    /**
     * 
     * @todo Implementar método
     */
    public function getRelacionesRecursosList() {
        
    }

}
