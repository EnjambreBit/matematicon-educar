<?php

namespace Edufw\services\educar\api;

use Edufw\core\EWebApp;
use Edufw\core\EException;

/**
 * Clase con métodos de REST.
 *
 * @author pgambetta
 */
class ApiConectados
{
    private static $pEntrada = NULL;
    private static $pEntradaSearch = NULL;
    private static $pEtiqueta = NULL;
    private static $pFileManager = NULL;
    private static $pMaestras = NULL;
    private static $pUsuario = NULL;
    private static $pValoracrion = NULL;

    /**
     * @return \Edufw\services\educar\models\conectados\Entrada
     */
    public static final function Entrada() 
    {
        if (ApiCommunication::$pEntrada == NULL) {
            ApiCommunication::$pEntrada = new \Edufw\services\educar\models\conectados\Entrada();
        }

        return ApiCommunication::$pEntrada;
    }
    
    /**
     * @return \Edufw\services\educar\models\conectados\EntradaSearch
     */
    public static final function EntradaSearch() 
    {
        if (ApiCommunication::$pEntradaSearch == NULL) {
            ApiCommunication::$pEntradaSearch = new \Edufw\services\educar\models\conectados\EntradaSearch();
        }

        return ApiCommunication::$pEntradaSearch;
    }
    
    /**
     * @return \Edufw\services\educar\models\conectados\Etiqueta
     */
    public static final function Etiqueta() 
    {
        if (ApiCommunication::$pEtiqueta == NULL) {
            ApiCommunication::$pEtiqueta = new \Edufw\services\educar\models\conectados\Etiqueta();
        }

        return ApiCommunication::$pEtiqueta;
    }
    
    /**
     * @return \Edufw\services\educar\models\conectados\FileManager
     */
    public static final function FileManger() 
    {
        if (ApiCommunication::$pFileManager == NULL) {
            ApiCommunication::$pFileManager = new \Edufw\services\educar\models\conectados\FileManager();
        }

        return ApiCommunication::$pFileManager;
    }
    
    /**
     * @return \Edufw\services\educar\models\conectados\Maestras
     */
    public static final function Maestras() 
    {
        if (ApiCommunication::$pMaestras == NULL) {
            ApiCommunication::$pMaestras = new \Edufw\services\educar\models\conectados\Maestras();
        }

        return ApiCommunication::$pMaestras;
    }
    
    /**
     * @return \Edufw\services\educar\models\conectados\Usuario
     */
    public static final function Usuario() 
    {
        if (ApiCommunication::$pUsuario == NULL) {
            ApiCommunication::$pUsuario = new \Edufw\services\educar\models\conectados\Usuario();
        }

        return ApiCommunication::$pUsuario;
    }
    
    /**
     * @return \Edufw\services\educar\models\conectados\Valoracion
     */
    public static final function Valoracion() 
    {
        if (ApiCommunication::$pValoracrion == NULL) {
            ApiCommunication::$pValoracrion = new \Edufw\services\educar\models\conectados\Valoracion();
        }

        return ApiCommunication::$pValoracrion;
    }
}
