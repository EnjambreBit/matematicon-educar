<?php
namespace Edufw\lib\components;

/**
 * Clase que hace uso de un sistema de cache.
 * Esta version usa como adaptador, APC.
 * Otros sistemas de cache son EAccelerator, XCache, Memcached
 *
 * @name ECache
 * @package lib/components
 * @version 20100708
 * @author gseip
 */
class ECache {
    private $adapter;
    private $active;

    public function __construct($adapter_name=null, $class_model=NULL) {
        $this->adapter = EWebApp::loadComponent(isset ($adapter_name)?"cache/$adapter_name":'cache/ApcCache', TRUE);
        if (isset($adapter_name,$class_model)) {   $this->adapter->setConfig(EWebApp::conf()->CACHE[$adapter_name][$class_model]);   }
        $this->active = EWebApp::conf()->APP_CACHE_ACTIVO;
    }

    /**
     * Agrega una variable al cache con un identificador unico.
     * @param string $id ID Cache
     * @param mixed $data Datos a ser almacenados
     * @param integer $ttl Datos a ser almacenados
     * @return bool True si OK
     */
    public function set($id, $data, $ttl=0) {
        return ($this->active ? $this->adapter->set($id, $data, $ttl) : FALSE);
    }

    /**
     * Recupera un valor del cache con su ID.
     * @param string|array $id Una clave identificando el cache o una lista de claves.
     * @return mixed El valor almacenado en cache. FALSE si esta inactivo el cache. FALSE caso contrario.
     */
    public function get($id) {
        return ($this->active ? $this->adapter->get($id) : FALSE);
    }

    /**
     * Remueve una variable almacenada en el cache
     * @param string $id Id de la variable
     * @return bool True si OK
     */
    public function del($id) {
        return ($this->active ? $this->adapter->del($id) : FALSE);
    }

    /**
     * Limpia la cache de variables
     * @return bool True si OK
     */
    public function delAll() {
        return ($this->active ? $this->adapter->delAll() : FALSE);
    }

    /**
     * Retorna si esta activo o no el cache
     * @return <boolean>
     */
    public function isActive() {
        return $this->active;
    }

    public function getAdapter() {
        return $this->adapter;
    }
}

?>
