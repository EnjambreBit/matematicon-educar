<?php
namespace Edufw\services\educar\models\repositorio\interaccion;
use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo HistorialUsuariosController
 *
 * Servicios relacionados al historial de acciones realizadas por un usuario, como descarga de recursos offline, reproducciones, etc.
 * 
 * @version 20120614
 * @author pgambetta
 * 
 * @property String $ci
 */
class HistorialUsuarios extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;
    const CODE_RECURSO_INEXISTENTE = 2;

    //codigos de error adicionales a partir del 3
    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_RECURSO_INEXISTENTE = 'Recurso inexistente';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';

    /**
     * Permite obtener un listado de ID de recursos descargados por un usuario, para un sitio determinado
     * 
     * @todo Implementar método
     */
    public function getRecursosDescargados() {
        
    }

    /**
     *  Indica si un conjunto IDs de recursos ya fue descargados por parte de un usuario, 
     *  para un sitio determinado. 
     * 
     * @todo Implementar método
     */
    public function checkRecursosDescargados() {
        
    }

}

