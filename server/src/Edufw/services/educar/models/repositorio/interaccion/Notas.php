<?php
namespace Edufw\services\educar\models\repositorio\interaccion;
use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo NotasController
 *
 * Servicios que permiten a un usuario agregar una nota personal sobre un recurso educativo. Las notas son propias de cada sitio y privadas al usuario.
 * 
 * @version 20120614
 * @author pgambetta
 * 
 * @property String $ci
 */
class Notas extends RestModel {
    //CODIGOS
    const CODE_SUCCES = 0;
    const CODE_USUARIO_INEXISTENTE = 1;
    const CODE_SITIO_INEXISTENTE = 2;
    const CODE_RECURSO_INEXISTENTE = 3;
    const CODE_CUERPO_INVALIDO = 4;
    const CODE_NOTA_INEXISTENTE = 5;

    // MENSAJES
    const MSG_SUCCES = 'Ok';
    const MSG_INTERNAL_ERROR = 'Internal server error';
    const MSG_PARAMS_ERROR = 'Parámetros insuficientes';
    const MSG_USUARIO_INEXISTENTE = 'Usuario inexistente';
    const MSG_SITIO_INEXISTENTE = 'Sitio inexistente';
    const MSG_CUERPO_INVALIDO = 'Cuerpo de nota no válido';
    const MSG_RECURSO_INEXISTENTE = 'Recurso inexistente';
    const MSG_NOTA_INEXISTENTE = 'Nota inexistente';

    /**
     * Permite agregar una nueva nota privada a un recurso existente, para un usuario y un sitio determinado.
     * 
     * @todo Implementar método
     */
    public function agregarNota() {
        
    }

    /**
     * Permite modificar una nota privada existente, para un usuario, recurso y sitio determinado.
     * 
     * @todo Implementar método
     */
    public function modificarNota() {
        
    }

    /**
     * Permite eliminar una nota privada existente, para un usuario, recurso y sitio determinado.
     * 
     * @todo Implementar método
     */
    public function eliminarNota() {
        
    }

    /**
     * Permite obtener las notas que un usuario agregó a un recurso, en un sitio particular.
     * 
     * @todo Implementar método
     */
    public function getNotasDeRecurso() {
        
    }

    /**
     * 
     * @todo Implementar método
     */
    public function getNotasDeUsuario() {
        
    }

}

