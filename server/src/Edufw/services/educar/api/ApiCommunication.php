<?php

namespace Edufw\services\educar\api;


use Edufw\core\EException;
use \Config;

/**
 * Clase con métodos de REST.
 *
 * @author pgambetta
 */
class ApiCommunication 
{

    public static $API_URIS = array(
        //BLOQUES
        'URI_GET_BLOQUE' => 'repositorio/rest/interaccion/Bloques/getBloque',
        'URI_GET_BLOQUES' => 'repositorio/rest/interaccion/Bloques/getBloquesDeSitio',
        'URI_GET_ALIAS' => 'repositorio/rest/interaccion/Bloques/getBloqueByAlias',
        'URI_GET_RECURSOS_DE_BLOQUE' => 'repositorio/rest/interaccion/Bloques/getRecursosBloque',
        'URI_GET_BLOQUES_DE_RECURSOS' => 'repositorio/rest/interaccion/Bloques/getBloquesRecursos',
        //BUSQUEDA
        'URL_RECURSO_BUSQUEDA_SIMPLE' => 'repositorio/rest/RecursoSearch/simpleSearch',
        'URL_RECURSO_BUSQUEDA_AVANZADA' => 'repositorio/rest/RecursoSearch/advanceSearch',
        //COMENTARIOS
        'URI_GET_COMENTARIOS' => 'repositorio/rest/interaccion/Comentarios/getComentariosDeRecurso',
        'URI_GET_COMENTARIOS_PAGINADOS' => 'repositorio/rest/interaccion/Comentarios/getComentariosPaginado',
        'URI_SET_COMENTARIOS' => 'repositorio/rest/interaccion/Comentarios/comentarRecurso',
        //ETIQUETAS
        'URI_NEW_ETIQUETA_USUARIO' => 'repositorio/rest/interaccion/Etiqueta/crearEtiquetaUsuario',
        'URI_MODIFICAR_ETIQUETA_USUARIO' => 'repositorio/rest/interaccion/Etiqueta/modificarEtiquetaUsuario',
        'URI_ELIMINAR_ETIQUETA_USUARIO' => 'repositorio/rest/interaccion/Etiqueta/eliminarEtiquetaUsuario',
        'URI_ASIGNAR_ETIQUETA_A_RECURSOS' => 'repositorio/rest/interaccion/Etiqueta/asignarRecursosAEtiquetaUsuario',
        'URL_GET_ETIQUETAS_USUARIO' => 'repositorio/rest/interaccion/Etiqueta/getEtiquetasDeUnUsuario',
        'URL_DESASIGNAR_ETIQUETA_A_RECURSO' => 'repositorio/rest/interaccion/Etiqueta/quitarRecursosAEtiquetaUsuario',
        'URL_GET_RECURSOS_DE_ETIQUETA' => 'repositorio/rest/interaccion/Etiqueta/getRecursosDeEtiquetaUsuario',
        'URL_GET_ETIQUETAS_USUARIO_EN_PORTAFOLIO' => 'repositorio/rest/interaccion/Etiqueta/getEtiquetasDeUnUsuarioEnPortafolio',
        //DESTACADOS
        'URI_GET_DESTACADOS' => 'repositorio/rest/interaccion/Bloques/getDestacadosBloque',
        // CUENTAS
        'URI_GET_LOGIN_VALIDATION' => 'cuentas/RestActions/checkUserLogged',
        'URI_CHEQ_AUTENTICACION' => 'cuentas/RestActions/checkUserLogged',
        'URI_LOGIN_USER_SITIO' => 'cuentas/RestActions/loginUser',
        'URI_GET_USER_DATA' => 'cuentas/RestActions/getUserData',
        'URI_LOGOUT' => 'cuentas/RestActions/logout',
        'URI_GET_PAISES' => 'cuentas/RestActions/getPaises',
        'URI_GET_PROVINCIAS' => 'cuentas/RestActions/getProvincias',
        'URL_CREAR_USUARIO'       =>    "cuentas/RestActions/crearUsuario",
        'URL_ACTUALIZAR_USUARIO'  =>    "cuentas/RestActions/actualizarUsuario",
        'URL_GET_USUARIO'         =>    "cuentas/RestActions/obtenerUsuario",
        'URL_GET_USUARIO_PUBLICO' =>    "cuentas/RestActions/obtenerUsuarioPublico",
        'URL_GET_TICKET_SUBIDA'   =>    "cuentas/RestActions/obtenerTicketSubida",
        'URL_CHECK_APODO'         =>    "cuentas/RestActions/comprobarApodo",
        //PORTAFOLIO
        'URL_GET_PORTAFOLIO' => 'repositorio/rest/interaccion/Portafolio/getPortafolio',
        'URI_MODIFICAR_PORTAFOLIO' => 'repositorio/rest/interaccion/Portafolio/modificarPortafolio',
        'URI_ELIMINAR_PORTAFOLIO' => 'repositorio/rest/interaccion/Portafolio/eliminarPortafolio',
        'URI_CREAR_PORTAFOLIO' => 'repositorio/rest/interaccion/Portafolio/crearPortafolio',
        'URI_QUITAR_RECURSOS_DE_PORTAFOLIO' => 'repositorio/rest/interaccion/Portafolio/quitarRecursosDePortafolio',
        'URL_LISTADO_PORTAFOLIOS' => 'repositorio/rest/interaccion/Portafolio/getPortafoliosList',
        'URL_GET_PORTAFOLIOS_USUARIO' => 'repositorio/rest/interaccion/Portafolio/getPortafoliosDeUsuario',
        'URL_INSERTAR_RECURSOS_EN_PORTAFOLIO' => 'repositorio/rest/interaccion/Portafolio/insertarRecursosEnPortafolio',
        'URL_OBTENER_PORTAFOLIOS_DE_RECURSOS' => 'repositorio/rest/interaccion/Portafolio/getPortafoliosDeRecursos',
        //RECURSOS
        'URI_GET_RECURSO_FULL' => 'repositorio/rest/interaccion/Recurso/getRecursoFull',
        'URL_LISTADO_RECURSOS' => 'repositorio/rest/interaccion/Recurso/getRecursosListLite',
        'URL_GET_RECURSO' => 'repositorio/rest/interaccion/Recurso/getRecursoFull',
        'URL_GET_RECURSO_LITE' => 'repositorio/rest/interaccion/Recurso/getRecursoLite',
        'URL_GET_RECURSOS_RELACIONADOS' => 'repositorio/rest/interaccion/Varios/getRelacionesImplicitas',
        'URL_GET_RECURSO_OFFLINE' => 'repositorio/rest/interaccion/Recurso/getRecursoOffline',
        'URL_GET_RECURSO_RELACIONES' => 'repositorio/rest/interaccion/Recurso/getRelacionesRecursosList',
    	'URL_GET_RECURSO_COLECCIONES' => 'repositorio/rest/interaccion/Recurso/getColeccionesRecursosList',
        //VARIOS
        'URL_GET_NOVEDADES' => 'repositorio/rest/interaccion/Varios/getNovedades',
        'URL_GET_VOTADOS' => 'repositorio/rest/interaccion/Varios/getMasVotados',
        'URL_GET_DESCARGADOS' => 'repositorio/rest/interaccion/Varios/getMasDescargadosOffline',
        'URL_GET_NUBE_TAG' => 'repositorio/rest/interaccion/Varios/getNubeEtiquetas',
        'URL_GET_MEGUSTA' => 'repositorio/rest/interaccion/Votacion/meGusta',
        'URL_GET_CHEK_VOTAR' => 'repositorio/rest/interaccion/Votacion/puedeVotar',
        'URL_GET_RANKING' => 'repositorio/rest/interaccion/Varios/getRanking',
        //OBTENCION DE ARCHIVOS
        'URL_DESCARGAR_VIDEO' => 'repositorio/rest/interaccion/Video/getVideoOffline',
        //CATALOGACION
        'URL_GET_TEMAS' => 'repositorio/rest/interaccion/Catalogacion/getTemas',
        'URL_GET_TIP_REC_EDUCATIVOS' => 'repositorio/rest/interaccion/Catalogacion/getTiposRecursosEducativos',
        'URL_GET_IDIOMAS' => 'repositorio/rest/interaccion/Catalogacion/getIdiomas',
        'URL_GET_SEG_ETARIOS' => 'repositorio/rest/interaccion/Catalogacion/getSegmentosEtarios',
        'URL_GET_EXTRACURRICULARES' => 'repositorio/rest/interaccion/Catalogacion/getExtracurriculares',
        'URL_GET_TIP_FUNCIONALES' => 'repositorio/rest/interaccion/Catalogacion/getTiposFuncionales',
        'URL_GET_MODALIDADES' => 'repositorio/rest/interaccion/Catalogacion/getModalidades',
        'URL_GET_FORMATOS' => 'repositorio/rest/interaccion/Catalogacion/getFormatos',
        'URL_GET_TEMAS_CANAL' => 'repositorio/rest/interaccion/Catalogacion/getTemasCanal',
        'URL_GET_HIJOS_TEMA' => 'repositorio/rest/interaccion/Catalogacion/getHijosTema',
        'URL_GET_HIJOS_TEMA_CANAL' => 'repositorio/rest/interaccion/Catalogacion/getHijosTemaCanal',
        'URL_GET_GENEROS_CINEMATOGRAFICOS' => 'repositorio/rest/interaccion/Catalogacion/getGenerosCinematograficos',
        'URL_GET_AUDIENCIAS' => 'repositorio/rest/interaccion/Catalogacion/getAudiencias',
        'URL_GET_TEMAS_CANAL_CANTIDADES' => 'repositorio/rest/interaccion/Catalogacion/getTemasCantidades',
        'URL_GET_HIJOS_CATEGORIA_ARTICULO' => 'repositorio/rest/interaccion/Catalogacion/getHijosCategoriaArticulo',
        'URL_GET_ETIQUETAS' => 'repositorio/rest/interaccion/Catalogacion/getEtiquetas',
        //TELEVISIVOS
        'URL_TELEVISIVO_BUSQUEDA_SIMPLE' => 'repositorio/rest/Televisivo/RecursoSearch/simpleSearch',
        'URL_TELEVISIVO_BUSQUEDA_AVANZADA' => 'repositorio/rest/Televisivo/RecursoSearch/advanceSearch',
        'URL_TELEVISIVO_BUSQUEDA_AVANZADA_FULL' => 'repositorio/rest/Televisivo/RecursoSearch/advanceSearchFull',
        'URL_TELEVISIVO_GRILLA' => 'repositorio/rest/Televisivo/Grilla/getDate',
        'URL_TELEVISIVO_LISTADO_RECURSOS' => 'repositorio/rest/Televisivo/getRecursosTelevisivosListLite',
        'URL_TELEVISIVO_GRILLA_ACTUAL' => 'repositorio/rest/Televisivo/Grilla/getNow',
        'URL_TELEVISIVO_GRILLA_RECORDATORIO' => 'repositorio/rest/Televisivo/Grilla/crearRecordatorio',
        'URL_TELEVISIVO_OCURRENCIAS_CAPITULOS_DE_EMISION' => 'repositorio/rest/Televisivo/Grilla/getOcurrenciasCapitulosDeEmisionEnGrilla',
        'URL_TELEVISIVO_OCURRENCIAS_RECURSO' => 'repositorio/rest/Televisivo/Grilla/getOcurrenciasRecursoEnGrilla',
        'URL_TELEVISIVO_GET_TEMAS_CANAL_CANTIDADES' => 'repositorio/rest/Televisivo/getTemasCanalCantidades',
        //VOTACION
        'URL_VOTACION_VOTAR' => 'repositorio/rest/interaccion/Votacion/votar',
        'URL_VOTACION_ME_GUSTA' => 'repositorio/rest/interaccion/Votacion/meGusta',
        'URL_VOTACION_PUEDE_VOTAR' => 'repositorio/rest/interaccion/Votacion/puedeVotar',
        //VIDEOS
        'URL_VIDEOS_GET_VIDEO_OFFLINE' => 'repositorio/rest/interaccion/Video/getVideoOffline',
        //ARTICULO:
        'URL_GET_NOTICIAS_CATEGORIAS' => '/repositorio/rest/interaccion/Catalogacion/getHijosCategoriaArticulo',
        'URL_GET_NOTICIAS_POR_CATEGORIAS' => '/repositorio/rest/interaccion/Varios/getArticulosDeCategoria',
        //USER CONTENT
        'URL_GET_CONTENIDO_FULL' => 'userContent/rest/Contenido/getContenidoFull',
        'URL_GET_CONTENIDO_LIST_LITE' => 'userContent/rest/Contenido/getContenidosListLite',
        'URL_GET_CONTENIDO_USUARIO' => 'userContent/rest/Contenido/getContenidosDeUsuario',
        'URL_SET_CONTENIDO_ESTADO_PUBLICACION' => 'userContent/rest/Contenido/modificarEstadoPublicacionContenido',
        'URL_SEND_CONTENIDO_PAPELERA' => 'userContent/rest/Contenido/enviarContenidoAPapelera',
        'URL_GET_CONTENIDO_PAPELERA' => 'userContent/rest/Contenido/recuperarContenidoPapelera',
        'URL_USER_CONTENT_BUSQUEDA_AVANZADA' => 'userContent/rest/userContentSearch/advanceSearch',
        'URL_USER_CONTENT_OBTENER_CATEGORIAS' => 'userContent/rest/Categorias/getCategorias',
        'URL_USER_CONTENT_AGREGAR_A_CATEGORIA' => 'userContent/rest/Categorias/addContenidoACategoria',
        'URL_USER_CONTENT_GET_CATEGORIA_BY_ALIAS' => 'userContent/rest/Categorias/getCategoriaByAlias',
        'URL_USER_CONTENT_ACTUALIZAR_METADATA' => 'userContent/rest/Contenido/updateMetaData',
        'URL_USER_CONTENT_AGREGAR_RECURSO_CARPETA' => 'userContent/rest/Carpetas/agregarRecurso',
        //BACKEND - RECURSOS
        'URL_BACKEND_RECURSO_OBTENER_RECURSOS' => 'backend/rest/Recurso/getRecursos',
        //BACKEND - USUARIOS
        'URL_BACKEND_USUARIOS_OBTENER_ROLES' => 'backend/rest/Usuarios/getRoles',
        'URL_BACKEND_USUARIOS_OBTENER_REGLAS' => 'backend/rest/Usuarios/getReglas',
        'URL_BACKEND_USUARIOS_TIENE_ROL' => 'backend/rest/Usuarios/tieneRol',
        'URL_BACKEND_USUARIOS_TIENE_REGLA' => 'backend/rest/Usuarios/tieneRegla',
        // FORMACION
        'URL_OBTENER_SEARCH_CURSOS' => 'formacion/rest/Cursos/searchCursos',
        'URL_OBTENER_CURSO_FULL' => 'formacion/rest/Cursos/getCursoFull',
        'URL_OBTENER_CURSO_LITE' => 'formacion/rest/Cursos/getCursoLite',
        'URL_OBTENER_INSCRIPCIONES_USUARIO' => 'formacion/rest/Cursos/getInscripciones',
        'URL_OBTENER_CERTIFICADO' => 'formacion/rest/Cursos/getCertificado',
        'URL_OBTENER_EDICION_ACTIVAS_CURSO' => 'formacion/rest/Cursos/getEdicionesActivasDeCurso',
        'URL_OBTENER_CATEGORIAS_CURSOS' => 'formacion/rest/Cursos/getCategoriasDeCursos',
        'URL_OBTENER_ESCUELA_FULL' => 'formacion/rest/Escuelas/getEscuelaFull',
        'URL_SEARCH_ESCUELA' => 'formacion/rest/Escuelas/searchEscuelas',
        'URL_OBTENER_SOLICITUD' => 'formacion/rest/Cursos/getSolicitudAprobada',
        //COMPRAS
        'URL_BUSCAR_ADJUDICACIONES' => 'repositorio/rest/Compras/buscarAdjudicaciones',
        'URL_BUSCAR_PUBLICACIONES' => 'repositorio/rest/Compras/buscarPublicaciones',
        'URL_GET_CATALOGACION_COMPRAS' => 'repositorio/rest/Compras/getCatalogacionCompras',
        'URL_GET_DETALLE_COMPRA' => 'repositorio/rest/Compras/getDetallePublicacion',
        //CONECTADOS
        'URL_GET_TICKET_UPLOAD'               =>  "conectados/rest/FileManager/getTicket",
        'URL_GET_TICKET_DOWNLOAD'             =>  "conectados/rest/FileManager/getDownloadTicket",
        'URL_CONECTADOS_NEWENTRADA'           =>  "conectados/rest/Entrada/newEntrada",
        'URL_CONECTADOS_EDITENTRADA'          =>  "conectados/rest/Entrada/editEntrada",
        'URL_CONECTADOS_DESPUBLICARENTRADA'   =>  "conectados/rest/Entrada/despublicarEntrada",
        'URL_CONECTADOS_GETENTRADA'           =>  "conectados/rest/Entrada/getEntrada",
        'URL_CONECTADOS_REPORTARENTRADA'      =>  "conectados/rest/Entrada/reportarEntrada",
        'URL_CONECTADOS_BLOQUEARENTRADA'      =>  "conectados/rest/Entrada/bloquearEntrada",
        'URL_CONECTADOS_GETMOSAICO'           =>  "conectados/rest/Entrada/getMosaico",
        'URL_CONECTADOS_VALORARENTRADA'       =>  "conectados/rest/Valoracion/valorarEntrada",
        'URL_CONECTADOS_DESVALORARENTRADA'    =>  "conectados/rest/Valoracion/desvalorarEntrada",
        'URL_CONECTADOS_REMOVEETIQUETAENTRADA'=>  "conectados/rest/Etiqueta/removeEtiquetasEntrada",
        'URL_CONECTADOS_ADDETIQUETAENTRADA'   =>  "conectados/rest/Etiqueta/addEtiquetasEntrada",
        'URL_CONECTADOS_NEWETIQUETA'          =>  "conectados/rest/Etiqueta/newEtiqueta",
        'URL_CONECTADOS_GETCOUTAUSUARIO'      =>  "conectados/rest/Usuario/getCuotaUsuario",
    	'URL_CONECTADOS_REPORTARUSUARIO'      =>  "conectados/rest/Usuario/reportarUsuario",
    	'URL_CONECTADOS_VERIFICARUSUARIO'     =>  "conectados/rest/Usuario/verificarUsuario",
        'URL_CONECTADOS_GETETIQUETACATEGORIA' =>  "conectados/rest/Maestras/getEtiquetaCategoria",
        'URL_CONECTADOS_GETARCHIVOTIPO'       =>  "conectados/rest/Maestras/getArchivoTipo",
        'URL_COENCTADOS_GETENTRADATIPO'       =>  "conectados/rest/Maestras/getEntradaTipo",
        'URL_CONECTADOS_GETCATEGORIA'         =>  "conectados/rest/Maestras/getCategoria",
        'URL_CONECTADOS_GETENTRADAFORMATO'    =>  "conectados/rest/Maestras/getEntradaFormato",
        'URL_CONECTADOS_GETENTRADAESTADO'     =>  "conectados/rest/Maestras/getEntradaEstado",
        'URL_CONECTADOS_GETORIGEN'            =>  "conectados/rest/Maestras/getOrigen",
        'URL_CONECTADOS_GETREPORTETIPO'       =>  "conectados/rest/Maestras/getReporteTipo",
        'URL_CONECTADOS_GETVALORACIONTIPO'    =>  "conectados/rest/Maestras/getValoracionTipo",
        'URL_CONECTADOS_GETPAIS'              =>  "conectados/rest/Maestras/getPaises",
        'URL_CONECTADOS_GETPROVINCIA'         =>  "conectados/rest/Maestras/getProvincia",
        'URL_CONECTADOS_ADVANCESEARCH'        =>  "conectados/rest/EntradaSearch/advanceSearch",
        'URL_CONECTADOS_SEARCHETIQUETAS'      =>  "conectados/rest/EntradaSearch/sugerirEtiquetas",
        //SOCIAL
        'URL_SOCIAL_GET_RECURSOS_DESTACADOS'  =>  "repositorio/rest/Social/recursos_destacados"
    );  //put your code here
    public static $ci = NULL;
    public static $sitio_nombre = NULL;
    public static $sitio_id = NULL;
    public static $web_service_client_key = NULL;
    private static $uri_service_api = NULL;
    private static $pGrilla = NULL;
    private static $pTelevisivoSearch = NULL;
    private static $pBloques = NULL;
    private static $pCatalogacion = NULL;
    private static $pCatalogacionUserContent = NULL;
    private static $pComentarios = NULL;
    private static $pEtiqueta = NULL;
    private static $pHistorialUsuarios = NULL;
    private static $pNotas = NULL;
    private static $pPortafolio = NULL;
    private static $pCarpeta = NULL;
    private static $pRecurso = NULL;
    private static $pVarios = NULL;
    private static $pVideo = NULL;
    private static $pVotacion = NULL;
    private static $pRecursoSearch = NULL;
    private static $pUserContentSearch = NULL;
    private static $pTelevisivo = NULL;
    private static $pRestActions = NULL;
    private static $pContenido = NULL;
    private static $pBackendRecursos = NULL;
    private static $pBackendUsuarios = NULL;
    private static $pCursos = NULL;
    private static $pCompras = NULL;
    private static $pEscuelas = NULL;
    private static $pConectados = NULL;
    private static $pSocial = NULL;

