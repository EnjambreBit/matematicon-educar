<?php

namespace Edufw\core;

// Se hace la inclusión de helpers para las vistas
require_once EWebApp::config()->CORE_LIB . 'helpers/TemplateHelper.php';

/**
 * Clase para procesamiento de vistas.
 * Procesa contenido de vistas con sus
 * respectivos layouts (si fueran necesarios).
 *
 * @author Gustavo Seip
 */
class EView
{
	/**
	 * Procesa contenido de vista, segun la vista recibida.
	 * Si corresponde, se aplica un layout (explícito o por default).
	 * Se retorna el resultado de la evaluacion.
	 * @param array $p Parametros de entrada. <br>
	 * Valores obligatorios: <br>
	 *		'view': Vista de presentacion <br>
	 * Valores opcionales: <br>
	 * 		array ['data']:   Datos que pueden usarse en la vista <br>
	 * 		string ['layout']: Layout que puede aplicarse a la vista <br>
	 * 		boolean ['use_global_rootbase']: Si está presente, el rootbase de <br> 
	 * 			localizacion de las vistas es global. <br>
	 */
	public final static function getProcessContent($p = array()) {
		ob_start();
		if (isset($p['data'])) {
			foreach($p['data'] as $name => $value) {
				$$name = $value;
			}
		}
		$use_root_base = isset($p['use_global_rootbase']) && $p['use_global_rootbase']==TRUE; 
		$rootbase = ($use_root_base) ? EWebApp::config()->CORE_LIB : EWebApp::config()->APP_ROOT;
		if (isset($p['view'])) {
			$content_layout = "{$rootbase}{$p['view']}.php";
		} else {
			throw new Exception('[EView, getProcessContent()] No se ha definido vista de presentacion');
		}
		if (isset($p['layout'])) {
			if ($p['layout'] === FALSE) {
				include_once $content_layout;
			} else {
				include_once "{$rootbase}{$p['layout']}.php";
			}
		}
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	
	/**
	 * Genera tags HTML para incluir una libreria javascript en una vista
	 *
	 * @param string $name
	 *        	Nombre de libreria javascript a incluir
	 * @return string Tags HTML para realizar la inclusion de la libreria javascript
	 */
	public final static function jsLibrary($name) {
		echo '<script type="text/javascript" src="' . EWebApp::config()->APP_URL . "js/$name" . '.js"></script>';
	}
	
	/**
	 * Obtiene vista de error generica para excepciones
	 *
	 * @param <Exception> $exception        	
	 * @param string $layout        	
	 * @return <string> vista representando la excepcion
	 */
	public final static function getErrorView($exception = NULL, $log_id = NULL, $layout = FALSE) {
		if ($layout) {
			$layout = 'web/views/error_pages/layouts/error_exception';
		}
		if (!isset($exception)) {
			$file = 'web/views/error_pages/error_404_exception_user';
		} else {
			$file = 'web/views/error_pages/error_404_exception';
		}
		return EView::getProcessContent(array(
			'view' => $file,
			'data' => array(
				'exception' => $exception,
				'log_id' => $log_id 
			),
			'layout' => $layout,
			'use_global_rootbase' => TRUE 
		));
	}
	
	/**
	 * Produce pagina no encontrada
	 */
	public final static function pageNotFound($p = array()) {
		ob_start(); // guardar en buffer lo que se produzca
		$view = EWebApp::config()->CORE_APP_ERROR_VIEWS[404];
		include_once $view;
		// Preparando datos necesarios para la vista e incluir la vista
		$out = ob_get_contents();
		ob_end_clean();
		echo $out;
                exit();
	}
}
