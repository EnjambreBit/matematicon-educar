<?php

namespace Edufw\cache;

use Edufw\cache\ICache;

/**
 * Clase que hace uso de un sistema de cache.
 * Esta version implementa uso de Memcached.
 * Nota: De los dos clientes php, Memcached es una version mas nueva que
 * Memcache.
 *
 * @version 20140505
 * @author Gustavo Seip
 */
class MemcachedCache implements ICache {
    private $_memcached;

    public function __construct() {
        $this->_memcached = new Memcached();
        $this->_memcached->addServers(EWebApp::conf()->CACHE['MemcachedCache']['DEFAULT']);
    }


    public function setConfig($config) {
        if (!isset($config)) return FALSE;
       $this->_memcached = new Memcached();
       $this->_memcached->addServers($config);
    }

    /**
     * Agrega una variable al cache con un identificador unico.
     * Nota: No utilizamos compresion al vuelo del dato a guardar
     * @param string $id ID Cache
     * @param mixed $data Datos a ser almacenados
     * @return bool True si OK
     */
    public function set($id, $data, $ttl=0) {
        return $this->_memcached->set($id,$data,$ttl);
    }

    /**
     * Recupera un valor del cache con su ID.
     * @param string|array $id Una clave identificando el cache o una lista de claves.
     * @return mixed El valor almacenado en cache. False caso contrario (o una expiracion).
     */
    public function get($id) {
        return $this->_memcached->get($id);
    }

    /**
     * Remueve una variable almacenada en el cache
     * @param string $id Id de la variable
     * @return bool True si OK
     */
    public function del($id) {
        return $this->_memcached->delete($id);
    }

    /**
     * Limpia la cache de variables
     * Nota: Memcache no permite luego de esta operacion, escribir en el cache
     * por un periodo de tiempo (comunmente <= 1seg)
     * @return bool True si OK
     */
    public function delAll() {
        return $this->_memcached->flush();
    }
        }
?>
