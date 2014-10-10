<?php
namespace Edufw\datasources\redis;

use Edufw\core\EException;
use Edufw\datasources\redis\RedisConnection;
use Redis;

/**
 *
 * @author ainu, lmoya
 * 
 * @method bool exists(string $key) Chequea la existencia de una clave
 * @method string get(string $key) Obtiene el valor de una clave
 * @method mixed hSet(string $key, string $hashKey, mixed $value) Adds a value to the hash stored at key. If this value is already in the hash, FALSE is returned.
 * @method mixed hGet() Gets a value from the hash stored at key. If the hash table doesn't exist, or the key doesn't exist, FALSE is returned.
 * @method array hGetAll(string $key) Returns the whole hash, as an array of strings indexed by strings.
 * @method bool multi() Abre un Statement para hacer multiples peticiones
 * @method array exec() Ejecuta el Statement
 */
class RedisObject {
        
    protected $prefixKey;
    protected $idKey;
    
    private static $connection;
    /**
     * @param string $prefixKey prefijo para namespace de claves
     * @param mixed $idKey clave para el objeto, puede ser un int o string
     * @param RedisConnection $connection objeto de conexion a la base de datos redis
     */
    public function __construct($prefixKey=null, $idKey=null, $connection=null) {
        self::$connection = isset($connection) ? $connection : self::createConn();
        $this->prefixKey = isset($prefixKey) ? $prefixKey : '';
        $this->idKey = isset($idKey) ? $idKey : $this->generateSerialID();
    }
    
    public function __destruct()
    {
      //TODO: REVISAR PORQUE SE CIERRA LA CONEXION
      //self::getConn()->close();
      //self::$connection = null;
    }

    /**
     * Obtiene la conexion a redis
     * @return \Redis
     */
    public static function getConn()
    {
      return isset(self::$connection) ? self::$connection : self::createConn();
    }
    /**
     * Genera un ID Serial para el namespace del prefijo
     * @return int ID de la ultima clave creada
     * @throws EException Falla la creacion o la coneccion
     */
    protected function generateSerialID(){
        $conn = self::getConn();
        if (!isset($this->prefixKey)) 
            throw new EException('[RedisObject, __generateSerialID()] No se espesificó el prefijo para la creación de la clave');
        if (!isset($conn))
            throw new EException('[RedisObject, __generateSerialID()] No se definio nunguna conexón con redis');
        return $conn->incr($this->prefixKey);
    }
    /**
     * Clave actual. Prefijo + clave
     * @return string
     */
    public function getKey(){
        return $this->prefixKey . $this->idKey;
    }
    /**
     * Obtiene el ID clave del objecto
     * @return mixed
     */
    public function getId(){
        return $this->idKey;
    }
    
    public function __call($name, $arguments) {
      
        return call_user_func_array(array(self::getConn(), $name), $arguments);
    }
    /**
     * Objecto de coneccion a Redis
     * @return Redis
     */
    private static function createConn()
    {
      $redisConnection = new RedisConnection();
      return $redisConnection->getConnection();
    }
}