<?php
namespace Edufw\services\educar\models\conectados;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo Rest Maestras
 *
 * @version 20120710
 * @author lmoya
 */
class Maestras extends RestModel
{

  /**
   * Obtiene los tipo de Origen
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_tablas_maestras#consulta_origenes_de_entrada
   * @return ApiResponse
   */
  public function getOrigen($origen_alias = null)
  {
    $this->data = array(
        "origen_alias" => $origen_alias
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETORIGEN'];
    return $this->callRestService();
  }
  /**
   * Obtiene los Paises
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_tablas_maestras#consulta_paises
   * @return ApiResponse
   */
  public function getPais($pais_id = null)
  {
    $this->data = array(
        "pais_id" => $pais_id
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETPAIS'];
    return $this->callRestService();
  }
  /**
   * Obtiene las Provincias
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_tablas_maestras#consulta_provincias
   * @return ApiResponse
   */
  public function getProvincia($provincia_id = null, $pais_id = null)
  {
    $this->data = array(
        "provincia_id" => $provincia_id,
        "pais_id" => $pais_id
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETPROVINCIA'];
    return $this->callRestService();
  }
  /**
   * Obtiene los tipo de Origen
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_tablas_maestras#consulta_tipos_de_archivos
   * @return ApiResponse
   */
  public function getTipoArchivo($archivo_tipo_alias = null)
  {
    $this->data = array(
        "archivo_tipo_alias" => $archivo_tipo_alias
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETARCHIVOTIPO'];
    return $this->callRestService();
  }
  /**
   * Obtiene las categorias de entrada
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_tablas_maestras#consulta_categorias_de_entrada
   * @return ApiResponse
   */
  public function getCategoria($categoria_alias = null)
  {
    $this->data = array(
        "categoria_alias" => $categoria_alias
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETCATEGORIA'];
    return $this->callRestService();
  }
  /**
   * Obtiene los tipo de Origen
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_tablas_maestras#consulta_origenes_de_entrada
   * @return ApiResponse
   */
  public function getEstadoEntrada($entrada_estado_alias = null)
  {
    $this->data = array(
        "entrada_estado_alias" => $entrada_estado_alias
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETENTRADAESTADO'];
    return $this->callRestService();
  }
  /**
   * Obtiene los Formatos de Entrada
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_tablas_maestras#consulta_formatos_de_entrada
   * @return ApiResponse
   */
  public function getFormatoEntrada($entrada_formato_alias = null)
  {
    $this->data = array(
        "entrada_formato_alias" => $entrada_formato_alias
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETENTRADAFORMATO'];
    return $this->callRestService();
  }
  /**
   * Obtiene los tipo de Entrada
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_tablas_maestras#consulta_tipos_de_entrada
   * @return ApiResponse
   */
  public function getTipoEntrada($entrada_tipo_alias = null)
  {
    $this->data = array(
        "entrada_tipo_alias" => $entrada_tipo_alias
    );
    $this->uri = $this->global_config['URL_COENCTADOS_GETENTRADATIPO'];
    return $this->callRestService();
  }
  /**
   * Obtiene los Tipo de Reportes
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_tablas_maestras#consulta_tipos_de_reporte
   * @return ApiResponse
   */
  public function getTipoReporte($reporte_tipo_alias = null)
  {
    $this->data = array(
        "reporte_tipo_alias" => $reporte_tipo_alias
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETREPORTETIPO'];
    return $this->callRestService();
  }
  /**
   * Obtiene los Tipos de Valoracion de Entradas
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_tablas_maestras#consulta_tipos_de_valoracion
   * @return ApiResponse
   */
  public function getTipoValoracion($valoracion_tipo_alias = null)
  {
    $this->data = array(
        "valoracion_tipo_alias" => $valoracion_tipo_alias
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETVALORACIONTIPO'];
    return $this->callRestService();
  }
}