    /**
     * @return \Edufw\services\educar\models\repositorio\televisivo\Grilla
     */
    public static final function Grilla() 
    {
        if (ApiCommunication::$pGrilla == NULL) {
            ApiCommunication::$pGrilla = new \Edufw\services\educar\models\repositorio\televisivo\Grilla();
        }

        return ApiCommunication::$pGrilla;
    }

    /**
     * @return \Edufw\services\educar\models\repositorio\televisivo\TelevisivoSearch
     */
    public static function TelevisivoSearch() 
    {
        if (ApiCommunication::$pTelevisivoSearch == NULL) {
            ApiCommunication::$pTelevisivoSearch = new \Edufw\services\educar\models\repositorio\televisivo\TelevisivoSearch();
        }

        return ApiCommunication::$pTelevisivoSearch;
    }

    /**
     * Servicios referidos a bloques de sitios. 
     * Un bloque es una agrupación lógica de recursos 
     * dentro de un sitio. Se utiliza para diferenciar 
     * el contenido de un sitio en secciones o regiones. 
     * Un bloque puede contener un conjunto de recursos y 
     * conjunto de destacados.
     * 
     * @return \Edufw\services\educar\models\repositorio\interaccion\Bloques
     */
    public static function Bloques() 
    {
        if (ApiCommunication::$pBloques == NULL) {
            ApiCommunication::$pBloques = new \Edufw\services\educar\models\repositorio\interaccion\Bloques();
        }
        return ApiCommunication::$pBloques;
    }

