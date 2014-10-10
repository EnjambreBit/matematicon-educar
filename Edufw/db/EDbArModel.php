<?php
namespace Edufw\db;

use Edufw\db\EDbStatement;
use Edufw\db\EDbQuery;

/**
 * Clase base para soporte de Active Record en una aplicacion
 * Soporte de clave multiple (o No autoincrementable).
 * Soporte ABMs/Queries con multiples configuraciones de base de datos
 * Nota: Necesita PHP 5.3+
 *
 * @version 20101104
 * @author gseip
 *
 * @example POSTGRES
 *  $config['DB']['database1']['DB_CONN_NAME'] = "database1_conn"; // Nombre de la conexion a la base
  $config['DB']['database1']['DB_NAME'] = "postgres"; // Nombre de la base de datos
  $config['DB']['database1']['DB_USER'] = "postgres";    // Usuario
  $config['DB']['database1']['DB_PASSWORD'] = "postgres";   // Contraseña
  $config['DB']['database1']['DB_HOST'] = "localhost"; // Host del servidor de base de datos
  $config['DB']['database1']['DB_PORT'] = "5432"; // Puerto del servidor de base de datos
  $config['DB']['database1']['DB_CHARSET'] = "UTF8";  // Inicializacion de conjunto de caracteres. Solo MYSQL Y POSTGRES
  $config['DB']['database1']['DB_ENGINE'] = "pgsql"; // soporte solamente para pgsql, mysql, oci
  $config['DB']['database1']['DB_PERSISTENT_CONNECTION'] = FALSE; // Indica conexion persistente (Cuidado, verificar soporte en base de datos)
  $config['DB']['database1']['DB_SCHEMA'] = 'public'; // Esquema de base de datos. Solo POSTGRES

  $config['DB_DEFAULT'] = $config['DB']['database1']; //Configuracion default de conexion a base de datos
 *
 */
abstract class EDbArModel {
    public static $TPL_INSERT = 'INSERT INTO %s (%s) VALUES (%s);'; // INSERT INTO {source} ({fields}) VALUES ({values});
    public static $TPL_UPDATE = 'UPDATE %s SET %s %s;'; // UPDATE {source} SET {fields} {conditions};
    public static $TPL_DELETE = 'DELETE FROM %s %s;'; // DELETE FROM {:source} {:conditions};
    public static $TPL_GENERIC_SELECT_WITH_CLAUSES = 'SELECT %s FROM %s as %s WHERE %s'; // SELECT {fields} FROM {table} WHERE {clauses}
    public static $TPL_GENERIC_SELECT = 'SELECT %s FROM %s'; // SELECT {fields} FROM {table}
    public static $TPL_JOIN = '%s JOIN %s %s %s %s;'; // {type} JOIN {source} {alias} {constraint} {conditions}

    private static $validator;
    private $new = FALSE; //Metadata para verificar si es nuevo el objeto
    protected $autoincrement = TRUE; //boolean indicando que el modelo tiene ID autoincrementable

    public function __construct() {
        $this->setIsNew(FALSE);
    }

    /**
     * Construye SQL para INSERT/UPDATE y ejecuta la sentencia
     * @param <boolean> Estado de la sentencia
     */
    public final function save() {
        $object = $this->instance();
        $metadata = $object::metadata();
        if ($this->_autoincrement) { //Hay clave autoincrementable
            unset ($metadata['params'][$metadata['primaryKeys'][0]]); } //Sacar clave primaria de los parametros. Hay id autoincrement.
            //unset ($propsObject[$metadata['primaryKeys'][0]]); //Sacar clave primaria de las propiedades. Hay id autoincrement.
        $propsObject = array_keys($metadata['params']); //Obtener nombres de los campos, con las keys de 'params' en metadata
        if ($object->isNew()) { //Construir INSERT
            $namesParams = array_values($metadata['params']);
            array_walk($namesParams, create_function('&$x', '$x = $x[0];'));
            $this->sql = sprintf(self::$TPL_INSERT,$metadata['tableName'], implode(',', $propsObject), implode(',',$namesParams)); //names, values
            $parameters = $this->buildInstancePropertiesParams(array('object'=>$object,'params'=>$propsObject,'metadata'=>$metadata,'hasWhere'=>FALSE));
            return EDbStatement::executeStatement($this->sql, null, $parameters);
        } else {    //Construir UPDATE
            $updateSet = $this->buildUpdateSet($object, $propsObject,$metadata);
            $this->sql = sprintf(self::$TPL_UPDATE,
            $metadata['tableName'], $updateSet[1],$this->buildCriteriaPK($metadata['primaryKeys'])); //names, values
            $parameters = $this->buildInstancePropertiesParams(array('object'=>$object,'params'=>$propsObject,'metadata'=>$metadata,'hasWhere'=>TRUE));
            return EDbStatement::executeStatement($this->sql, null, $parameters);

        }
        return FALSE;
    }

