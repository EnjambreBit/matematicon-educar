<?php
namespace Edufw\services\educar\models\repositorio\interaccion;
use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST para realizar acciones sobre etiquetas.
 *
 * Un usuario puede crear sus propias etiquetas (tags) y aplicarlas a los recursos educativos. Las etiquetas de usuario son propias de cada usuario y por cada sitio.
 * Requieren autenticación previa.
 * 
 * @name Recurso
 * @version 20120614
 * @author pgambetta
 */
class Etiqueta extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_USUARIO_INEXISTENTE = 1;
    const CODE_SITIO_INEXISTENTE = 2;
    const CODE_ETIQUETA_INEXISTENTE = 3;
    const CODE_RECURSO_INEXISTENTE = 4;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';
    const MSG_USUARIO_INEXISTENTE = 'Usuario inexistente';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_ETIQUETA_INEXISTENTE = 'Etiqueta inexistente';
    const MSG_RECURSO_INEXISTENTE = 'Recursos inexistente';

    /**
     * Crea una nueva etiqueta de usuario
     * @method crearEtiquetaUsuario
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param integer $rec_id Es el ID del recurso
     * @param string $etiqueta Es el texto de la etiqueta deseada
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function crearEtiquetaUsuario($usr_id, $login_token, $etiqueta, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "etiqueta_descripcion" => $etiqueta
        );
        $this->uri = ApiCommunication::get_api_uri('URI_NEW_ETIQUETA_USUARIO');
        return $this->callRestService();
    }

    /**
     * Modificar una etiqueta existente
     * @method modificarEtiquetaUsuario
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param string $etiqueta_id Es el id de la etiqueta a modificar
     * @param string $etiqueta_descripcion Es el texto de la etiqueta a modificar
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function modificarEtiquetaUsuario($usr_id, $login_token, $etiqueta_id, $etiqueta_descripcion, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "etiqueta_id" => $etiqueta_id,
            "etiqueta_descripcion" => $etiqueta_descripcion
        );
        $this->uri = ApiCommunication::get_api_uri('URI_MODIFICAR_ETIQUETA_USUARIO');
        return $this->callRestService();
    }
    
    /**
     * Eliminar una etiqueta existente
     * @method eliminarEtiquetaUsuario
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param string $etiqueta_id Es el id de la etiqueta a eliminar     
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function eliminarEtiquetaUsuario($usr_id, $login_token, $etiqueta_id, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "etiqueta_id" => $etiqueta_id
        );
        $this->uri = ApiCommunication::get_api_uri('URI_ELIMINAR_ETIQUETA_USUARIO');
        return $this->callRestService();
    }

    /**
     * Asigna una etiqueta a un recurso
     * @method asignarRecursosAEtiquetaUsuario
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param integer $etiqueta_id Es el ID de la etiqueta
     * @param array $recursos Es un array de id's de recursos
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function asignarRecursosAEtiquetaUsuario($usr_id, $login_token, $etiqueta_id, $recursos, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "etiqueta_id" => $etiqueta_id,
            "recursos" => $recursos
        );
        $this->uri = ApiCommunication::get_api_uri('URI_ASIGNAR_ETIQUETA_A_RECURSOS');
        return $this->callRestService();
    }

    /**
     * Quita una etiqueta de uno o más recursos
     * @method quitarRecursosAEtiquetaUsuario
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param integer $etiqueta_id Es el ID de la etiqueta
     * @param array $recursos Es un array de id's de recursos
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function quitarRecursosAEtiquetaUsuario($usr_id, $login_token, $etiqueta_id, $recursos, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "etiqueta_id" => $etiqueta_id,
            "recursos" => $recursos
        );
        $this->uri = ApiCommunication::get_api_uri('URL_DESASIGNAR_ETIQUETA_A_RECURSO');
        return $this->callRestService();
    }

    /**
     * 
     * @todo Implementar método
     */
    public function getRecursosDeEtiquetaUsuario() {
        
    }

    /**
     * Obtiene las etiquetas de un usuario
     * @method getEtiquetasDeUnUsuario 
     * 
     * @param string $usr_id Es el ID del usuario
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getEtiquetasDeUnUsuario($usr_id, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_ETIQUETAS_USUARIO');
        return $this->callRestService();
    }

    /**
     * Obtiene las etiquetas de un usuario dentro de un portafolio
     * @method getEtiquetasDeUnUsuarioEnPortafolio 
     * 
     * @param string $usr_id Es el ID del usuario
     * @param integer $portafolio_id Es el ID del portafolio donde se encuentra la etiqueta
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getEtiquetasDeUnUsuarioEnPortafolio($usr_id, $portafolio_id, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "portafolio_id" => $portafolio_id
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_ETIQUETAS_USUARIO_EN_PORTAFOLIO');
        return $this->callRestService();
    }

}