    /**
     * Servicios relacionados a la obtención de información utilizada para catalogación: tipos, categorías, árboles de clasificación, etc.
     *
     * @return \Edufw\services\educar\models\repositorio\interaccion\Catalogacion
     */
    public static function Catalogacion() 
    {
        if (ApiCommunication::$pCatalogacion == NULL) {
            ApiCommunication::$pCatalogacion = new \Edufw\services\educar\models\repositorio\interaccion\Catalogacion();
        }

        return ApiCommunication::$pCatalogacion;
    }

    /**
     * Servicios relacionados a la obtención de información utilizada para catalogación de UserContent.
     *
     * @return \Edufw\services\educar\models\contenidos\CatalogacionUserContent
     */
    public static function CatalogacionUserContent() 
    {
        if (ApiCommunication::$pCatalogacionUserContent == NULL) {
            ApiCommunication::$pCatalogacionUserContent = new \Edufw\services\educar\models\contenidos\CatalogacionUserContent();
        }

        return ApiCommunication::$pCatalogacionUserContent;
    }

    /**
     * Servicios que permiten a un usuario realizar un comentario sobre un recurso educativo. Los comentarios son propios de cada sitio.
     *
     * @return \Edufw\services\educar\models\repositorio\interaccion\Comentarios
     */
    public static function Comentarios() 
    {
        if (ApiCommunication::$pComentarios == NULL) {
            ApiCommunication::$pComentarios = new \Edufw\services\educar\models\repositorio\interaccion\Comentarios();
        }

        return ApiCommunication::$pComentarios;
    }

