<?php
namespace Edufw\utils;

/**
 * Util para Metodos REST
 * TODO: Esta clase no esta terminada. Usar ERestCompatible

 * @author lmoya
 * @copyright (c) 2011-2013 Basado en la clase creada por pgambetta para EduFramework
 * @link http://en.wikipedia.org/wiki/Representational_State_Transfer WIKI sobre REST
 */
class ERest
{
  //<editor-fold defaultstate="collapsed"  desc="Metodos de entrada de datos">
  const GET = 'GET', POST = 'POST', PUT = 'PUT', DELETE = 'DELETE';
  //</editor-fold>

  //<editor-fold defaultstate="collapsed"  desc="Tipos de salida de presentacion">
  const OUTPUT_JSON = 0, OUTPUT_XML = 1, OUTPUT_HTML = 2, OUTPUT_TEXT = 3;
  //</editor-fold>

  //<editor-fold defaultstate="collapsed"  desc="Tipos de entrada de datos">
  const INPUT_JSON = 0, INPUT_TEXT = 1, INPUT_ARRAY = 2, INPUT_OBJECT = 3;
  //</editor-fold>

  //<editor-fold defaultstate="collapsed"  desc="Codigos de salida de Servicios REST">
  const
          RESP_CONTINUE = 100,
          RESP_SWITCHPROTOCOL = 101,
          RESP_OK = 200,
          RESP_CREATED = 201,
          RESP_NOCONTENT = 204,
          RESP_MOVEDPERMANENTLY = 301,
          RESP_FOUND = 302,
          RESP_SEEOTHER = 303,
          RESP_NOTMODIFIED = 304,
          RESP_TEMPORARYREDIRECT = 307,
          RESP_BADREQUEST = 400,
          RESP_UNAUTHORIZED = 401,
          RESP_FORBIDDEN = 403,
          RESP_NOTFOUND = 404,
          RESP_METHODNOTALLOWED = 405,
          RESP_NOTACCEPTABLE = 406,
          RESP_CONFLICT = 409,
          RESP_GONE = 410,
          RESP_LENGTHREQUIRED = 411,
          RESP_PRECONDITIONFAILED = 412,
          RESP_UNSUPPORTEDMEDIATYPE = 415,
          RESP_INTERNALSERVERERROR = 500,
          RESP_NOTIMPLEMENTED = 501,
          RESP_SERVICEUNAVAILABLE = 503,
          RESP_INSUFFICIENTSTORAGE = 507,
          RESP_LOOPDETECTED = 508,
          RESP_BANDWIDTHEXCEEDED = 509,
          RESP_CONNECTIONTIMEOUT = 520;
  //</editor-fold>

  /**
   * Metodo de la peticion REST
   * @var String
   * @example ERest::GET
   */
  private $method;
  /**
   * Header para la peticion REST
   * @var String
   * @example 'Content-type: application/x-www-form-urlencoded'
   */
  private $header;
  /**
   * Datos enviados en la peticion.
   * @var mixed
   */
  private $content;
  /**
   * Tipo de los datos enviandos a la peticion REST.
   *
   * @var String
   * @example ERest::INPUT_JSON
   */
  private $content_type;
  /**
   * URI del servicio REST
   *
   * @link https://en.wikipedia.org/wiki/Uniform_Resource_Identifier
   * @var String
   */
  private $uri;
  /**
   * <b>Todos los parametros estan deprecados. Solo utilizar en caso de EduApi 1.0</b>
   *
   * @param type $aplication [deprecated] Nombre de la Aplicacion Educar
   * @param type $private_key [deprecated] Clave privada Educar
   * @param type $uri [deprecated] URI servicio Educar
   * @param bool $deprecated_mode [deprecated] Activa peticiones para EduAPI 1.0
   */
  public function __construct()
  {
    $this->header = 'Content-type: application/x-www-form-urlencoded';
    $this->deprecated_encripted = $deprecated_mode;
    $this->method = self::POST;
  }

  public function setContent($data)
  {
    switch ($this->content_type)
    {
      case self::INPUT_JSON:
        $this->content = http_build_query(json_encode($data));
        break;
      case self::INPUT_TEXT:
        $this->content = $data;
        break;
      default:
        $this->content = http_build_query($data);
        break;
    }

  }

  /**
   * Llama a un método REST y devuelve la respuesta del mismo
   * @param Array $data [deprecated] Con la información a ser pasada al método.
   * @return Mixed Respuesta del servicio REST
   */
  public function callRestService($data = null)
  {
    $opts = array('http' => array('method' => $this->method, 'header' => $this->header, 'content' => $this->content ));
    $context = stream_context_create($opts);
    $returnContent = file_get_contents($this->uri, false, $context);
    //TODO: Procesar respuesta segun tipo
//    $jsonDecoded = json_decode($returnContent, TRUE);


  }

}
