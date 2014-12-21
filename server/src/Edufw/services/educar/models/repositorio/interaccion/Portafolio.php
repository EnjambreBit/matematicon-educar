<?php
namespace Edufw\services\educar\models\repositorio\interaccion;
use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST para realizar acciones sobre portafolios.
 *
 * Un portafolio es una colección de recursos agrupados en una carpeta lógica, para un usuario particular. Cada usuario puede agregar recursos a carpetas de favoritos, inventadas por ellos, denominadas portfolios. Los portafolios son propios de cada usuario para cada sitio.
 * Requieren autenticación previa.
 * 
 * @name Portafolio
 * @version 20120614
 * @author pgambetta
 */
class Portafolio extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_USUARIO_INEXISTENTE = 1;
    const CODE_SITIO_INEXISTENTE = 2;
    const CODE_PORTAFOLIO_INEXISTENTE = 3;
    const CODE_PORTAFOLIO_INVALIDO = 3;
    const CODE_RECURSO_INEXISTENTE = 4;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';
    const MSG_USUARIO_INEXISTENTE = 'Usuario inexistente';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_PORTAFOLIO_INEXISTENTE = 'Portafolio inexistente';
    const MSG_PORTAFOLIO_INVALIDO = 'Descripción inválida';
    const MSG_RECURSO_INEXISTENTE = 'Recursos inexistente';

    const CODE_EN_CONSTRUCCION = 'Esta funcionalidad se encuentra en construcción';

    /**
     * Crea un nuevo portafolio
     * @method crearPortafolio
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param string $portafolio_descripcion Es el texto del portafolio a crear
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function crearPortafolio($usr_id, $login_token, $portafolio_descripcion, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "portafolio_descripcion" => $portafolio_descripcion
        );
        $this->uri = ApiCommunication::get_api_uri('URI_CREAR_PORTAFOLIO');
        return $this->callRestService();
    }

    /**
     * Actualizar un portafolio existente
     * @method updatePortafolio
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param string $portafolio_id
     * @param string $portafolio_descripcion
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function updatePortafolio($usr_id, $login_token, $portafolio_id, $portafolio_descripcion, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "portafolio_id" => $portafolio_id,
            "portafolio_descripcion" => $portafolio_descripcion
        );
        $this->uri = ApiCommunication::get_api_uri('URI_MODIFICAR_PORTAFOLIO');
        return $this->callRestService();
    }

    /**
     * Eliminar un portafolio existente
     * @method deletePortafolio
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param string $portafolio_id
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function deletePortafolio($usr_id, $login_token, $portafolio_id, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "portafolio_id" => $portafolio_id
        );
        $this->uri = ApiCommunication::get_api_uri('URI_ELIMINAR_PORTAFOLIO');
        return $this->callRestService();
    }

    /**
     * Obtiene los portafolios de un usuario
     * @method getPortafolio
     * 
     * @param string $usr_id Es el ID del usuario
     * @param integer $portafolio_id Es el ID del portafolio
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getPortafolio($usr_id, $portafolio_id, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "portafolio_id" => $portafolio_id
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_PORTAFOLIO');
        return $this->callRestService();
    }

    /**
     * Obtiene los portafolios de un usuario
     * @method getPortafoliosDeUsuario
     * 
     * @param string $usr_id Es el ID del usuario
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getPortafoliosDeUsuario($usr_id, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_PORTAFOLIOS_USUARIO');
        return $this->callRestService();
    }

    /**
     * Asigna un portafolio a uno o más recursos
     * @method insertarRecursosEnPortafolio
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param integer $portafolio_id Es el ID del portafolio
     * @param array $recursos Es un array de id's de recursos
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function insertarRecursosEnPortafolio($usr_id, $login_token, $portafolio_id, $recursos, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "portafolio_id" => $portafolio_id,
            "recursos" => $recursos
        );
        $this->uri = ApiCommunication::get_api_uri('URL_INSERTAR_RECURSOS_EN_PORTAFOLIO');
        return $this->callRestService();
    }

    /**
     * Quita un portafolio de uno o más recursos
     * @method quitarRecursosDePortafolio
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param string $login_token Es el token de login
     * @param integer $portafolio_id Es el ID del portafolio
     * @param array $recursos Es un array de id's de recursos
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function quitarRecursosDePortafolio($usr_id, $login_token, $portafolio_id, $recursos, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "login_token" => $login_token,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "portafolio_id" => $portafolio_id,
            "recursos" => $recursos
        );
        $this->uri = ApiCommunication::get_api_uri('URI_QUITAR_RECURSOS_DE_PORTAFOLIO');
        return $this->callRestService();
    }

    /**
     * Obtiene un detalle de los portafolios listados
     * @method getPortafoliosList
     * 
     * @param string $usr_id s el ID del usuario
     * @param array $portafolios_id Array de ids de portafolios ej: array(142, 122, 154)
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse devuelve los ids de los recursos que hay dentro del portafolio
     */
    public function getPortafoliosList($usr_id, $portafolios_id, $sitio_id = false)
    {
        $this->data = array(
            "usr_id" => $usr_id,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "portafolios" => $portafolios_id
        );
        $this->uri = ApiCommunication::get_api_uri('URL_LISTADO_PORTAFOLIOS');
        return $this->callRestService();
    }

    /**
     * Obtiene los portafolios de aquellos recursos recibidos
     * @method getPortafoliosDeRecursos
     * 
     * @param string $usr_id Es el nombre de usuario
     * @param array $recursos Es un array de id's de recursos
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse devuelve los ids de los recursos que hay dentro del portafolio
     */
    public function getPortafoliosDeRecursos($usr_id, $recursos, $sitio_id = false) {
        $this->data = array(
            "usr_id" => $usr_id,
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "recursos" => $recursos
        );
        $this->uri = ApiCommunication::get_api_uri('URL_OBTENER_PORTAFOLIOS_DE_RECURSOS');
        return $this->callRestService();   
    }

}
