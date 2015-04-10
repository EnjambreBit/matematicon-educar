<?php

namespace Edufw\services\educar\models\repositorio;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo TelevisivoController
 *
 * Servicios exclusivos para los tipos funcionales “Emisión” y “Capítulo”.
 * 
 * @version 20120614
 * @author pgambetta
 */
class Televisivo extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;
    const CODE_RECURSO_INEXISTENTE = 2;
    const CODE_TEMPORADA_INEXISTENTE = 3;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_RECURSO_INEXISTENTE = 'Recurso inexistente';
    const MSG_TEMPORADA_INEXISTENTE = 'Temporada inexistente';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';

    //TIPOS FUNCIONALES
    const EMISION = 11;
    const CAPITULO = 12;

    /**
     * 
     * @todo Implementar método
     */
    public function getTemporada() {
        
    }

    /**
     * Obtiene la cantidad de recursos que existen por tema canal y por tipo de emisión, para un sitio determinado. 
     * Devuelve las cantidades para los IDs de tema canal especificados en el array “temas”.  
     * @method getTemasCanalCantidades
     *
     * @param array $temas listado de id's de temas_canal
     *
     * @return ApiResponse
     */
    public function getTemasCanalCantidades($temas, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "temas" => $temas
        );
        $this->uri = $this->global_config['URL_TELEVISIVO_GET_TEMAS_CANAL_CANTIDADES'];
        return $this->callRestService();
    }

    /**
     * Obtiene un listado de recursos televisivos dado un array de ID'S
     * @method getRecursosTelevisivosListLite
     *
     * @param array $recursos Es el ID del recurso
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getRecursosTelevisivosListLite($recursos, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "recursos" => $recursos
        );
        $this->uri = $this->global_config['URL_TELEVISIVO_LISTADO_RECURSOS'];
        return $this->callRestService();
    }

}

