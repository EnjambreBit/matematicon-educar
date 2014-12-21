<?php

namespace Edufw\services\educar\models\repositorio;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST para busquedas sobre recursos 
 * educativos.
 *
 * Listado de servicios relacionados al motor de búsqueda del Repositorio.
 * 
 * @name RecursoSearch
 * @version 20120614
 * @author pgambetta
 */
class RecursoSearch extends RestModel {

    /**
     * Permite realizar una búsqueda simple al motor de búsqueda del Repositorio.
     * 
     * @method simpleSearch
     *
     * @param Array $values Array de filtros de busqueda. Indices posibles:
     * search_string, limit, offset, calculateAmounts, sort_column, sort_mode,
     * fields (Array de campos a solicitar. Valores:
     * NULL => Trae todos los campos.
     *"rec_id", "rec_titulo", "rec_descripcion", "rec_small_icon_image_id", "rec_medium_icon_image_id", "etiquetas",
     *"rec_tipo_funcional_id", "tip_rec_educativo_id", "rec_puntaje", "rec_cant_votos", "rec_cant_accesos", "rec_cant_descargas_offline", 
     *"rec_cant_portafolios", "rec_cant_etiquetas_usuarios", "rec_cant_comentarios", "rec_cant_reproduccion_video", "rec_cant_descarga_video", "rec_fecha", "rec_version")
     * 
     * @return ApiResponse Array de la forma array("codigo" => "" , "mensaje" => "",	"resultLength" => , "result" => array([...]) [...])
     */
    public function simpleSearch($values)
    {
        $data = array();        
        $data["sitio_id"] = isset($values["sitio_id"]) ? $values["sitio_id"] : ApiCommunication::$sitio_id;
        $data["search_string"] = isset($values["search_string"]) ? $values["search_string"]: null ;
        $data["limit"] = isset($values["limit"]) ? $values["limit"]: null ;
        $data["offset"] = isset($values["offset"]) ? $values["offset"]: null ;        
        $data["fields"] = isset($values["fields"]) ? $values["fields"]: array
        (
          "rec_id", "rec_titulo", "rec_descripcion", "rec_small_icon_image_id", "rec_medium_icon_image_id", "etiquetas",
          "rec_tipo_funcional_id", "tip_rec_educativo_id", "rec_puntaje", "rec_cant_votos", "rec_cant_accesos", "rec_cant_descargas_offline", 
          "rec_cant_portafolios", "rec_cant_etiquetas_usuarios", "rec_cant_comentarios", "rec_cant_reproduccion_video", "rec_cant_descarga_video", "rec_fecha", "rec_version"
        );
        $data["calculateAmounts"] = isset($values["calculateAmounts"]) ? $values["calculateAmounts"]: true;
        $data["sort_column"] = isset($values["sort_column"]) ? $values["sort_column"]: null;
        $data["sort_mode"] = isset($values["sort_mode"]) ? $values["sort_mode"]: "ASC";
        
        $this->data = $data;
        $this->uri =  ApiCommunication::get_api_uri('URL_RECURSO_BUSQUEDA_SIMPLE');
        
        return $this->callRestService();
    }

