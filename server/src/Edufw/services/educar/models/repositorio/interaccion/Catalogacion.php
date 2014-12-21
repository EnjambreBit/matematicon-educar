<?php
namespace Edufw\services\educar\models\repositorio\interaccion;
use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST que contiene getters sobre datos de catalogacion.
 *
 * Servicios relacionados a la obtención de información utilizada para catalogación: tipos, categorías, árboles de clasificación, etc.
 * 
 * @name Catalogacion
 * @version 20120614
 * @author pgambetta
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
    public function getTemas() {
        $this->data = array();
        $this->uri = ApiCommunication::get_api_uri('URL_GET_TEMAS');
        return $this->callRestService();
    }

    /**
     * Obtiene las categorias del tipo de recurso educativo
     * @method getTiposRecursosEducativos
     *
     * @return ApiResponse Array(Array("id" => int, "desc" => string))
     */
    public function getTiposRecursosEducativos() {
        $this->data = array();
        $this->uri = ApiCommunication::get_api_uri('URL_GET_TIP_REC_EDUCATIVOS');
        return $this->callRestService();
    }

    /**
     * Obtiene las categorias de idiomas
     * @method getIdiomas
     *
     * @return ApiResponse Array(Array("id" => int, "desc" => string))
     */
    public function getIdiomas() {
        $this->data = array();
        $this->uri = ApiCommunication::get_api_uri('URL_GET_IDIOMAS');
        return $this->callRestService();
    }

    /**
     * Obtiene las categorias de segmento etario
     * @method getSegmentosEtarios
     *
     * @return ApiResponse Array(Array("id" => int, "desc" => string))
     */
    public function getSegmentosEtarios() {
        $this->data = array();
        $this->uri = ApiCommunication::get_api_uri('URL_GET_SEG_ETARIOS');
        return $this->callRestService();
    }

    /**
     * Obtiene las categorias extracurriculares
     * @method getExtracurriculares
     *
     * @return ApiResponse Array(Array("id" => int, "desc" => string))
     */
    public function getExtracurriculares() {
        $this->data = array();
        $this->uri = ApiCommunication::get_api_uri('URL_GET_EXTRACURRICULARES');
        return $this->callRestService();
    }

    /**
     * Obtiene las categorias del tipo funcional
     * @method getTiposFuncionales
     *
     * @return ApiResponse Array(Array("id" => int, "desc" => string))
     */
    public function getTiposFuncionales() {
        $this->data = array();
        $this->uri = ApiCommunication::get_api_uri('URL_GET_TIP_FUNCIONALES');
        return $this->callRestService();
    }

    /**
     * Obtiene las categorias de modalidades
     * @method getModalidades
     *
     * @return ApiResponse Array(Array("id" => int, "desc" => string))
     */
    public function getModalidades() {
        $this->data = array();
        $this->uri = ApiCommunication::get_api_uri('URL_GET_MODALIDADES');
        return $this->callRestService();
    }

    /**
     * Obtiene los formatos disponibles
     * @method getFormatos 
     *
     * @return ApiResponse Array(Array("id" => int, "desc" => string))
     */
    public function getFormatos() {
        $this->data = array();
        $this->uri = ApiCommunication::get_api_uri('URL_GET_FORMATOS');
        return $this->callRestService();
    }

    /**
     * Obtiene las categorias de temas para canal
     * @method getTemasCanal
     *
     * @return ApiResponse Array(Array("id" => int, "desc" => string))
     */
    public function getTemasCanal() {
        $this->data = array();
        $this->uri = ApiCommunication::get_api_uri('URL_GET_TEMAS_CANAL');
        return $this->callRestService();
    }

    /**
     * Obtiene todos los hijos del tema canal especificado
     * @method getHijosTemaCanal
     *
     * @param int $tema_canal_id ID del tema canal del cual se quieren obtener los hijos
     * 
     * @return ApiResponse Array(Array("id" => int, "desc" => string))
     */
    public function getHijosTemaCanal($tema_canal_id) {
        $this->data = array(
            "temas_canal_id" => $tema_canal_id
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_HIJOS_TEMA_CANAL');
        return $this->callRestService();
    }
    
    /**
     * Obtiene un arbol geraquico con los temas a partir del ID enviado
     * @method getHijosTema
     * 
     * @param int $tema_id ID del tema (null trae todo)
     * 
     * @return ApiResponse
     */
    public function getHijosTema($tema_id = null) {
      $this->data = array(
        "tema_id"  => $tema_id
      );
      $this->uri = ApiCommunication::get_api_uri('URL_GET_HIJOS_TEMA');
      return $this->callRestService();
    }

    /**
     * Permite obtener la cantidad de recursos que existen para cada tema especificado, 
     * en un sitio determinado
     * 
     * @param array $temas IDs del tema
     * 
     * @todo Implementar método
     */
    public function getTemasCanalCantidades($temas = array()) {
      $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,          
        "temas"  => $temas
      );
      $this->uri = ApiCommunication::get_api_uri('URL_GET_TEMAS_CANAL_CANTIDADES');
      return $this->callRestService();        
    }

    /**
     * 
     * 
     */
    public function getGenerosCinematograficos() {
        $this->data = array();
        $this->uri = ApiCommunication::get_api_uri('URL_GET_GENEROS_CINEMATOGRAFICOS');
        return $this->callRestService();
    }

    /**
     * Permite obtener  todos los elementos hijos de un tema padre. 
     * Trae recursivamente todos los hijos del tema padre indicado.
     * 
     * @param int $articulo_categoria_id ID de la categoria (null trae todo)
     * 
     * @return ApiResponse
     */
    public function getHijosCategoriaArticulo($articulo_categoria_id = null) {
      $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,          
        "articulo_categoria_id"  => $articulo_categoria_id
      );
      $this->uri = ApiCommunication::get_api_uri('URL_GET_HIJOS_CATEGORIA_ARTICULO');
      return $this->callRestService();           
    }
    
    /**
     * Obtiene las categorias de audiencia
     * @method getAudiencias
     *
     * @return ApiResponse Array(Array("id" => int, "desc" => string, "padre_id" => int))
     */
    public function getAudiencias() {
        $this->data = array();
        $this->uri = ApiCommunication::get_api_uri('URL_GET_AUDIENCIAS');
        return $this->callRestService();
    }    

}
