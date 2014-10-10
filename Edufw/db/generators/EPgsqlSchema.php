<?php
namespace Edufw\db\generators;
use PDO;
/**
 * Clase para generar modelos de tablas existentes en base de datos.
 *
 * Importante: Solo comprobado bajo Postgresql 8.4
 * @author gseip
 * @version 201010
 */
class EPgsqlSchema {
    public static $pathModels;
    public static $dbConfig;

    public static function generateModels($pathModels, $schema, $dbConfig=NULL) {
        self::$pathModels = $pathModels;
        $tables = self::findTables($schema, $dbConfig);
        foreach ($tables as $table) {
            $className = self::parseTableName($table['table_name']);
            $clase = "<?php \n class $className extends ArModel { \n";
            $columns = self::findColumns($schema, $table['table_name'], $dbConfig);
            $properties = "";
            ////// PROPIEDADES //////////
            foreach ($columns as $col) {
                $properties .= "    public \${$col['attname']};\n";
            }
            $clase .= $properties;

            ////// CLAVES PRIMARIAS Y FORANEAS ////////
            $constraints = self::findConstraints($schema, $table['table_name'], $dbConfig); //contype indkey
            $fkeys = array();
            foreach ($constraints as $constraint) {
                if ($constraint['contype'] === 'p') { // primary key
                    $pkeys = self::findPrimaryKeys($schema, $table['table_name'], $constraint['indkey'], $dbConfig);
                } else if ($constraint['contype'] === 'f') { // foreign key
                    $fkeys = array_merge($fkeys, self::findForeignKeys($constraint['consrc']));
                }
            }
            $defPK = " 'primaryKeys'=>array(";
            foreach ($pkeys as $pkey) {
                $defPK .= "'{$pkey['attname']}',";
            }
            $defPK = substr($defPK, 0, strrpos($defPK, ',')) . ")";
            if (!empty($fkeys)) {
                $defFK = " 'foreignKeys'=>array(";
                foreach ($fkeys as $fkey) {
                    $defFK .= "array('{$fkey['localKey']}','{$fkey['tableName']}','{$fkey['fk']}'),";
                }
                $defFK = substr($defFK, 0, strrpos($defFK, ',')) . ')';
            }
            $defTableName = "'tableName'=>'{$table['table_name']}'";

            $defFields = "'fields'=>array(";
            $defParams = "'params'=>array(";
            foreach ($columns as $col) {
                $defFields .= "'{$col['attname']}'=>'label_{$col['attname']}',";
                $defParams .= "'{$col['attname']}'=>array(':{$col['attname']}'," . self::getColumnType($col['type']) . "),";
            }
            $defFields = substr($defFields, 0, strrpos($defFields, ',')) . ')';
            $defParams = substr($defParams, 0, strrpos($defParams, ',')) . ')';

            $clase .= "\n
    public function  __construct() {
        parent::__construct();
    } \n";
            $clase .= "\n
    public static function metadata() {
        return array(\n         $defTableName,\n        $defFields,\n       $defParams,\n       $defPK";
            if (!empty($fkeys))
                $clase .= ",\n        $defFK\n";
            $clase .= "       );\n    }\n
    public function instance() {
        return \$this;
    }\n

}\n?>";

            $file = fopen($pathModels . '/' . $className . '.php', 'w');
            fwrite($file, $clase);
            fclose($file);
            $clases[] = $clase;
        }
        return true;
    }



    public static function getColumnType($type) {
        if (preg_match("/^integer.*/", $type) || preg_match("/^timestamp.*/", $type))
            return PDO::PARAM_INT;
        elseif (preg_match("/^character.*/", $type) || preg_match("/^text.*/", $type) || preg_match("/^tipo_origen.*/", $type))
            return PDO::PARAM_STR;
        return $type . ' NO DEFINIDO';
    }

    public static function parseTableName($tableName) {
        if (preg_match('/.*_.*/', $tableName) !== 0) {
            $temp = explode('_', $tableName);
            $tableName = '';
            foreach ($temp as $value) {
                $tableName .= ucfirst($value);
            }
            return $tableName;
        }
        return ucfirst($tableName);
    }

    public static function isPK($field, $pkeys) {
        foreach ($pkeys as $pkey) {
            if ($field == $pkey['attname'])
                return TRUE;
        }
        return FALSE;
    }

}
