<?php
namespace Edufw\db;
use Edufw\core\EException;
use Edufw\db\EDbConnection;
use Edufw\core\EWebApp;
use PDOException;
/**
 * Clase abstracta con propiedades comunes para las sentencias y consultas.
 *
 * @name EDbSQL
 * @package lib\components\active_record
 * @version 20110111
 * @author gseip
 */
abstract class EDbSQL {
    private $_parameters = array();
    protected $_preparedStatement;
    protected $_EDbConnection;
    private $_sql;

    /**
     * Establece instruccion SQL
     * @param string $sql Instruccion SQL
     */
    public function setSql($sql) {
        $this->_sql = $sql;
        if ($this->validateSqlInstructions()) {
            try {
                $this->_preparedStatement = $this->_EDbConnection->getConnection()->prepare($this->_sql);
            } catch (PDOException $e) {
                $this->_preparedStatement = FALSE;  }
            if ($this->_preparedStatement===FALSE) {
                unset($this->_preparedStatement);
                throw new EException("[EDbSQL, setSql()] El DBMS no ha podido preparar la sentencia SQL \n".(isset($e)?
                    "CODE: {$e->getCode()}\nMESSAGE: {$e->getMessage()}":"")); }   }
    }

    /**
     * Retorna instruccion SQL
     * @return string Instruccion SQL
     */
    public function getSql() {
        return $this->_sql;
    }

    /**
     * Establece lista de parametros para PDOStatement
     * @param array $params Lista de parametros para PDOStatement
     */
    public function setParameters($params) {
        if (isset($params)) {
            $this->_parameters = $params;
            if (!isset($this->_preparedStatement)) {
                throw new EException("[EDbSQL, setParameters()] No se establecio instancia de PDOStatement");    }
            try {
                foreach ($params as $param) {
                $this->_preparedStatement->bindParam($param[0], $param[1], EDbConnection::getPdoType($param[1]));  }
            } catch (PDOException $e) {
                throw new EException("[EDbSQL, setParameters()] Parametros PDOStatement invalido/s", 0, $e); }   }
    }

    /**
     * Obtiene lista de parametros para PDOStatement
     * @return array Lista de parametros para PDOStatement
     */
    public function getParameters() {
        return $this->_parameters;
    }

    /**
     * Establece una conexion a base de datos
     * @param EDbConnection $edbConnection Conexion a base de datos
     */
    public function setEDbConnection($edbConnection) {
        if (!isset($edbConnection)) {
            if (isset(EWebApp::conf()->DB_DEFAULT)) {
                $this->_EDbConnection = new EDbConnection(EWebApp::conf()->DB_DEFAULT);  }
            else {
                throw new EException("[EDbSQL, setEDbConnection()] No hay configuracion de DBMS para establecer");   }
        } else {
            $this->_EDbConnection = $edbConnection;     }
        if (!$this->_EDbConnection->isActive()) {
            $this->_EDbConnection->open();     }
    }

    /**
     * Valida instrucciones SQL
     */
    private function validateSqlInstructions() {
        if (!isset($this->_sql)) {
            throw new EException("[EDbSQL, validateSqlInstructions()] Instruccion SQL no establecida");    }
        return TRUE;
    }

    /**
     * Obtiene instancia de PreparedStatement
     * (util para postprocesamiento de SQL)
     */
    public function getPreparedStatement() {
        return $this->_preparedStatement;
    }

    /**
     * Ejecuta sentencia preparada
     */
    public abstract function execute();

}