    /**
     * Un usuario puede crear sus propias etiquetas (tags) y aplicarlas a los recursos educativos. Las etiquetas de usuario son propias de cada usuario y por cada sitio.
     *
     * Requieren autenticación previa.
     *
     * @return \Edufw\services\educar\models\repositorio\interaccion\Etiqueta
     */
    public static function Etiqueta() 
    {
        if (ApiCommunication::$pEtiqueta == NULL) {
            ApiCommunication::$pEtiqueta = new \Edufw\services\educar\models\repositorio\interaccion\Etiqueta();
        }

        return ApiCommunication::$pEtiqueta;
    }

    /**
     * Servicios relacionados al historial de acciones realizadas por un usuario, como descarga de recursos offline, reproducciones, etc.
     *
     * @return \Edufw\services\educar\models\repositorio\interaccion\HistorialUsuarios
     */
    public static function HistorialUsuarios() 
    {
        if (ApiCommunication::$pHistorialUsuarios == NULL) {
            ApiCommunication::$pHistorialUsuarios = new \Edufw\services\educar\models\repositorio\interaccion\HistorialUsuarios();
        }

        return ApiCommunication::$pHistorialUsuarios;
    }

    /**
     * Servicios que permiten a un usuario agregar una nota personal sobre un recurso educativo. Las notas son propias de cada sitio y privadas al usuario.
     *
     * @return \Edufw\services\educar\models\repositorio\interaccion\Notas
     */
    public static function Notas() 
    {
        if (ApiCommunication::$pNotas == NULL) {
            ApiCommunication::$pNotas = new \Edufw\services\educar\models\repositorio\interaccion\Notas();
        }

        return ApiCommunication::$pNotas;
    }

