<?php
namespace Edufw\db;
use Edufw\core\EException;
use PDO;
/**
 * Representa una asociacion con procedimientos almacenados persistidos en
 * base de datos, para posibilitar llamadas a procedimientos remotos.
 * Las sintaxis soportadas son las siguientes:
 * - Postgres:
 *      "SELECT procedure_name(IN arg1,IN arg2,...,OUT arg1,...,OUT argN)"
 * Ejemplo:
 *   *** En base de datos creamos la siguiente funcion ***
 *      -- Function: el_triple(character varying, integer)
 *      CREATE FUNCTION el_triple(IN cadena character varying, IN numero integer, OUT salida_numero integer) RETURNS integer AS $BODY$
 *      BEGIN
 *          salida_numero := numero * 3;
 *      END;
 *      $BODY$ LANGUAGE 'plpgsql' VOLATILE
 *   *** En un metodo de modelo de logica de negocios ***
 *      public function obtener_el_triple() {
 *          EWebApp::loadComponent('active_record/EDbCommand');
 *          $cadena = 'soy pepe';
 *          $numero = 1000;
 *          $edbCommand = new EDbCommand('el_triple', array(array(1,$cadena),array(2,$numero)));
 *          var_dump($edbCommand->execute());
 *      }
 *   *** Como retorno deberiamos ver lo siguiente...***
 *      array
 *        0 =>
 *          array
 *              'el_triple' => int 3000
 *
 * @name EDbCommand
 * @package lib\components\active_record
 * @version 20110127
 * @author gseip
 */
class EDbCommand extends EDbSQL {
    const T_PG_DEFAULT = 'SELECT %s(%s)'; // "SELECT procedure_name(IN arg1,IN arg2,...,OUT arg1,...,OUT argN)"

    /**
     * Nombre del procedimiento almacenado
     */
    private $spName;

    /**
     * Constructor.
     * @param string $spName Nombre del procedimiento almacenado
     * @param array $parameters Parametros de la funcion
     * @param EDbConnection $edbConnection Conexion a la base de datos
     */
    public function __construct($spName, $params=NULL, $edbConnection=NULL) {
        $this->setEDbConnection($edbConnection);
        $this->prepare($spName, $params);
    }

    public function execute() {
        $this->_preparedStatement->execute();
        return $this->_preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Prepara SQL para llamada a procedimientos almacenados, soporte de
     * parametros (opcional)
     * @return string
     */
    private function prepare($spName, $params) {
        if (!isset($spName))  {  throw new EException('[EDbCommand, setName()] No se estableció nombre del procedimiento almacenado');  }
        $this->spName = strtolower($spName);
        $count_params = empty($params)?0:count($params);
        $param = '';
        if (!empty($count_params)) {
            for ($index = 0; $index < $count_params; $index++) {  $param .= '?,';    }
            $param = substr($param, 0, strlen($param)-1);
        }
        $this->setSql(sprintf(self::T_PG_DEFAULT, $this->spName, $param));
        $this->setParameters($params);
    }

    /**
     * Metodo helper para ejecutar comandos utilizando la conexion default
     * @param string $spName Nombre del procedimiento almacenado
     * @param array $parameters Parametros de la funcion
     * @param EDbConnection $edbConnection Conexion a la base de datos
     * @return <type>
     */
    public static function executeCommand($spName, $params=NULL, $edbConnection=NULL) {
        $edbCommand = new EDbCommand($spName, $params, $edbConnection);
        return $edbCommand->execute();
    }

}