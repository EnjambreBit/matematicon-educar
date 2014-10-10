<?php

namespace Edufw\core;

use \Edufw\core\EView;
use Edufw\core\ELogger;
use Edufw\core\ELoggerLevel;
use Edufw\core\EConfig;
use Edufw\core\ERouter;

/**
 * Contexto global que procesa requerimientos de usuario.
 *
 * @author Gustavo Seip
 * @version 2.0
 */
class EWebApp
{

    /**
     * Configuracion
     */
    private static $eConfig;

    /**
     * Ruteo
     */
    private $eRouter;

    public function  __construct(ERouter $eRouter) {
        $this->eRouter = $eRouter;
    }

    /**
     * Ejecuta la aplicacion web.
     * Crea instancia de EApp e inicia el procesamiento
     * del requerimiento web.
     */
    public final function run()
    {
        set_error_handler(array(
            "\Edufw\core\EWebApp",
            "errorHandler"
        )); // Establecemos handler de error (callback static method class)
        switch (self::config()->APP_MODE) {
            case 'prod' :
                ini_set('log_errors', 'On'); // Controlar errores
                error_reporting(E_ERROR | E_WARNING); // Establecer niveles de error para reportar en produccion
                ini_set('display_errors', 'Off'); // No mostrar errores en pantalla
                ini_set('log_error', self::$eConfig->ELogger['main']['log_folder']); // Archivo para informe de errores global
                ini_set('log_errors_max_len', '1024'); // Maxima longitud de los errores
                break;
            default : // modo dev o desarrollo
                ini_set('log_errors', 'On');
                error_reporting(E_ALL | ~E_STRICT);
                ini_set('display_errors', 'On');
                break;
        }
        $values = $this->eRouter->connect();
        try {
            $this->callController($values);
        } catch (EException $eex) {
            self::error_run($eex);
        } catch (Exception $e) {
            $eex = new EException("Error inesperado", 0, $e);
            self::error_run($eex);
        }
    }

    /**
     * Genera informacion de log ante errores de aplicacion
     *
     * @param type $eex        	
     * @return type
     */
    public final static function error_run($eex)
    {
        $log_id = isset($log_id) ? $log_id : 0;
//        $log_id = ELogger::log('Error de sistema', ELoggerLevel::LEVEL_ERROR, $eex);
        ERouter::sendHeaderHTTP(); // Enviamos 404 como prioritario
        if (self::config()->APP_MODE == 'dev') { // Si modo de aplicacion es desarrollo
            $e = $eex->getException();
//            if (ERouter::isAjaxRequest()) { // Si tenemos un requerimiento AJAX
//                $out = EView::getErrorView($e, $log_id);
//            } else {
                $out = EView::getErrorView($e, $log_id, TRUE);
//            }
        } else { // Si modo de aplicacion produccion, etc
            $out = EView::getErrorView(NULL, $log_id);
        }
        echo $out;
        return;
    }

    /**
     * Obtiene configuracion de aplicación
     */
    public final static function config()
    {
        if (self::$eConfig === NULL) {
            self::$eConfig = new EConfig();
        }
        return self::$eConfig;
    }

    /**
     * Instancia controlador, y ejecuta la accion requerida
     *
     * @param <string> $classController
     *        	Nombre del controlador
     * @param <string> $action
     *        	Nombre de la accion a ejecutar dentro del controlador
     * @param <array> $params
     *        	Arreglo de parametros adicionales para la accion
     */
    public final function callController($values)
    {
        if ($values !== FALSE) {
            try {
                $controllerNS = $values[0];
                $methodName = $values[1];
                $data = $values[2];
                $controllerName = $values[3];
                $objController = new $controllerNS;
                unset($values);
                if (isset($_POST['data']) && is_array($_POST['data'])) { // Si se definen campos HTML con la convencion data[nombre_campo]
                    $objController->setData($_POST['data']);
                }
                if($objController::$templateRenderer === null && !empty(EWebApp::config()->TEMPLATE_RENDERER)){
                    $objController->loadTemplateRenderer();
                }
                $objController->beforeRunAction($controllerNS, $methodName); // Antes de que se ejecute la accion, realizar algun proceso
                $par = array();
                try {
                    $rm = new \ReflectionMethod($controllerNS, $methodName);
                    $par = $rm->getParameters();
                } catch (\Exception $e) {
                    throw new \Exception('Metodo en controller no encontrado', 404);
                }
                $tot = count($par);
                $parameters = array();
                for($i=0;$i<$tot;$i++){
                    if(isset($data[$i])){
                        $parameters[$i] = strip_tags($data[$i]);
                    } else if(!$par[$i]->isOptional()){
                        throw new \Exception('Metodo en controller con inconsistencia de parametros', 404);
                    }
                }
                unset($data);
                \call_user_func_array(array($objController, $methodName), $parameters);
                unset($parameters);

                // Por default se renderiza una vista con el mismo nombre del método dentro de la carpeta del nombre del controlador
                if($objController->autoRender){
                    $objController->render(strtolower($controllerName) . DIRECTORY_SEPARATOR . $methodName);
                }
            } catch (\Exception $e) {
                if (EWebApp::config()->APP_MODE !== 'dev') {
                    EView::pageNotFound();
                } else {
                    throw new EException('', 0, $e);
                }
            }
        } else {
            if (EWebApp::config()->APP_MODE !== 'dev') {
                EView::pageNotFound();
            } else {
                throw new EException('Módulo no encontrado', 0);
            }
        }
    }

    /**
     * Handler para atrapar errores de sistema y controlarlos por excepcion
     *
     * @see http://ar.php.net/manual/en/class.errorexception.php#95415
     */
    public static final function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $error_is_enabled = (bool) ($errno & ini_get('error_reporting')); // Determine if this error is one of the enabled ones in php config (php.ini, .htaccess, etc)
        // -- FATAL ERROR - throw an Error Exception
        if (in_array($errno, array(
                    E_USER_ERROR,
                    E_RECOVERABLE_ERROR
                )) && $error_is_enabled) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }   // -- NON-FATAL ERROR/WARNING/NOTICE - log the error if it's enabled, otherwise just ignore it
        elseif ($error_is_enabled) {
            error_log($errstr, 0);
            $pattern = '/^(require){1}(.*)(controllers\/){1}(.*)(failed to open stream: No such file or directory){1}$/';
            $test = preg_match($pattern, $errstr);
            if($test){
                if (EWebApp::config()->APP_MODE !== 'dev') {
                    EView::pageNotFound();
                } else {
                    throw new EException($errstr, $errno);
                }   
            } else {
                return false; // Make sure this ends up in $php_errormsg, if appropriate
            }
        }
    }

}
