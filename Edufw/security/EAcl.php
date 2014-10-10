<?php
namespace Edufw\security;

use Edufw\db\EDbQuery;

/**
 * Control de acceso de usuarios (Version para bases de datos)
 * @author gseip
 * @version 20100202
 */

class EAcl {

    /**
     * Verifica si dentro de las reglas ACL del usuario, se encuentra el acceso
     * al método dentro del controlador llamado.
     * @param <string> $controller - Controlador donde se trata la peticion
     * @param <string> $method - Funcion que trata la peticion
     * @param <array> $protectedMethods - Lista de metodos protegidos en el controlador
     * @param <array> $accessRules - Reglas de acceso que posee el usuario
     * @return <boolean> TRUE si valida ok el acceso. FALSE caso contrario
     */
    public function checkAccess($controller, $method, $protectedMethods, $accessRules ) {
        if (!in_array('*', $protectedMethods) && !in_array($method, $protectedMethods)) return TRUE; //El metodo es publico. No se controla el acceso
        $controller = substr($controller, 0, strpos($controller, 'Controller'));
        //TO DO: VERIFICAR isset($accessRules[$controller])
        if(isset($accessRules[$controller])){
            if ( $accessRules!==NULL && (in_array("*", $accessRules[$controller]) || in_array($method, $accessRules[$controller])) )
                return TRUE; // * = Acceso a todos los metodos o El metodo privado es accesible por el usuario
            return FALSE;
        }
        else
            return false;
    }

    /**
     * Obtiene reglas establecidas para el rol aplicado al usuario
     * @param <integer> $rol_id ID del rol del usuario
     */
    public function getAccessRules($usr_id) {
        $sql = 'SELECT      DISTINCT RE.reg_regla, RE.reg_id, RE.reg_nombre, RE.reg_descripcion
                FROM        usuario US
                INNER JOIN  rol_usr RU ON RU.usr_id = US.usr_id AND US.usr_id = :usr_id
                INNER JOIN  rol RO ON RO.rol_id = RU.rol_id AND RO.rol_estado = 1
                INNER JOIN  permiso PE ON PE.rol_id = RO.rol_id
                INNER JOIN  regla RE ON RE.reg_id = PE.reg_id';
        $reglas = EDbQuery::executeQuery($sql, null, array(array(":usr_id", $usr_id)));
        $sql = "SELECT  RE.reg_regla, RE.reg_id, RE.reg_nombre, RE.reg_descripcion
                FROM    regla RE
                WHERE   RE.reg_id IN (1, 29, 75)"; // 1 => MAIN, 29 => LOGOUT, 75 => ModificarDatos
        $reglasGral = EDbQuery::executeQuery($sql, null, null);
        foreach ($reglasGral as $reglaGral){
            $reglas[] = $reglaGral;
        }
        return $reglas;
    }

    /**
     * Procesa reglas obtenidas de base de datos y las procesa.
     * Formato de cada elemento de la lista: array(ControllerName,ActionName)
     * @param <type> $rules Reglas obtenidas de base de datos
     * @return <type> Lista de reglas a aplicar a un rol determinado
     */
    public function processRules($rules) {
        if (empty ($rules)) return FALSE;
        $ruleList = array();
        foreach ($rules as $rule) {
            $names = explode(':', $rule['reg_regla']);
            if(isset($names[0]) && isset($names[1])){
                $ruleList[$names[0]][] = $names[1];
            } else if(isset($names[0])){
                $ruleList[$names[0]][] = 'undefined';
            }
        }
        if (!empty ($ruleList)) return $ruleList;
        return FALSE;
    }

}