    /**
     * Construye SQL para DELETE y ejecuta la sentencia
     * @param <object> $object
     * @return <boolean> Estado de la sentencia
     */
    public final function delete() {
        $object = $this->instance();
        $metadata = $object::metadata();
        $this->sql = sprintf(self::$TPL_DELETE, $metadata['tableName'], $this->buildCriteriaPK($metadata['primaryKeys']));
        $parameters = $this->buildInstancePropertiesParams(array(array('object' => $object,'metadata'=>$metadata,'hasWhere'=>TRUE)));
        return EDbStatement::executeStatement($this->sql, null, $parameters);
    }

    /**
     * Verificar estado del modelo
     * @return <boolean> Estado del modelo
     */
    public final function isNew() {
        return $this->new;
    }

    /**
     * Establece que el modelo es nuevo
     * @param <boolean> TRUE si es nuevo / FALSE caso contrario
     */
    public final function setIsNew($x) {
        $this->new = $x;
    }

    /**
     * Construye parametros para UPDATE
     * @param <object> Instancia de objeto
     * @param <array> Lista de propiedades
     * @param <array> Lista de parametros
     * @return <array> Lista de parametros para UPDATE
     */
    private final function buildUpdateSet($object, $props, $metadata) {
        $set = '';
        $count = count($props);
        for ($index = 0; $index < $count; $index++) {
            if (!in_array($props[$index], $metadata['primaryKeys']) && $object->$props[$index] != NULL) {
                $set .= "{$props[$index]}={$metadata['params'][$props[$index]][0]},";
                $fieldsOK[] = $props[$index];
            }
        }
        return array($fieldsOK, substr($set, 0, strrpos($set, ',')));
    }

    /**
     * Construye criterio con claves PK
     * @param <array> Lista de claves PK
     * @return <string> Clausula con claves PK
     */
    private final function buildCriteriaPK($propsPK) {
        $criteria = '';
        foreach ($propsPK as $propPK) {
            $criteria .= " $propPK=:$propPK AND";
        }
        return substr($criteria, 0, strrpos($criteria, 'AND'));
    }

    /**
     * Construye array de parametros PDO:Statement con claves PK
     * @return <array> Lista de parametros PDO:Statement
     */
    private final function buildPrimaryKeysPdoParams($metadata) {
        foreach ($metadata['primaryKeys'] as $key) {
            $params[] = array($metadata['params'][$key][0], $this->instance()->$key);
        }
        return $params;
    }

    private final function buildInstancePropertiesParams($data) {
        foreach ($data['params'] as $field) {
            $paramsResponse[] = array($data['metadata']['params'][$field][0], $data['object']->$field);
        }
        if ($data['hasWhere'])
            foreach ($data['metadata']['primaryKeys'] as $key) {
                $paramsResponse[] = array($data['metadata']['params'][$key][0], $data['object']->$key);
        }
        return $paramsResponse;
    }

    /**
     * Carga propiedades de objeto con datos retornados de una consulta (solo formato de PDO),
     * generalmente, de una sola fila.
     * Ej.  $usuario = new Usuario();
     *      $usuario->loadProperties(rows) //Solo se usara $rows[0] para la carga
     *
     * @param <array> $rows Datos de consulta
     */
    public final function loadProperties($rows) {
        if (empty($rows) || isset($rows[1])) return FALSE; //Verificamos vacios ("","0",FALSE,array(),etc) o hay mas de una fila en la lista
        $object = $this->instance();
        foreach ($rows[0] as $key => $value)
            $object->$key = $value;
        return TRUE;
    }

