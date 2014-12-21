<?php

namespace Edufw\services\educar\models\cuentas;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo RestActions
 *
 * Agrupación de servicios referidos a usuarios y control de autenticación en el sistema.
 *
 * @version 20120614
 * @author pgambetta
 */
class RestActions extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 1;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_RECURSO_INEXISTENTE = 'Recurso inexistente';
    const MSG_TEMPORADA_INEXISTENTE = 'Temporada inexistente';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';

    //TIPOS FUNCIONALES
    const EMISION = 11;
    const CAPITULO = 12;

    /**
     * Recibe un usuario y un password y lo inserta en sesion
     *
     * @method loginUser
     *
     * @param string $usr_id Es el id del usuario
     * @param string $usr_pswd Es el password del usuario
     * @param integer $sitio_id [opcional]
     *
     * @return ApiResponse
     */
    public function loginUser($usr_id, $usr_pswd, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "usr_id" => $usr_id,
            "usr_pswd" => $usr_pswd
        );
        $this->uri = ApiCommunication::get_api_uri('URI_LOGIN_USER_SITIO');
        return $this->callRestService(self::CODE_SUCCES);
    }

     /**
     * Analiza si un token se encuentra o no el la base de datos
     * @method checkUserLogged
     *
     * @param string $login_token Es el token de login
     * @param integer $sitio_id [opcional]
      *
     * @return ApiResponse
     */
    public function checkUserLogged($login_token, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "login_token" => $login_token
        );
        $this->uri = ApiCommunication::get_api_uri('URI_CHEQ_AUTENTICACION');
        return $this->callRestService();
    }

    /**
     * Obtiene los datos de un usuario logueado
     * @method getUserData
     *
     * @param string $login_token Es el token de login
     * @param integer $sitio_id [opcional]
     *
     * @return ApiResponse
     */
    public function getUserData($login_token, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "login_token" => $login_token
        );
        $this->uri = ApiCommunication::get_api_uri('URI_GET_USER_DATA');
        return $this->callRestService();
    }
    /**
     * Recibe un token e intenta quitarlo de sesion
     *
     * @method logout
     *
     * @param string $login_token Es el token de login
     * @param integer $sitio_id [opcional]
     *
     * @return ApiResponse
     */
    public function logout($login_token, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "login_token" => $login_token
        );
        $this->uri = ApiCommunication::get_api_uri('URI_LOGOUT');
        return $this->callRestService();
    }

    /**
     * Retorna un listado de paises
     *
     * @param integer $sitio_id [opcional]
     *
     * @return ApiResponse
     */
    public function getPaises($sitio_id = false){
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id
        );
        $this->uri = ApiCommunication::get_api_uri('URI_GET_PAISES');
        return $this->callRestService();
    }

    /**
     * Retorna un listado de provincias/estados de un país determinado
     *
     * @param integer $pais_id Es el ID del pais
     * @param integer $sitio_id [opcional]
     *
     * @return ApiResponse
     */
    public function getProvincias($pais_id, $sitio_id = false){
        $this->data = array(
            'pais_id' => $pais_id,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id
        );
        $this->uri = ApiCommunication::get_api_uri('URI_GET_PROVINCIAS');
        return $this->callRestService();
    }
    /**
     * Obtiene los datos de un usuario
     * @method obtenerUsuarioPublico
     *
     * @param string $usr_id ID de usuario
     *
     * @return ApiResponse
     */
    public function obtenerUsuario($usr_id, $login_token)
    {
        $this->data = array(
            "sitio_id" => ApiCommunication::$sitio_id,
            "usr_id" => $usr_id,
            "login_token" => $login_token
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_USUARIO');
        return $this->callRestService();
    }
    /**
     * Obtiene los datos de un usuario publico
     * @method obtenerUsuarioPublico
     *
     * @param string $usr_id ID de usuario
     *
     * @return ApiResponse
     */
    public function obtenerUsuarioPublico($usr_id) {
        $this->data = array(
            "usr_id" => $usr_id
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_USUARIO_PUBLICO');
        return $this->callRestService();
    }
    /**
     * Actualiza los campos de un Usuario
     * @param String $usr_id ID de usuario
     * @param String $login_token Token de session
     * @param Array $campos_usuario Array de campos de datos de usuario.
     * @return type
     */
    public function actualizarUsuario($usr_id, $login_token, $campos_usuario){

      $this->data = array(
          "sitio_id" => ApiCommunication::$sitio_id,
          "usr_id" => $usr_id,
          "login_token" => $login_token
      );

      $this->data["usr_nombre"] = isset($campos_usuario['usr_nombre']) ? $campos_usuario['usr_nombre'] : null;
      $this->data["usr_apellido"] = isset($campos_usuario['usr_apellido'])? $campos_usuario['usr_apellido'] : null;
      $this->data["usr_fecha_nacimiento"] = isset($campos_usuario['usr_fecha_nacimiento'])? $campos_usuario['usr_fecha_nacimiento'] : null;
      $this->data["usr_sexo"] = isset($campos_usuario['usr_sexo'])? $campos_usuario['usr_sexo'] : null;
      $this->data["usr_mail"] = isset($campos_usuario['usr_mail'])? $campos_usuario['usr_mail'] : null;
      $this->data["usr_tipo_documento"] = isset($campos_usuario['usr_tipo_documento'])? $campos_usuario['usr_tipo_documento'] : null;
      $this->data["usr_numero_documento"] = isset($campos_usuario['usr_numero_documento'])? $campos_usuario['usr_numero_documento'] : null;
      $this->data["usr_pais_id"] = isset($campos_usuario['usr_pais_id'])? $campos_usuario['usr_pais_id'] : null;
      $this->data["usr_provincia_id"] = isset($campos_usuario['usr_provincia_id'])? $campos_usuario['usr_provincia_id'] : null;
      $this->data["usr_localidad"] = isset($campos_usuario['usr_localidad'])? $campos_usuario['usr_localidad'] : null;
      $this->data["usr_direccion"] = isset($campos_usuario['usr_direccion'])? $campos_usuario['usr_direccion'] : null;
      $this->data["usr_codigo_postal"] = isset($campos_usuario['usr_codigo_postal'])? $campos_usuario['usr_codigo_postal'] : null;
      $this->data["usr_telefono"] = isset($campos_usuario['usr_telefono'])? $campos_usuario['usr_telefono'] : null;
      $this->data["usr_apodo"] = isset($campos_usuario['usr_apodo'])? $campos_usuario['usr_apodo'] : null;
      $this->data["usr_biografia"] = isset($campos_usuario['usr_biografia'])? $campos_usuario['usr_biografia'] : null;
      $this->data["usr_avatar"] = isset($campos_usuario['usr_avatar'])? $campos_usuario['usr_avatar'] : null;
      $this->data["tipo_recuperacion_id"] = isset($campos_usuario['tipo_recuperacion_id'])? $campos_usuario['tipo_recuperacion_id'] : null;
      $this->data["use_pregunta_secreta"] = isset($campos_usuario['use_pregunta_secreta'])? $campos_usuario['use_pregunta_secreta'] : null;
      $this->data["use_respuesta_secreta"] = isset($campos_usuario['use_respuesta_secreta'])? $campos_usuario['use_respuesta_secreta'] : null;


    $this->uri = ApiCommunication::get_api_uri('URL_ACTUALIZAR_USUARIO');
    return $this->callRestService();

    }
    /**
     * Obtiene un Ticket para subir el Avatar de usuario
     * @param String $usr_id ID de usuario
     * @param String $login_token Token de session
     * @return type
     */
    public function obtenerTicketSubida($usr_id, $login_token) {
        $this->data = array(
            "sitio_id" => ApiCommunication::$sitio_id,
            "usr_id" => $usr_id,
            "login_token" => $login_token
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_TICKET_SUBIDA');
        return $this->callRestService();
    }
    /**
     * Chequea que un apodo para un usuario este disponible
     * @param type $usr_apodo Apodo / Nickname de usuario
     * @return type
     */
    public function comprobarApodo($usr_apodo) {
        $this->data = array(
            "sitio_id" => ApiCommunication::$sitio_id,
            "usr_apodo" => $usr_apodo
        );
        $this->uri = ApiCommunication::get_api_uri('URL_CHECK_APODO');
        return $this->callRestService();
    }
}