    /**
     * Permite realizar una búsqueda avanzada al motor de búsqueda del Repositorio.
     * Para cada indice indicado del array $values se puede especificar un string de busqueda.
     * Los valores posibles de “limit” son 1, 3, 5, 7, 10, 15, 20, 25 y 30.
     * El campo “calculateAmounts” en “true” indica que se deben calcular las cantidades de los filtros facetados.
     * El campo "sort_column" puede tener los valores rec_fecha, ranking, alfabetico.
     * La columna “sort_mode” indica el orden en el cuál ordenar la columna indicada en “sort_column”.
     * 
     * @method advanceSearch
     *
     * @param Array $values Array de filtros de busqueda. Indices posibles:
     * rec_titulo, rec_descripcion, autor_descripcion, fecha_desde, fecha_hasta, fecha_exacta, rec_formato_id, 
     * tema_id, modalidad_id, idioma_id, rec_tipo_funcional_id, limit, offset, calculateAmounts, sort_column, 
     * sort_mode, fields (Array de campos a solicitar. Valores:
     * NULL => Trae todos los campos.
     *"rec_id", "rec_titulo", "rec_descripcion", "rec_small_icon_image_id", "rec_medium_icon_image_id", "etiquetas",
     *"rec_tipo_funcional_id", "tip_rec_educativo_id", "rec_puntaje", "rec_cant_votos", "rec_cant_accesos", "rec_cant_descargas_offline", 
     *"rec_cant_portafolios", "rec_cant_etiquetas_usuarios", "rec_cant_comentarios", "rec_cant_reproduccion_video", "rec_cant_descarga_video", "rec_fecha", "rec_version")
     * 
     * @example
     * $this->searchAdvance(array("rec_titulo" => "San Martin"), array("rec_id", "rec_descripcion") )
     * 
     * 
     * @return ApiResponse Array de la forma array("codigo" => "" , "mensaje" => "",	"resultLength" => , "result" => array([...]) [...])
     */
    public function advanceSearch($values)
    {
        $data = array();
        $data["sitio_id"] = isset($values["sitio_id"]) ? $values["sitio_id"] : ApiCommunication::$sitio_id;
        $data["rec_titulo"] = isset($values["rec_titulo"]) ? $values["rec_titulo"]: null ;
        $data["rec_descripcion"] = isset($values["rec_descripcion"]) ? $values["rec_descripcion"]: null ;
        $data["rec_formato_id"] = isset($values["rec_formato_id"]) ? $values["rec_formato_id"]: null ;
        $data["rec_tipo_funcional_id"] = isset($values["rec_tipo_funcional_id"]) ? $values["rec_tipo_funcional_id"]: null ;
        $data["tip_rec_educativo_id"] = isset($values["tip_rec_educativo_id"]) ? $values["tip_rec_educativo_id"] : null;
        $data["autor_id"] = isset($values["autor_id"]) ? $values["autor_id"]: null ;
        $data["autor_descripcion"] = isset($values["autor_descripcion"]) ? $values["autor_descripcion"]: null ;
        $data["fecha_desde"] = isset($values["fecha_desde"]) ? $values["fecha_desde"]: null ;
        $data["fecha_hasta"] = isset($values["fecha_hasta"]) ? $values["fecha_hasta"]: null ;
        $data["fecha_exacta"] = isset($values["fecha_exacta"]) ? $values["fecha_exacta"]: null ;
        $data["bloque_sitio_id"] = isset($values["bloque_sitio_id"]) ? $values["bloque_sitio_id"]: null ;        
        $data["etiqueta_id"] = isset($values["etiqueta_id"]) ? $values["etiqueta_id"]: null ;        
        $data["tema_id"] = isset($values["tema_id"]) ? $values["tema_id"]: null ;        
        $data["idioma_id"] = isset($values["idioma_id"]) ? $values["idioma_id"]: null ;
        $data["modalidad_id"] = isset($values["modalidad_id"]) ? $values["modalidad_id"]: null ;
        $data["segmento_etario_id"] = isset($values["segmento_etario_id"]) ? $values["segmento_etario_id"]: null ;
        $data["extracurricular_id"] = isset($values["extracurricular_id"]) ? $values["extracurricular_id"]: null ;
        $data["editor_id"] = isset($values["editor_id"]) ? $values["editor_id"]: null ;
        $data["inicio_letras"] = isset($values["inicio_letras"]) ? $values["inicio_letras"]: null ;
        $data["tipo_articulo_id"] = isset($values["tipo_articulo_id"]) ? $values["tipo_articulo_id"]: null ;
        $data["audiencia_id"] = isset($values["audiencia_id"]) ? $values["audiencia_id"]: null ;
        $data["limit"] = isset($values["limit"]) ? $values["limit"]: null ;
        $data["offset"] = isset($values["offset"]) ? $values["offset"]: null ;        
        $data["fields"] = isset($values["fields"]) ? $values["fields"]: array
        (
          "rec_id", "rec_titulo", "rec_descripcion", "rec_small_icon_image_id", "rec_medium_icon_image_id", "etiquetas",
          "rec_tipo_funcional_id", "tip_rec_educativo_id", "rec_puntaje", "rec_cant_votos", "rec_cant_accesos", "rec_cant_descargas_offline", 
          "rec_cant_portafolios", "rec_cant_etiquetas_usuarios", "rec_cant_comentarios", "rec_cant_reproduccion_video", "rec_cant_descarga_video","rec_fecha_alta","rec_fecha", "rec_version"
        );
        $data["calculateAmounts"] = isset($values["calculateAmounts"]) ? $values["calculateAmounts"]: true;
        $data["sort_column"] = isset($values["sort_column"]) ? $values["sort_column"]: null;
        $data["sort_mode"] = isset($values["sort_mode"]) ? $values["sort_mode"]: "ASC";
        
        $this->data = $data;
//        $this->uri = $this->global_config['URL_RECURSO_BUSQUEDA_AVANZADA'];
        $this->uri = ApiCommunication::get_api_uri('URL_RECURSO_BUSQUEDA_AVANZADA');
       
        return $this->callRestService();
    }

}

?>