    /**
     * Carga propiedades de un modelo segun sus propiedades identificatorias (PK Keys)
     * @return <boolean> TRUE caso exito, FALSE caso contrario
     */
    public final function load_model() {
        $class_name = get_class($this->instance());
        $metadata = $class_name::metadata();
        $this->sql = sprintf(self::$TPL_GENERIC_SELECT_WITH_CLAUSES, '*',$metadata['tableName'], $class_name, $this->buildCriteriaPK($metadata['primaryKeys']));
        $resultSet =  EDbQuery::executeQuery($this->sql, NULL, $this->buildPrimaryKeysPdoParams($metadata));
        unset($metadata,$class_name);
        return $this->loadProperties($resultSet);
    }

    /**
     * Verifica existencia del modelo en la tabla en base de datos
     * @return <boolean> TRUE caso exito, FALSE caso contrario
     */
    public final function exist_model() {
        $class_name = get_class($this->instance());
        $metadata = $class_name::metadata();
        $this->sql = sprintf(self::$TPL_GENERIC_SELECT_WITH_CLAUSES, 'count(*)',$metadata['tableName'], $class_name, $this->buildCriteriaPK($metadata['primaryKeys']));
        $resultSet =  EDbQuery::executeQuery($this->sql, NULL, $this->buildPrimaryKeysPdoParams($metadata));
        unset($metadata,$class_name);
        return ($resultSet !== FALSE && $resultSet[0]['count']>0 ? TRUE : FALSE);
    }

    /**
     * Obtiene el total de modelos en la tabla de base de datos
     * @return <type>
     */
    public final function count() {
        $class_name = get_class($this->instance());
        $metadata = $class_name::metadata();
        $this->sql = sprintf(self::$TPL_GENERIC_SELECT,'count(*)' ,$metadata['tableName']);
        $resultSet =  EDbQuery::executeQuery($this->sql);
        unset($metadata,$class_name);
        return $resultSet !== FALSE ? $resultSet[0]['count'] : 0;
    }

    public function getVRules() { return NULL; }

    public function validate() {
        $validator = new ArValidator( $this->getVRules(), $this->instance());
        if (!$validator->validate())
        {   return $validator->getErrors();     }
        return FALSE;
    }

    ////////// METODOS ABSTRACTOS //////////
    public abstract function instance();

}

/**
 * Clase para validacion de modelos (server side)
 *
 * [Regla][SubRegla][Clausulas]
 * @author gseip
 * @version 20101126
 */
class ArValidator {
    private $_rules = array();
    private $_errors = array();
    private $_object;

    public function __construct($rules, $object) {
        if (empty($rules) || empty($object))
        {   throw new Exception('Reglas de validación y/o instancia de objeto inválida/s');    }
        $this->_rules = $rules;
        $this->_object = $object;
    }

    public function validate() {
        foreach ($this->_rules as $attribute => $attributeRules) { //Obtener las reglas
            foreach ($attributeRules as $rule) {  //recorrer las reglas asociada a un campo
                $result = call_user_func_array(array($this,$rule[0]), array($this->_object->$attribute, $rule[1]));
                if ($result===TRUE) {
                    $this->_errors[$attribute] = $rule[2]; //Guardar el mensaje asociado a la regla
                    break; //No continuar el ciclo con este atributo y proseguir con el siguiente
                }
            }
        }
    }

    /**
     * Retornar errores si los hubiere
     * @return <mixed>
     */
    public function getErrors() {
        return $this->_errors;
    }

    //---------- METODOS TESTING ---------
    private function between($check, $rule) {
        $check = strlen($check);
        if (!is_array($rule))
        {   throw new Exception('[ArValidator, between] Regla inválida');   }
        return ($check>=$rule[0] && $check<=$rule[1] ? FALSE : TRUE);
    }
    private function pattern($check, $rule) {
        if (!is_string($rule))
        {   throw new Exception('[ArValidator, pattern] Regla inválida');   }
        return (preg_match($rule,$check)!==1 ? TRUE : FALSE);
    }
    private function required($check, $rule=NULL) {
        return (empty($check) ? TRUE : FALSE);
    }
//    private function equalTo($check,$otherValue) {
//        return true;
//    }
}