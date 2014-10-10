<?php
namespace Edufw\db;
use PDO;
/**
 * Representa una consulta a ejecutar en base de datos. Tiene como
 * objetivo la obtencion de resultados.
 *
 * @name EDbQuery
 * @package lib\components\active_record
 * @version 20110811
 * @author gseip
 */
class EDbQuery extends EDbSQL   {
    public $return_fetch_mode = PDO::FETCH_ASSOC;

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
        return $this->_preparedStatement->fetchAll($this->return_fetch_mode);
    }

    /**
     * Metodo helper para crear consultas
     */
    public static function createQuery($select, $from, $where, $group_by, $having, $order_by, $limit, $offset) {
        $result = 'SELECT '. $select . ' FROM '. $from;
        if($where!=="") $result .= ' WHERE ' . $where;
        if($group_by!=="") $result .= ' GROUP BY ' . $group_by;
        if($having!=="") $result .= ' HAVING (' . $having . ' )';
        if($order_by!=="") $result .= ' ORDER BY '. $order_by;
        if($limit!=="") $result .= ' LIMIT ' . $limit;
        if($offset!=="") $result .= ' OFFSET ' . $offset;
        return $result;
    }

    /**
     * Metodo helper para realizar consultas utilizando la conexion default
     * @param string $sql Instruccion SQL
     * @param string $edbConnection Conexion a la base de datos
     * @param array $parameters
     * @return array Lista resultado
     */
    public static function executeQuery($sql, $edbConnection=NULL, $parameters=NULL) {
        $edbQuery = new EDbQuery($edbConnection, $sql, $parameters);
        return $edbQuery->execute();
    }

    /**
     * Metodo helper para realizar consultas utilizando la conexion default, el cuál realiza una segunda peticíon con el prefijo (/NO LOAD BALANCE/) en caso de no recibir resultados
     *
     * @param string $sql Instruccion SQL
     * @param string $edbConnection Conexion a la base de datos
     * @param array $parameters
     * @return array Lista resultado
     */
    public static function executeNoLoadBalanceQuery($sql, $edbConnection=NULL, $parameters=NULL) {
        $edbQuery = new EDbQuery($edbConnection, $sql, $parameters);
        $result = $edbQuery->execute();
        if(empty($result)){
            $newSql = str_ireplace('SELECT', 'SELECT /*NO LOAD BALANCE*/ ', $sql);
            $edbQuery = new EDbQuery($edbConnection, $newSql, $parameters);
            return $edbQuery->execute();
        }
        return $result;
    }

}