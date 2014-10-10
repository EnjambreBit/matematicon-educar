<?php

namespace Edufw\core;

use Edufw\core\EView;
use Edufw\core\EWebApp;

/**
 * Clase base para todos los controladores.
 *
 * @name EComponent
 * @package lib
 * @version 20140408
 * @author pgambetta
 */
abstract class EComponent {

    /**
     * Guarda los datos a ser usados por la vista requerida
     * 
     * @var Array
     */
    protected $_data = array();

    /**
     * Instancia al componente, y ejecuta la accion requerida
     *
     * @param <string> $module
     *        	Nombre del modulo
     * @param <string> $component
     *        	Nombre del componente
     * @param <array> $data
     *        	Arreglo de parametros adicionales para la accion
     */
    public static function callComponent($module, $component, $data = array()) {
        try {
            $componentNS = EWebApp::config()->APP_NAME . '\\' . $module . '\\components\\' . $component . 'Component';
            $objComponent = new $componentNS();
            $action = 'execute' . $component;
            if (method_exists($objComponent, $action)) {
                $ref = new \ReflectionMethod($objComponent, $action);
                $par = $ref->getParameters();
                $cant = count($par);
                if ($cant === 1 && !empty($data)) {
                    $objComponent->$action($data);
                } else {
                    $objComponent->$action();
                }
                unset($data);
            } else {
                throw new \Exception('Metodo en componnte no encontrado', 404);
            }
            // Se renderiza la vista relacionada con el componete
            $objComponent->render($module, $component);
            unset($componentNS, $objComponent, $action, $ref, $par, $cant, $data);
        } catch (\Exception $e) {
            if (EWebApp::config()->APP_MODE !== 'dev') {
                EView::pageNotFound();
            } else {
                throw new EException('', 0, $e);
            }
        }
    }

    /**
     * Renderiza una vista para producir una salida HTML o una presentacion REST
     * @param <string> $module Nombre del módulo de la vista a renderizar
     * @param <string> $file Nombre del archivo de vista a renderizar
     * @param <string> $layout Nombre de la estructura HTML que contendra la vista
     * <p>$layout='layout1' renderiza con layout 'layout1'
     *    $layout=FALSE renderiza sin layout (util para AJAX)
     *    $layout=NULL renderiza con layout 'index' predeterminado
     * </p>
     */
    public final function render($module, $file) {
        $file = $module . '/views/components/' . lcfirst($file);
        $out = self::getProcessContent(array('view' => $file, 'data' => $this->_data));
        echo $out;
    }

    /**
     * Procesa contenido de vista, segun la vista recibida.
     * Si corresponde, se aplica un layout (explícito o por default).
     * Se retorna el resultado de la evaluacion.
     * @param array $p Parametros de entrada. <br>
     * Valores obligatorios: <br>
     * 		'view': Vista de presentacion <br>
     * Valores opcionales: <br>
     * 		array ['data']:   Datos que pueden usarse en la vista <br>
     */
    private final static function getProcessContent($p = array()) {
        ob_start();
        if (isset($p['data'])) {
            foreach ($p['data'] as $name => $value) {
                $$name = $value;
            }
        }
        $rootbase = EWebApp::config()->APP_ROOT;
        if (isset($p['view'])) {
            $content_layout = "{$rootbase}{$p['view']}.php";
        } else {
            throw new Exception('[EView, getProcessContent()] No se ha definido vista de presentacion');
        }
        include $content_layout;
        $out = ob_get_contents();
        ob_end_clean();
        return $out;
    }

}
