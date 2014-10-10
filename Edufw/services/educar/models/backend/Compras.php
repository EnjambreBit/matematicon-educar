<?php

namespace Edufw\services\educar\models\backend;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo Compras
 *
 * Servicios referidos a la obtención de compras
 * 
 * @version 20130311
 * @author ajchambeaud
 */
class Compras extends RestModel {
    //CODIGOS

    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;
    const CODE_CURSO_INEXISTENTE = 2;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_CURSO_INEXISTENTE = 'Curso inexistente';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';

    /**
     * Permite obtener un listado de adjudicaciones segun parametros de busqueda
     * @method buscarPublicaciones
     * 
     * @param array $data Es el array con los datos a ser enviados
     * 
     * 
     * @return array En caso de no encontrar el sitio retorna array vacio
     */
    public function buscarAdjudicaciones($data) {
        try {
            $this->loadDataPublicaciones($data);
            $this->uri = $this->global_config['URL_BUSCAR_ADJUDICACIONES'];
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Compras/buscarPublicaciones] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }
    
    /**
     * Permite obtener un listado de compras segun parametros de busqueda
     * @method buscarPublicaciones
     * 
     * @param array $data Es el array con los datos a ser enviados
     * 
     * 
     * @return array En caso de no encontrar el sitio retorna array vacio
     */
    public function buscarPublicaciones($data) {
        try {
            $this->loadDataPublicaciones($data);
            $this->uri = $this->global_config['URL_BUSCAR_PUBLICACIONES'];
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Compras/buscarPublicaciones] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }
    
    private function loadDataPublicaciones($data){
        $this->data = array(
            "sitio_id" => ApiCommunication::$sitio_id
        );

        if(isset($data['sort_column']) && $data['sort_column'] !== false){
            $this->data['sort_column'] = $data['sort_column'];
        }

        if(isset($data['sort_mode']) && $data['sort_mode'] !== false){
            $this->data['sort_mode'] = $data['sort_mode'];
        }

        if(isset($data['limit']) && $data['limit'] !== false){
            $this->data['limit'] = $data['limit'];
        }

        if(isset($data['offset']) && $data['offset'] !== false){
            $this->data['offset'] = $data['offset'];
        }

        if(isset($data['tipo_adjudicacion_id']) && $data['tipo_adjudicacion_id'] !== false){
            $this->data['tipo_adjudicacion_id'] = $data['tipo_adjudicacion_id'];
        }

        if(isset($data['publicacion_estado_id']) && $data['publicacion_estado_id'] !== false){
            $this->data['publicacion_estado_id'] = $data['publicacion_estado_id'];
        }

        if(isset($data['publicacion_expediente_anio']) && $data['publicacion_expediente_anio'] !== false){
            $this->data['publicacion_expediente_anio'] = $data['publicacion_expediente_anio'];
        }

        if(isset($data['titulo']) && $data['titulo'] !== false){
            $this->data['titulo'] = $data['titulo'];
        }

        if(isset($data['descripcion']) && $data['descripcion'] !== false){
            $this->data['descripcion'] = $data['descripcion'];
        }

        if(isset($data['str_search']) && $data['str_search'] !== false){
            $this->data['str_search'] = $data['str_search'];
        }
    }
    
    /**
     * Permite obtener información para arbol de filtros de compras
     * @method getCatalogacionCompras
     * 
     * @return array En caso de no encontrar el sitio retorna array vacio
     */
    public function getCatalogacionCompras() {
        try {
            $this->uri = $this->global_config['URL_GET_CATALOGACION_COMPRAS'];
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Compras/getCatalogacionCompras] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }
    
    /**
     * Permite obtener el detalle de una publicacion y sus archivos asociados
     * @method getDetallePublicacion
     * 
     * @param (int) $id id de la publicacion
     * 
     * @return array En caso de no encontrar el sitio retorna array vacio
     */
    public function getDetallePublicacion($id){
        try {
            $this->uri = $this->global_config['URL_GET_DETALLE_COMPRA'];
            $this->data = array(
                'id' => $id
            );            
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Compras/getDetallePublicacion] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }
}