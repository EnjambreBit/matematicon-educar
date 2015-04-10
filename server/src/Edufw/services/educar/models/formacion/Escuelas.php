<?php

namespace Edufw\services\educar\models\formacion;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo Escuelas
 *
 * Servicios referidos a la obtención de escuelas
 * 
 * @version 20121120
 * @author pgambetta
 */
class Escuelas extends RestModel {
    //CODIGOS

    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;
    const CODE_ESCUELA_INEXISTENTE = 2;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_CURSO_INEXISTENTE = 'Escuela inexistente';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';

    /**
     * Realiza una búsqueda en el padrón de escuelas
     * 
     * @param array $data (
     *      integer limit Es el límite de resultados a buscar
     *      integer offset Es el offset deseado para realizar la búsqueda
     *      integer prov_id [opcional] Es el ID de la provincia a la cual pertenece la escuela
     *      integer departamento_id [opcional] Es el ID del departamento a la cual pertenece la escuela
     *      string texto [opcional] Es el texto a buscar
     *      string sort_mode [opcional] Es la columna con la cual se desea ordenar
     *      string sort_column [opcional] Es el modo de ordenamiento asc - desc
     *      boolean get_total [opcional] Establece si se debe obtener la cantidad total o no
     * )
     * 
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function searchEscuelas($data) {
        try {
            $this->data = array();
            
            $this->data["sitio_id"] = isset($data['sitio_id']) && $data['sitio_id'] !== false ? $data['sitio_id'] : ApiCommunication::$sitio_id;
            
            if(isset($data['prov_id']) && $data['prov_id'] !== false){
                $this->data["prov_id"] = $data['prov_id'];
            }
            if(isset($data['departamento_id']) && $data['departamento_id'] !== false){
                $this->data["departamento_id"] = $data['departamento_id'];
            }
            if(isset($data['texto']) && $data['texto'] !== false){
                $this->data["texto"] = $data['texto'];
            }
            if(isset($data['limit']) && $data['limit'] !== false){
                $this->data["limit"] = $data['limit'];
            }
            
            if(isset($data['offset']) && $data['offset'] !== false){
                $this->data["offset"] = $data['offset'];
            }
            
            if(isset($data['sort_mode']) && $data['sort_mode'] !== false){
                $this->data["sort_mode"] = $data['sort_mode'];
            }
            
            if(isset($data['sort_column']) && $data['sort_column'] !== false){
                $this->data["sort_column"] = $data['sort_column'];
            }
            
            if(isset($data['get_totalFound']) && $data['get_totalFound'] !== false){
                $this->data["get_totalFound"] = $data['get_totalFound'];
            }

            $this->uri = ApiCommunication::get_api_uri('URL_SEARCH_ESCUELA');
            return $this->callRestService();
        } catch (\Exception $e) {
            $logger = new \Edufw\core\logger\ELogger();
            $logger->warning('[Escuelas/searchEscuelas] ' . $e->getMessage());
        }

        return array();
    }

    /**
     * Obtiene los datos de un escuela
     * 
     * @param integer $prov_id Es el ID de la provincia a la cual pertenece la escuela
     * @param integer $departamento_id Es el ID del departamento a la cual pertenece la escuela
     * @param integer $localidad_id Es el ID de la localidad a la cual pertenece la escuela
     * @param integer $esculea_id Es el ID de la escuela
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getEscuelaFull($prov_id, $departamento_id, $localidad_id, $esculea_id, $sitio_id = false) {
        try {
            $type = 'id';
            $this->data = array(
                "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
                "prov_id" => $prov_id,
                "departamento_id" => $departamento_id,
                "localidad_id" => $localidad_id,
                "esculea_id" => $esculea_id
            );

            $this->uri = ApiCommunication::get_api_uri('URL_OBTENER_ESCUELA_FULL');
            return $this->callRestService();
        } catch (\Exception $e) {
            $logger = new \Edufw\core\logger\ELogger();
            $logger->warning('[Escuelas/getEscuelaFull] ' . $e->getMessage());
        }

        return array();
    }

}