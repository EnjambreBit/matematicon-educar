<?php
namespace Edufw\services\educar\models\conectados;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo Rest EntradaSearch
 *
 * @version 20120710
 * @author lmoya
 */
class EntradaSearch extends RestModel
{
  const MIN_OFFSET = 0;
  const MIN_LIMIT = 10;
  const SORT_MODE = 'DESC';

  /**
   * Realiza una busqueda avanzada de entradas, con los filtros y parametros enviados.
   *
   * @param Array $filtrers Filtros de busqueda especificados en la Wiki del servicio de busqeuda avanzada.
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_busqueda_entradas
   * @return ApiResponse
   */
  public function advanceSearch($filters)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
    );
    $this->data['fields'] = isset($filters['fields']) ? $filters['fields'] : array();
    $this->data['texto_busqueda'] = trim(isset($filters['texto_busqueda']) ? $filters['texto_busqueda'] : '');
    //Fechas
    $this->data['fecha_inicio_evento'] = isset($filters['fecha_inicio_evento']) ? $filters['fecha_inicio_evento'] : NUll;
    $this->data['fecha_fin_evento'] = isset($filters['fecha_fin_evento']) ? $filters['fecha_fin_evento'] : NUll;
    $this->data['fecha_alta_desde'] = isset($filters['fecha_alta_desde']) ? $filters['fecha_alta_desde'] : NUll;
    $this->data['fecha_alta_hasta'] = isset($filters['fecha_alta_hasta']) ? $filters['fecha_alta_hasta'] : NUll;
    $this->data['cant_max_registros'] = isset($filters['cant_max_registros']) ? $filters['cant_max_registros'] : NUll;
    //Obtencion de FILTROS
    $this->data['entrada_id'] = isset($filters['entrada_id']) ? $filters['entrada_id'] : NUll;
    $this->data['usr_id'] = isset($filters['usr_id']) ? $filters['usr_id'] : NUll;
    $this->data['pais_id'] = isset($filters['pais_id']) ? $filters['pais_id'] : NUll;
    $this->data['provincia_id'] = isset($filters['provincia_id']) ? $filters['provincia_id'] : NUll;
    $this->data['etiqueta_id'] = isset($filters['etiqueta_id']) ? $filters['etiqueta_id'] : NUll;
    $this->data['entrada_tipo_alias'] = isset($filters['entrada_tipo_alias']) ? $filters['entrada_tipo_alias'] : NUll;
    $this->data['entrada_evento_tipo_alias'] = isset($filters['entrada_evento_tipo_alias']) ? $filters['entrada_evento_tipo_alias'] : NUll;
    $this->data['formato_alias'] = isset($filters['formato_alias']) ? $filters['formato_alias'] : NUll;
    $this->data['tipo_costo_alias'] = isset($filters['tipo_costo_alias']) ? $filters['tipo_costo_alias'] : NUll;
    $this->data['categoria_alias'] = isset($filters['categoria_alias']) ? $filters['categoria_alias'] : NUll;

    //Aplicacion de limites, orden y otros
    $this->data['offset'] = isset($filters['offset']) ? $filters['offset'] : self::MIN_OFFSET;
    $this->data['limit'] = isset($filters['limit'])? $filters['limit'] : self::MIN_LIMIT;
    $this->data['sort_column'] = isset($filters['sort_column']) ? $filters['sort_column'] : '';
    $this->data['sort_mode'] = isset($filters['sort_mode']) ? $filters['sort_mode'] : self::SORT_MODE;
    $this->data['calculate_amounts'] = isset($filters['calculate_amounts']) ? $filters['calculate_amounts'] : FALSE;
    $this->data['match_mode_extended'] = isset($filters['match_mode_extended']) ?  $filters['match_mode_extended'] : FALSE;

    $this->uri = $this->global_config['URL_CONECTADOS_ADVANCESEARCH'];
    return $this->callRestService();
  }

  /**
   * Realiza una busqueda entre las etiquetas y sugiere posibles coincidencias.
   *
   * @param Array $filtrers Filtros de busqueda especificados en la Wiki del servicio de sugerir etiquetas.
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_etiquetado_entradas#sugerir_etiquetas
   * @return ApiResponse
   */
  public function sugerirEtiquetas($filters)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
    );
    $this->data['search_string'] = trim(isset($filters['search_string']) ? $filters['search_string'] : '');

    $this->uri = $this->global_config['URL_CONECTADOS_SEARCHETIQUETAS'];
    return $this->callRestService();
  }

}

