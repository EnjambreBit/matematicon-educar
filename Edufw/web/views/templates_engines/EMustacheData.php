<?php
namespace Edufw\web\views\templates_engines;

use Edufw\core\EWebApp;

/**
 * Implementacion de la libreria Mustache
 *
 * @author pgambetta
 *
 * @TODO: limpieza de cosas que al final no se usaron.
 */
class EMustacheData {

    const MUSTACHE_EXTENSION = '.mustache';
    const HEAD_SCRIPTS = 'headScripts';
    const FOOTER_SCRIPTS = 'footerScripts';
    const STYLESHEETS = 'stylesheets';
    const JS_VARS = 'jsVars';
    const PAGE_TITLE = 'pageTitle';
    const BODY_CLASSES = 'bodyClasses';
    const CLIENT_TEMPLATES = 'clientTemplates';

    private static $context_keys = array(self::HEAD_SCRIPTS, self::FOOTER_SCRIPTS, self::STYLESHEETS, self::JS_VARS, self::PAGE_TITLE, self::BODY_CLASSES, self::CLIENT_TEMPLATES);

    private static $pageTitle = null;
    private static $bodyClasses = null;
    private static $headScripts = array();
    private static $footerScripts = array();
    private static $stylesheets = array();
    private static $jsVars = array(
        'str_var' => array(),
        'no_str_var' => array()
    );
    private static $data = array();
    private static $clientTemplates = array();

    private static $templates_url;
    private static $partials_url;

    public function __construct($templates_url = false, $partials_url = false) {
        // Si no recibimos opciones cargar las defaults
        self::$templates_url = $templates_url == false ? EWebApp::config()->MODULE_PATH . \Edufw\web\views\templates_engines\EMustache::DEFAULT_TEMPLATES_PATH : $templates_url;
        self::$partials_url = $partials_url == false ? EWebApp::config()->MODULE_PATH . \Edufw\web\views\templates_engines\EMustache::DEFAULT_PARTIALS_PATH : $partials_url;
    }

    /**
     * Carga el titulo de la pagina
     * @uses head.mustache
     * @param string $pageTitle
     */
    public function setPageTitle($pageTitle){
        self::$pageTitle = $pageTitle;
    }

    /**
     * Carga un array de clases al body
     * @uses head.mustache
     * @param array $bodyClasses Array de clases a cargar al body
     */
    public function setBodyClasses($bodyClasses = array()){
        self::$bodyClasses = implode(' ', $bodyClasses);
    }

    /**
     * Genera el array para cargar un css mediante mustache
     * @uses head.mustache
     * @param string $href Path del css a cargar.
     * @param string $media [opcional, default "all"]
     * @param bool $local Establece si el css a cargar esta en el actual dominio o no
     */
    public function addCss($href, $media = 'all', $local = true){
        $source = $local ? EWebApp::config()->APP_URL . $href : $href;
        $css = array('media' => $media, 'href' => $source);
        array_push(self::$stylesheets, $css);
    }

    /**
     * Método para cargar css a partir de un array
     *
     * $css = array(
     *      array('href' => 'css_testing'),
     *      array(
     *          'href' => 'css_testing2',
     *          'local' => false,
     *          'media' => 'print'
     *      )
     * )
     *
     * @param array $css Array de css a cargar
     */
    public function addCssArray($css){
        array_walk($css, array($this, 'addCssFromArray'));
    }

    private function addCssFromArray($css){
        $source = !isset($css['local']) || $css['local'] == true ? EWebApp::config()->APP_URL . $css['href'] : $css['href'];
        $media  = isset($css['media']) ? $css['media'] : 'all';
        $css = array('media' => $media, 'href' => $source);
        array_push(self::$stylesheets, $css);
    }

    /**
     * Genera el array para cargar un js mediante mustache
     * @uses head.mustache
     * @param string $src Path del js a cargar.
     * @param bool $local Establece si el js a cargar esta en el actual dominio o no
     * @param bool $async Establece si el js de forma asincrónica
     */
    public function addHeaderJs($src, $local = true, $async = false){
        $source = $local ? EWebApp::config()->APP_URL . $src : $src;
        $js = array('src' => $source);
        if($async){
            $js['async'] = 'async';
        }
        array_push(self::$headScripts, $js);
    }

    /**
     * Método para cargar js a partir de un array en el header
     *
     * $js = array(
     *      array('src' => 'js_testing'),
     *      array(
     *          'src' => 'js_testing2',
     *          'local' => false,
     *          'async' => true
     *      )
     * )
     *
     * @param array $js Array de js a cargar
     */
    public function addHeaderJsArray($js){
        array_walk($js, array($this, 'addHeaderJsFromArray'));
    }

    private function addHeaderJsFromArray($js){
        $source = !isset($js['local']) || $js['local'] == true ? EWebApp::config()->APP_URL . $js['src'] : $js['src'];
        $njs = array('src' => $source);
        if(isset($js['async']) && $js['async'] == true){
            $njs['async'] = 'async';
        }
        array_push(self::$headScripts, $njs);
    }

    /**
     * Genera el array para cargar un js mediante mustache
     * @uses foot.mustache
     * @param string $src Path del js a cargar.
     * @param bool $local Establece si el js a cargar esta en el actual dominio o no
     * @param bool $async Establece si el js de forma asincrónica
     */
    public function addFooterJs($src, $local = true, $async = false){
        $source = $local ? EWebApp::config()->APP_URL . $src : $src;
        $js = array('src' => $source);
        if($async){
            $js['async'] = 'async';
        }
        array_push(self::$footerScripts, $js);
    }

    /**
     * Método para cargar js a partir de un array en el footer
     *
     * $js = array(
     *      array('src' => 'js_testing'),
     *      array(
     *          'src' => 'js_testing2',
     *          'local' => false,
     *          'async' => true
     *      )
     * )
     *
     * @param array $js Array de js a cargar
     */
    public function addFooterJsArray($js){
        array_walk($js, array($this, 'addFooterJsFromArray'));
    }

    private function addFooterJsFromArray($js){
        $source = !isset($js['local']) || $js['local'] == true ? EWebApp::config()->APP_URL . $js['src'] : $js['src'];
        $njs = array('src' => $source);
        if(isset($js['async']) && $js['async'] == true){
            $njs['async'] = 'async';
        }
        array_push(self::$footerScripts, $njs);
    }

    /**
     * Genera el array para cargar uno o mas templates para el cliente js
     *
     * @uses foot.mustache
     * @param array $partials Es un array con los path locales de los parciales a cargar
     */
    public function addClientPartials($partials){
        array_walk($partials, array($this, 'addClientPartialsFromArray'));
    }

    private function addClientPartialsFromArray($partials){
        $template = array (
            'id' => 'tpl_' . str_replace('/', '_', $partials),
            'tpl' => file_get_contents(self::$templates_url . $partials . self::MUSTACHE_EXTENSION)
        );

        array_push(self::$clientTemplates, $template);
    }
    /**
     * @param array $data Array de datos a ser pasados a mustache
     */
    public function addData($data = array()){
        if(is_array($data) && !empty($data)){
           self::$data = array_merge(self::$data, $data);
        }
    }

    public function createContext(){
        $values = array(self::$headScripts, self::$footerScripts, self::$stylesheets, self::$jsVars, self::$pageTitle, self::$bodyClasses, self::$clientTemplates);
        $context = array_combine(self::$context_keys, $values);
        $context = array_merge($context, self::$data);
        return $context;
    }
}
