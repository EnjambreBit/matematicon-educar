<?php

namespace Edufw\cache;

use Edufw\cache\ICache;

/**
 * Clase que hace uso de un sistema de cache. Esta version implementa uso
 * de APC.
 * 
 * @version 20140505
 * @author Gustavo Seip
 */
class ApcCache implements ICache {
    /**
     * Agrega una variable al cache con un identificador unico.
     * @param string $id ID Cache
     * @param mixed $data Datos a ser almacenados
     * @return bool True si OK
     */
    public function set($id, $data, $ttl=0) {
        return apc_store($id, $data, $ttl);
    }

    /**
     * Recupera un valor del cache con su ID.
     * @param string|array $id Una clave identificando el cache o una lista de claves.
     * @return mixed El valor almacenado en cache. False caso contrario (o una expiracion).
     */
    public function get($id) {
        return apc_fetch($id);
    }

    /**
     * Remueve una variable almacenada en el cache
     * @param string $id Id de la variable
     * @return bool True si OK
     */
    public function del($id) {
        return apc_delete($id);
    }

    /**
     * Limpia la cache de variables
     * @return bool True si OK
     */
    public function delAll() {
        return apc_clear_cache('user'); //user es el tipo de cache
    }
}

?>
