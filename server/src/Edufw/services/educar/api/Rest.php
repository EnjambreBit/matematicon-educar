<?php
namespace Edufw\services\educar\api;
use Edufw\security\CodecCrypt;
use Edufw\core\EException;

class ApiResponse {
     
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
    
    // Propiedades privadas
   
    /**
     * Posee toda la respuesta recibida en la petición API
     * 
     * @var String
     */
    private $a;

    /**
     * Posee el codigo de respuesta de la peticion API
     * 
     * @var Integer
     */
    private $c;
    
    /**
     * Posee el mensaje de respuesta de la peticion API
     * @var String 
     */
    private $m;
    
    /**
     * Es el constructor de la clase
     * @method __construct
     * 
     * @param string $a (API response) Es la respuesta de la petición API
     */
    public function __construct($a, $sc = FALSE) {
        $this->c = intval($a['codigo']);
        $this->m = $a['mensaje'];
        unset($a['codigo'], $a['mensaje']);
        $this->a = $a;
        $success_code = ($sc === FALSE) ? self::API_SUCCESS_CODE : $sc;
        if($this->c === $success_code){
            $this->error = false;
        }
        $this->build();
    }
    
    private function build(){
        foreach ($this->a as $key => $value) {
            $this->data[$key] = $value;
        }
    }
   
    /**
     * Retorna el código de respuesta de la petición API
     * @method getApiErrorCode
     * 
     * @return Integer
     */
    public function getApiErrorCode(){
        return $this->c;
    }
   
    /**
     * Retorna el mensaje de respuesta de la petición API
     * @method getApiMessage
     * 
     * @return String
     */
    public function getApiMessage(){
        return $this->m;
    }
    
    /**
     * Retorna el log producido por la petición API
     * @method getApiLog
     * 
     * @return String
     */
    public function getApiLog(){
        return '[ApiResponse, getApiLog][codigo: ' . $this->c . ', respuesta: ' . $this->m . ']';
    }
    
    //throw new EException("[Class: BloquesController, Method: callRestService][respuesta: ".$jsonDecoded['error_code']."] Request mal formado.");
}

/**
 * Clase con métodos de REST.
 *
 * http://en.wikipedia.org/wiki/Representational_State_Transfer
 *
 * @author pgambetta
 * @version 20111904
 */
class Rest {

    private $web_service_client_key, $uri, $aplication, $private_code;

    /** constantes con codigos de respuesta HTTP utilizados en REST */
    const RESP_OK = 200, RESP_CREATED = 201, RESP_NOCONTENT = 204, RESP_MOVEDPERMANENTLY = 301,
          RESP_FOUND = 302, RESP_SEEOTHER = 303, RESP_NOTMODIFIED = 304, RESP_TEMPORARYREDIRECT = 307,
          RESP_BADREQUEST = 400, RESP_UNAUTHORIZED = 401, RESP_FORBIDDEN = 403,
          RESP_NOTFOUND = 404, RESP_METHODNOTALLOWED = 405, RESP_NOTACCEPTABLE = 406,
          RESP_GONE = 410, RESP_LENGTHREQUIRED = 411, RESP_PRECONDITIONFAILED = 412,
          RESP_UNSUPPORTEDMEDIATYPE = 415, RESP_INTERNALSERVERERROR = 500;

    public function __construct($aplication, $private_key, $uri) {
        $this->private_code = rand(1000, 999999);
        $this->web_service_client_key = $private_key;
        $this->response_web_service_client_key = $this->private_code . "_" . $private_key;
        $this->uri = $uri;
        $this->aplication = $aplication;
    }

    /**
     * Llama a un método REST y devuelve la respuesta del mismo
     * @param $data Array Con la información a ser pasada al método.
     * @param $ci sting Es la clave privada
     * @param $uri sting Es la URL a la que se quiere llamar.
     * @return string El string es un array codificado, para la decodificación utilizar la función estática Rest::decodeRestResponse
     */
    public function callRestService($data, $ci){
	//atd (APLICATION - TOKEN) DATA || ud (USER) DATA
        $dataToEncrypt = array('atd' => array( 'app' => $this->aplication, 'tok' => $this->private_code), 'ud' => $data);
        $json = $this->encryptJson($dataToEncrypt);
        $postdata = http_build_query(array('data'=> $json, 'ci' => $ci));
        $opts = array('http' => array('method'  => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
        $context  = stream_context_create($opts);
        $returnContent = FALSE;
        $returnContent = file_get_contents($this->uri, false, $context);
        if ($returnContent === FALSE) {
            $exceptionMessage = 'Requerimiento mal formado';
        } else {
            $jsonDecode = json_decode($returnContent, TRUE);
            if ($jsonDecode === FALSE || isset($jsonDecode['error_code'])) {
                $exceptionMessage = "Requerimiento mal formado [{$jsonDecode['error_code']}]";
            }
        }
        if (isset($exceptionMessage)) {
            throw new EException($exceptionMessage);
        }
        return $this->decodeRestResponse($returnContent);
    }
    
    public function getURLParamsEncypted($data){
        //atd (APLICATION - TOKEN) DATA || ud (USER) DATA
        $dataToEncrypt = array('atd' => array( 'app' => $this->aplication, 'tok' => $this->private_code), 'ud' => $data);
        $json = $this->encryptJson($dataToEncrypt);
        $postdata = http_build_query(array('data'=> $json));
        return $postdata;
    }

     /**
     * Decodifica la respuesta recibida mediante la función estática Rest::CallRestService.
     * @param $data string Es el array codificado devuelto por la función mencionada
     * @return array Luego de ser decodificado el string obtenemos un array asociativo que posee un integer de indice 'codigo' y puede tener otros valores opcionales como [data/error/mensaje]
     */
    private function decodeRestResponse($data){
        if(isset ($data)){
            $decodedData = $this->getEncryptedJson(trim($data));
            if($decodedData == NULL){
                throw new EException("[Class: Rest, Method: decodeRestResponse] Hubo un error al desencriptar");
            }
            return $decodedData;
        }
        throw new EException("[Class: Rest, Method: decodeRestResponse] No se recibieron los parametros necesarios para desencriptar");
    }

    private function encryptJson($data){
        return @CodecCrypt::codecRC4_HEX(json_encode($data), $this->web_service_client_key);
    }

    private function getEncryptedJson($data){
        return json_decode(@CodecCrypt::decodeHEX_RC4($data, $this->response_web_service_client_key), TRUE);
    }
}
