<?php

namespace Edufw\cache;

/**
 * Interfaz para implementar sistemas de cache
 *
 * @name ICache
 * @package lib/components/cache
 * @version 20101217
 * @author gseip
 */
interface ICache {
    /**
     * Agrega una variable al cache con un identificador unico.
     * @param string $id ID Cache
     * @param mixed $data Datos a ser almacenados
     * @return bool True si OK
     */
    public function set($id, $data);

    /**
     * Recupera un valor del cache con su ID.
     * @param string|array $id Una clave identificando el cache o una lista de claves.
     * @return mixed El valor almacenado en cache. False caso contrario (o una expiracion).
     */
    public function get($id);

    /**
     * Remueve una variable almacenada en el cache
     * @param string $id Id de la variable
     * @return bool True si OK
     */
    public function del($id);

    /**
     * Limpia la cache de variables
     * @return bool True si OK
     */
    public function delAll();
}

?>
