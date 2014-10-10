<?php
namespace Edufw\services\educar\models\conectados;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo Entrada
 *
 * @version 20120710
 * @author lmoya
 */
class Entrada extends RestModel
{

  /**
   * Obtiene el mosaico de la home
   *
   * @param String $categoria_alias <b>[opcional]</b> alias de la categoria
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_obtencion_entradas#obtener_mosaico
   * @return ApiResponse
   */
  public function getMosaico($categoria_alias = null)
  {
    $this->data = array(
        "categoria_alias" => $categoria_alias,
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETMOSAICO'];
    return $this->callRestService();
  }

  /**
   * Obtiene una entrada con todo su detalle
   *
   * @param int $entrada_id ID de la entrada para obtener el detalle
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_obtencion_entradas#obtener_detalle_entrada
   * @return ApiResponse
   */
  public function getEntrada($entrada_id)
  {
    $this->data = array(
        "entrada_id" => $entrada_id,
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETENTRADA'];
    return $this->callRestService();
  }

  /**
   * Permite crear una nueva entrada (ya sea un contenido o un evento).
   *
   * @param String $usr_id ID de usuario
   * @param String $login_token Token de login de usuario
   * @param String $entrada_tipo_alias ALIAS del tipo de entrada
   * @param String $entrada_formato_alias ALIAS del formato de entrada
   * @param String $categoria_alias ALIAS de categoria
   * @param Array $opcional [optional] Arreglo de parametros opcionales
   * <p> indice : [tipo] parametro
   * <ul>
   *  <li>entrada_titulo        : [String] Titulo de la entrada</li>
   *  <li>entrada_texto         : [String] Texto de la entrada</li>
   *  <li>entrada_enlace        : [String] Link de la entrada</li>
   *  <li>pais_id               : [int] ID de pais de ubicacion</li>
   *  <li>provincia_id          : [int] ID de provencia de ubicacion</li>
   *  <li>entrada_domicilio     : [Strint] Direccion fisica del evento</li>
   *  <li>entrada_latitud       : [float] latitud de la ubicacion</li>
   *  <li>entrada_longitud      : [float] logintud de la ubicacion</li>
   *  <li>entrada_organizador   : [String] Descripcion del organizador</li>
   *  <li>tipo_costo_alias      : [String] ALIAS del tipo de costo de la entrada</li>
   *  <li>entrada_fecha_inicio  : [String] mm/dd/yyyy hh:mi:ss</li>
   *  <li>entrada_fecha_fin     : [String] mm/dd/yyyy hh:mi:ss</li>
   *  <li>archivo_sid           : [int] SID del archivo a asociar</li>
   * </ul>
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_administracion_entradas#crear_entrada
   * @return ApiResponse
   */
  public function newEntrada($usr_id, $login_token, $entrada_tipo_alias, $entrada_formato_alias, $categoria_alias, $opcional = array())
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "entrada_tipo_alias" => $entrada_tipo_alias,
        "entrada_formato_alias" => $entrada_formato_alias,
        "categoria_alias" => $categoria_alias
    );
    $this->data['entrada_evento_tipo_alias'] = isset($opcional['entrada_evento_tipo_alias']) ? $opcional['entrada_evento_tipo_alias'] : null;
    $this->data['entrada_titulo'] = isset($opcional['entrada_titulo']) ? $opcional['entrada_titulo'] : null;
    $this->data['entrada_texto'] = isset($opcional['entrada_texto']) ? $opcional['entrada_texto'] : null;
    $this->data['entrada_enlace'] = isset($opcional['entrada_enlace']) ? $opcional['entrada_enlace'] : null;
    $this->data['entrada_enlace_adicional'] = isset($opcional['entrada_enlace_adicional']) ? $opcional['entrada_enlace_adicional'] : null;
    $this->data['pais_id'] = isset($opcional['pais_id']) ? $opcional['pais_id'] : null;
    $this->data['provincia_id'] = isset($opcional['provincia_id']) ? $opcional['provincia_id'] : null;
    $this->data['entrada_domicilio'] = isset($opcional['entrada_domicilio']) ? $opcional['entrada_domicilio'] : null;
    $this->data['entrada_localidad'] = isset($opcional['entrada_localidad']) ? $opcional['entrada_localidad'] : null;
    $this->data['entrada_latitud'] = isset($opcional['entrada_latitud']) ? $opcional['entrada_latitud'] : null;
    $this->data['entrada_longitud'] = isset($opcional['entrada_longitud']) ? $opcional['entrada_longitud'] : null;
    $this->data['entrada_organizador'] = isset($opcional['entrada_organizador']) ? $opcional['entrada_organizador'] : null;
    $this->data['tipo_costo_alias'] = isset($opcional['tipo_costo_alias']) ? $opcional['tipo_costo_alias'] : null;
    $this->data['entrada_fecha_inicio'] = isset($opcional['entrada_fecha_inicio']) ? $opcional['entrada_fecha_inicio'] : null;
    $this->data['entrada_fecha_fin'] = isset($opcional['entrada_fecha_fin']) ? $opcional['entrada_fecha_fin'] : null;
    $this->data['archivo_sid'] = isset($opcional['archivo_sid']) ? $opcional['archivo_sid'] : null;
    $this->data['etiquetas'] = isset($opcional['etiquetas']) ? $opcional['etiquetas'] : null;
    $this->uri = $this->global_config['URL_CONECTADOS_NEWENTRADA'];
    return $this->callRestService();
  }
  /**
   * Permite dar de baja (logica) una entrada asignándole como estado “eliminado”.
   * @param String $usr_id ID de usuario
   * @param String $login_token Token de conexion
   * @param int $entrada_id ID de entrada
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_administracion_entradas#dar_de_baja_una_entrada
   * @return ApiResponse
   */
  public function despublicarEntrada($usr_id, $login_token, $entrada_id)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "entrada_id" => $entrada_id
    );
    $this->uri = $this->global_config['URL_CONECTADOS_DESPUBLICARENTRADA'];
    return $this->callRestService();
  }
  /**
   *  Permite registrar una denuncia asociada a una entrada. Registrar una denuncia implica registrar el tipo de reporte (o motivo de denuncia)
   *
   * @param String $usr_id ID de usuario
   * @param String $login_token Token de login de usuario
   * @param int $entrada_id ID de la entrada
   * @param String $reporte_tipo_alias ALIAS del tipo de reporte
   * @param String $reporte_texto [opcional] Textp descriptivo del reporte
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_denuncia_entradas
   * @return ApiResponse
   */
  public function reportarEntrada($usr_id, $login_token, $entrada_id, $reporte_tipo_alias, $reporte_texto = null)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "entrada_id" => $entrada_id,
        "reporte_tipo_alias" => $reporte_tipo_alias,
        "reporte_texto" => $reporte_texto
    );
    $this->uri = $this->global_config['URL_CONECTADOS_REPORTARENTRADA'];
    return $this->callRestService();

  }
  /**
   * Permite editar una entrada. Solamente se puede editar una entrada de tipo “evento” y se permite modificar únicamente las fechas de inicio y/o de fin del evento. Ningún otro dato puede ser modificado.
   *
   * @param String $usr_id ID de usuario
   * @param String $login_token Token de login de usuario
   * @param int $entrada_id ID de la entrada
   * @param type $entrada_fecha_inicio
   * @param type $entrada_fecha_fin
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_administracion_entradas#editar_entrada
   * @return ApiResponse
   */
  public function editEntrada($usr_id, $login_token, $entrada_id, $entrada_fecha_inicio = null, $entrada_fecha_fin = null)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "entrada_id" => $entrada_id,
        "entrada_fecha_inicio" => $entrada_fecha_inicio,
        "entrada_fecha_fin" => $entrada_fecha_fin
    );
    $this->uri = $this->global_config['URL_CONECTADOS_EDITENTRADA'];
    return $this->callRestService();
  }
  /**
   *  Permite bloquear una entrada. El usuario bloqueante debe ser de Educ.ar.
   *
   * @param String $usr_id ID de usuario
   * @param String $login_token Token de login de usuario
   * @param int $entrada_id ID de la entrada
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_denuncia_entradas
   * @return ApiResponse
   */
  public function bloquearEntrada($usr_id, $login_token, $entrada_id)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "entrada_id" => $entrada_id
    );
    $this->uri = $this->global_config['URL_CONECTADOS_BLOQUEARENTRADA'];
    return $this->callRestService();

  }
}
