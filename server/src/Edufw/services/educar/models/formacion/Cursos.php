<?php

namespace Edufw\services\educar\models\formacion;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo Cursos
 *
 * Servicios referidos a la obtención de cursos
 *
 * @version 20121120
 * @author pgambetta
 */
class Cursos extends RestModel {
    //CODIGOS

    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;
    const CODE_CURSO_INEXISTENTE = 2;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_CURSO_INEXISTENTE = 'Curso inexistente';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';

    /**
     * Permite obtener un listado de cursos segun el tipo
     *
     * @param string $curso_estado Es el estado del curso (ESTADOS POSIBLES: activo, inactivo, todos)
     *
     * @param array $data Es el array con los datos a ser enviados
     *
     * Forma del array:
     * limit [OPCIONAL] Es el límite de cursos a obtener
     * offset [OPCIONAL] Es el offset de cursos a obtener
     * categorias [OPCIONAL] Es un array de índices de categorías (filtro)
     * modalidades [OPCIONAL] Es un array de índices de modalidades (filtro)
     * programas [OPCIONAL] Es un array de índices de programas (filtro)
     * perfiles [OPCIONAL] Es un array de índices de perfiles (filtro)
     * audiencia [OPCIONAL] Es un id de audiencia o array de id's de audiencias (filtro)
     * curso_estado {int} 0 o 1, Establece el estado de los cursos a obtener
     * 
     * sort_column [OPCIONAL] Es el nombre de la columna por el cuál se desea ordenar
     * get_total [OPCIONAL] Default FALSE, Establece si obtener o nó el total de cursos
     *
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function searchCursos($sitio_id = false, $data = array()) {
        try {
            $this->data = array(
                "sitio_id" => $sitio_id !== false ? $sitio_id : ApiCommunication::$sitio_id,
                "curso_estado" => $data['curso_estado']
            );

            if(isset($data['limit']) && $data['limit'] !== false){
                $this->data['limit'] = $data['limit'];
            }

            if(isset($data['offset']) && $data['offset'] !== false){
                $this->data['offset'] = $data['offset'];
            }

            if(isset($data['categorias']) && $data['categorias'] !== false){
                $this->data['categorias'] = $data['categorias'];
            }

            if(isset($data['modalidades']) && $data['modalidades'] !== false){
                $this->data['modalidades'] = $data['modalidades'];
            }

            if(isset($data['programas']) && $data['programas'] !== false){
                $this->data['programas'] = $data['programas'];
            }

            if(isset($data['perfiles']) && $data['perfiles'] !== false){
                $this->data['perfiles'] = $data['perfiles'];
            }

            if(isset($data['audiencia']) && $data['audiencia'] !== false){
                $this->data['audiencia'] = $data['audiencia'];
            }

            if(isset($data['sort_column']) && $data['sort_column'] !== false){
                $this->data['sort_column'] = $data['sort_column'];
            }

            if(isset($data['get_totalFound']) && $data['get_totalFound'] !== false){
                $this->data['get_totalFound'] = $data['get_totalFound'];
            }

            if(isset($data['visibles']) && $data['visibles'] !== false){
                $this->data['visibles'] = $data['visibles'];
            }

            $this->uri = $this->global_config['URL_OBTENER_SEARCH_CURSOS'];
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Cursos/getListLiteCursos] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }

    /**
     * Obtiene todos los datos de un curso
     *
     * @param integer $curso_id Es el ID del curso a obtener
     * @param integer $sitio_id [opcional]
     *
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function getCursoFull($curso_id, $sitio_id = false) {
        try {
            $this->data = array(
                "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
                "curso_id" => $curso_id
            );

            $this->uri = $this->global_config['URL_OBTENER_CURSO_FULL'];
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Cursos/getCursoFull] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }

    /**
     * Obtiene los datos básicos de un cursos
     *
     * @param integer $curso_id Es el ID del curso a obtener
     * @param integer $sitio_id [opcional]
     *
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function getCursoLite($curso_id, $sitio_id = false) {
        try {
            $this->data = array(
                "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
                "curso_id" => $curso_id
            );

            $this->uri = $this->global_config['URL_OBTENER_CURSO_LITE'];
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Cursos/getCursoLite] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }

    public function getEdicionesActivasDeCurso($curso_id, $sitio_id = false){
        try {
            $this->data = array(
                "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
                "curso_id" => $curso_id
            );

            $this->uri = $this->global_config['URL_OBTENER_EDICION_ACTIVAS_CURSO'];
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Cursos/getEdicionesActivasDeCurso] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }

    /**
     * @param int $sitio_id
     * @param array $data Es el array con los datos a ser enviados
     *
     * Forma del array:
     * modalidades [OPCIONAL] Es un array de índices de modalidades (filtro)
     * programas [OPCIONAL] Es un array de índices de programas (filtro)
     * perfiles [OPCIONAL] Es un array de índices de perfiles (filtro)
     * audiencia [OPCIONAL] Es un id de audiencia o array de id's de audiencias (filtro)
     * curso_estado [OPCIONAL] {int} 0 o 1, Establece el estado de los cursos a obtener
     */
    public function getCategoriasDeCursos($sitio_id = false, $data = array()){
        try {
            $this->data = array("sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id);
            if(isset($data['perfiles']) && !empty($data['perfiles'])){
                $this->data['perfiles'] = $data['perfiles'];
            }
            if(isset($data['modalidades']) && !empty($data['modalidades'])){
                $this->data['modalidades'] = $data['modalidades'];
            }
            if(isset($data['curso_estado'])){
                $this->data['curso_estado'] = $data['curso_estado'];
            }
            if(isset($data['programas']) && !empty($data['programas'])){
                $this->data['programas'] = $data['programas'];
            }
            if(isset($data['audiencia']) && !empty($data['audiencia'])){
                $this->data['audiencia'] = $data['audiencia'];
            }
            if(isset($data['visibles'])){
                $this->data['visibles'] = $data['visibles'];
            }
            
            $this->uri = $this->global_config['URL_OBTENER_CATEGORIAS_CURSOS'];
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Cursos/getCategoriasDeCursos] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }

    /**
     * Obtiene las inscipciones de un usuario
     *
     * @param string $login_token Es el token de login del usuario logueado en el sitio
     * @param string $usr_id Es el ID del usuario
     * @param string $type Es el tipo de ID (dni, id) por ahora solo "id"
     * @param integer $sitio_id [opcional]
     *
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function getInscripciones($login_token, $usr_id, $type, $sitio_id = false) {
        try {
            $type = 'id';
            $this->data = array(
                "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
                "login_token" => $login_token,
                "usr_id" => $usr_id,
                "type" => $type
            );

            $this->uri = $this->global_config['URL_OBTENER_INSCRIPCIONES_USUARIO'];
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Cursos/getInscripciones] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }

    /**
     * Obtiene los datos del certificado deseado
     *
     * @param string $login_token Es el token de login del usuario logueado en el sitio
     * @param string $usr_id Es el ID del usuario
     * @param string $edicion_id Es el ID de la edicion
     * @param string $cur_id Es el ID del curso
     * @param integer $sitio_id [opcional]
     *
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function getCertificado($login_token, $usr_id, $edicion_id, $cur_id, $sitio_id = false) {
        try {
            $type = 'id';
            $this->data = array(
                "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
                "login_token" => $login_token,
                "usr_id" => $usr_id,
                "edicion_id" => $edicion_id,
                "cur_id" => $cur_id
            );

            $this->uri = $this->global_config['URL_OBTENER_CERTIFICADO'];
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Cursos/getCertificado] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }

    /**
     * Obtiene las solicitudes aprobadas de un usuario
     *
     * @param string $login_token Es el token de login del usuario logueado en el sitio
     * @param string $usr_id Es el ID del usuario
     * @param integer $sitio_id [opcional]
     *
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function getSolicitudAprobada($login_token, $usr_id, $sitio_id = false) {
        try {
            $this->data = array(
                "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
                "login_token" => $login_token,
                "usr_id" => $usr_id
            );

            $this->uri = $this->global_config['URL_OBTENER_SOLICITUD'];
            return $this->callRestService();
        } catch (Exception $e) {
            ELogger::log('[Cursos/getSolicitudAprobada] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }

        return array();
    }

}