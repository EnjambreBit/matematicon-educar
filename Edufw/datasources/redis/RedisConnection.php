<?php
namespace Edufw\datasources\redis;
use Edufw\core\EWebApp;
use Edufw\core\EException;
use Redis;

/**
 * Description of RedisConnection
 *
 * @author ainu, lmoya
 */
class RedisConnection {
    /**
     * Redisphp Object
     * @var Redis 
     */
    private $redis;
    private $authRequired;
    private $authPassword;
    private $host;
    private $port;
    private $db;

    public function __construct($config = NULL) {
        if (isset($config)) {
            $this->authRequired = $config['auth_required'];
            $this->authPassword = $config['auth_password'];
            $this->host = $config['host'];
            $this->port = $config['port'];
            $this->db = isset($config['db']) ? $config['db'] : 0;
        } else {
            if (isset(EWebApp::config()->REDIS_DEFAULT_CONFIG)){
                $this->authRequired = EWebApp::config()->REDIS_DEFAULT_CONFIG['auth_required'];
                $this->authPassword = EWebApp::config()->REDIS_DEFAULT_CONFIG['auth_password'];
                $this->host = EWebApp::config()->REDIS_DEFAULT_CONFIG['host'];
                $this->port = EWebApp::config()->REDIS_DEFAULT_CONFIG['port'];
                $this->db = isset(EWebApp::config()->REDIS_DEFAULT_CONFIG['db']) ? EWebApp::config()->REDIS_DEFAULT_CONFIG['db'] : 0;
            }else{
                throw new EException('[RedisConnection, __construct()] No se establecio una configuración defaut para redis');
            }
        }
    }

    public function open() {
        try {
            $this->redis = new Redis();
            $this->redis->connect($this->host, $this->port);
            if($this->authRequired){
                if(!isset($this->authPassword))
                    throw new EException('[RedisConnection, getConnection()] No se establecio un password para la conección con redis');
                if(!$this->redis->auth($this->authPassword))
                    throw new EException('[RedisConnection, getConnection()] Fallo la autenticación con redis');
            }
            $this->redis->select($this->db);
            if(!$this->redis->ping()){
                 throw new EException("[RedisConnection, getConnection()] Fallo en abrir conexion con la base de datos redis {$this->db}");
            }
        } catch (Exception $exc) {
            throw new EException("[RedisConnection, getConnection()] Fallo en abrir conexion con la base de datos redis {$this->db}");
        }
    }
    
    public function isActive(){
        return isset($this->redis);
    }
    
    public function close(){
        $this->redis->close();
        unset($this->redis);
    }
    /**
     * 
     * @return Redis
     */
    public function getConnection(){
        if(!$this->isActive()){
            $this->open();
        }
        return $this->redis;
    }
}