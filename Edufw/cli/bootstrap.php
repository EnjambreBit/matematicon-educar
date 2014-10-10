<?php
/**
 * Carga dependencias core del framework
 *
 * @author Gustavo Seip
 */
use Edufw\core\EWebApp;
use Edufw\core\ERouter;
use Edufw\core\EClassLoader;

require 'config.php'; // Incluir configuracion global del framework
// Path de busqueda para el auto-loader
set_include_path(get_include_path().PATH_SEPARATOR.$fwconfig['CORE_APP'].PATH_SEPARATOR.$fwconfig['APP_SRC'].PATH_SEPARATOR.$fwconfig['VENDOR_PATH'].PATH_SEPARATOR.$fwconfig['APP_LIB']);
require $fwconfig['CORE_LIB'].'core/EClassLoader.php';
$loader = new EClassLoader($fwconfig);
$loader->register();
EWebApp::config()->set($fwconfig); // Establecer configuracion global

