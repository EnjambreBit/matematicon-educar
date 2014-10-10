<?php

namespace Edufw\services\educar\models;
use Edufw\services\educar\api\Rest;
use Edufw\services\educar\api\ApiResponse;
use Edufw\services\educar\api\ApiCommunication;

class RestModel 
{
    
    // constantes
     
    const API_ERROR_EXCEPTION_CODE = -1;
    const API_SUCCESS_CODE = 0;
    
    // Propiedades publicas
    
    /**
     * Boolean que establece si la petición API fue o no satisfactoria
     * 
     * @var Bool
     */
    public $error = true;
    
    /**
     * Array que posee la información obtenida mediante la petición API
     * 
     * @var Array
     */
    public $data = array();
    
    /**
     * Es el nombre del sitio que realiza la peticion
     * 
     * @var String 
     */
    protected $site_name;

    /**
     * Es la clave de la aplicacion
     * 
     * @var String 
     */
    protected $web_service_client_key;    
    
    /**
     * Es la url a donde realizar la peticion
     * 
     * @var String 
     */
    protected $uri;
    
    /**
     * Es el id de clave de la aplicacion
     * 
     * @var String 
     */
    protected $ci;
    
    /**
     * Es el id del sitio
     * 
     * @var Integer 
     */
    protected $sitio_id;
    
     public function __construct() 
    {
        $this->ci = ApiCommunication::$ci;
        $this->site_name = ApiCommunication::$sitio_nombre;
        $this->web_service_client_key = ApiCommunication::$web_service_client_key;
    }
    
     /**
     * Realiza un petición REST y devuelve un array con los resultados
     * @method callRestService
     * 
     * @return ApiResponse
     */
    public function callRestService($response_code = FALSE) 
    {
        try{
            $code = false;
            $r = new Rest($this->site_name, $this->web_service_client_key, $this->uri);
            $a = $r->callRestService($this->data, $this->ci);
            unset($r);
            return new ApiResponse($a);
        } catch (\Edufw\core\EException $e){
            $a = array('codigo' => $e->getCode(), 'mensaje' => $e->getMessage());
            return new ApiResponse($a, ApiResponse::API_ERROR_EXCEPTION_CODE);
        }
    }
}
