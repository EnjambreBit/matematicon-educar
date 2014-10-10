<?php

namespace Edufw\core;

use Edufw\core\EWebApp;
use Edufw\core\logger\ELogger;

/**
 * Clase para procesamiento de requerimientos. Establece la relacion entre una subruta de URL y un controlador/accion.
 *
 * @author Gustavo Seip - Pablo Gambetta
 * @version 2.1
 */
final class ERouter 
{
    private $routes;
    
    private static $REGEX = array(
        'text' => '([\w\-\_\|\*]+)',
        'alphabetic' => '(\w+)',
        'numeric' => '([0-9]+)',
        'alphanumeric' => '([0-9\w]+)'
    );
    
    private static $defaultRegexRoutes = array(
        '/^\/(\w+)\/(\w+)\/(\w+)/' => array(         
            'pos' => array(
                ':module' => 1,
                ':controller' => 2,
                ':action' => 3
            ),
            'val' => array(
                ':module' => "(\w+)",
                ':controller' => "(\w+)",
                ':action' => "(\w+)"
            ),
            'vars' => array()
        ),
        '/^\/(\w+)\/(\w+)\/$/' => array(
            'pos' => array(
                ':module' => 1,
                ':controller' => 2
            ),
            'val' => array(
                ':module' => "(\w+)",
                ':controller' => "(\w+)",
                ':action' => false
            ),
            'vars' => array()
        ),
        '/^\/(\w+)\/$/' => array(
            'pos' => array(
                ':module' => 1
            ),
            'val' => array(
                ':module' => "(\w+)",
                ':controller' => false,
                ':action' => false
            ),
            'vars' => array()
        )
    );
    
    private static $module = false;
    private static $controller = false;
    private static $method = false;
    private $userRegexRoutes;
    
    const ERouter400_BadRequest = 'HTTP/1.1 400 Bad Request';
    const ERouter401_Unauthorized = 'HTTP/1.1 401 Unauthorized';
    const ERouter403_Forbidden = 'HTTP/1.1 403 Forbidden';
    const ERouter404_NotFound = 'HTTP/1.1 404 Not Found';
    const HContentTypeJSON = 'Content-Type: application/json; charset=utf-8';
    const HContentTypeTEXTPLAIN = 'Content-Type: text/plain; charset=utf-8';

    /**
     * <p>Constructor de ERouter</p>
     *
     * @param array $routes <p> Es un array del tipo <b>clave=>valor</b> donde la clave es la ruta y el valor es un array de parámetros</p> <br> <p> La ruta debe iniciar con <b>"/"</b> y luego separar los grupos deseados con <b>"/"</b>, para soportar parámetros al final de la URL simplemente finalizar con <b>"*"</b> <br> Los grupos que representan una variable <b>(:var_name)</b>, módulo <b>(:module)</b>, controlador <b>(:controller)</b> o acción <b>(:action)</b> deben iniciar con <b>":"</b> <br> Ninguno de ellos son obligatorios, y en caso de no estar presentes se asignará el valor por defecto cargado en el archivo de configuración <br> Los grupos que simplemente son para exigir una texto dentro de la url no llevan <b>":"</b> <br> De esta forma una ruta válida podría ser <b>"/admin/:controller/:action/*</b></p> <br> <p> El array de parámetros es un array del tipo <b>clave=>valor</b>, en el mismo se indica cómo debe validad cada grupo, y se asignan los valores necesarios para el funcionamiento del router <br> <b>Los valores OBLIGATORIOS son: (:module, :controller, :action)</b> y en caso de no recibirlos, se cargan los valores por defecto cargados en el archivo de configuración <br> Aparte de recibir expresiones regulares <b>las cuales deben estar siempre entre ()</b> se pueden definir constantes soportadas por el router <br> <b>Constantes soportadas:</b> (alphanumeric, alphabetic, text) </p>
     * 
     * @example <ul>$routes['/:controller/:action'] = array(':module' => 'MODULE_NAME')); // <b>Manejar la aplicación con un único módulo, no se reciben variables.</b> <br> $routes['/:controller/:action/*'] = array(':module' => 'MODULE_NAME')); // <b>Manejar la aplicación con un único módulo, se reciben variables.</b> <br> $routes['/:mi_variable/texto/:action/:controller/:module/*] = array(':mi_variable' => 'text')); // <b>Ruta personalizada con variable de usuario, texto, ubicaciones y variables generales</b>
     */
    public function __construct($routes = array()) 
    {
        if(isset(EWebApp::config()->PROYECT_DEFAULT))
        {
            $pd = EWebApp::config()->PROYECT_DEFAULT;
            if(isset($pd['module']) && isset($pd['controller']) && isset($pd['method']))
            {
                self::$module = $pd['module'];
                self::$controller = $pd['controller'];
                self::$method = $pd['method'];
            } 
            else 
            {
                $logger = new ELogger('ERouter');
                $logger->warning('No se definio en la configuracion PROYECT_DEFAULT');
                unset($logger);
            }
            unset($pd);
        } else {
            $logger = new ELogger('ERouter');
            $logger->warning('No se definio en la configuracion PROYECT_DEFAULT');
            unset($logger);
        }
        
        $this->prepareRegex($routes);
        
        self::$defaultRegexRoutes['/^\/$/'] = array(
            'pos' => array(),
            'val' => array(
                ':module' => self::$module,
                ':controller' => self::$controller,
                ':action' => self::$method
            ),
            'vars' => array()
        );
        
        // Se cargan las rutas
        $this->routes = $this->userRegexRoutes;
        foreach (self::$defaultRegexRoutes as $key => $value) {
            if(!isset($this->routes[$key]))
                $this->routes[$key] = $value;
        }
        self::$defaultRegexRoutes = null;
    }
    
