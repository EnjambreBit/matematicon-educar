<?php
/* 
 * Aquí se definen las rutas parametrizadas para el proyecto.
 * 
 * Importante:
 * ***********
 * - Se debe agregar las nuevas rutas al array $routes (creado en el bootstrap del framework)
 * - Se deben ordenar las reglas de los mas especifico a lo mas general. Esta condición de puede determinar en base a 
 *   la cantidad de "/" y subcadenas, que se encuentren en el patron.
 *   Ej. La regla '/\//' es mas general que '/\/(\w+)\/(\w+)/', porque...
 *      -- cant "/" de '/\//' = 1 y cant subcadenas = 0
 *      -- cant "/" de '/\/(\w+)\/(\w+)/' es 2 y cant subcadenas = 2
 *      -- Total1 '/\//' = 1 + 0 = 1
 *      -- Total2 '/\/(\w+){1}\/(\w+){1}/' = 2 + 2 = 4
 *      Resultado: Total2 > Total1, por lo tanto; el patron '/\//' es mas general que el patron '/\/(\w+)\/(\w+)/'
 * - Si dos patrones tienen el mismo orden, solo difieren en su semantica.
 *   Ej. "//\admin/\login/" y "//\admin/\logout/" tienen el mismo orden, pero difieren en la 2da subcadena
 *      en cuanto a semantica.
 * 
 * ERouter::__construct()
 * ***********************
 *
 * Constructor de ERouter
 * 
 * El framework soporta las siguientes url's ("/", "/module/", "/module/controller/", "/module/controller/action")
 * 
 * @param array $routes Es un array del tipo clave => valor, donde la clave es la ruta y el valor un array de parámetros
 *
 * CLAVE
 *
 *  La ruta debe iniciar con "/" y luego separar los grupos deseados con "/", para soportar parámetros al final de la URL simplemente finalizar con "*"
 *  Los grupos que representan una variable (:var_name), módulo (:module), controlador (:controller) o acción (:action) deben iniciar con ":"
 *  Ninguno de ellos son obligatorios, y en caso de no estar presentes se asignará el valor por defecto cargado en el archivo de configuración 
 *  Los grupos que simplemente son para exigir una texto dentro de la url no llevan ":"
 *  De esta forma una ruta válida podría ser "/admin/:controller/:action/*
 *
 * PARAMETROS
 *  
 * El array de parámetros es un array del tipo (clave=>valor), en el mismo se indica cómo debe validad cada grupo, 
 * y se asignan los valores necesarios para el funcionamiento del router 
 * Los valores OBLIGATORIOS son: (:module, :controller, :action) y en caso de no recibirlos, se cargan los valores por defecto cargados en el archivo de configuración 
 * Aparte de recibir expresiones regulares [las cuales deben estar siempre entre ()] se pueden definir constantes soportadas por el router
 * 
 * Constantes soportadas:
 * **********************
 *      alphanumeric
 *      alphabetic
 *      text
 *
 * Ejemplos de utilización de ERouter
 * **********************************
 *
 * Manejar la aplicación con un único módulo, todas las peticiones serán atendidas por el módulo comunidad. No se reciben variables.
 * $routes['/:controller/:action'] = array(':module' => 'ModuleName'));
 *
 * Manejar la aplicación con un único módulo, todas las peticiones serán atendidas por el módulo comunidad. Se reciben N variables.
 * $routes['/:controller/:action/*'] = array(':module' => 'ModuleName'));
 *
 * Se recibe una variable de texto al inicio de la url, luego se pide que exista el texto "texto" y luego se pide la acción,
 * el controlador y el módulo y que pueda recibir variables.
 * $routes['/:mi_variable/texto/:action/:controller/:module/*'] = array(':mi_variable' => 'alphanumeric'));
*/
$routes['/comunidades'] = array(':module' => 'Comunidad', ':controller' => 'Listado', ':action' => 'inicio');
$routes['/usuario/perfil/modificar'] = array(':module' => 'Usuario', ':controller' => 'Perfil', ':action' => 'modificar');
$routes['/usuario/:alias/perfil'] = array(':module' => 'Usuario', ':controller' => 'Perfil', ':action' => 'index', ':alias' => 'text');
$routes['/usuario/:alias/actividad'] = array(':module' => 'Usuario', ':controller' => 'Actividad', ':action' => 'inicio', ':alias' => 'text');
$routes['/comunidad/:alias/actividad'] = array(':module' => 'Comunidad', ':controller' => 'Actividad', ':action' => 'inicio', ':alias' => 'text');
$routes['/comunidad/:alias/listado'] = array(':module' => 'Comunidad', ':controller' => 'Listado', ':action' => 'inicio', ':alias' => 'text');
$routes['/comunidad/:alias/miembros'] = array(':module' => 'Comunidad', ':controller' => 'Miembros', ':action' => 'inicio', ':alias' => 'text');
$routes['/comunidad/:alias/detalle'] = array(':module' => 'Comunidad', ':controller' => 'Detalle', ':action' => 'acercade', ':alias' => 'text');


// Analizar esta ruta (no funciona con http://social.educ.ar/Comunidad/listado/inicio)
//$routes['/:module/:alias/:controller'] = array(':action' => 'inicio', ':alias' => 'text');
//$routes['/usuario/perfil/:alias'] = array(':module' => 'Usuario', ':controller' => 'Perfil', ':action' => 'index', ':alias' => 'text');