    /**
     * Un portafolio es una colección de recursos agrupados en una carpeta lógica, para un usuario particular. Cada usuario puede agregar recursos a carpetas de favoritos, inventadas por ellos, denominadas portfolios. Los portafolios son propios de cada usuario para cada sitio.
     *
     * Requieren autenticación previa.
     *
     * @return \Edufw\services\educar\models\repositorio\interaccion\Notas
     */
    public static function Portafolio() 
    {
        if (ApiCommunication::$pPortafolio == NULL) {
            ApiCommunication::$pPortafolio = new \Edufw\services\educar\models\repositorio\interaccion\Notas();
        }

        return ApiCommunication::$pPortafolio;
    }

    /**
     * Agrupacion de servicios referidos a Contenidos de Usuarios
     * @return \Edufw\services\educar\models\contenidos\Carpeta
     */
    //public static function Carpeta()
    //{
        //if (ApiCommunication::$pCarpeta == NULL) {
            //EWebApp::loadModel('client_userContent/rest/Carpeta', false);
            //ApiCommunication::$pCarpeta = new \Edufw\services\educar\models\contenidos\Carpeta();
        //}

        //return ApiCommunication::$pCarpeta;
    //}

    /**
     * Servicios relacionados a la obtención de información de un recurso, en base a su ID.
     *
     * @return \Edufw\services\educar\models\repositorio\interaccion\Recurso
     */
    public static function Recurso() 
    {
        if (ApiCommunication::$pRecurso == NULL) {
            ApiCommunication::$pRecurso = new \Edufw\services\educar\models\repositorio\interaccion\Recurso();
        }

        return ApiCommunication::$pRecurso;
    }

