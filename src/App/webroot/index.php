<?php
    
/**
 * Punto de entrada a la aplicación
 */

/**
 * Utilizar DS como separador de directorios
 * (NO MODIFICAR ESTA CONFIGURACION)
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * PATH COMPLETO hasta el directorió público de la aplicación
 * (NO MODIFICAR ESTA CONFIGURACION)
 */
define('WWW_ROOT', dirname(__FILE__) . DS);

/**
 * Estas definiciones sólo deberían ser modificadas en caso de haber
 * instalado Edufw en un directorio diferente al recibido en la distribución
 * del mismo
 * 
 * Cuando se esté utilizando una configuración personalizada, asegurarse de utilizar
 * DS como separador de directorio
 */

/**
 * Nombre de la carpeta core del framework
 */
define('CORE_NAME', 'Edufw');

/**
 * PATH COMPLETO hasta el ROOT de la aplicación
 */
define('ROOT', dirname(dirname(dirname(dirname(__FILE__)))) . DS);

/**
 * PATH COMPLETO hasta el directorio 'src' (contenedor de la aplicación)
 */
define('APP_SRC_PATH', dirname(dirname(WWW_ROOT)) . DS);

/**
 * PATH COMPLETO hasta el ROOT de la aplicación (src/NOMBRE_PROYECTO/)
 */
define('APP_ROOT', dirname(WWW_ROOT) . DS);

/**
 * ADVERTENCIA (NO MODIFICAR EL RESTO DE LAS CONFIGURACIONES)
 * **********************************************************
 * **********************************************************
 * 
 * No es necesario la modificación de las siguientes configuraciones,
 * las mismas se generar a partir de los PATH COMPLETOS cargados anteriormente
 */

/**
 * Nombre de la carpeta pública
 * (No es necesario su modificación, simplemente cargando el PATH COMPLETO en WWW_ROOT es suficiente
 */
$webroot = trim(WWW_ROOT, DS);
define('WEBROOT_NAME', substr($webroot, strrpos($webroot, DS) + 1));

/**
 * Nombre de la carpeta contenedora del proyecto 'src'
 * (No es necesario su modificación, simplemente cargando el PATH COMPLETO en APP_SRC_PATH es suficiente
 */
$src_dir = trim(APP_SRC_PATH, DS);
define('APP_SRC', substr($src_dir, strrpos($src_dir, DS) + 1));

/**
 * Es el nombre del proyecto
 * (No es necesario su modificación, simplemente cargando el PATH COMPLETO en APP_ROOT es suficiente
 */
$app_dir = trim(APP_ROOT, DS);
define('APP_NAME', substr($app_dir, strrpos($app_dir, DS) + 1));

/**
 * PATH COMPLETO hasta el directorio "Edufw"
 * (No es necesario su modificación, simplemente cargando el PATH COMPLETO en ROOT es suficiente
 */
define('CORE_INCLUDE_PATH', ROOT . DS . CORE_NAME . DS);

// Dar inicio a la aplicacion
require_once APP_SRC_PATH . DS . 'bootstrap.php';