    /**
     * Realiza el proceso de deduccion de controlador y metodo que deba tratar el requerimiento realizado, en base a la uri.
     * 
     * @return mixed $r <br> array [controller, method] <br> FALSE En caso de no poder deducirse los valores
     */
    public final function connect() 
    {
        $uri = isset($_GET['r']) ? $_GET['r'] : '/'; //slash como root
        $uri = $uri[0] != '/' ? "/$uri" : $uri; //slash antes de uri. Servers como NGINX no remueven el slash de la URI
        $r = $this->static_connect($uri);
        return $r;
    }

    /**
     * Redirecciona a la URL deseada
     * @param <string> $location URL a la cual se quiere redirigir
     * @param <boolean> $internal Si TRUE (por omision), redirecciona a recurso interno de la aplicacion,
     *                  sino a recurso externo (Ex. www.wikipedia.com)
     */
    public final static function redirect($location, $internal = TRUE) 
    {
        if ($internal)
            header("Location: " . EWebApp::config()->APP_URL . $location, true);
        else
            header("Location: " . $location, true);
        exit();
    }

    /**
     * Envía header HTTP segun codigo HTTP recibido, al cliente
     * @param <String> $header Header HTTP de estado. Por omisión, 404
     * @param <bool> $replace Parámetro opcional que indica si el header debería reemplazar un header similar anterior o agregar un segundo header del mismo tipo
     * @param <int> $code Fuerza la respuesta HTTP a un valor específico. Tener en cuenta que este parámetro sólo tiene sentido si el string no es vacio. Por omisión 404
     */
    public final static function sendHeaderHTTP($header = self::ERouter404_NotFound, $replace = true, $code = 404) 
    {
        if (headers_sent()) //Los headers han sido enviados. retornar!!
            return;
        header($header, $replace, $code);
    }
    
    /**
     * Deduce controlador y accion, en base al URI usando 
     * la tabla de ruteo configurada previamente.
     * [host/][subruta1/.../subruta_n/][controller/][action]
     * @param string $u URI de donde deducir controlador y accion
     * @return mixed Lista con controlador y accion. FALSE caso contrario
     */
    private function static_connect($uri) 
    {
        $app_data = false;
        foreach ($this->routes as $routeName => $routeValue) 
        {
            $test = preg_match($routeName, $uri, $foundValues);
            if ($test) 
            {
                $data = $this->preparePath($routeValue, $uri, $foundValues);
                $app_data = $this->loadApp($data);
                break;
            }
        }
        return $app_data;
    }
    
