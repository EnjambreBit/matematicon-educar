<?php
namespace Edufw\core;

/**
 * Representa una excepcion generica para los propositos generales
 * del framework
 *
 * @name EException
 * @package lib
 * @version 20110125
 * @author gseip
 */
class EException extends \Exception {
    /**
     * Profundidad de busqueda de excepciones internas
     */
    const DEPTH_INTERNAL_EXCEPTION = 15;
    /**
     * Numero de lineas bajo las cuales se delimita la linea donde ocurrio
     * la excepcion
     */
    const NUMBER_LINES_DELIMITATION = 20;

    public $innerExceptionSearch = TRUE;

    /**
     * Extrae lineas de codigo de un fuente y que incluye la
     * linea donde ocurrio la excepcion.
     * @param <type> $file
     * @param <type> $line
     * @return <type>
     */
    public static function getSourceLines($file,$line) {
        // determine the max number of lines to display
        $line--;	// adjust line number to 0-based from 1-based
        if($line<0 || ($lines=@file($file))===false || ($lineCount=count($lines))<=$line)
            return array();
        $halfLines=(int)(self::NUMBER_LINES_DELIMITATION/2);
        $beginLine=$line-$halfLines>0?$line-$halfLines:0;
        $endLine=$line+$halfLines<$lineCount?$line+$halfLines:$lineCount-1;
        $sourceLines=array();
        for($i=$beginLine;$i<=$endLine;++$i)
                $sourceLines[$i+1]=$lines[$i];
        return $sourceLines;
    }

    /**
     * Obtiene texto formateado de la fuente del error, para que pueda ser
     * legible en archivo de log
     * @param <type> $lista
     * @param <type> $line
     * @return <type>
     */
    public static function getPrintableSource($lista, $line) {
        $out='';
        foreach ($lista as $key => $value) {
            $out .= (isset($line) && $line==$key ? trim($key)."# ====> ".trim($value)."\n" : trim($key)."# ".trim($value)."\n");
        }
        return $out;
    }

    

    /**
     * Obtiene exception instanciada, segun propiedad que indique profundidad
     * de busqueda de excepciones anidadas
     * @return <type>
     */
    public function getException() {
        return ($this->innerExceptionSearch ? self::getInnerException($this) : $this);
    }

    /**
     * Obtiene excepcion anidada (si la hubiere) hasta N niveles de profundidad
     * @param <type> $exception
     * @param <type> $initLimit
     * @return <type>
     */
    public static function getInnerException($exception, $initLimit=1) {
        $previousException = $exception->getPrevious();
        if ($initLimit>self::DEPTH_INTERNAL_EXCEPTION) {  return FALSE; }
        elseif ($previousException==NULL) {   return $exception;    }
        return self::getInnerException($previousException, $initLimit+1);
    }
}