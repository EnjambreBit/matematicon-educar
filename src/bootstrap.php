<?php
/**
 * Carga dependencias core del framework
 *
 * @author Gustavo Seip
 */
use Edufw\core\EWebApp;
use Edufw\core\ERouter;
use Edufw\core\EClassLoader;
        
require 'fwconfig.php'; // Incluir configuracion global del framework
// Path de busqueda para el auto-loader
set_include_path(get_include_path().PATH_SEPARATOR.$fwconfig['ROOT'].PATH_SEPARATOR.$fwconfig['CORE_APP'].PATH_SEPARATOR.$fwconfig['APP_SRC'].PATH_SEPARATOR.$fwconfig['VENDOR_PATH'].PATH_SEPARATOR.$fwconfig['APP_LIB']);
require $fwconfig['CORE_LIB'].'core' . DS . 'EClassLoader.php';
$loader = new EClassLoader($fwconfig);
$loader->register();
EWebApp::config()->set($fwconfig); // Establecer configuracion global
require_once $fwconfig['CORE_APP'].'config.php';
EWebApp::config()->set($config); // Establecer configuracion local
unset($config);
// Se crea el array de rutas utilizado por Erouter
$routes = array();
require_once $fwconfig['CORE_APP'].'routes.php';
$eRouter = new ERouter($routes);
unset($fwconfig, $routes);
$eWebApp = new EWebApp($eRouter);
$eWebApp->run();