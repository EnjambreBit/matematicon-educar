<?php
namespace Edufw\core;
use Edufw\core\EView;
use Edufw\core\ERouter;
use Edufw\core\EWebApp;

/**
 * Clase base para todos los controladores.
 *
 * @name EController
 * @package lib
 * @version 20110204
 * @author gseip
 */
abstract class EController {
    
    /**
     * Etablce si las propiedades del sitio ya fueron o no cargadas
     * 
     * @var bool 
     */
    public static $LOADED_VARS = false;
    
    /**
     * Posse el nombre del sitio en ejecucion
     * 
     * @var string 
     */
    public $site_name;
    /**
     * Posee el path al root de las vistas del sitio
     * @var string 
     */
    public $path_view;
    /**
     * Posee el path al root de los layouts del sitio
     * @var string 
     */
    public $path_layout;
    /**
     * Posee el path root de los js del sitio
     * @var string 
     */
    public $path_js;
    /**
     * Posee el path al root de los css del sitio
     * @var string 
     */
    public $path_css;
    /**
     * Posee el path al root de las imágenes del sitio
     * @var string 
     */
    public $path_img;
    /**
     * Posee el path (con dominio) al root de los js del sitio
     * @var string 
     */
    public $full_path_js;
    /**
     * Posee el path (con dominio) al root de los css del sitio
     * @var string 
     */
    public $full_path_css;
    /**
     * Posee el path (con dominio) al root de las imágenes del sitio
     * @var string 
     */
    public $full_path_img;
    /**
     * Posee el path (completo) al root de la carpeta de subida de archivos del sitio
     * @var string 
     */
    public $path_upload;
    /**
     * Posee el path (con dominio) al root de la carpeta pública de subida de archivos del sitio
     * @var string 
     */
    public $path_webroot_upload;
    
    /**
     * Lista de conexiones a base de datos
     */
    protected $_edbConnections = array();
    /**
     * Conexion por default afectada a este controlador
     * 
     * @var Object
     */    
    public static $_edbDefaultConnection;
    
    /**
     * Posee todas las propiedades cargadas en la clase de configuración 
     * 
     * @var array 
     */
    public static $_local_config;
    
    /**
     * Guarda los datos a ser usados por la vista requerida
     * 
     * @var Array
     */
    protected $_data = array();

    /**
     * @var \Mustache_Engine
     */
    public static $templateRenderer = null;
    
    /**
     * @var array Es el request de la peticion 
     */
    public $request;
    
    /**
     * Establece si renderizar de forma automática o no
     * @var bool
     */
    public $autoRender = true;
    
    /**
     * Acciones realizadas antes de que se ejecute la accion requerida,
     * (util para ACLs).
     * @param <string> $controller Recurso al cual se desea acceder (URL_BASE/resource/action/param1/param2/.../paramN)
     * @param <string> $action Accion a la cual se desea acceder
     */
    public function beforeRunAction($controller, $action) {
        ;
    }

    /**
     * Renderiza una vista para producir una salida HTML o una presentacion REST
     * @param <string> $file Nombre del archivo de vista a renderizar
     * @param <mixed> $data Arreglo de parametros a pasar a la vista
     * @param <string> $layout Nombre de la estructura HTML que contendra la vista
     * <p>$layout='layout1' renderiza con layout 'layout1'
     *    $layout=FALSE renderiza sin layout (util para AJAX)
     *    $layout=NULL renderiza con layout 'index' predeterminado
     * </p>
     */
    public final function render($file, $layout=FALSE) {
        $file = EWebApp::config()->MODULE_NAME . '/views/' . $file;
        $layout = $layout !==false ? EWebApp::config()->MODULE_NAME . '/views/layouts/' . $layout : $layout;
        $out = EView::getProcessContent(array('view'=>$file,'data'=>$this->_data,'layout'=>$layout));
        echo $out;
        exit;
    }

    /**
     * @param string $template Es el template a utilizar
     * @param array $context Array con los datos a ser procesados
     */
    public final function renderTemplate($template, $context){
        echo self::$templateRenderer->render($template, $context);
        exit;
    }
    
