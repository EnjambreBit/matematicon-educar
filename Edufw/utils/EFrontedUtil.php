<?php

namespace Edufw\utils;

use Edufw\core\EWebApp;

/**
 * Utilidad de retro-compatibilidad con Edufw 1.0 para carga de js/css y variables
 *
 * @author pgambetta
 */
class EFrontedUtil {
    
    const TAG_SCRIPT = '<script type="text/javascript" src="%s.js?version=%s"></script>';
    const TAG_CUSTOM_SCRIPT = '<script type="text/javascript">%s</script>';
    const TAG_NO_SCRIPT = '<noscript type="text/javascript">%s</noscript>';
    const TAG_CSS = '<link rel="stylesheet" type="text/css" href="%scss/%s.css" media="%s"/>';
    const TAG_STR_VAR = "var %s = '%s';";
    const TAG_VAR = "var %s = %s;";
    
    private $path = array();
    private $version;
    private static $jsVars = array();
    private static $headerIndexScripts = array();
    private static $headerScripts = array();
    private static $headerLastScript;
    private static $headerIndexCss = array();
    private static $headerNoScripts = array();
    private static $headerCustomScripts = array();
    private static $headerCss = array();
    private static $packages = array();
    public static $jsCssHtml;

    /**
     * Constructor de la clase, setea las propiedades e incluye lo necesario para funcionar.
     *
     * @param bool $package (Opcional), default false, establece que paquete de js cargar
     */
    public function __construct() {
        // Se cargan los path a utilizar.
        $this->path['url'] = EWebApp::config()->APP_URL;
        $this->path['public'] = $this->path['url'] . 'lib_public/';
        $this->path['public_js'] = $this->path['url'] . 'lib_public/js/';
        $this->path['js'] = $this->path['url'] . 'js/';

        $this->version = (file_exists(EWebApp::config()->APP_ROOT . 'version.txt'))
            ? file_get_contents(EWebApp::config()->APP_ROOT . 'version.txt')
            : 1;
    }
    
    public function render(){
        $this->sendJsVars();
        $this->getJsAndCss();
    }
    
    /**
     * Carga un script que se encuentre dentro de la carpeta "lib_public/js". en el header
     *
     * @param string $source Path del script a cargar. Ej: lib_public/example
     */
    public function loadHeaderPublicScript($source){
        if(!in_array($source, self::$headerIndexScripts)){
            self::$headerIndexScripts[] = $source;
            self::$headerScripts[] = array(
                $this->path['public_js'] . $source,
                $this->version
            );
        }
    }
    
    /**
     * Carga un script que se encuentre dentro de la carpeta "lib_public/js". en el header
     *
     * @param string $source Path del script a cargar. Ej: lib_public/example
     */
    public function loadHeaderPublicExternalScript($source){
        if(!in_array($source, self::$headerIndexScripts)){
            self::$headerIndexScripts[] = $source;
            self::$headerScripts[] = array(
                $this->path['public'] . $source,
                $this->version
            );
        }
    }    

    /**
     * Carga un script (en el header) que se encuentre dentro de la carpeta "js".
     *
     * @param string $source Path del script a cargar. Ej: views/example/example.js
     */
    public function loadHeaderScript($source){
        if(!in_array($source, self::$headerIndexScripts)){
            self::$headerIndexScripts[] = $source;
            self::$headerScripts[] = array(
                $source,
                $this->version
            );
        }
    }
    
    /**
     * Carga un script (en el header, ultimo a cargar) que se encuentre dentro de la carpeta "js".
     *
     * @param string $source Path del script a cargar. Ej: views/example/example.js
     */
    public function loadHeaderLastScript($source){
        if(!in_array($source, self::$headerIndexScripts)){
            self::$headerIndexScripts[] = $source;
            self::$headerLastScript = array(
                $source,
                $this->version
            );
        }
    }
    
    /**
     * Carga el codigo de  un script en el header.
     *
     * @param string $source Path del script a cargar. Ej: views/example/example.js
     */
    public function loadHeaderCustomScript($source){
        if(!in_array($source, self::$headerIndexScripts)){
            self::$headerIndexScripts[] = $source;
            self::$headerCustomScripts[] = $source;
        }        
    }    

