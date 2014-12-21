<?php

namespace Edufw\services\educar\models\backend;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo Recursos
 *
 * Servicios referidos a la obtención de recursos para backend
 * 
 * @version 20121015
 * @author pgambetta
 */
class Recursos extends RestModel {
    
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;
    const CODE_RECURSO_INEXISTENTE = 2;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_RECURSO_INEXISTENTE = 'Recurso inexistente';
    
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';

    /**
     * Permite obtener los recursos de un usuario
     * 
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function getRecursos() {
        return array();
    }

}

