<?php
namespace Edufw\services\educar\models\conectados;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo Etiqueta
 *
 * @version 20120710
 * @author lmoya
 */
class Etiqueta extends RestModel
{

  /**
   * Agrega una etiqueta a una entrada
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_etiquetado_entradas#etiquetar_entrada
   * @return ApiResponse
   */
  public function addEtiquetasEntrada($usr_id, $login_token, $etiquetas)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "etiquetas" => $etiquetas
    );
    $this->uri = $this->global_config['URL_CONECTADOS_ADDETIQUETAENTRADA'];
    return $this->callRestService();
  }

  /**
   * Remueve una etiqueta de una entrada
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_etiquetado_entradas#quitar_etiqueta_de_entrada
   * @return ApiResponse
   */
  public function removeEtiquetasEntrada($usr_id, $login_token, $etiquetas)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "etiquetas" => $etiquetas
    );
    $this->uri = $this->global_config['URL_CONECTADOS_REMOVEETIQUETAENTRADA'];
    return $this->callRestService();
  }

  /**
   * Crea una etiqueta
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_etiquetado_entradas
   * @return ApiResponse
   */
  public function newEtiqueta($usr_id, $login_token, $etiqueta_descripcion)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "etiqueta_descripcion" => $etiqueta_descripcion
    );
    $this->uri = $this->global_config['URL_CONECTADOS_NEWETIQUETA'];
    return $this->callRestService();
  }
  /**
   * Servicio que devuelve las etiquetas asociadas a un categoría determinada.
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_etiquetado_entradas#obtener_etiquetas_por_categoria
   * @return ApiResponse
   */
  public function getEtiquetaCategoria($categoria_alias)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "categoria_alias" => $categoria_alias
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETETIQUETACATEGORIA'];
    return $this->callRestService();
  }
}
