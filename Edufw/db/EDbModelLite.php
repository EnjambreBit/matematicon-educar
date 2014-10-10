<?php
namespace Edufw\db;
use Edufw\core\EWebApp;
use Edufw\core\EException;
/**
 * Clase liviana para la creacion de modelos de base datos. No implemena joins entre tablas.
 *
 * @version 20130418
 * @author gseip, lmoya
 *
 */
abstract class EDbModelLite
{
  const VIOLATION_UNIQUE_KEY = 23505;

  protected static $TPL_INSERT = 'INSERT INTO %s (%s) VALUES (%s) RETURNING %s;'; // INSERT INTO {source} ({fields}) VALUES ({values});
  protected static $TPL_UPDATE = 'UPDATE %s SET %s WHERE %s;'; // UPDATE {source} SET {fields} WHERE {conditions};
  protected static $TPL_DELETE = 'DELETE FROM %s WHERE %s;'; // DELETE FROM {:source} WHERE {:conditions};
  protected static $TPL_GENERIC_SELECT_WITH_CLAUSES = 'SELECT %s FROM %s as %s WHERE %s'; // SELECT {fields} FROM {table} WHERE {clauses}
  protected static $TPL_GENERIC_SELECT = 'SELECT %s FROM %s'; // SELECT {fields} FROM {table}
  protected static $TPL_JOIN = '%s JOIN %s %s %s %s;'; // {type} JOIN {source} {alias} {constraint} {conditions}
  /**
   * Array de tablas del Modelo
   * @var array
   */
  protected $table = array();
  /**
   * Array de primary Keys
   * @var array
   */
  protected $primaryKey = array();
  /**
   * Array con las ultimas primary keys insertadas en la base
   * @var array
   */
  protected $lastId = array();
  /**
   * Cantidad de filas affectadas
   * @var int
   */
  protected $affetedRows = array();
  /**
   * Ultima consulta ejecutada
   * @var String
   */
  protected $sql = '';
  /**
   * Indica si se cargo el modelo para hacer un update o delete
   * @var bool
   */
  private $modelLoaded = false;
  /**
   * Nombre de la conexion a la base de datos
   * @var String
   */
  static protected $connName;
  /**
   * Conexion a la base de datos (por default usa null)
   * @var EDbConnection
   */
  static private $conn = null;


  /**
   * Construye un Modelo liviano
   *
   * @param Array $parameters [opcional] <p>Parametros de configuracion (opcional, pueden usarse los setters)</p>
   * @example
   * Ejemplo de parametros de configuracion
   * <p>array("conn" => 'una_configuracion', "table" => 'tabla1', "primayKey"  => ('un_id', 'otro_id'))</p>
   */
  public function __construct($parameters = NULL)
  {
    if(!empty($parameters))
    {
      self::$connName = isset($parameters['conn']) ? $parameters['conn'] : 'globalbackend';
      if(isset($parameters['primaryKey']))
        $this->setPrimaryKey ($parameters['primaryKey']);
      if(isset($parameters['table']))
        $this->setTable ($parameters['table']);
    }
  }

// <editor-fold defaultstate="collapsed"  desc="Setters/Getters de la clase">
  /**
   * Setea el <b>objeto</b> de conexion a la base de datos<br>
   * <i>(preferentemente usar solo el setConnName o constructor y luego llamar a getConn)</i>
   * @param EDbConnection $conn Objeto de conexion a la base de datos
   */
  protected final function setConn($conn)
  {
    self::$conn = (get_class($conn) === 'EDbConnection') ? $conn : null;
    self::$connName = !empty($conn) ? $conn->getName() : '';
  }
  /**
   * Setea el <b>string</b> de conexion a la base de datos
   * @param String $conn String de conexion a la base de datos
   */
  protected final function setConnName($conn)
  {
    self::$connName = $conn;
  }
  /**
   * Setea el array de tablas del modelo.
   *
   * @param mixed $tableNames Strings de tablas del modelo
   * @example $modelo->setTable(array('tabla1','tabla2'));
   */
  protected final function setTable($tableNames)
  {
    $this->table = is_array($tableNames) ? $tableNames : implode(',', trim($tableNames));
  }
  /**
   * Setea las claves primarias. Solo debe ser utilizado para Updates.
   *
   * @param mixed $pKeys Strings de primary keys del modelo
   * @example $modelo->setPrimaryKeys(array('key1','key2'));
   */
  protected final function setPrimaryKey($pKeys)
  {
    $this->primaryKey = is_array($pKeys) ? $pKeys : implode(',', trim($pKeys));;
  }
  /**
   * Entrega la conexion <b>(unica)</b> actual a la base de datos.<br />
   * Si detecta un cambio en el string de conexion devuelve una conexion nueva, desechando la anterior.
   * @return EDbConnection Objeto de conexion a la base
   */
  static protected final function getConn()
  {
    return (isset(self::$conn) && self::$conn->getName() == self::$connName) ? self::$conn : self::$conn = new EDbConnection(EWebApp::conf()->DB[self::$connName]);
  }
  /**
   * Devuelve un arreglo con los ultimos ID insertados en la base
   * @return array Arreglo de primary keys del ultimo elemento insertado
   */
  public final function getLastId()
  {
    return $this->lastId;
  }
  /**
   * Devuelve la cantidad de columnas insertadas/modificadas
   * @return int cantidad de columnas insertadas/modificadas
   */
  public final function getAffectedRows()
  {
    return $this->affetedRows;
  }
  /**
   * Ultima query ejecutada
   * @return String
   */
  public final function getLastSqlQuery()
  {
    return $this->sql;
  }
    //  public abstract function instance();
  // </editor-fold>

