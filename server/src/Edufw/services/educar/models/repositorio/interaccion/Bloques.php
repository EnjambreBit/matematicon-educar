<?php
namespace Edufw\services\educar\models\repositorio\interaccion;
use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;
use Edufw\utils\TreeBuilder;

/**
 * Descripcion de Modelo Bloques
 *
 * Servicios referidos a bloques de sitios. Un bloque es una agrupación lógica de recursos dentro de un sitio. Se utiliza para diferenciar el contenido de un sitio en secciones o regiones. Un bloque puede contener un conjunto de recursos y conjunto de destacados.
 * 
 * @version 20120614
 * @author pgambetta
 */
class Bloques extends RestModel {
    
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;
    const CODE_RECURSO_INEXISTENTE = 2;
    const CODE_BLOQUE_INEXISTENTE = 2;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_RECURSO_INEXISTENTE = 'Recurso inexistente';
    const MSG_BLOQUE_INEXISTENTE = 'Bloque inexistente';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';
    
    public static $aliasIds = null;

    /**
     * Permite obtener el conjunto de bloques existentes para un sitio
     * 
     * @param string $bloque_alias [OPCIONAL] Establece desde que bloque devolver el tree
     * @param integer $sitio_id [opcional]
     * 
     * @return array En caso de no encontrar el sitio o el bloque retorna array vacio
     */
    public function getBloquesDeSitio($bloque_alias = null, $sitio_id = false) {
        try{
            $bloque_data = array();
            $estructura_sitio = array();
            $this->data = array(
                "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id
            );
            $this->uri = ApiCommunication::get_api_uri('URI_GET_BLOQUES');
            $estructura = $this->callRestService();
            if(!$estructura->error){
                foreach ($estructura->data['bloques'] as $bloque) {
                    $bloque_data[$bloque['alias']] = array(
                        'id' => $bloque['id'],
                        'titulo' => $bloque['titulo'],
                        'descripcion' => $bloque['descripcion'],
                        'id_padre' => $bloque['id_padre']
                    );
                }
                $estructura_sitio = TreeBuilder::addNode($estructura->data['bloques'], null);
                if($bloque_alias !== null && isset($bloque_data[$bloque_alias])){
                    $estructura_sitio = TreeBuilder::cloneNode($estructura_sitio, $bloque_data[$bloque_alias]['id']);
                }
                return $estructura_sitio;
            }            
        } catch (Exception $e){
            ELogger::log('[Bloques/getBloquesDeSitio] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
        }
        return array();
    }

    /**
     * Permite obtener la información de un bloque de un sitio.
     * 
     * @param int $bloque_sitio_id ID del bloque a buscar
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getBloque($bloque_sitio_id, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "bloque_sitio_id" => $bloque_sitio_id
        );
        $this->uri = ApiCommunication::get_api_uri('URI_GET_BLOQUE');
        return $this->callRestService();        
    }

    /**
     * Obtiene los recursos de un bloque
     * @method getRecursosBloque
     * 
     * @param int $bloque_sitio_id Es el ID del bloque
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getRecursosBloque($bloque_sitio_id, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "bloque_sitio_id" => $bloque_sitio_id
        );
        $this->uri = ApiCommunication::get_api_uri('URI_GET_RECURSOS_DE_BLOQUE');
        return $this->callRestService();
    }

    /**
     * Obtiene los bloques a los que pertenecen los recursos dados
     * @method getBloquesRecursos
     * 
     * @param array $recursos Listado de ID's de recursos
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getBloquesRecursos($recursos, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "recursos" => $recursos
        );
        $this->uri = ApiCommunication::get_api_uri('URI_GET_BLOQUES_DE_RECURSOS');
        return $this->callRestService();
    }
    
    /**
     * Obtiene los destacados de un bloque
     * @method getDestacadosBloque
     * 
     * @param integer $bloque_id Es el ID del bloque
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getDestacadosBloque($bloque_id, $limit = 100, $offset = 0, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "bloque_sitio_id" => $bloque_id,
            "limit" => $limit,
            "offset" => $offset
        );
        $this->uri = ApiCommunication::get_api_uri('URI_GET_DESTACADOS');
        return $this->callRestService();
    }

    /**
     * Obtiene la informacion de un bloque
     * @method getBloqueByAlias
     * 
     * @param string $bloque_sitio_alias Es el alias del bloque
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getBloqueByAlias($bloque_sitio_alias, $sitio_id = false) {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "bloque_sitio_alias" => $bloque_sitio_alias
        );
        $this->uri = ApiCommunication::get_api_uri('URI_GET_ALIAS');
        return $this->callRestService();
    }
    
    /**
     * Obtiene la informacion de un bloque
     * @method getBloqueByAlias
     * 
     * @param string $bloque_sitio_alias Es el alias del bloque
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getAliasIdArray($sitio_id = false) {
        try{
            $alias_ids = array();

            $this->data = array(
                "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id
            );
            $this->uri = ApiCommunication::get_api_uri('URI_GET_BLOQUES');

            $response = $this->callRestService();

            if(!$response->error){
                foreach ($response->data['bloques'] as $bloque)
                    $alias_ids[$bloque['alias']] = $bloque['id'];
                $response->data = $alias_ids;
            }            
        } catch (Exception $e){
          ELogger::log('[Bloques/getAliasIdArray] ' . $e->getMessage(), ELoggerLevel::LEVEL_WARN);
          $response->error = true;
          $response->data = array();            
        }

        return $response;
    }    

}
