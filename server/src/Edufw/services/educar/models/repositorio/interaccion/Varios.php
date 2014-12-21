<?php
namespace Edufw\services\educar\models\repositorio\interaccion;
use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Modelo con soporte REST para realizar acciones varias.
 *
 * Agrupación de diversos tipos de servicios no relacionados a ninguna categoría en especial.
 * 
 * @name Varios
 * @version 20120614
 * @author pgambetta
 */
class Varios extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;
    const CODE_RECURSO_INEXISTENTE = 2;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_RECURSO_INEXISTENTE = 'Recurso inexistente';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';
    const CODE_EN_CONSTRUCCION = 'Esta funcionalidad se encuentra en construcción';

    /**
     * 
     * @todo Implementar método
     */
    public function getNovedades() {
        
    }

    /**
     * 
     * @todo Implementar método
     */
    public function getRanking() {
        
    }

    /**
     * 
     * @todo Implementar método
     */
    public function getDestacados() {
        
    }

    /**
     * 
     * @todo Implementar método
     */
    public function getPermisosDeRecurso() {
        
    }

    /**
     * Servicio que se utiliza para corroborar si el Repositorio se encuentra Online. 
     * 
     * No devuelve ninguna información particular.
     * 
     * @todo Implementar método
     */
    public function ping() {
        
    }

    /**
     * Permite obtener el conjunto de recursos más votados para un sitio particular. 
     * 
     * @todo Implementar método
     */
    public function getMasVotados() {
        
    }

    /**
     * Permite obtener el conjunto de recursos más descargados en su formato offline, para un sitio particular.
     * 
     * @todo Implementar método
     */
    public function getMasDescargadosOffline() {
        
    }

    /**
     * Permite obtener el conjunto de etiquetas que pertenecen a las etiquetas más utilizadas para catalogar.
     * 
     * @todo Implementar método
     */
    public function getNubeEtiquetas() {
        
    }

    /**
     * Permite obtener el conjunto de etiquetas que pertenecen a las etiquetas más utilizadas para catalogar.
     * 
     * @todo Implementar método
     */
    public function getSitioData() {
        
    }

    /**
     * 
     * @todo Implementar método
     */
    public function getArticulosDeCategoria() {
        
    }

    /**
     * Obtiene las relaciones implicitas de un recurso
     * @method getRelacionesImplicitas
     * 
     * @param integer $rec_id Es el ID del recurso
     * 
     * @return ApiResponse
     */
    public function getRelacionesImplicitas($rec_id) {
        $this->data = array(
            "sitio_id" => ApiCommunication::$sitio_id,
            "rec_id" => $rec_id
        );
        $this->uri = ApiCommunication::get_api_uri('URL_GET_RECURSOS_RELACIONADOS');
        return $this->callRestService();
    }

}
