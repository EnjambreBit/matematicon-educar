<?php

namespace Edufw\core\logger;

use Edufw\core\logger\ELoggerLevel;
use Edufw\core\EWebApp;

/**
 * Se encarga de las tareas pertinentes a log de distintos eventos sucedidos
 * en la ejecucion de un proceso en el contexto de aplicacion web.
 * Basado en:
 *      Monolog: https://github.com/Seldaek/monolog
 *      Logbook: https://pythonhosted.org/Logbook/index.html
 * @author Gustavo Seip
 */
class ELogger 
{
    private $handlers;
    private $channel;

    /**
     * Construye un logger usando por omisión.
     * handler:     ELoggerHandleFile
     * formatter:   ELoggerFileFormatter
     */
    public function __construct($channel, array $handlers = array()) 
    {
        $this->channel = $channel;
        $this->handlers = $handlers;
        if (empty($this->handlers)) {
            $d =  EWebApp::config()->CORE_ELOGGER['default'];
            $this->handlers[] = new $d['handler']($d);
        }

    }

    public function emergency($message, array $context = array()) 
    {
        return $this->log(ELoggerLevel::EMERGENCY, $message, $context);
    }
    
    public function alert($message, array $context = array()) 
    {
        return $this->log(ELoggerLevel::ALERT, $message, $context);

    }

    public function critical($message, array $context = array()) 
    {
        return $this->log(ELoggerLevel::CRITICAL, $message, $context);

    }

    public function error($message, array $context = array()) 
    {

         return $this->log(ELoggerLevel::ERROR, $message, $context);
   }

    public function warning($message, array $context = array()) 
    {
        return $this->log(ELoggerLevel::WARNING, $message, $context);

    }

    public function notice($message, array $context = array()) 
    {
        return $this->log(ELoggerLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = array()) 
    {
        return $this->log(ELoggerLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = array()) 
    {
        return $this->log(ELoggerLevel::DEBUG, $message, $context);

    }
    
    public function log($level, $message, array $context = array()) 
    {
        $message = new ELoggerMessage($message,$this->channel,$level);
        $message->setContext($context);
        foreach($this->handlers as $handler) {
            $handler->pushMessage($message);
            $handler->dispatchMessage();
        }
        return true;
    }

}
