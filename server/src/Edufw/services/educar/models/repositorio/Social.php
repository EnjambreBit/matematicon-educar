<?php
namespace Edufw\services\educar\models\repositorio;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;
/**
 * Social
 *
 *
 *
 * @author lmoya
 */
class Social extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';

    /**
     * Obtiene los recursos destacados para los intereses seleccionados
     *
     * @param array $filters filtros de los recursos destacados
     * @return ApiResponse
     */
    public function recursos_destacados($filters) {
        $this->data = array(
            "tema_id" => isset($filters['temas']) ? $filters['temas'] : null ,
            "extracurricular_id" => isset($filters['extracurriculares']) ? $filters['extracurriculares'] : null,
            "order" => isset($filters['order'])? $filters['order'] : 'DESC',
            "limit" => isset($filters['limit'])? $filters['limit'] : '10'
        );
        $this->uri = ApiCommunication::get_api_uri('URL_SOCIAL_GET_RECURSOS_DESTACADOS');
        return $this->callRestService();
    }

}

