<?php

namespace Edufw\services\educar\models\contenidos\interaccion;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST que contiene getters sobre datos de catalogación.
 *
 * Servicios relacionados a la obtención de información utilizada para catalogación.
 * 
 * @name Catalogacion
 * @version 20121212
 * @author fbasile
 */
class Catalogacion extends RestModel {
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
     * Obtiene un listado de temas
     * @method getTemas
     *
     * @return ApiResponse
     */
    public function getCategorias() {
        $this->data = array(
            "sitio_id" => ApiCommunication::$sitio_id
        );
        $this->uri = $this->global_config['URL_USER_CONTENT_OBTENER_CATEGORIAS'];
        return $this->callRestService();
    }

    /**
     * Asigna un Contenido a una categoría de un sitio específico
     * @method addContenidoACategoria
     *
     * @return ApiResponse
     */
    public function addContenidoACategoria($usr_id, $login_token, $categoria_id, $contenido_id) {
        $this->data = array(
            "sitio_id" => ApiCommunication::$sitio_id,
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "categoria_id" => $categoria_id,
            "contenido_id" => $contenido_id
        );
        $this->uri = $this->global_config['URL_USER_CONTENT_AGREGAR_A_CATEGORIA'];
        return $this->callRestService();
    }
    
    /**
     * Asigna un Contenido a una categoría de un sitio específico
     * @method addContenidoACategoria
     *
     * @return ApiResponse
     */
    public function getCategoriaByAlias($categoria_sitio_alias) {
        $this->data = array(
            "sitio_id" => ApiCommunication::$sitio_id,
            "categoria_sitio_alias" => $categoria_sitio_alias
        );
        $this->uri = $this->global_config['URL_USER_CONTENT_GET_CATEGORIA_BY_ALIAS'];
        return $this->callRestService();
    }
    
    
     

}
