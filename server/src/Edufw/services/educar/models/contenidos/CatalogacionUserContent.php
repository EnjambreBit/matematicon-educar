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
 * @version 20121212
 * @author fbasile
 */
class CatalogacionUserContent extends RestModel {

    /**
     * Obtiene un listado de categorías
     * 
     * @param integer $categoria_sitio_id Es el ID de la categoría deseada
     * @method getCategorias
     *
     * @return ApiResponse
     */
    public function getCategorias($categoria_sitio_id = false) {
        $this->data = array('sitio_id' => ApiCommunication::$sitio_id);
        if($categoria_sitio_id !== false){
            $this->data['categoria_id'] = $categoria_sitio_id;
        }
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