    /**
     * Agrupación de diversos tipos de servicios no relacionados a ninguna categoría en especial.
     *
     * @return \Edufw\services\educar\models\repositorio\interaccion\Varios
     */
    public static function Varios() 
    {
        if (ApiCommunication::$pVarios == NULL) {
            ApiCommunication::$pVarios = new \Edufw\services\educar\models\repositorio\interaccion\Varios();
        }

        return ApiCommunication::$pVarios;
    }

    /**
     * Servicios relacionados a la obtención y descarga de videos.
     *
     * @return \Edufw\services\educar\models\repositorio\interaccion\Video
     */
    public static function Video() 
    {
        if (ApiCommunication::$pVideo == NULL) {
            ApiCommunication::$pVideo = new \Edufw\services\educar\models\repositorio\interaccion\Video();
        }

        return ApiCommunication::$pVideo;
    }

    /**
     * Servicios dedicados a la votación de recursos por parte de los usuarios.
     *
     * Requieren autenticación previa.
     * @return \Edufw\services\educar\models\repositorio\interaccion\Votacion
     */
    public static function Votacion() 
    {
        if (ApiCommunication::$pVotacion == NULL) {
            ApiCommunication::$pVotacion = new \Edufw\services\educar\models\repositorio\interaccion\Votacion();
        }

        return ApiCommunication::$pVotacion;
    }

