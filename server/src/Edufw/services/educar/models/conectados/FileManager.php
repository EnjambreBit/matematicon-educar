<?php
namespace Edufw\services\educar\models\conectados;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo FileManager
 *
 * @version 20120710
 * @author lmoya
 */
class FileManager extends RestModel
{

  /**
   * Obtiene un ticket de Upload para un archivo
   *
   * @link
   * @return ApiResponse
   */
  public function getTicket($usr_id, $login_token, $file_extension, $file_size)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "file_extension" => $file_extension,
        "file_size" => $file_size
    );
    $this->uri = $this->global_config['URL_GET_TICKET_UPLOAD'];
    return $this->callRestService();
  }

  /**
   * Obtiene un ticket de Descarga para un archivo
   *
   * @link
   * @return ApiResponse
   */
  public function getDownloadTicket($usr_id, $login_token, $entrada_id, $archivo_sid)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "entrada_id" => $entrada_id,
        "archivo_sid" => $archivo_sid
    );
    $this->uri = $this->global_config['URL_GET_TICKET_DOWNLOAD'];
    return $this->callRestService();
  }
}
