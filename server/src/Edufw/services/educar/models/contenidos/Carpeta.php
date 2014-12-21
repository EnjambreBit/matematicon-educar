<?php

namespace Edufw\services\educar\models\contenidos;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST que contiene getters sobre datos de catalogación.
 *
 * Servicios relacionados a la obtención de información utilizada para catalogación.
 * 
 * @name Catalogacion
 * @version 20130408
 * @author lmoya
 */
class Carpeta extends RestModel {

    /**
     * Obtiene un listado de temas
     * @method getTemas
     *
     * @return ApiResponse
     */
    public function agregarRecurso($usr_id, $login_token, $carpeta_id, $rec_id) {
        $this->data = array(
            "sitio_id" => ApiCommunication::$sitio_id,
            "usr_id" => $usr_id,
            "login_token" => $login_token,        
            "rec_id" => $rec_id,
            "carpeta_id" => $carpeta_id
        );
        $this->uri = $this->global_config['URL_USER_CONTENT_AGREGAR_RECURSO_CARPETA'];
        return $this->callRestService();
    }  

}
