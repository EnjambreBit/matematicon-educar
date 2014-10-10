<?php

namespace Edufw\services\educar\controllers;

use Edufw\core\EController;
use Edufw\core\EWebApp;
use Edufw\sessions\SessionUtilities;
use Edufw\services\educar\api\ApiCommunication;
use Edufw\utils\TreeBuilder;
use Edufw\services\educar\api\ApiResponse;
use Edufw\core\EException;
use Edufw\core\ELogger;
use Edufw\core\ELoggerLevel;
use Edufw\services\educar\api\BloquesConfig;

/**
 * BloquesController
 *
 * @author pgambetta, lmoya
 */
class BloquesController extends EController {
    // Constantes
    const PATH_TO_CONFIG = 'configurations/sitios/';
    const DATA_CODE_SESSION = 0;
    const DATA_MSG_SESSION = 'Datos obtenidos de "session"';
    const DATA_CODE_REST = 1;
    const DATA_MSG_REST = 'Datos obtenidos mediante peticion REST';

    //Servicios de consulta de descripcioness
    const SERV_TIPO_EDUCATIVO = 0;
    const SERV_TIPO_FUNCIONAL = 1;
    const SERV_FORMATOS = 2;
    const SERV_TEMAS = 3;
    const SERV_TEMAS_CANAL = 4;
    const SERV_IDIOMAS = 5;
    const SERV_SEGMENTO_ETARIO = 6;
    const SERV_EXTRACURRICULAR = 7;
    const SERV_MODALIDAD = 8;

    const RESPONSE_CODE_SUCCESS = 0;

    /**
     * Es el ID del sitio al cual pertenezco
     * @var Integer
     */
    public $sitio_id;

    /**
     * Posee la estructura de bloques a partir del alias recibido en el constructor, por lo que si se
     * recibe un alias que este relacionado con la funcionalidad del controlador, se obtendria la estructura
     * local deseada (la parte del sitio a la cual pertenezco)
     * @var Array
     */
    public $estructura_local = array();

    /**
     * Posee la estructura de bloques de todo el sitio
     * @var Array
     */
    public $estructura_sitio = array();

    /**
     * Es un array del tipo clave=>valor (alias_bloque=>id_bloque) el cual posee todos los id's de los bloques del sitio
     * @var Array
     */
    public $alias_ids = array();

    /**
     * Es un array del tipo clave=>array (alias_bloque=>bloque_data_array)
     * @var Array
     */
    public $bloque_data = array();

    /**
     * Es un array que define si los datos se obtuvieron de sesion o a traves de API
     * @var Array
     */
    public $obtencion_tipo = array();
    /**
     * Array que posee los destacados de un bloque
     * @var Array
     */
    public $destacados_bloque = array();

    // Propiedades privadas
    //Nombre de los metodos a utilizar
    private static $SERVICES = array
    (
        0 => 'getTipoRecursoEducativo',
        1 => 'getTipoFuncional',
        2 => 'getFormatos',
        3 => 'getListadoTemas',
        4 => 'getTemasCanal',
        5 => 'getIdiomas',
        6 => 'getSegEtarios',
        7 => 'getExtracurriculares',
        8 => 'getModalidades'
    );
    private $sessionObj;
    protected $bloque;
    
    /**
     * Es el constructor de la clase
     * @method __construct
     *
     */
    public function __construct() {
    	$this->bloque = EWebApp::config()->ALIAS_BLOQUE_PRINCIPAL;
        ApiCommunication::setApiData();
        $this->setEstructuraInicial();
        $this->_data['base_img_url'] = BloquesConfig::URL_GET_IMAGEN;
        $this->_data['base_video_url'] = BloquesConfig::URL_GET_VIDEO;
        $this->_data['base_descargas_url'] = BloquesConfig::URL_GET_FILE;
    }

    /**
     *  Servicios relacionados con la grilla de un sitio
     *
     * @return ApiCommunication
     */
    public final function Grilla(){
        return ApiCommunication::Grilla();
    }

    /**
     * Servicios de búsqueda televisivas
     *
     * @return ApiCommunication
     */
    public final function TelevisivoSearch(){
        return ApiCommunication::TelevisivoSearch();
    }

    /**
     * Servicios referidos a bloques de sitios. Un bloque es una agrupación lógica de recursos dentro de un sitio. Se utiliza para diferenciar el contenido de un sitio en secciones o regiones. Un bloque puede contener un conjunto de recursos y conjunto de destacados.
     *
     * @return ApiCommunication::Bloques()
     */
    public final function Bloques(){
        return ApiCommunication::Bloques();
    }

    /**
     * Servicios relacionados a la obtención de información utilizada para catalogación: tipos, categorías, árboles de clasificación, etc.
     *
     * @return ApiCommunication::Catalogacion()
     */
    public final function Catalogacion(){
        return ApiCommunication::Catalogacion();
    }

    /**
     * Servicios que permiten a un usuario realizar un comentario sobre un recurso educativo. Los comentarios son propios de cada sitio.
     *
     * @return ApiCommunication::Comentarios()
     */
    public final function Comentarios(){
        return ApiCommunication::Comentarios();
    }

    /**
     * Un usuario puede crear sus propias etiquetas (tags) y aplicarlas a los recursos educativos. Las etiquetas de usuario son propias de cada usuario y por cada sitio.
     *
     * Requieren autenticación previa.
     *
     * @return ApiCommunication::Etiqueta()
     */
    public final function Etiqueta(){
        return ApiCommunication::Etiqueta();
    }

