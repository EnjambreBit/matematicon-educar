<?php
namespace Edufw\sessions;

/**
 * Gestiona sesiones de usuario
 * 
 * @name ESession
 * @package lib/components
 * @version 20100201
 * @author gseip
 */
class ESession {
    protected $_namespace = "Default"; //Nombre de la session

    /**
     * Constructor - retorna una instancia de objeto de la sesion nombrada por el nombre en su espacio de nombres
     * @param string $namespace - Nombre del espacio de nombres
     * @param string $sessionId - Opcional, indica id de session
     * @return void
     * <code>
     * $session = new ESession('MiSitio')
     * </code]
     */
    public function __construct($namespace = 'Default', $sessionId = null) {
        if ($namespace === '')   throw new Exception('El nombre del espacio de nombres no debe ser una cadena vacia.');
        if ($namespace[0] == "_" || preg_match('#(^[0-9])#i', $namespace[0]))
            throw new Exception('El nombre del espacio de nombres no debe empezar con guion bajo o con numero.');
        $this->_namespace = $namespace;
        $this->start();
        if ($sessionId != null) $this->setId($sessionId);
        $id = $this->getId();
        echo "";
    }

    public static function instance($namespace = 'Default') {
        $instance = new ESession($namespace);
        $instance->start();
        return $instance;
    }

    /**
     * Inicia una session
     * @return void
     */
    public function start() {
        if (!isset($_SESSION)) session_start(); //Inicia session. Disponemos de $_SESSION
        $_SESSION[$this->_namespace]['session_id'] = session_id();
    }

    /**
     * Chequea si la session fue iniciada
     * @return boolean
     */
    public static function isStarted() {
        if (isset($_SESSION))  return true;
        return false;
    }

    /**
     * Destruye todos los datos de la session
     */
    public function destroy($fullDestroy=FALSE) {
        if (!$this->isStarted()) throw new Exception("Session no iniciada.");
        if (isset($_SESSION[$this->_namespace])) unset($_SESSION[$this->_namespace]);
        if ($fullDestroy)
            session_destroy();
    }

    /**
     * Obtiene el ID de la session
     */
    public function getId() {
        if (!isset($_SESSION))   throw new Exception("Session no iniciada");
        return $_SESSION[$this->_namespace]['session_id'];
    }

    /**
     * Establece el ID de la session
     */
    public function setId($id) {
        if (isset($_SESSION)) throw new Exception("Session se encuentra iniciada.");
        if (!is_string($id) || $id === '')     throw new Exception("Session id debe ser una cadena y no puede ser vacia.");
        session_id($id);
    }

    /**
     * Obtiene variable del espacio de nombres por referencia
     * @param string $name Si la variable no existe retorna null
     * @return mixed
     */
    public function &__get($name) { //METODO MAGICO PHP5 El & soluciona un bug en la sobrecarga de arrays (Overloaded array properties)
        if ($name === '') throw new Exception("Nombre de la clave no debe estar vacio");
        if (!$this->isStarted()) throw new Exception("Session no iniciada");
        $name = (string)$name;
        if (!isset($_SESSION[$this->_namespace][$name])) {
            $null = NULL;
            return $null;//NULL no es una referencia valida. Por lo tanto, hay que asignar NULL a una variable
        }
        return $_SESSION[$this->_namespace][$name];
    }

    /**
     * Establece una variable dentro de una session
     * @param string $name Nombre de la clave
     * @param mixed $value Valor para la clave
     */
    public function __set($name, $value) {
        if ($name === "")   throw new Exception("Nombre de la clave no debe estar vacio");
        if (!$this->isStarted()) throw new Exception("Session no iniciada");
        $name = (string)$name;
        $_SESSION[$this->_namespace][$name] = $value;
    }

    public function __isset($name) {
        return isset ($_SESSION[$this->_namespace][$name]);
    }

    /**
     * Elimina un espacio de nombres dentro de una session,
     * o alguna variable dentro del espacio de nombres
     * @param string $name Solo elimina la variable dentro del espacio de nombres
     */
    public function namespaceUnset($name = null) {
        if (!$this->isStarted()) throw new Exception("Session no iniciada");
        if (empty($name)) {
            unset($_SESSION[$this->_namespace]);
        } else {
            unset($_SESSION[$this->_namespace][$name]);
        }
    }
}