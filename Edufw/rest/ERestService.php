<?php
namespace Edufw\rest;
use Edufw\utils\ERest;
use Edufw\core\ERouter;

/**
 * Clase que representa un helper REST.
 * Presta servicios de REST a un controlador.
 *
 * @name ERestService
 * @category Helper a nivel sistema
 * @package lib/components/helpers
 * @version 20110203
 * @author gseip
 */
class ERestService {
    //public $httpMethod;
    public $httpParams;
    public $outputType = ERest::OUTPUT_JSON;
    public $received_http_method = "";
    private $templateView;
    private $content;
    private $action; //Usado en REST no standard

    public static $MAP_RESOURCE = array(
        'GET'=>'view', 'POST'=>'add', 'PUT'=>'edit', 'DELETE'=>'delete');

    public function __construct() {
        $this->parseHttpParams();
    }

    public function parseExtensions($extension) {

    }

    public function parseHttpParams() {
        $this->received_http_method = $_SERVER['REQUEST_METHOD'];
        switch ($_SERVER['REQUEST_METHOD']) {
            case ERest::GET:
                $this->httpParams = $_GET;
                break;
            case ERest::DELETE:
                $this->httpParams = $_GET;
                break;
            case ERest::POST:
                $this->httpParams = $_POST;
                break;
            case ERest::PUT:
                $put = fopen('php://input', 'r');
                $put_data = '';
                while ($data = fread($put, 1024)) $put_data .= $data;
                fclose($put);
                parse_str($put_data, $this->httpParams);
                break;
        }
    }

    public function mapAction() {
        return self::$MAP_RESOURCE[$_SERVER['REQUEST_METHOD']];
    }

    /**
     * todo gus Falta implementar
     */
    public function mapCustomAction() {

    }

    public function output() {
        if (!isset($this->content)) {
            throw new EException('[ERestService, output] No se definio el contenido para la presentacion');  }
        if(headers_sent()){ header_remove(); }
        switch ($this->outputType) {
            case ERest::OUTPUT_TEXT:
                ERouter::sendHeaderHTTP(ERouter::HContentTypeTEXTPLAIN);
                echo $this->content;
                break;
            case ERest::OUTPUT_JSON:
                $jsonData = $this->toJSON($this->content);
                ERouter::sendHeaderHTTP(ERouter::HContentTypeJSON);
                echo $jsonData;
                break;
            case ERest::OUTPUT_XML:
                break;
            case ERest::OUTPUT_HTML:
                break;
        }
        exit;
    }

    public function setContent($content) {
        if (!isset($content)) {
            throw new EException('[ERestService, setBodyMessage] Se debe definir el contenido para la presentacion');  }
        $this->content = $content;
    }

    public function setTemplateView($templateView) {
        if (!isset($templateView)) {  throw new EException('[ERestService, setTemplateView] Se debe definir la plantilla de presentacion bajo la cual renderizar el contenido');  }
        $this->templateView = $templateView;
    }

    public function addETag($etag) {

    }

    private function toJSON($data) {
        return json_encode($data);
    }

    private function toXML() {

    }

    private function toCVS() {

    }

}
