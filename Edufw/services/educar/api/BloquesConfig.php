<?php
namespace Edufw\services\educar\api;

class BloquesConfig {
    
    const ERROR_LAYOUT = 'error';
    
    // Generales
    const URL_GET_IMAGEN = 'repositorio/Imagen/ver?image_id=';
    const URL_GET_FILE = 'repositorio/Download/file?file_id=';
    const URL_GET_VIDEO = 'repositorio/Video/ver?';
    const URL_VISUALIZAR_UNIDAD_HTML = 'Dinamico/UnidadHtml/obtenerSitio?rec_id=';
    const DOMAIN_FILE = 'http://globalbackendtest.educ.ar/'; //prod http://globalbackend.educ.ar/
    const DOMAIN_VIDEOS = 'http://globalbackendtest.educ.ar/'; //prod http://globalbackend.educ.ar/
    const DOMAIN_API = 'http://globalbackendtest.educ.ar/'; //prod http://globalbackend.educ.ar/
    
    public $DOMAIN_LOGIN = 'http://globalbackendtest.educ.ar/'; //prod http://registro.educ.ar/
    
    // PATH TO LAYOUT
    //---------------
    private $PATH_LAYOUT = 'layouts/sitios/';
    
    //TIPO DE OBTENCION
    //-----------------
    private $ENABLE_SESSION = false;
    
    // SESSION
    //--------
    private $SESSION_NAME = 'sitiosBloquesController';
    
    // REST
    //--------
    private $REST_RESPONSE_SUCCESS = 0;
    private $URI_CHEQ_USER_LOGGED;
     
    private $SUB_LOGIN_ERROR = 'cuentas/ServicioLogin/index';
    private $REGISTRO_URL = 'cuentas/Registro/index?servicio=educar';
    
     /**
     * Constructor
     * 
     * Es el constructor de la clase, aquí se construyen las propiedades a ser utilizadas.
     * 
     * Por lo general se concatena $this->URI_SERVER_API con la constante deseada.
     */
    public function __construct() {
        $this->LOGIN_ERROR = $this->DOMAIN_LOGIN . $this->SUB_LOGIN_ERROR;
        $this->REGISTRO_URL = $this->DOMAIN_LOGIN . $this->REGISTRO_URL;
        $this->ERROR_LAYOUT = $this->PATH_LAYOUT . self::ERROR_LAYOUT;
    }
    
   /**
    * @method getProperties
    * 
    * Método que devuelve todas las propiedades de la clase
    * 
    */
    public function getProperties($sitioConf){
        $this->URI_SERVER_API = isset($sitioConf->URI_SERVER_API) ? $sitioConf->URI_SERVER_API : self::DOMAIN_API;
        // Generales
        $url_file = isset($sitioConf->GET_FILES) ? $sitioConf->GET_FILES : self::DOMAIN_FILE;
        $url_video = isset($sitioConf->URI_SERVER_VIDEOS) ? $sitioConf->URI_SERVER_VIDEOS : self::DOMAIN_VIDEOS;
        
        $this->URL_GET_IMAGEN =  $url_file . self::URL_GET_IMAGEN;
        $this->URL_GET_VIDEO = $url_video . self::URL_GET_VIDEO;
        $this->URL_GET_FILE = $url_file . self::URL_GET_FILE;
        $this->URL_VISUALIZAR_UNIDAD_HTML = $url_file . self::URL_VISUALIZAR_UNIDAD_HTML;
        return get_object_vars($this);
    }
    
}

?>