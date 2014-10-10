<?php
namespace Edufw\services\educar\models\conectados;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo Rest Valoracion
 *
 * @version 20120710
 * @author lmoya
 */
class Valoracion extends RestModel
{

  /**
   * Agrega una etiqueta a una entrada
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_etiquetado_entradas#etiquetar_entrada
   * @return ApiResponse
   */
  public function valorarEntrada($usr_id, $login_token,$entrada_id, $valoracion_tipo_alias)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "entrada_id" => $entrada_id,
        "val_tipo_alias" => $valoracion_tipo_alias
    );
    $this->uri = $this->global_config['URL_CONECTADOS_VALORARENTRADA'];
    return $this->callRestService();
  }

  /**
   * Agrega una etiqueta a una entrada
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_etiquetado_entradas#etiquetar_entrada
   * @return ApiResponse
   */
  public function desvalorarEntrada($usr_id, $login_token,$entrada_id)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "entrada_id" => $entrada_id
    );
    $this->uri = $this->global_config['URL_CONECTADOS_DESVALORARENTRADA'];
    return $this->callRestService();
  }
}

