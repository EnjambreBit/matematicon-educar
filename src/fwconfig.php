<?php

/**
 * Configuracion General del framework
 * 
 * En caso de querer incluir configuraciones propias 
 * del proyecto y/o del modulo, modificar el archivo config.php en 
 * PROYECT_NAME/ o en PROYECT_NAME/MODULE_NAME/
 * 
 * Nota: PHP version >= 5.3
 */

/* ********************** VARIABLES CORE **********************
 * Para el correcto funcionamiento del framework se recomienda
 * no reemplazar/modificar las siguientes variables:
 */

//DEFINICIONES DISPONIBLES: (ROOT, APP_ROOT, WWW_ROOT, WEBROOT_NAME, APP_SRC_PATH, APP_SRC, APP_NAME, CORE_NAME)
$fwconfig = array();
$fwconfig['ROOT'] = ROOT;
$fwconfig['CORE_APP'] = $fwconfig['APP_ROOT'] = APP_ROOT;
$fwconfig['APP_NAME'] = APP_NAME;
$fwconfig['APP_WEBROOT'] = WWW_ROOT;
$fwconfig['CORE_NAMESPACE'] = CORE_NAME;
$fwconfig['CORE_LIB'] = CORE_INCLUDE_PATH;
$fwconfig['APP_SRC'] = APP_SRC_PATH;
$fwconfig['APP_LIB'] = CORE_INCLUDE_PATH . 'lib' . DS;
$fwconfig['VENDOR_PATH'] = CORE_INCLUDE_PATH . 'vendor' . DS;
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443))
    ? $protocol = 'https://'
    : $protocol = 'http://';
$fwconfig['APP_URL'] = $protocol . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') . '/';
$fwconfig['APP_HOST_NAME'] = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');

/* ******************** VARIABLES REEMPLAZABLES ********************
 * En caso de querer modificar el funcionamiento default de las
 * siguientes variables, simplemente crearlas en el config del proyecto
 * y/o del modulo con el valor deseado, de esta forma se reemplazara
 * el valor original por el ingresado en el config mencionado
 */

/**
 * Modo de ejecución.
 * 'dev' para mostrar errores por pantalla, etc [modo desarrollo]
 * 'prod' para no mostrar errores por pantalla [modo produccion]
 */
$fwconfig['APP_MODE'] = 'dev';

/**
 * Configuracion de ELogger
 * [nombre config] => [handler, formatter,[datos propios segun handler utilizado]]
 */
$fwconfig['CORE_ELOGGER'] = array(
    'default' => array(
        'handler' => 'Edufw\core\logger\ELoggerHandlerFile',
        'formatter' => 'Edufw\core\logger\ELoggerFileFormatter',
        'rootpath' => '/space/log/social/'
    )
);

/**
 * Vistas de Error
 * Paths a layouts y views de error
 */
$fwconfig['CORE_ERROR_LAYOUT'] = 'error';
$fwconfig['CORE_APP_ERROR_VIEWS'] = array(
	404 => $fwconfig['CORE_LIB'].'web/views/error_pages/error_404.php'
);
