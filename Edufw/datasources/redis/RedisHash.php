<?php
namespace Edufw\datasources\redis;
/**
 * Clase para persistencia y acceso a claves de tipo Hash en Redis
 *
 * @author ainu, lmoya
 * 
 * @method mixed hSet(string $key, string $hashKey, mixed $value) Adds a value to the hash stored at key. If this value is already in the hash, FALSE is returned.
 * @method mixed hGet() Gets a value from the hash stored at key. If the hash table doesn't exist, or the key doesn't exist, FALSE is returned.
 */
class RedisHash extends RedisObject{
    
    public $hashValues;
    
    public function __construct($prefixKey = null, $idKey = null, $connection = null) {
        parent::__construct($prefixKey, $idKey, $connection);
    }

    public function get() {
        return self::getConn()->hGetAll($this->getKey());
    }
    
    public function delete(){
        return self::getConn()->delete($this->getKey());
    }
    
    public function save($multi = true) {
        if ($multi)
            self::getConn()->multi();
        foreach ($this->hashValues as $key => $value) {
            //TODO: revisar si vale la pena hacer un hMSet
            self::getConn()->hSet($this->getKey(), $key, $value);
        }
        if ($multi)
            return self::getConn()->exec();
    }
    
    public function set($key, $value) {
        $this->hashValues[$key] = $value;
    }
}