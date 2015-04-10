<?php
namespace Edufw\services\educar\models\conectados;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo Rest Usuario
 *
 * @version 20120710
 * @author lmoya
 */
class Usuario extends RestModel
{

  /**
   * Obtiene la couta restante de un usuario
   *
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_usuarios#obtener_cuota_de_usuario
   * @return ApiResponse
   */
  public function getCuotaUsuario($usr_id)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id
    );
    $this->uri = $this->global_config['URL_CONECTADOS_GETCOUTAUSUARIO'];
    return $this->callRestService();
  }

  /**
   *  Permite registrar una denuncia asociada a un usuario.
   *
   * @param String $usr_id_denunciante ID de usuario denunciante
   * @param String $login_token Token de login de usuario denunciante
   * @param int $usr_id_denunciado ID de usuario denunciado
   * @param String $reporte_texto [opcional] Texto descriptivo del reporte
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_denuncia_usuarios
   * @return ApiResponse
   */
  public function reportarUsuario($usr_id_denunciante, $login_token, $usr_id_denunciado, $reporte_texto = null)
  {
  	$this->data = array(
  			"sitio_id" => ApiCommunication::$sitio_id,
  			"usr_id_denunciante" => $usr_id_denunciante,
  			"login_token" => $login_token,
  			"usr_id_denunciado" => $usr_id_denunciado,
  			"reporte_texto" => $reporte_texto
  	);
  	$this->uri = $this->global_config['URL_CONECTADOS_REPORTARUSUARIO'];
  	return $this->callRestService();

  }

  /**
   * Permite verificar si el usuario que se pasa por parámetro está activo o no.
   *
   * @param String $usr_id ID de usuario
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:servicios_conectados:servicios_usuarios#verificar_usuario_activo
   * @return ApiResponse
   */
  public function verificarUsuario($usr_id, $login_token)
  {
  	$this->data = array(
  			"sitio_id" => ApiCommunication::$sitio_id,
  			"usr_id" => $usr_id,
            "login_token" => $login_token
  	);
  	$this->uri = $this->global_config['URL_CONECTADOS_VERIFICARUSUARIO'];
  	return $this->callRestService();

  }

}

