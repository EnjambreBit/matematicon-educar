<?php

/**
 * Clase que instancia un clase MustacheData
 */
class mustacheData {

    /**
     * @var Edufw\web\views\templates_engines\EMustacheData
     */
    private static $mustacheData = null;

    /**
     * @return Edufw\web\views\templates_engines\EMustacheData
     */
    public static function getInstance(){
        if(self::$mustacheData == null){
            self::$mustacheData = new Edufw\web\views\templates_engines\EMustacheData();
        }

        return self::$mustacheData;
    }
}

/**
* Instancia al componente, y ejecuta la accion requerida
*
* @param <string> $module Nombre del modulo
* @param <string> $component Nombre del componente
* @param <array> $data Arreglo de parametros adicionales para la accion
*/
function include_component($module, $component, $data = array()){
    Edufw\core\EComponent::callComponent($module, $component, $data);
}

/**
 * Incluye un template respetando las especificaciones de mustache
 * @param <string> $template Path al template mustache
 * @param <array> $context [opcional] Es un array con el contexto de mustache
 */
function mst_include_template($template, $context = array()){
    echo Edufw\web\views\templates_engines\EMustache::mustache()->render($template, $context);
}

/**
 * Retorna un objeto EMustache
 * @return Edufw\web\views\templates_engines\EMustache
 */
function mst_mustache(){
    return Edufw\web\views\templates_engines\EMustache::mustache();
}

/**
 * Carga un css
 * @param <string> $href Es el path al css que se desea cargar
 * @param <string> $media [opcional]
 * @param <bool> $local [opcional] Establece si el archivo se encuentra o no en el mismo dominio
 */
function include_css($href, $media = 'all', $local = true){
$source = $local ? Edufw\core\EWebApp::config()->APP_URL . 'css/' . $href : $href;
    echo sprintf('<link rel="stylesheet" type="text/css" href="%s.css" media="%s"/>', $source, $media);
}

/**
 * Carga un js
 * @param <string> $href Es el path al css que se desea cargar
 * @param <bool> $local [opcional] Establece si el archivo se encuentra o no en el mismo dominio
 */
function include_js($src, $local = true){
    $source = $local ? Edufw\core\EWebApp::config()->APP_URL . 'js/' . $src : $src;
    sprintf('<script type="text/javascript" src="%s.js"></script>', $source);
}

/**
 * Imprime lo recibido entre un tag script
 * @param <string> $html
 */
function include_custom_script($html){
    sprintf('<script type="text/javascript">%s</script>', $html);
}

/**
 * Imprime lo recibido entre un tag noscript
 * @param <string> $html
 */
function include_no_script($html){
    sprintf('<noscript type="text/javascript">%s</noscript>', $html);
}
