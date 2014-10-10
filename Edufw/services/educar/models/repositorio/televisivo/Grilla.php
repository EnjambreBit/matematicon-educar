<?php

namespace Edufw\services\educar\models\repositorio\televisivo;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo UserConfirmation
 *
 * @version 20120614
 * @author pgambetta
 */
class Grilla extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_SITIO_INEXISTENTE = 1;
    const CODE_FECHA_INCORRECTA = 2;
    const CODE_HORA_INCORRECTA = 3;
    const CODE_EMAIL_INCORRECTO = 4;
    const CODE_HORARIO_INCORRECTO = 5;
    const CODE_RANGO_FECHA_INCORRETCTO = 2;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';
    const MSG_FECHA_INCORRECTA = 'Fecha incorrecta';
    const MSG_HORA_INCORRECTA = 'Hora incorrecta';
    const MSG_EMAIL_INCORRECTO = 'Email invalido';
    const MSG_HORARIO_INCORRECTO = 'Recurso inexistente en grilla';
    const MSG_RANGO_FECHA_INCORRETCTO = 'Rango de fecha y hora incorrecto ';

    /**
     * Permite obtener la programación de la grilla de un día entero, para el sitio especificado.
     * 
     * @param string $fecha
     * @param integer $sitio_id [opcional]
     * 
     * @method getDate
     * 
     * @return ApiResponse
     */
    public function getDate($fecha, $sitio_id = false)
    {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "fecha" => $fecha
        );
        $this->uri =  ApiCommunication::get_api_uri('URL_TELEVISIVO_GRILLA');
        return $this->callRestService();
    }

    /**
     * Permite obtener el recurso actual asignado en la grilla de programación, para el sitio especificado. 
     * 
     * @param integer $sitio_id [opcional]
     * 
     * @method getNow
     * 
     * @return ApiResponse
     */
    public function getNow($sitio_id = false)
    {
        $this->data = array("sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id);
        $this->uri =  ApiCommunication::get_api_uri('URL_TELEVISIVO_GRILLA_ACTUAL');
        return $this->callRestService();        
    }

    /**
     * Permite agregar un nuevo recordatorio para un slot de la grilla de un sitio determinado. El recordatorio se agrega para el usuario especificado.
     * Luego un proceso envia un mail de recordatorio con la cantidad de horas de anticipación especificadas.
     * 
     * @method crearRecordatorio
     * @param string $user Es el ID de usuario
     * @param string $login_token Es el token de login
     * @param string $fecha Es la fecha del recordatorio
     * @param string $horario_inicio Es el rango horario para el recordatorio
     * @param string $mail_destino Es el mail del usuario donde llegará el recordatorio
     * @param string $horas_anticipacion Son la cantidad de horas de anticipación del recordatorio
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function crearRecordatorio($user, $login_token, $fecha, $horario_inicio, $mail_destino, $horas_anticipacion, $sitio_id = false)
    {
         $this->data = array(
             "usr_id" => $user,
             "login_token" => $login_token,
             "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
             "fecha" => $fecha,
             "horario_inicio" => $horario_inicio,
             "mail" => $mail_destino,
             "horas_anticipacion" => $horas_anticipacion
        );
        $this->uri =  ApiCommunication::get_api_uri('URL_TELEVISIVO_GRILLA_RECORDATORIO');
        return $this->callRestService();       
    }

    /**
     * Dado un recurso de tipo funcional Emision y un sitio, 
     * permite obtener todas las apariciones de los capítulos de esta emisión 
     * en la grilla del sitio especificado en el rango de fechas especificado.
     * 
     * @method getOcurrenciasCapitulosDeEmisionEnGrilla
     * 
     * @param integer $rec_id Es el ID de la emisión deseada
     * @param string $fecha_inicio
     * @param string $horario_inicio
     * @param string $fecha_fin
     * @param string $horario_fin
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */

    public function getOcurrenciasCapitulosDeEmisionEnGrilla($rec_id, $fecha_inicio, $horario_inicio, $fecha_fin, $horario_fin, $sitio_id = false)
    {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "rec_id" => $rec_id,
            "fecha_inicio" => $fecha_inicio,
            "horario_inicio" => $horario_inicio,
            "fecha_fin" => $fecha_fin,
            "horario_fin" => $horario_fin
        );
        $this->uri =  ApiCommunication::get_api_uri('URL_TELEVISIVO_OCURRENCIAS_CAPITULOS_DE_EMISION');
        return $this->callRestService();      
    }

    /**
     * Dado un recurso de tipo funcional Emision y un sitio, 
     * permite obtener todas las apariciones de los capítulos de esta emisión 
     * en la grilla del sitio especificado en el rango de fechas especificado.
     * 
     * @method getOcurrenciasRecursoEnGrilla
     * 
     * @param integer $rec_id Es el ID de la emisión deseada
     * @param string $fecha_inicio
     * @param string $horario_inicio
     * @param string $fecha_fin
     * @param string $horario_fin
     * @param integer $sitio_id [opcional]
     * 
     * @return ApiResponse
     */
    public function getOcurrenciasRecursoEnGrilla($rec_id, $fecha_inicio, $horario_inicio, $fecha_fin, $horario_fin, $sitio_id = false)
    {
        $this->data = array(
            "sitio_id" => $sitio_id == false ? ApiCommunication::$sitio_id : $sitio_id,
            "rec_id" => $rec_id,
            "fecha_inicio" => $fecha_inicio,
            "horario_inicio" => $horario_inicio,
            "fecha_fin" => $fecha_fin,
            "horario_fin" => $horario_fin
        );
        $this->uri =  ApiCommunication::get_api_uri('URL_TELEVISIVO_OCURRENCIAS_RECURSO');
        return $this->callRestService();        
    }

}
