<?php

namespace Edufw\services\educar\models\contenidos;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST para busquedas sobre contenidos de usuario 
 *
 * Listado de servicios relacionados al motor de búsqueda del Repositorio.
 * 
 * @name UserContentSearch
 * @version 20121120
 * @author fbasile
 */
class UserContentSearch extends RestModel {
    
    /**
     * Permite realizar una búsqueda avanzada al motor de búsqueda del Repositorio.
     * Para cada indice indicado del array $values se puede especificar un string de busqueda.
     * Los valores posibles de “limit” son 1, 3, 5, 7, 10, 15, 20, 25 y 30.
     * El campo "sort_column" puede tener los valores rec_fecha, ranking, alfabetico.
     * La columna “sort_mode” indica el orden en el cuál ordenar la columna indicada en “sort_column”.
     * 
     * @method advanceSearch
     *
     * @param Array $values Array de filtros de busqueda. Indices posibles:
     * contenido_titulo, contenido_descripcion, inicio_letras, fecha_desde, fecha_hasta, fecha_exacta,
     * fecha_alta_desde, fecha_alta_hasta, fecha_alta_exacta, categoria_sitio_id, carpeta_id, etiqueta_id, usr_id,
     * file_tipo_almacenamiento_id, cantidad_archivos_desde, cantidad_archivos_hasta, cantidad_archivos_exacta,
     * fecha_evento_desde, fecha_evento_hasta, fecha_evento_exacta, horario_evento_desde, horario_evento_hasta,
     * pais_id, prov_id, rec_id, tipo_creacion_contenido_id, limit, offset, sort_column, sort_mode
     * NULL => Trae todos los campos.
     * contenido_id, contenido_titulo, contenido_descripcion, contenido_fecha_alta, contenido_fecha, contenido_cant_votos,
     * contenido_puntaje, contenido_cant_comentarios, contenido_cant_visualizaciones, usr_id, carpeta_id, contenido_cantidad_archivos,
     * etiquetas, contenido_fecha_evento, contenido_horario_evento, contenido_ubicacion
     * 
     * @example
     * $this->advanceSearch(array("contenido_titulo" => "San Martin"), array("contenido_id", "contenido_descripcion") )
     * 
     * @return ApiResponse Array de la forma array("codigo" => "" , "mensaje" => "",	"resultLength" => , "result" => array([...]) [...])
     */
    public function advanceSearch($values)
    {
        $data = array();
        $data["sitio_id"] = ApiCommunication::$sitio_id;
        $data["fields"] = isset($values["fields"]) ? $values["fields"]: array
        (
          "contenido_id", "contenido_titulo", "contenido_descripcion", "contenido_fecha_alta", "contenido_fecha", "contenido_cant_votos",
          "contenido_puntaje", "contenido_cant_comentarios", "contenido_cant_visualizaciones", "usr_id", "carpeta_id", "contenido_cantidad_archivos", 
          "etiquetas", "contenido_fecha_evento", "contenido_horario_evento", "contenido_ubicacion", "tipo_contenido"
        );
        $data["contenido_titulo"] = isset($values["contenido_titulo"]) ? $values["contenido_titulo"]: null;
        $data["contenido_descripcion"] = isset($values["contenido_descripcion"]) ? $values["contenido_descripcion"]: null;
        $data["inicio_letras"] = isset($values["inicio_letras"]) ? $values["inicio_letras"]: null;
        $data["fecha_desde"] = isset($values["fecha_desde"]) ? $values["fecha_desde"]: null;
        $data["fecha_hasta"] = isset($values["fecha_hasta"]) ? $values["fecha_hasta"]: null;
        $data["fecha_exacta"] = isset($values["fecha_exacta"]) ? $values["fecha_exacta"]: null;
        $data["fecha_alta_desde"] = isset($values["fecha_alta_desde"]) ? $values["fecha_alta_desde"]: null;
        $data["fecha_alta_hasta"] = isset($values["fecha_alta_hasta"]) ? $values["fecha_alta_hasta"]: null;
        $data["fecha_alta_exacta"] = isset($values["fecha_alta_exacta"]) ? $values["fecha_alta_exacta"]: null;
        $data["categoria_sitio_id"] = isset($values["categoria_sitio_id"]) ? $values["categoria_sitio_id"]: null;
        $data["carpeta_id"] = isset($values["carpeta_id"]) ? $values["carpeta_id"]: null;
        $data["etiqueta_id"] = isset($values["etiqueta_id"]) ? $values["etiqueta_id"]: null;
        $data["usr_id"] = isset($values["usr_id"]) ? $values["usr_id"] : null;
        $data["file_tipo_almacenamiento_id"] = isset($values["file_tipo_almacenamiento_id"]) ? $values["file_tipo_almacenamiento_id"]: null;
        $data["cantidad_archivos_desde"] = isset($values["cantidad_archivos_desde"]) ? $values["cantidad_archivos_desde"]: null;
        $data["cantidad_archivos_hasta"] = isset($values["cantidad_archivos_hasta"]) ? $values["cantidad_archivos_hasta"]: null;        
        $data["cantidad_archivos_exacta"] = isset($values["cantidad_archivos_exacta"]) ? $values["cantidad_archivos_exacta"]: null;        
        $data["fecha_evento_desde"] = isset($values["fecha_evento_desde"]) ? $values["fecha_evento_desde"]: null;        
        $data["fecha_evento_hasta"] = isset($values["fecha_evento_hasta"]) ? $values["fecha_evento_hasta"]: null;
        $data["fecha_evento_exacta"] = isset($values["fecha_evento_exacta"]) ? $values["fecha_evento_exacta"]: null;
        $data["horario_evento_desde"] = isset($values["horario_evento_desde"]) ? $values["horario_evento_desde"]: null;
        $data["horario_evento_hasta"] = isset($values["horario_evento_hasta"]) ? $values["horario_evento_hasta"]: null;
        $data["pais_id"] = isset($values["pais_id"]) ? $values["pais_id"]: null;
        $data["prov_id"] = isset($values["prov_id"]) ? $values["prov_id"]: null;
        $data["rec_id"] = isset($values["rec_id"]) ? $values["rec_id"]: null;
        $data["tipo_creacion_contenido_id"] = isset($values["tipo_creacion_contenido_id"]) ? $values["tipo_creacion_contenido_id"]: null;
        $data["tema_id"] = isset($values["tema_id"]) ? $values["tema_id"]: null;
        $data["limit"] = isset($values["limit"]) ? $values["limit"]: null;
        $data["offset"] = isset($values["offset"]) ? $values["offset"]: null;        
        $data["sort_column"] = isset($values["sort_column"]) ? $values["sort_column"]: null;
        $data["sort_mode"] = isset($values["sort_mode"]) ? $values["sort_mode"]: "ASC";
        
        $this->data = $data;
        $this->uri = $this->global_config['URL_USER_CONTENT_BUSQUEDA_AVANZADA'];
        
        return $this->callRestService();
    }

}

?>