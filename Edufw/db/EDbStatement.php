<?php
namespace Edufw\db;
use PDO;
/**
 * Representa una sentencia a aplicar en base de datos. Tiene como
 * objetivo la ejecucion de sentencias en base de datos.
 *
 * @name EDbStatement
 * @package lib\components\active_record
 * @version 20110111
 * @author gseip
 */
class EDbStatement extends EDbSQL {
    public $affectedFiles;

    /**
     * Constructor.
     * @param EDbConnection $edbConnection Conexion a la base de datos
     * @param string $sql Instruccion SQL
     * @param array $parameters
     */
    public function __construct($edbConnection=NULL, $sql=NULL, $parameters=NULL) {
        $this->setEDbConnection($edbConnection);
        $this->setSql($sql);
        $this->setParameters($parameters);
    }

    /**
     * Ejecuta sentencia preparada
     * @return array Lista resultado
     */
    public function execute() {
        $this->_preparedStatement->execute();
        $this->affectedFiles =  $this->_preparedStatement->rowCount();
        return ($this->affectedFiles>0 ? TRUE : FALSE);
    }

    /**
     * Metodo helper para ejecutar sentencias utilizando la conexion default
     * @param string $sql Instruccion SQL
     * @param string $edbConnection Conexion a la base de datos
     * @param array $parameters
     * @return integer Filas afectadas por la sentencia SQL
     */
    public static function executeStatement($sql, $edbConnection, $parameters=NULL) {
        $edbStatement = new EDbStatement($edbConnection, $sql, $parameters);
        $edbStatement->execute();
        return $edbStatement->affectedFiles;
    }

    /**
     * Retorna result set (si se especifica en la sentencia).
     * @return type
     */
    public function getStatementResultSet() {
        return $this->_preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    }
}