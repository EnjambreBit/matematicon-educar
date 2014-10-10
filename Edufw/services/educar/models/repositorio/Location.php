<?php

namespace Edufw\services\educar\models\repositorio;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Locacion de puntos mediante GIS
 *
 *
 *
 * @author lmoya
 */
class Location extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';

    /**
     * Obtiene los detalles de una posicion a partir de sus latitud y longitud
     *
     * @param float $latitud Latitud de la posicion
     * @param float $longitud Longitud de la posicion
     * @return ApiResponse
     */
    public function position_details($latitud, $longitud) {
        $this->data = array(
            "latitud" => $latitud,
            "longitud" => $longitud
        );
        $this->uri = $this->global_config['URL_LOCATION_POSITIONDETAILS'];
        return $this->callRestService();
    }

    public function listlite($gis_id) {
        $this->data = array(
            "gis_id" => $gis_id,
        );
        $this->uri = $this->global_config['URL_LOCATION_LISTLITE'];
        return $this->callRestService();
    }

}