    /**
     * Genera el codigo HTML para cargar un css en el header de la vista
     * 
     * @param string $source Path del script a cargar. Ej: css/example
     * @param string $media [opcional, default "all"]
     */
    public function loadHeaderCss($source, $media='all'){
        if(!in_array($source, self::$headerIndexCss)){
            self::$headerIndexCss[] = $source;
            self::$headerCss[] = array(
                $this->path['url'],
                $source,
                $media
            );
        }
    }

    /**
     * Carga un noscript (en el header).
     *
     * @param string $html Codigo HTML a insertar en el noscript.
     */
    public function loadHeaderNoScript($html) {
        if(!in_array($html, self::$headerIndexScripts)){
            self::$headerIndexScripts[] = $html;
            self::$headerNoScripts[] = $html;
        }
        
    }
    
    /**
     * Agrega una variable para despues generarla mediante javascript
     * 
     * @param string $varName Es el nombre de la variable js
     * @param string $varValue [string, array, integer] El valor de la variable
     * @param string $type ["string", "array", "integer"] Establece que tipo de variable a enviar
     * 
     * @see En caso de que el tipo seleccionado sea array, el array debe ser numérico y no asociativo
     */
    public static function addJsVar($varName, $varValue){
        self::$jsVars[$varName]['value'] = $varValue; 
    }
            
    /**
     * Quita todos los elementos a ser cargados en una vista/layouts
     */
    public static function clearData(){
        self::$packages = null;
        self::$jsVars = null;
        self::$headerScripts = null;
        self::$headerCss = null;
    }
    /**
     * Genera el javascript con todas las variables que se encuentren en jsVars
     *
     * PARA CORRECTO FUNCIONAMIENTO - ejecutarlo en el header
     *
     */
    private function sendJsVars(){
        if(!empty (self::$jsVars)){
            $strJs = '';
            foreach (self::$jsVars as $key => $var) {
                if(is_string($var['value']))
                {
                    $strJs.= sprintf(self::TAG_STR_VAR, $key, $var['value']);
                } else {
                    if(is_array($var['value'])) 
                    {
                        $vars = '[';
                        $total = count($var['value']);
                        for($i = 0; $i < $total; $i++){
                            $vars .= '"' . $var['value'][$i] . '", ';
                        }
                        $vars = substr($vars, 0, strrpos($vars, ','));
                        $vars .= ']';  
                    } 
                    else if(is_bool($var['value'])) 
                    {
                        $vars = ($var['value']) ? 'true' : 'false';
                        $strJs.= sprintf(self::TAG_VAR, $key, $vars);
                    } 
                    else 
                    {
                        $vars = $var['value'];
                    }
                    
                    $strJs.= sprintf(self::TAG_VAR, $key, $vars);
                }
            }
            
            echo sprintf(self::TAG_CUSTOM_SCRIPT, $strJs);
        }
    }

   /**
    * Genera el codigo HTML con las variables JS deseadas, los pedidos de archivos JS y CSS
    *
    */
    private function getJsAndCss(){
        
        (string)$strJs = "";
        
        while(!empty(self::$headerNoScripts)){
            $noScript = array_shift(self::$headerNoScripts);
            $strJs .= sprintf(self::TAG_NO_SCRIPT, $noScript);
        }
        
        while(!empty(self::$headerScripts)){
            $script = array_shift(self::$headerScripts);
            $strJs .= sprintf(self::TAG_SCRIPT, $script[0], $script[1]);
        }
        
        while(!empty(self::$headerCustomScripts)){
            $customScript = array_shift(self::$headerCustomScripts);
            $strJs .= sprintf(self::TAG_CUSTOM_SCRIPT, $customScript);
        }
        
        if(self::$headerLastScript !== false){
            $lastScript = self::$headerLastScript;
            $strJs .= sprintf(self::TAG_SCRIPT, $lastScript[0], $lastScript[1]);
        }
        
        while(!empty(self::$headerCss)){
            $css = array_shift(self::$headerCss);
            $strJs .= sprintf(self::TAG_CSS, $css[0], $css[1], $css[2]);
        }
        
        echo $strJs;
    }

}