    /**
     * Realiza el envio correcto de headers y el json correspondiente
     * @param <array|json> $json El json a ser enviado, si se recibe un array el mismo es codificado
     * @param <bool> $replace Parámetro opcional que indica si el header debería reemplazar un header similar anterior o agregar un segundo header del mismo tipo
     * @param <int> $code Fuerza la respuesta HTTP a un valor específico. Tener en cuenta que este parámetro sólo tiene sentido si el string no es vacio. Por omisión 404
     */
    public final function sendJSON($json, $replace = true, $code = 200) {
        ERouter::sendHeaderHTTP(ERouter::HContentTypeJSON, $replace, $code);
        echo is_array($json) ? json_encode($json) : $json;
        exit;
    }
    
    /**
     * Convierte un array a un json, formateando la respuesta en el standar educ.ar
     * 
     * @param array $data Es un array de datos a ser convertido en parte del JSON
     * @param bool $result [OPCIONAL] Determina si la peticion fue o no un exito
     */
    public final function sendJsonResponse($data, $result = true) {
        $json = json_encode(array ("ResultSet"=>array("Result"=>$result , 'data'=>$data)));
        $this->sendJSON($json);
    }
    
    /**
     * Muestra pagina por ocurrencia de error interno de sistema
     * @param string $e Excepcion ocurrida
     */
    public function internal_error() {
        $s = new ESession();
        if (isset($s->SYSTEM_INTERNAL_ERROR_EXCEPTION)) {
            $this->_data['exception'] = $s->SYSTEM_INTERNAL_ERROR_EXCEPTION;
            unset($s->SYSTEM_INTERNAL_ERROR_EXCEPTION);  }
        if (EWebApp::conf()->APP_MODE=='dev') {
            $this->render('error/exception', NULL, 'layouts/exception');
        }   else    {

        }
    }

    /**
     * Establece el arreglo de datos del controlador
     * @param <array> $data Arreglo con datos, usualmente obtenidos de una variable $_POST
     */
    public final function setData($data) {
        $this->_data = array_merge($this->_data, $data);
    }
    
    public final function setParameters($params){
        $this->request = array_merge($this->request, $params);
    }
    /**
     * Carga en caso de que el proyecto lo requiera una librería de manejo de templates
     */
    public final function loadTemplateRenderer(){
        $templateData = EWebApp::config()->TEMPLATE_RENDERER;            
        if($templateData['class'] === 'EMustache'){
            $options = isset($templateData['mustacheOptions']) ? $templateData['mustacheOptions'] : null;
            self::$templateRenderer = \Edufw\web\views\templates_engines\EMustache::mustache($options);
        }
        
    }
    
    /**
     * Establece el arreglo de datos del controlador
     * @param <array> $data Arreglo con datos, usualmente obtenidos de una variable $_POST
     */
    public final function setParams($params) {
        $this->_params = $params;
    }

    /**
     * Redirecciona a otro controlador. Este nuevo controlador tiene el control del flujo de procesamiento.
     * @param <type> $controllerName Nombre de la clase del controlador necesario.
     * @param <type> $method Nombre del metodo a llamar sobre este controlador
     * @param <type> $data Datos pasados al nuevo controlador.
     */
    public final static function redirect($controllerName, $method, $data=NULL) {
        $controllerInstance = EWebApp::loadController($controllerName, TRUE); //Obtener instancia
        if (isset ($data))
            $controllerInstance->setData($data);
        $controllerInstance->$method(); //El flujo de procesamiento pasa al controlador llamado!
        throw new Exception("EController.redirect - Redireccion incompleta");
        exit;
    }
    
    /**
     * Convierte la variable $_POST['__params'], $_GET['__params'] y retorna un array.
     * Limpiando cualquier tipo de inyeccion de codigo y tags html.
     */
    public final function getRequest(){
        $result = array();
        foreach ($_REQUEST as $key => $value) {
            if($key == '__params'){
                $result = json_decode(strip_tags(urldecode($_REQUEST['__params'])), true);
            } else {
                $result[$key] = strip_tags($value);
            }
        }
        return $result;
    }
}
