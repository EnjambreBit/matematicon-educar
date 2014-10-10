<?php

namespace Edufw\core;

/**
 * Autocargador de clases usando spl_autoload_register()
 * @see http://www.php-fig.org/psr/psr-0/
 *
 * @author Gustavo Seip
 * @version 2.0
 */
class EClassLoader
{

    private $namespace_lib_name = '';
    private $app_src_root = '';
    private $core_root = '';
    private $app_mode = '';

    public function __construct($c = FALSE)
    {
        if ($c === FALSE) {
            throw new Exception('[EClassLoader] No se especifico la configuracion general de framework ');
        }
        $this->app_src_root = $c['APP_SRC'];
        $this->core_root = $c['CORE_APP'];
        $this->app_mode = $c['APP_MODE'];
        $this->namespace_lib_name = $c['CORE_NAMESPACE'];
    }

    /**
     * Registrar el autocargador con spl_autoload_register
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * Desregistrar el autocargador con spl_autoload_unregister
     * @return void
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'autoload'));
    }

    /**
     * Funcion que se registra bajo SPL para autocarga de clase core o de modulo
     * @param string $class
     */
    public function autoload($class)
    {
        $r = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        $pu = strpos($r, '_');
        $prb = strrpos($r, DIRECTORY_SEPARATOR);
        if(($pu !== false && $prb === false) || (($pu !== false && $prb !== false) && ($pu > $prb))){
            $r = str_replace('_', DIRECTORY_SEPARATOR, $r);
        }

        require $r;
        if (!class_exists($class) && !interface_exists($class)) {
//        if (!class_exists($class) && !interface_exists($class) && !trait_exists($class)) {  // PHP >= 5.4
            throw new \Exception("[EClassLoader] No es posible cargar la clase [$class]. Posibles problemas:\n
				- Espacio de nombres mal definido\n
				- El archivo [$r] no contiene la CLASE o INTERFACE $class requerida\n");
        }
    }

}

