<?php

namespace Edufw\datasources\redis;
/**
 * Clase para persistencia y acceso a listas en Redis
 * @author ainu, lmoya
 */
class RedisList extends RedisObject {
    
    public function __construct($prefixKey = null, $idKey = null, $connection = null) {
        parent::__construct($prefixKey, $idKey, $connection);
    }
    
    
    /**
     * Agrega un elemento al final de la lista (append)
     * @param String $value elemento a agragar a la lista
     * @return mixed devuelve un entero con la cantidad de elementos en la lista luego de la inserción, o bien FALSE en caso de fallo
     */ 
    public function rightPush($value){
        return self::getConn()->rPush($this->getKey(), $value . time());
    }
    
    
    /**
     * Obtener y quitar el ultimo elemento de la lista 
     * @return mixed devuelve un String con el elemento de la lista, o bien FALSE en caso de fallo
     */ 
    public function rightPop(){
        return self::getConn()->rPush($this->getKey());
    }
    
    
    /**
     * Agrega un elemento al principio de la lista (prepend)
     * @param String $value elemento a agragar a la lista
     * @return mixed devuelve un entero con la cantidad de elementos en la lista luego de la inserción, o bien FALSE en caso de fallo
     */ 
    public function leftPush($value){
        return self::getConn()->lPush($this->getKey(), $value);
    }
    
    
    /**
     * Obtiene y remueve el primer elemento de la lista
     * @return mixed devuelve un String con el elemento de la lista, o bien FALSE en caso de fallo
     */
    public function leftPop(){
        return self::getConn()->lPop($this->getKey());
    }
    
    /**
     * Obtiene la cantidad de elementos presentes en la lista
     * @return Int cantidad de elementos en la lista (FALSE en caso de fallo)
     */
    public function count(){
        return self::getConn()->lSize($this->getKey());
    }    
    
}

?>
