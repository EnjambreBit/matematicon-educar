<?php

namespace Edufw\datasources\redis;
/**
 * Clase para persistencia y acceso a claves de tipo Set en Redis
 *
 * @author ainu, lmoya
 */
class RedisSet extends RedisObject {

    public function __construct($prefixKey = null, $idKey = null, $connection = null) {
        parent::__construct($prefixKey, $idKey, $connection);
    }

    /**
     * Obtiene una lista de elementos presentes en el conjunto
     * @return Array miembros del conjunto
     */
    public function getMembers() {
        return self::getConn()->smembers($this->getKey());
    }

    /**
     * Obtiene una lista de elementos del conjunto que no estan presentes en ninguno de los conjuntos recibidos en $sest
     * @param Array $sets lista de keys de conjuntos a restar
     * @return Array lista de elementos presentes en el conjunto que no se encuentran en ninguno de los conjuntos recibidos
     */    
    public function getMembersNotIn($sets) {
        $args = array($this->getKey());
        foreach ($sets as $value) {
            $args[] = $value;
        }
        return call_user_func_array(array(self::getConn(), 'sDiff'), $args);
    }

    /**
     * Obtiene una lista de elementos del conjunto que estén presentes tambien en el conjunto $set
     * @param String $set clave del conjunto
     * @return Array lista de elementos presentes en el conjunto y en $set
     */
    public function getMembersIn($set) {
        return $this->getMembersInAll(array($set));
    }

    /**
     * Obtiene una lista de elementos del conjunto que estén presentes en todos los conjuntos recibidos en $sets
     * Es decir, se obtiene una lista con la intersección entre el conjunto de partida y los conjuntos recibidos.
     * @param Array $sets keys de los conjuntos
     * @return Array lista de elementos presentes en el conjunto y en todos los conjuntos recibidos
     */
    public function getMembersInAll($sets) {
        $args = array($this->getKey());
        foreach ($sets as $value) {
            $args[] = $value;
        }
        return call_user_func_array(array(self::getConn(), 'sInter'), $args);
    }

    /**
     * Obtiene una lista de elementos del conjunto que estén presentes en alguno de los conjuntos recibidos en $sets
     * Es decir, se obtiene una lista con la intersección entre el conjunto de partida y la union de los conjuntos presentes en $sets
     * @param Array $sets keys de los conjuntos
     * @return Array lista de elementos presentes en el conjunto y en alguno los conjuntos recibidos
     */
    public function getMembersInAny($sets) {
        $temp_union_key = $this->getKey() . ':get:members:in:any';
        $args = array($temp_union_key);
        foreach ($sets as $value) {
            $args[] = $value;
        }
        self::getConn()->multi();
        call_user_func_array(array(self::getConn(), 'sUnionStore'), $args);
        self::getConn()->sInter($this->getKey(), $temp_union_key);
        self::getConn()->delete($temp_union_key);
        $result = self::getConn()->exec();
        return $result[1];
    }

    /**
     * Agrega un nuevo elemento al conjunto
     * @param String $value elemento a agregar al conjunto
     * @return boolean devuelve 1 en caso de exito y 0 si no se pudo agregar el elemento (si el elemento ya existe en el conjunto retorna 0)
     */    
    public function addMember($value) {
        return self::getConn()->sAdd($this->getKey(), $value);
    }

    /**
     * Elimina un elemento del conjunto
     * @param String $value elemento a quitar del conjunto
     * @return boolean devuelve 1 en caso de exito y 0 si no se pudo eliminar el elemento (si el elemento no existe en el conjunto retorna 0)
     */ 
    public function removeMember($value) {
        return self::getConn()->sRem($this->getKey(), $value);
    }

    /**
     * Elimina el conjunto completo
     * @return boolean devuelve 1 en caso de exito y 0 si no se pudo eliminar el conjunto
     */ 
    public function delete() {
        return self::getConn()->delete($this->getKey());
    }

}