  /**
   * <p>Construye SQL para <b>INSERT/UPDATE</b> y ejecuta la sentencia.</p>
   * <p>El <b>UPDATE</b> se ejecuta si previamente se hizo un load, caso contrario se hace un <b>INSERT</b><p>
   *
   * @return bool Estado de la sentencia
   */
  public function save()
  {
    $parameters_sql = array();

    $values = __get_public_vars_array($this);
    $values = array_filter($values, function($v){return !empty($v);});

    $column_insert = $values_insert = array();
    $fields_update = $conditions_update = array();
    //Se crea un strings de claves primarias
    $primary_keys = ' ' . implode(' ', $this->primaryKey);
    //Se cargan todos los valores para INSERT o UPDATE
    foreach ($values as $key => $value)
    {
      //Insert values
      $column_insert[] = $key;
      $values_insert[] = ":$key";
      //Update values
      if (strpos($primary_keys, $key)) //Primary Keys conditions
        $conditions_update[] = "$key=:$key";
      else //fields
        $fields_update[] = "$key=:$key";
      //Binding
      $parameters_sql[] = array(":$key", $value);
    }
    //construir sql
    if (!$this->modelLoaded) //INSERT
    {
      $this->sql = sprintf(self::$TPL_INSERT, implode(',', $this->table), implode(',', $column_insert), implode(',', $values_insert), implode(',', $this->primaryKey));
      $return = EDbQuery::executeQuery($this->sql, self::getConn(), $parameters_sql);
      $this->lastId = isset($return[0]) ? $return[0] : array();
      $this->affetedRows = count($return);
      return (isset($return[0]) && !empty($return[0]) ) ? TRUE : FALSE;
    }
    else //UPDATE
    {
      $this->sql = sprintf(self::$TPL_UPDATE, implode(',', $this->table), implode(',', $fields_update), implode(' AND ', $conditions_update));
      $result = EDbStatement::executeStatement($this->sql, self::getConn(), $parameters_sql);
      $this->affetedRows = $result;
      return !empty($result) ? TRUE : FALSE;
    }

    return FALSE;
  }

  /**
   * Construye SQL para <b>DELETE</b> con <b>WHERE</b> para las claves primarias y ejecuta la sentencia
   *
   * @return bool Estado de la sentencia
   */
  public function delete()
  {
    $this->sql = sprintf(self::$TPL_DELETE, implode(',', $this->table), $this->buildCriteriaPK());
    return EDbStatement::executeStatement($this->sql, self::getConn(), $this->buildPrimaryKeysPdoParams());
  }