    /**
     * Listado de servicios relacionados al motor de búsqueda del Repositorio.
     *
     * @return \Edufw\services\educar\models\repositorio\RecursoSearch
     */
    public static function RecursoSearch() 
    {
        if (ApiCommunication::$pRecursoSearch == NULL) {
            ApiCommunication::$pRecursoSearch = new \Edufw\services\educar\models\repositorio\RecursoSearch();
        }

        return ApiCommunication::$pRecursoSearch;
    }

    /**
     * Listado de servicios relacionados al motor de búsqueda de contenidos de usuario del Repositorio.
     *
     * @return \Edufw\services\educar\models\contenidos\UserContentSearch
     */
    public static function UserContentSearch() 
    {
        if (ApiCommunication::$pUserContentSearch == NULL) {
            ApiCommunication::$pUserContentSearch = new \Edufw\services\educar\models\contenidos\UserContentSearch();
        }

        return ApiCommunication::$pUserContentSearch;
    }

    /**
     * Servicios exclusivos para los tipos funcionales “Emisión” y “Capítulo”.
     *
     * @return \Edufw\services\educar\models\repositorio\Televisivo
     */
    public static function Televisivo() 
    {
        if (ApiCommunication::$pTelevisivo == NULL) {
            ApiCommunication::$pTelevisivo = new \Edufw\services\educar\models\repositorio\Televisivo();
        }

        return ApiCommunication::$pTelevisivo;
    }

    /**
     * Agrupación de servicios referidos a usuarios y control de autenticación en el sistema.
     *
     * @return \Edufw\services\educar\models\cuentas\RestActions
     */
    public static function RestActions() 
    {
        if (ApiCommunication::$pRestActions == NULL) {
            ApiCommunication::$pRestActions = new \Edufw\services\educar\models\cuentas\RestActions();
        }

        return ApiCommunication::$pRestActions;
    }

    /**
     * Agrupacion de servicios referidos a Contenidos de Usuarios
     * @return \Edufw\services\educar\models\contenidos\Contenido
     */
    public static function Contenido() 
    {
        if (ApiCommunication::$pContenido == NULL) {
            ApiCommunication::$pContenido = new \Edufw\services\educar\models\contenidos\Contenido();
        }

        return ApiCommunication::$pContenido;
    }

