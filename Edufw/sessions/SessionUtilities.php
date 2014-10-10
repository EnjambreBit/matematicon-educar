<?php

namespace Edufw\sessions;

use Edufw\security\EAuth;
use Edufw\core\EWebApp;

/**
 * Clase que posee los métodos necesarios para consultar, insertar y quitar usuarios en sesión
 *
 * @author pgambetta
 * @version 20120427
 */
class SessionUtilities {

    /**
     * @property <string> $session_name Nombre de la sesión
     */
    private $session_name;
    
    /**
     * @property <string> $user_name Nombre del usuario al que se le desea realizar la acción
     */
    private $user_name;
    
    /**
     * Instancia de la clase EAuth
     *
     * @property EAuth $_auth
     */
    protected $_auth; //Componente de autenticacion
    
    /**
    * Constructor de la clase SessionUtilities, puede o no recibir parámetros.
    *
    * @param <string> $user_name ID del usuario
    * @param <string> $session_name Nombre que tendrá la sesión, en caso de no pasar ningún nombre se utiliza uno por default.
    */
    public function __construct($user_name=null, $session_name=null) {
        $this->session_name = $session_name === null ? EWebApp::config()->APP_MAIN_AUTH_NAME : $session_name;
        $this->user_name = $user_name;
        $this->_auth = new EAuth($this->session_name);
        $this->_data['baseurl'] = EWebApp::config()->APP_URL;
    }
    	
    /**
    * Inyecta un usuario en sesión
    */
    public function putUserInSession(){
        $this->_auth->start();
        $this->_auth->setData($this->user_name);
    }

    /**
    * Inyecta datos de un usuario en su sesión
    *
    * @param <array> $data Array con los datos a ser guardados, se almacenan (clave=>valor) donde clave es el indice del array
    */
    public function addData($data){
        $this->_auth->start();
        if(!$this->_auth->isValid()){
            $this->_auth->setData('adding_data');
        }
        foreach ($data as $key => $value) {
            $this->_auth->addData($key, $value);
        }
    }

    /**
    * Analiza la existencia de un usuario en sesión y lo retorna si es así.
    * 
    * @return false en caso de que no exista la sesión
    */
    public function isInSession(){
        $this->_auth->start();
        // Si se validó al usuario
        if ($this->_auth->isValid()) {
            return  $this->_auth->_username;
        }
        // Si falló --- SE ELIMINA LA SESION ---
        $this->_auth->finalize();
        // Si falló
        return false;
    }

    /**
    * Analiza la existencia de cierta información en la sesión actual.
    *
    * @param <string> La información a ser buscada
    *
    * @return <bool>
    */
    public function issetData($data){
        $this->_auth->start();
        return isset ($this->_auth->$data);
    }

    /*
    * Método que retorna lo pedido en caso de que exista
    *
    * @param <string> $variable Nombre de la variable que se desea obtener (username|AclRules)
    *
    * @return $variable en caso de éxito y false en caso contrario
    */
    public function getSessionData($variable){
        $this->_auth->start();
        if ($this->_auth->isValid()) {
            return  $this->_auth->$variable;
        }
        return false;
    }

    /**
    * Web logout al sistema
    */
    public function logout() {
        $this->_auth->start();
        $this->_auth->finalize();
        $this->_data['deslog']=true;
    }

}