    /**
     * Prepara las expresiones regulares que serán utilizadas en static_connect, y genera un array de valores con las posiciones de los grupos
     */
    private function prepareRegex($routes)
    {
        foreach ($routes as $route => $params) 
        {
            $posParams = strripos($route, '*');
            if($posParams !== false && $posParams >= 0)
            {
                $endstring = '/';
                $route = substr($route, 0, $posParams - 1);
            } else {
                $endstring = '$/';
            }
            
            $search = array_values(array_filter(explode('/', $route)));
            
            // Nos aseguramos que se encuentren cargados los elementos (modulo, controlador y acción)
            $pathElements = array();
            if(!isset($params[':module'])){
                $pathElements[':module'] = in_array(':module', $search) ? self::$REGEX['alphabetic'] : self::$module;
            } else {
                $pathElements[':module'] = $params[':module'];
            }
            if(!isset($params[':controller'])){
                $pathElements[':controller'] = in_array(':controller', $search) ? self::$REGEX['alphabetic'] : self::$controller;
            } else {
                $pathElements[':controller'] = $params[':controller'];
            }
            if(!isset($params[':action'])){
                $pathElements[':action'] = in_array(':action', $search) ? self::$REGEX['alphabetic'] : self::$method;
            } else {
                $pathElements[':action'] = $params[':action'];
            }
            
            $replace = array();
            $vars = array();
            $searchPos = array();
            
            // Cargamos todas las propiedades
            foreach ($search as $pos => $key)
            {
                $strpos = strpos($key, ':');
                if($strpos === 0)
                {
                    // El módulo, controlador y la acción ya fueron cargadas
                    if($key !== ':module' && $key !== ':controller' && $key !== ':action')
                    {
                        if(key_exists($key, $params))
                        {
                            $searchPos[$key] = $pos + 1;
                            $vars[] = $key;
                            
                            $replace[] = (key_exists($params[$key], self::$REGEX))
                                ? self::$REGEX[$params[$key]]
                                : $params[$key];
                        } else {
                            $replace[] = '(' . self::$REGEX['alphabetic'] . ')';
                        }
                    } else {
                        $searchPos[$key] = $pos + 1;
                        if(key_exists($pathElements[$key], self::$REGEX))
                        {
                            $replace[] = self::$REGEX[$pathElements[$key]];
                        } else {
                            $replace[] = strpos($pathElements[$key], '(') !== FALSE ? $pathElements[$key] : '(' . $pathElements[$key] . ')';
                        }
                    }
                } else {
                    $replace[] = '(' . $key . ')';
                }
            }
            
            // Se agrega la "/" para ser reemplazada por "\/"
            $search[] = '/';
            $replace[] = '\/';
            $regex = '/^' . str_replace($search, $replace, $route) . $endstring;
            
            if(!isset($this->userRegexRoutes[$regex]))
            {
                $this->userRegexRoutes[$regex] = array(
                    'pos' => $searchPos,
                    'val' => $pathElements,
                    'vars' => $vars
                );
            }
        }
    }
    
    /**
     * Una vez encontrado el match de la expresión regular se construye el path para cargar el controlador correspondiente
     * 
     * @param array $routeValue Posee la estructura generada en prepareRegex
     * @param string $uri Es la url recibida
     * @param string $foundValues Es el array de match de preg_match
     * 
     * @return array
     */
    private function preparePath($routeValue, $uri, $foundValues)
    {
        self::$module = isset($routeValue['pos'][':module']) ? $foundValues[$routeValue['pos'][':module']] : $routeValue['val'][':module'];
        self::$controller = isset($routeValue['pos'][':controller']) ? $foundValues[$routeValue['pos'][':controller']] : $routeValue['val'][':controller'];
        self::$method = isset($routeValue['pos'][':action']) ? $foundValues[$routeValue['pos'][':action']] : $routeValue['val'][':action'];
        unset($routeValue['pos'][':module'], $routeValue['pos'][':controller'], $routeValue['pos'][':action']);

        // Se analiza la existencia de variables (url user_friendly)
        $data = array();
        $userVar = array_values($routeValue['pos']);
        foreach ($userVar as $variable) {
            if(!empty($foundValues[$variable])){
                $data[] = $foundValues[$variable];
            }
        }
        $variables = array_values(array_filter(explode('/', str_replace($foundValues[0], '', $uri))));
        return array_merge($data, $variables);
    }

    /**
     * Genera el array necesario para ejecutar la aplicación
     * 
     * @param array $data Array de parámetros recibido por URL
     * 
     * @return array
     */
    private function loadApp($data)
    {
        self::$module = ucwords(self::$module);
        // Se carga datos del modulo
        $module_path = EWebApp::config()->APP_SRC . EWebApp::config()->APP_NAME . DIRECTORY_SEPARATOR . self::$module . DIRECTORY_SEPARATOR;
        // Analizar si existe el módulo deseado
        if(file_exists($module_path))
        {
            EWebApp::config()->MODULE_NAME = self::$module;
            EWebApp::config()->MODULE_PATH = $module_path;
            // Se incluye el config del modulo (debe existir)
            $c = $module_path.'config.php';
            $config = array();
            require_once $c;
            EWebApp::config()->set($config);
            unset($config);
        }
        unset($module_path);
        // Ahora se debe analizar que exista un controlador y método
        if(!self::$controller || !self::$method) {
            if(isset(EWebApp::config()->MODULE_DEFAULT))
                $md = EWebApp::config()->MODULE_DEFAULT;
            if(!self::$controller)
                self::$controller = isset($md['controller']) ? $md['controller'] : 'undefined';
            if(!self::$method)
                self::$method = isset($md['method']) ? $md['method'] : 'undefined';
        }

        // Se encontro tanto un controlador como un método
        self::$controller = ucwords(self::$controller);
        $controllerNS = EWebApp::config()->APP_NAME . '\\' . self::$module . '\controllers\\' . self::$controller . 'Controller';
        return array($controllerNS, self::$method, $data, self::$controller);
    }
}