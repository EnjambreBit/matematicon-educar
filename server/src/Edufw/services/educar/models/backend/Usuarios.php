<?php

namespace Edufw\services\educar\models\backend;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo Usuarios
 *
 * Servicios referidos a la obtención de usuarios para backend
 * 
 * @version 20121015
 * @author pgambetta
 */
class Usuarios extends RestModel {
    
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;
    const CODE_ROLES_INEXISTENTES = 2;
    const CODE_REGLAS_INEXISTENTES = 2;
  
    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_ROLES_INEXISTENTES = 'El usuario no tiene roles';
    const MSG_REGLAS_INEXISTENTES = 'El usuario no tiene reglas';
    
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';
    
    /**
     * Permite obtener los roles de un usuario
     * 
     * @param string $user Es el ID del usuario
     * @param string $login_token Es el token recibido por el login
     * @param integer $sitio_id [opcional]
     * 
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function getRoles($user, $login_token, $sitio_id = false) {
        try{
            $this->data = array(
                "sitio_id" => $sitio_id ==false ? ApiCommunication::$sitio_id : $sitio_id,
                "usr_id" => $user,
                "login_token" => $login_token
            );
        $this->uri = $this->global_config['URL_BACKEND_USUARIOS_OBTENER_ROLES'];
        return $this->callRestService();
        } catch (Exception $e){
            ELogger::log('[Bloques/getBloquesDeSitio] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }
        
        return array();
    }
    
    /**
     * Permite obtener las reglas de un usuario
     * 
     * @param string $user Es el ID del usuario
     * @param string $login_token Es el token recibido por el login
     * @param integer $sitio_id [opcional]
     * 
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function getReglas($user, $login_token, $sitio_id = false) {
        try{
            $this->data = array(
                "sitio_id" => $sitio_id ==false ? ApiCommunication::$sitio_id : $sitio_id,
                "usr_id" => $user,
                "login_token" => $login_token
            );
        $this->uri = $this->global_config['URL_BACKEND_USUARIOS_OBTENER_REGLAS'];
        return $this->callRestService();
        } catch (Exception $e){
            ELogger::log('[Bloques/getBloquesDeSitio] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }
        
        return array();
    }
    
    /**
     * Permite saber si el usuario tiene o no una regla
     * 
     * @param string $user Es el ID del usuario
     * @param string $login_token Es el token recibido por el login
     * @param string $regla_nombre Es el nombre de la regla que queremos saber si el usuario tiene
     * @param integer $sitio_id [opcional]
     * 
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function tieneRegla($user, $login_token, $regla_nombre, $sitio_id = false) {
        try{
            $this->data = array(
                "sitio_id" => $sitio_id ==false ? ApiCommunication::$sitio_id : $sitio_id,
                "usr_id" => $user,
                "login_token" => $login_token,
                "regla_nombre" => $regla_nombre
            );
        $this->uri = $this->global_config['URL_BACKEND_USUARIOS_TIENE_REGLA'];
        return $this->callRestService();
        } catch (Exception $e){
            ELogger::log('[Bloques/getBloquesDeSitio] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }
        
        return array();
    }

    /**
     * Permite saber si el usuario tiene o no un rol
     * 
     * @param string $user Es el ID del usuario
     * @param string $login_token Es el token recibido por el login
     * @param string $rol_nombre Es el nombre del rol que queremos saber si el usuario tiene
     * @param integer $sitio_id [opcional]
     * 
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function tieneRol($user, $login_token, $rol_nombre, $sitio_id = false) {
        try{
            $this->data = array(
                "sitio_id" => $sitio_id ==false ? ApiCommunication::$sitio_id : $sitio_id,
                "usr_id" => $user,
                "login_token" => $login_token,
                "rol_nombre" => $rol_nombre
            );
        $this->uri = $this->global_config['URL_BACKEND_USUARIOS_TIENE_ROL'];
        return $this->callRestService();
        } catch (Exception $e){
            ELogger::log('[Bloques/getBloquesDeSitio] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }
        
        return array();
    }
}

