<?php

namespace Edufw\utils;

/**
 * Clase con métodos de REST.
 * <p>Remplazo de Class/util/Rest</p>
 *
 * @author pgambetta
 * @deprecated since version 2.0
 * @link http://en.wikipedia.org/wiki/Representational_State_Transfer
 * @version 20111904
 */
class ERestCompatible
{

  private $web_service_client_key, $uri, $aplication, $private_code;

  public function __construct($aplication, $private_key, $uri)
  {
    $this->private_code = rand(1000, 999999);
    $this->web_service_client_key = $private_key;
    $this->response_web_service_client_key = $this->private_code . "_" . $private_key;
    $this->uri = $uri;
    $this->aplication = $aplication;
  }

  /**
   * Llama a un método REST y devuelve la respuesta del mismo
   * @param $data <Array> Con la información a ser pasada al método.
   * @param $uri <sting> Es la URL a la que se quiere llamar.
   * @return <string> El string es un array codificado, para la decodificación utilizar la función estática Rest::decodeRestResponse
   */
  public function callRestService($data)
  {
    //atd (APLICATION - TOKEN) DATA || ud (USER) DATA
    $dataToEncrypt = array('atd' => array('app' => $this->aplication, 'tok' => $this->private_code), 'ud' => $data);
    $json = $this->encryptJson($dataToEncrypt);
    $postdata = http_build_query(array('data' => $json, 'ci' => $data['ci']));
    $opts = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
    $context = stream_context_create($opts);
    $returnContent = file_get_contents($this->uri, false, $context);
    $jsonDecoded = json_decode($returnContent, TRUE);
    if ($returnContent === FALSE || isset($jsonDecoded['error_code']))
    {
      throw new Exception("[Class: Rest, Method: callRestService][respuesta: " . $jsonDecoded['error_code'] . "] Request mal formado.");
    }
    return $this->decodeRestResponse($returnContent);
  }

  public function getURLParamsEncypted($data)
  {
    //atd (APLICATION - TOKEN) DATA || ud (USER) DATA
    $dataToEncrypt = array('atd' => array('app' => $this->aplication, 'tok' => $this->private_code), 'ud' => $data);
    $json = $this->encryptJson($dataToEncrypt);
    $postdata = http_build_query(array('data' => $json));
    return $postdata;
  }

  /**
   * Decodifica la respuesta recibida mediante la función estática Rest::CallRestService.
   * @param $data <string> Es el array codificado devuelto por la función mencionada
   * @return <array> Luego de ser decodificado el string obtenemos un array asociativo que posee un integer de indice 'codigo' y puede tener otros valores opcionales como [data/error/mensaje]
   */
  private function decodeRestResponse($data)
  {
    if (isset($data))
    {
      $decodedData = $this->getEncryptedJson(trim($data));
      if ($decodedData == NULL)
      {
        throw new Exception("[Class: Rest, Method: decodeRestResponse] Hubo un error al desencriptar");
      }
      return $decodedData;
    }
    throw new Exception("[Class: Rest, Method: decodeRestResponse] No se recibieron los parametros necesarios para desencriptar");
  }

  private function encryptJson($data)
  {
    return @CodecCrypt::codecRC4_HEX(json_encode($data), $this->web_service_client_key);
  }

  private function getEncryptedJson($data)
  {
    return json_decode(@CodecCrypt::decodeHEX_RC4($data, $this->response_web_service_client_key), TRUE);
  }

}