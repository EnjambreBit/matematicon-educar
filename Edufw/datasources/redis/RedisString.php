<?php
namespace Edufw\datasources\redis;

/**
 * Clase para persistencia y acceso a claves de tipo String en Redis
 *
 * @author ainu
 */
class RedisString extends RedisObject{
    
    public $value;
    
    public function __construct($prefixKey = null, $idKey = null, $connection = null) {
        parent::__construct($prefixKey, $idKey, $connection);
    }
    /**
     * Obtiene el valor de la clave
     * @return string
     */
    public function get() {
        return self::getConn()->get($this->getKey());
    }
    /**
     * Elemina la clave y su valor
     */
    public function delete() {
        self::getConn()->delete($this->getKey());
    }
    /**
     * Guarda el valor en la clave
     * @return bool
     */
    public function save() {
        return self::getConn()->set($this->getKey(), $this->value);
    }
    /**
     * Setea el valor de la clave
     * @param string $value valor de la clave
     */
    public function set($value) {
        $this->value = $value;
    }
}