  /**
   * Carga un modelo con su valores para realizar un <b>UPDATE</b>.<br />
   * <b>Requiere</b> que se haya seteado las propiedades de la PK.
   *
   * @return bool Verdadero para la carga sin inconvenietes.
   */
  public function load()
  {
    $class_name = get_class($this);
    $columns = __get_columns_with_alias($this, $class_name);
    $this->sql = sprintf(self::$TPL_GENERIC_SELECT_WITH_CLAUSES, implode(',', $columns), implode(',', $this->table), '"'.$class_name.'"', $this->buildCriteriaPK($class_name));
    $resultSet = EDbQuery::executeQuery($this->sql, self::getConn(), $this->buildPrimaryKeysPdoParams());
    unset($class_name);
    $this->modelLoaded = true;
    return $this->loadProperties($resultSet);
  }
  /**
   * Realiza una consulta a la base y setea las propiedades del modelo.
   *
   * @param String $sql Consulta SQL
   * @param Array $params
   * @return bool Verdadero para la carga sin inconvenietes.
   */
  public function query($sql, $params = null, $autoload = false)
  {
    $this->sql = $sql;
    $result = EDbQuery::executeQuery($this->sql, self::getConn(), $params);
    return ($autoload) ? $this->load($result) : $result;
  }

// <editor-fold defaultstate="collapsed"  desc="Metodos Internos para la creacion de consultas">
  /**
   * Carga propiedades de objeto con datos retornados de una consulta <i>(solo formato de PDO)</i>,
   * generalmente, de una sola fila.
   *
   * @param array $rows Datos de consulta
   *
   * @example Ej.<br />
   * <p>$usuario = new Usuario();</p>
   * <p>$usuario->loadProperties(rows) //Solo se usara $rows[0] para la carga</p>
   */
  protected final function loadProperties($rows)
  {
    if (empty($rows) || isset($rows[1]))
      return FALSE; //Verificamos vacios ("","0",FALSE,array(),etc) o hay mas de una fila en la lista
    foreach ($rows[0] as $key => $value)
      $this->$key = $value;
    return TRUE;
  }

  /**
   * Construye criterio con claves PK
   * @param String $alias Alias de la tabla a consultar
   * @return String Clausula con claves PK
   */
  private final function buildCriteriaPK($alias = null)
  {
    $criteria = '';
    foreach ($this->primaryKey as $propPK)
    {
      $criteria .= isset($alias)? ' "'.$alias.'".'.$propPK : " $propPK";
      $criteria .= "=:$propPK AND";
    }
    return substr($criteria, 0, strrpos($criteria, 'AND'));
  }

  /**
   * Construye array de parametros <b>PDO:Statement</b> con claves <b>PK</b>
   * @return Array Lista de parametros PDO:Statement
   */
  private final function buildPrimaryKeysPdoParams()
  {
    $params = array();
    foreach ($this->primaryKey as $key)
    {
      $params[] = array($key, $this->$key);
    }
    return $params;
  }
//</editor-fold>

}
// <editor-fold defaultstate="collapsed"  desc="Metodos Internos para obtener variables">
/**
 * <p>Funcion interna que obtiene las variables publicas de una instancia de un objeto
 * con sus respectivos valores</p>
 *
 * @param object $obj Objeto del cual se quieren tener las variables publicas
 */
function __get_public_vars_array($obj)
{
  return get_object_vars($obj);
}

/**
 * Funcion interna que obtiene las variables publicas de una instancia de un objeto
 *
 * @param object $obj Objeto del cual se quieren tener las variables publicas
 * @param string $alias Alias de la tabla
 */
function __get_columns_with_alias($obj, $alias = null)
{
  $l = get_object_vars($obj);
  $c = array();
  foreach ($l as $key => $value)
    $c[] = isset($alias)? '"'.$alias.'".'.$key : $key;
  return $c;
}
//</editor-fold>