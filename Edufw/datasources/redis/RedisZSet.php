<?php

namespace Edufw\datasources\redis;
/**
 * Clase para persistencia y acceso a claves de tipo ZSet (Sorted Sets) en Redis
 *
 * @author ainu, lmoya
 */
class RedisZSet extends RedisObject {

    public function __construct($prefixKey = null, $idKey = null, $connection = null) {
        parent::__construct($prefixKey, $idKey, $connection);
    }
    
    public function memberExists($member_value){
        return (bool)  self::getConn()->zScore($this->getKey(), $member_value);
    }

    /**
     * Obtiene un rango de elementos del conjunto desde indice $start hasta indice $end ordenados por score ASC
     * @param Int $limit cantidad de elementos a retornar
     * @param Int $offset indice del primer elemento a retornar
     * @return boolean devuelve 1 en caso de exito y 0 si no se pudo agregar el elemento (si el elemento ya existe en el conjunto retorna 0)
     */    
    public function getMembersByIndexRange($limit, $offset = 0, $withscores=FALSE) {
        return self::getConn()->zRange($this->getKey(), $offset, $offset+$limit-1, $withscores);
    }
    
    /**
     * Obtiene un rango de elementos del conjunto desde indice $start hasta indice $end ordenados por score DESC
     * @param Int $limit cantidad de elementos a retornar
     * @param Int $offset indice del primer elemento a retornar
     * @return boolean devuelve 1 en caso de exito y 0 si no se pudo agregar el elemento (si el elemento ya existe en el conjunto retorna 0)
     */    
    public function getMembersByIndexReverseRange($limit, $offset = 0, $withscores=FALSE) {
        return self::getConn()->zRevRange($this->getKey(), $offset, $offset+$limit-1, $withscores);
    }
    
    /**
     * Obtiene un rango de elementos del conjunto con $minScore >= score <= $maxScore ordenados por score ASC
     * @param String $minScore score minimo del elemento a retornar, ejemplo '100'(mayor o igual a 100), '(100' (mayor a 100), '-inf' (menor score posible)
     * @param String $maxScore score maximo del elemento a retornar, ejemplo '100'(mayor o igual a 100), '(100' (mayor a 100), '+inf' (mayor score posible)
     * @param Array $options array asociativo opcion-valor todas las cuales son optativas, ejemplo:<br>
     * <ul>
     * <li>withscores => TRUE/FALSE</li>
     * <li>limit => array($offset, $count)</li>
     * </ul>
     * @return Array lista valores de los elementos del conjunto ordenado de acuerdo al score. si se selecciona la opción withscores el resultado se devuelve 
     * en forma de array asociativo con la forma 'valor' => 'score' 
     */    
    public function getMembersByScoreRange($minScore, $maxScore, $options = array()) {
        return self::getConn()->zRangeByScore($this->getKey(), $minScore, $maxScore, $options);
    }
    
    /**
     * Obtiene un rango de elementos del conjunto con $minScore >= score <= $maxScore ordenados por score DESC
     * @param String $minScore score minimo del elemento a retornar, ejemplo '100'(mayor o igual a 100), '(100' (mayor a 100), '-inf' (menor score posible)
     * @param String $maxScore score maximo del elemento a retornar, ejemplo '100'(mayor o igual a 100), '(100' (mayor a 100), '+inf' (mayor score posible)
     * @param Array $options array asociativo opcion-valor todas las cuales son optativas, ejemplo:<br>
     * <ul>
     * <li>withscores => TRUE/FALSE</li>
     * <li>limit => array($offset, $count)</li>
     * </ul>
     * @return Array lista valores de los elementos del conjunto ordenado de acuerdo al score. si se selecciona la opción withscores el resultado se devuelve 
     * en forma de array asociativo con la forma 'valor' => 'score' 
     */    
    public function getMembersByScoreReverseRange($minScore, $maxScore, $options = array()) {
        return self::getConn()->zRevRangeByScore($this->getKey(), $maxScore, $minScore, $options);
    }
    
    /**
     * Agrega un nuevo elemento al conjunto ordenado
     * @param Int $score valor a usar como criterio de orden en el conjunto
     * @param String $value elemento a agregar al conjunto
     * @return boolean devuelve 1 en caso de exito y 0 si no se pudo agregar el elemento (si el elemento ya existe en el conjunto retorna 0)
     */    
    public function addMember($score, $value) {
        return self::getConn()->zAdd($this->getKey(), (int)$score, $value);
    }
    
    /**
     * Elimina un elemento del conjunto ordenado
     * @param String $value elemento a quitar del conjunto ordenado
     * @return boolean devuelve 1 en caso de exito y 0 si no se pudo eliminar el elemento (si el elemento no existe en el conjunto retorna 0)
     */ 
    public function removeMember($value) {
        return self::getConn()->zDelete($this->getKey(), $value);
    }
    
    
    /**
     * Obtiene el numero total de elementos del conjunto ordenado
     * @return  Int numero de elementos de conjunto
     */ 
    public function countMembers() {
        return self::getConn()->zSize($this->getKey());
    }
    
    
    /**
     * Obtiene el numero de elementos del conjunto ordenado cuyo score esta entre $minScore y $maxScore
     * @param String $minScore score minimo del elemento a retornar, ejemplo '100'(mayor o igual a 100), '(100' (mayor a 100), '-inf' (menor score posible)
     * @param String $maxScore score maximo del elemento a retornar, ejemplo '100'(mayor o igual a 100), '(100' (mayor a 100), '+inf' (mayor score posible)
     * @return  Int numero de elementos del subconjunto
     */ 
    public function countMembersInScoreRange($minScore, $maxScore) {
        return self::getConn()->zCount($this->getKey(),$minScore,$maxScore);
    }
    
    /**
     * Incrementa el score de un elemento del conjunto, si el elemento no existe se crea con un valor inicial de cero antes de incrementarlo
     * @param String $member elemento del conjunto cuyo score se quiere incrementar
     * @param Int $value valor a ser sumado al score del elemento
     * @return  Int el nuevo score del elemento
     */ 
    public function incrementScore($member, $value) {
        return self::getConn()->zIncrBy($this->getKey(), $value, $member);
    }
}
?>
