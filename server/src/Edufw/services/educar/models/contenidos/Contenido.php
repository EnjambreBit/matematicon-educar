<?php

namespace Edufw\services\educar\models\contenidos;

use Edufw\services\educar\models\RestModel;
use Edufw\services\educar\api\ApiCommunication;

/**
 * Descripcion de Modelo UserConfirmation
 *
 * @version 20120710
 * @author lmoya
 * @package App\Sitios
 * @subpackage userContent
 */
class Contenido extends RestModel 
{
  //CODIGOS DE ERROR

  const CODE_SUCCES = 0;
  const CODE_CONTENT_INVALID = 1;
  const CODE_CONTENT_OVERFLOW = 2;
  const CODE_PUBLICATION_STATE_INVALID = 3;

  //CONSTANTES API
  const MAX_CONTENIDOS = 100;

  static private $ESTADOS = array('publico', 'privado', 'compartido');
  const ESTADO_PRIVADO = 1;
  const ESTADO_PUBLICO = 2;
  const ESTADO_COMPARTIDO = 3;

  /**
   * Obtiene un contenido de usuario
   * 
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:user_content?&#obtener_contenido
   * @return ApiResponse
   */
  public function getContenidoFull($usr_id, $login_token, $contenido_id)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,        
        "contenido_id" => $contenido_id
    );
    $this->uri = $this->global_config['URL_GET_CONTENIDO_FULL'];
    return $this->callRestService();
  }
  
  /**
   * Obtiene una lista liviana de contenidos
   * titulo, cantidad de archivos, estado de publicacion, fecha de publicacion
   * 
   * 
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:user_content?&#obtener_lista_de_contenidos_livianos_lite
   * @return ApiResponse
   */
  public function getContenidosListLite($usr_id, $login_token, $contenidos)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,        
        "contenidos" => $contenidos
    );
    $this->uri = $this->global_config['URL_GET_CONTENIDO_LIST_LITE'];
    return $this->callRestService();
  }
  
  /**
   * Obtiene los contenidos de un usuario
   * 
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:user_content?&#obtener_contenidos_de_usuario
   * @return ApiResponse
   */
  public function getContenidosDeUsuario($usr_id, $login_token, $limit, $offset, $sort_column = 'titulo', $sort_mode = 'DESC', $filter = array())
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "limit" => $limit,
        "offset" => $offset,
        "sort_column" => $sort_column,
        "sort_mode" => $sort_mode,
        "filter" => $filter
    );
    $this->uri = $this->global_config['URL_GET_CONTENIDO_USUARIO'];
    return $this->callRestService();
  }
  
  /**
   * Cambio el estado de publicacion de un contenido
   * @method setContenidoEstadoPublicacion
   * 
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:user_content?&#cambiar_estado_de_publicacion_de_contenidos_de_usuario
   * @return ApiResponse
   */
  public function setContenidoEstadoPublicacion($usr_id, $login_token, $contenidos, $estado)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,        
        "contenidos" => $contenidos,
        "estado_publicacion_id" => $estado
    );
    $this->uri = $this->global_config['URL_SET_CONTENIDO_ESTADO_PUBLICACION'];
    return $this->callRestService();
  }
  
    /**
   * Obtiene categoria por alias
   * @method getCategoriaPorAlias
   * 
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:user_content:servicios_categorias#obtener_categoria_por_alias
   * @return ApiResponse
   */
    public function getCategoriaPorAlias($alias)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "categoria_sitio_alias" => $alias     
    );
    $this->uri = $this->global_config['URL_USER_CONTENT_OBTENER_CATEGORIAS_BY_ALIAS'];
    return $this->callRestService();
  }
  
    /**
   * Asigna un contenido a una categoría
   * @method setContenidoToCategoria
   * 
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:user_content:servicios_categorias#agregar_un_contenido_a_categorias
   * @return ApiResponse
   */
    public function setContenidoToCategoria($values)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $values['usr_id'],
        "login_token" => $values['login_token'],      
        "categoria_id" => $values['categoria_id'], 
        "contenido_id" => $values['contenido_id']
    );
    $this->uri = $this->global_config['URL_USER_CONTENT_AGREGAR_A_CATEGORIA'];
    return $this->callRestService();
  }

  
  /**
   * Manda una lista de contenidos a la papelera
   * 
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:user_content?&#enviar_contenido_a_la_papelera
   * @return ApiResponse
   */
  public function enviarContenidoAPapelera($usr_id, $login_token, $contenidos)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,        
        "contenidos" => $contenidos
    );
    $this->uri = $this->global_config['URL_SEND_CONTENIDO_PAPELERA'];
    return $this->callRestService();
  }
  
  /**
   * recupera un lista de contenidos de la papelera
   * 
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:user_content?&#recuperar_contenido_de_la_papelera
   * @return ApiResponse
   */
  public function recuperarContenidoPapelera($usr_id, $login_token, $contenidos)
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,        
        "contenidos" => $contenidos
    );
    $this->uri = $this->global_config['URL_GET_CONTENIDO_PAPELERA'];
    return $this->callRestService();
  }
  
  /**
   * Obtiene los contenidos en una papelera de un usuario
   * 
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:user_content?&#obtener_contenidos_de_papelera_de_usuario
   * @return ApiResponse
   */
  public function getContenidosPapeleraUsuario($usr_id, $login_token, $limit, $offset, $sort_column = 'titulo', $sort_mode = 'DESC', $filter = array())
  {
    $this->data = array(
        "sitio_id" => ApiCommunication::$sitio_id,
        "usr_id" => $usr_id,
        "login_token" => $login_token,
        "limit" => $limit,
        "offset" => $offset,
        "sort_column" => $sort_column,
        "sort_mode" => $sort_mode,
        "filter" => $filter
    );
    $this->uri = $this->global_config['URL_GET_CONTENIDO_USUARIO'];
    return $this->callRestService();
  }  
  
  /**
   * Permite modificar atributos de meta-datos de un contenido. Requiere autenticación. El servicio intenta geolocalizar el contendio utilizando la API de GoogleMaps. En caso satisfactorio actualiza la latitud y longitud.
   * 
   * @param array $values
   * 
   * <p>
   *    <ul>
   *	<li>string usr_id</li>
   *	<li>string login_token</li>
   *    <li>int contenido_id</li>
   *    <li>string contenido_titulo</li>
   *    <li>string contenido_descripcion</li>
   *    <li>int pais_id</li>
   *    <li>int prov_id</li>
   *    <li>string localidad</li>
   *    <li>string geo_latitud</li>
   *    <li>string geo_longitud</li>
   *    <li>string fecha_evento</li>
   *	<li>string horario_evento</li>
   *    <li>int modalidad_id</li>
   *    <li>array temas</li>
   *    <li>int escuela_prov_id</li>
   *    <li>int escuela_departamento_id</li>
   *    <li>int escuela_localidad_id</li>
   *    <li>int esculea_id</li>
   *    <li>string escuela_otra</li>
   *    <li>int [opcional] sitio_id</li>
   *    </ul>
   * </p>
   * 
   * @link http://dw.educ.ar/doku.php/tecnologia:desarrollo:externa:api:user_content:servicios_obtencion_contenidos#modificar_datos_de_contenido
   * @return ApiResponse
   */
  public function updateMetaData($values)
  {
    $this->data = $values;
    if(!isset($values['sitio_id'])){
      $this->data['sitio_id'] = ApiCommunication::$sitio_id;
    }
    $this->uri = $this->global_config['URL_USER_CONTENT_ACTUALIZAR_METADATA'];
    return $this->callRestService();
  }
  
  
}