    /**
     * Servicios relacionados al historial de acciones realizadas por un usuario, como descarga de recursos offline, reproducciones, etc.
     *
     * @return ApiCommunication::HistorialUsuarios()
     */
    public final function HistorialUsuarios(){
        return ApiCommunication::HistorialUsuarios();
    }

    /**
     * Servicios que permiten a un usuario agregar una nota personal sobre un recurso educativo. Las notas son propias de cada sitio y privadas al usuario.
     *
     * @return ApiCommunication::Notas()
     */
    public final function Notas(){
        return ApiCommunication::Notas();
    }

    /**
     * Un portafolio es una colección de recursos agrupados en una carpeta lógica, para un usuario particular. Cada usuario puede agregar recursos a carpetas de favoritos, inventadas por ellos, denominadas portfolios. Los portafolios son propios de cada usuario para cada sitio.
     *
     * Requieren autenticación previa.
     *
     * @return ApiCommunication::Portafolio()
     */
    public final function Portafolio(){
        return ApiCommunication::Portafolio();
    }

    /**
     * Servicios relacionados a la obtención de información de un recurso, en base a su ID.
     *
     * @return ApiCommunication::Recurso()
     */
    public final function Recurso(){
        return ApiCommunication::Recurso();
    }

    /**
     * Agrupación de diversos tipos de servicios no relacionados a ninguna categoría en especial.
     *
     * @return ApiCommunication::Varios()
     */
    public final function Varios(){
        return ApiCommunication::Varios();
    }

    /**
     * Servicios relacionados a la obtención y descarga de videos.
     *
     * @return ApiCommunication::Video()
     */
    public final function Video(){
        return ApiCommunication::Video();
    }

    /**
     * Servicios dedicados a la votación de recursos por parte de los usuarios.
     *
     * Requieren autenticación previa.
     *
     * @return ApiCommunication::Votacion()
     */
    public final function Votacion(){
        return ApiCommunication::Votacion();
    }

    /**
     * Listado de servicios relacionados al motor de búsqueda del Repositorio.
     *
     * @return ApiCommunication::RecursoSearch()
     */
    public final function RecursoSearch(){
        return ApiCommunication::RecursoSearch();
    }

    /**
     * Servicios exclusivos para los tipos funcionales “Emisión” y “Capítulo”.
     *
     * @return ApiCommunication::Televisivo()
     */
    public final function Televisivo(){
        return ApiCommunication::Televisivo();
    }
    /**
     * Agrupacion de servicios de Conectados.
     *
     * @return ApiCommunication::Conectados()
     */
    public final function Conectados(){
        return ApiCommunication::Conectados();
    }

    /**
     * Agrupación de servicios referidos a usuarios y control de autenticación en el sistema.
     *
     * @return ApiCommunication::RestActions()
     */
    public final function RestActions(){
        return ApiCommunication::RestActions();
    }

    /**
     * Metodo a ser sobreescrito en caso de querer una funcionalidad antes de que se ejecute el metodo correspondiente
     * @method beforeRunAction
     *
     */
    public function beforeRunAction($controller, $action) {}

    // ************************************************************
    // ********************* METODOS PRIVADOS *********************
    // ************************************************************

    private function setEstructuraInicial() {
    	$this->sessionObj = new SessionUtilities(null, EWebApp::config()->APP_SESSION_NAME);
    	//debug
    	if ($this->sessionObj->issetData('__estructuraSitio')) {
    		$this->sessionObj->logout();
    	}
    	//////////////
        if ($this->sessionObj->issetData('__estructuraSitio')) {
            $this->obtencion_tipo['codigo'] = self::DATA_CODE_SESSION;
            $this->obtencion_tipo['mensaje'] = self::DATA_MSG_SESSION;
            $datos = $this->sessionObj->getSessionData('__estructuraSitio');
            $this->estructura_sitio = $datos['general'];
            $this->estructura_local = $datos['local'];
            $this->alias_ids = $datos['aliasIds'];
            $this->bloque_data = $datos['bloqueData'];
        } else {
            $this->obtencion_tipo['codigo'] = self::DATA_CODE_REST;
            $this->obtencion_tipo['mensaje'] = self::DATA_MSG_REST;
            $this->obtenerEstructuraInicial();
        }
    }

    private function obtenerEstructuraInicial() {
        try{
            $estructura = ApiCommunication::Bloques()->getBloquesDeSitio();
            
        } catch (EException $e){
            $estructura = array('codigo' => ApiResponse::API_ERROR_EXCEPTION_CODE, 'mensaje' => $e->getMessage());
        }
        if (!empty($estructura)) {
            foreach ($estructura as $bloque) {
                $this->alias_ids[$bloque['alias']] = $bloque['id'];
                $this->bloque_data[$bloque['alias']] = array(
                    'id' => $bloque['id'],
                    'titulo' => $bloque['titulo'],
                    'descripcion' => $bloque['descripcion']
                );
            }
            $this->estructura_sitio = TreeBuilder::addNode($estructura, null);
            $id = isset($this->alias_ids[$this->bloque]) ? $this->alias_ids[$this->bloque] : null;
            $this->estructura_local = TreeBuilder::cloneNode($this->estructura_sitio, $id);
            $this->sessionObj->addData(
				array('__estructuraSitio' =>
                	array('general' => $this->estructura_sitio),
                    array('local' => $this->estructura_local),
                    array('aliasIds' => $this->alias_ids),
                    array('bloqueData' => $this->bloque_data)
				)
            );
        } else {
            ELogger::log('[BloquesController, obtenerEstructuraInicial] ' . $estructura['mensaje'], ELoggerLevel::LEVEL_ERROR);
            if(EWebApp::config()->APP_MODE == 'dev') {
                $this->_data['error'] = $estructura['mensaje'];
            }
        }

    }


}
