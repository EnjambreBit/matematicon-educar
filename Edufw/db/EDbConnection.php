<?php
namespace Edufw\db;
use Edufw\core\EWebApp;
use Edufw\core\EException;
use PDO;
/**
 * Representa una conexion de base de datos.
 *
 * @name EConnection
 * @package lib\components\active_record
 * @version 20110413
 * @author gseip
 */
class EDbConnection {

    private $_connection;
    private $_config;

    /**
     * Constructor.
     * @param Array $config Datos de configuracion de base de datos
     */
    public function __construct($config=NULL) {
        if (!isset(EWebApp::conf()->DB_DEFAULT)) {
            throw new EException('[EConnection, __constructor()] No se establecio en configuracion de sistema, la configuracion de base de datos default');
        }
        $this->_config = isset($config) ? $config : EWebApp::conf()->DB_DEFAULT;
    }

    /**
     * Abre una conexion a la base de datos
     * Notas: En conexion Oracle se asume que se usa Oracle Instant Client
     */
    public final function open() {
        if (!isset($this->_config)) {
            throw new EException('[EConnection, open()] No se establecio configuracion de base de datos. Usar EConnection::setConfig()');
        }
        if ($this->isActive()) {
            $this->close();
        }
        //Construccion de dsn
        $dsn = "{$this->_config['DB_ENGINE']}:dbname={$this->_config['DB_NAME']};host={$this->_config['DB_HOST']}" . (isset($this->_config['DB_PORT']) ? ";port={$this->_config['DB_PORT']}" : '');
        try {
            $this->_connection = new PDO($dsn, $this->_config['DB_USER'], $this->_config['DB_PASSWORD'], array(PDO::ATTR_PERSISTENT => $this->_config['DB_PERSISTENT_CONNECTION']));
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //error PDO en modo exception
            if (isset($this->_config['DB_CHARSET'])) {
                //Establecer charset en conexion (Solo POSTGRES y MYSQL)
                $this->_connection->exec("SET NAMES '{$this->_config['DB_CHARSET']}'");
            }
            if(isset($this->_config['DB_SCHEMA'])) {
                $this->_connection->exec("SET search_path TO {$this->_config['DB_SCHEMA']}");
            } //Establecer esquema de base de datos. Solo POSTGRES
        } catch (PDOException $e) {
            throw new EException("[EConnection, open()] Fallo en abrir conexion a la base de datos {$this->_config['DB_NAME']}");
        }
    }

    /**
     * Cierra conexion a la base de datos
     */
    public final function close() {
        if ($this->isActive()) {
            $this->_connection = NULL;
        }
    }

    /**
     * Verifica si hay conexion de base de datos
     * @return boolean TRUE si la conexion esta activa. FALSE caso contrario
     */
    public final function isActive() {
        return (isset($this->_connection) ? TRUE : FALSE);
    }

    /**
     * Obtiene conexion activa de base de datos
     * @return PDO Conexion a la base de datos
     */
    public final function getConnection() {
        return $this->_connection;
    }

    public static final function getPdoType($value) {
        if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        } elseif (is_int($value)) {
            return PDO::PARAM_INT;
        } elseif (is_null($value)) {
            return PDO::PARAM_NULL;
        } elseif (is_resource($value)) {
            return PDO::PARAM_LOB;
        }
        return PDO::PARAM_STR; //Ante cuaquier cosa distinta de los tipos de datos definidos, se retorna PDO::PARAM_STR
    }

    /**
     * Obtiene nombre de la conexion
     */
    public function getName() {
        return $this->_config['DB_CONN_NAME'];
    }

}