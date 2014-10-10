<?php
namespace Edufw\security;

use Edufw\sessions\ESession;

/**
 * Autenticacion de usuarios (Version para bases de datos)
 * @author gseip
 * @version 20100201
 */
class EAuth {
    protected $appSession;
    protected $appName;
    protected $authData;
    protected $isValid = false;
    public $_username;
    public $_rol;

    public function __construct($appName) {
        $this->appName = $appName;
    }

    /**
     * Inicia el componente EAuth
     */
    public function start() {
        $this->appSession = new ESession($this->appName);
        $this->validate();
    }

    /**
     * Finaliza el componente EAuth
     */
    public function finalize() {
        if ($this->appSession->isStarted())
            $this->appSession->destroy();
    }

    /**
     * Establece los datos de usuario para EAuth
     * @param <type> $username Nombre de usuario
     * @param <type> $rol Nombre del rol
     */
    public function setData($username, $rol=NULL) {
        $this->appSession->authData = array();
        $this->appSession->authData['_username'] = $username;
        $this->appSession->authData['_rol'] = $rol;
        $this->appSession->authData['_time'] = time();
        $this->appSession->authData['_initialized'] = true;
    }

    public function addData($key, $data) {
        $this->appSession->authData[$key] = $data;
    }

    /**
     * Valida datos de autenticacion de usuario (Implementamos version minima)
     * @see http://phpsec.org/projects/guide/4.html
     * @see http://www.serversidemagazine.com/php/session-hijacking
     * @return <Boolean>
     */
    public function validate() {
        if (isset ($this->appSession->authData))
            if ($this->appSession->authData['_initialized'] && isset($this->appSession->authData['_username'])) {
                $this->_time = time();
                $this->_username = $this->appSession->authData['_username'];
                $this->_rol = $this->appSession->authData['_rol'];
                $this->isValid = true;
                return;
        }
        $this->isValid = false;
    }

    public function isValid() {
        return $this->isValid;
    }

    ////////////////// Magic ////////////////////
    public function  __set($name,  $value) {
        if (isset ($this->appSession->authData)) {
            $this->appSession->authData[$name] = $value;
            return TRUE;
        }
        return FALSE;
    }
    public function  __get($name) {
        if (isset ($this->appSession->authData))
            return $this->appSession->authData[$name];
        return NULL;
    }

    public function __isset($name) {
        if(isset ($this->appSession->authData)){
            $authData = $this->appSession->authData;
            return isset ($authData[$name]);
        }
        return false;
    }

}