    /**
     * Agrupacion de servicios referidos a recursos de Backend
     * @return \Edufw\services\educar\models\backend\Recursos
     */
    public static function BackendRecursos() 
    {
        if (ApiCommunication::$pBackendRecursos == NULL) {
            ApiCommunication::$pBackendRecursos = new \Edufw\services\educar\models\backend\Recursos();
        }

        return ApiCommunication::$pBackendRecursos;
    }

    /**
     * Agrupacion de servicios referidos a usuarios de Backend
     *
     * @return \Edufw\services\educar\models\backend\Usuarios
     */
    public static function BackendUsuarios() 
    {
        if (ApiCommunication::$pBackendUsuarios == NULL) {
            ApiCommunication::$pBackendUsuarios = new \Edufw\services\educar\models\backend\Usuarios();
        }

        return ApiCommunication::$pBackendUsuarios;
    }

    /**
     * Agrupacion de servicios referidos a Cursos
     *
     * @return \Edufw\services\educar\models\formacion\Cursos
     */
    public static function Cursos() 
    {
        if (ApiCommunication::$pCursos == NULL) {
            ApiCommunication::$pCursos = new \Edufw\services\educar\models\formacion\Cursos();
        }

        return ApiCommunication::$pCursos;
    }

    /**
     * Agrupacion de servicios referidos a Compras
     *
     * @return \Edufw\services\educar\models\backend\Compras
     */
    public static function Compras() 
    {
        if (ApiCommunication::$pCompras == NULL) {
            ApiCommunication::$pCompras = new \Edufw\services\educar\models\backend\Compras();
        }

        return ApiCommunication::$pCompras;
    }

    /**
     * Agrupacion de servicios referidos a Escuelas
     *
     * @return \Edufw\services\educar\models\formacion\Escuelas
     */
    public static function Escuelas() 
    {
        if (ApiCommunication::$pEscuelas == NULL) {
            ApiCommunication::$pEscuelas = new \Edufw\services\educar\models\formacion\Escuelas();
        }

        return ApiCommunication::$pEscuelas;
    }
    /**
     * Agrupacion de servicios de Conectados
     * @return \Edufw\services\educar\api\ApiConectados
     */
    public static function Conectados() 
    {
        if (ApiCommunication::$pConectados == NULL) {
            ApiCommunication::$pConectados = new \Edufw\services\educar\api\ApiConectados();
        }

        return ApiCommunication::$pConectados;
    }
    /**
     * Agrupacion de servicios de Social
     * @return \Edufw\services\educar\models\repositorio\Social
     */
    public static function Social() 
    {
        if (ApiCommunication::$pSocial == NULL) {
            ApiCommunication::$pSocial = new \Edufw\services\educar\models\repositorio\Social();
        }

        return ApiCommunication::$pSocial;
    }

    public function __construct($config)
    {
        ApiCommunication::$ci = $config['ci'];
        ApiCommunication::$sitio_nombre = $config['sitio_nombre'];
        ApiCommunication::$sitio_id = $config['sitio_id'];
        ApiCommunication::$web_service_client_key = $config['web_service_client_key'];
        ApiCommunication::$uri_service_api = $config['uri_service_api'];
    }

    /**
     * Devuelve la url correcta para visualizar un destacado
     *
     * @param string $destacado Array devuelto por la peticion a la API
     * @param string $url_visualizacion_recursos Es la URL donde se visualizan los recursos del sitio
     * @param string $url_visualizacion_contenidos Es la URL donde se visualizan los contenidos del sitio
     *
     * @return string
     */
    public static function GetDestacadoURL($destacado, $url_visualizacion_recursos, $url_visualizacion_contenidos)
    {
        if(isset($destacado['contenido_id']) && !empty($destacado['contenido_id'])){
            return $url_visualizacion_recursos . $destacado['contenido_id'];
        }
        if(isset($destacado['rec_id'])&& !empty($destacado['rec_id'])){
            return  $url_visualizacion_contenidos . $destacado['rec_id'];
        }
        if(isset($destacado['url']) && !empty($destacado['url'])){
            return $destacado['url'];
        }

        return '#';
    }
    
    /**
     * Obtiene uri completa al servicio requerido
     * @param String $service_name
     * @return String
     */
    public static function get_api_uri($service_name='') 
    {
        if (key_exists($service_name, self::$API_URIS)) {
            return self::$uri_service_api.self::$API_URIS[$service_name];
        }
        throw new EException('[ApiCommunication, get_api_uri] Servicio no registrado');
    }
}
