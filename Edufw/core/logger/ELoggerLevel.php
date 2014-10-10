<?php

namespace Edufw\core\logger;

/**
 * Niveles de error para ELogger
 * @see protocolo para syslog (http://tools.ietf.org/html/rfc5424)
 *
 * @author Gustavo Seip
 */
class ELoggerLevel 
{
    /**
     * system is unusable.
     */
    const EMERGENCY = 600;
    /*
     * Action must be taken immediately. Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     */
    const ALERT     = 550;
    /**
     * Critical conditions. Example: Application component unavailable, unexpected exception.
     */
    const CRITICAL  = 500;
    /*
     * Runtime errors that do not require immediate action but should typically be logged and monitored.
     */
    const ERROR     = 400;
    /*
     * Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, 
     * poor use of an API, undesirable things that are not necessarily wrong.
     */
    const WARNING   = 300;
    /*
     * Normal but significant events.
     */
    const NOTICE    = 250;
    /*
     * Interesting events. Examples: User logs in, SQL logs.
     */
    const INFO      = 200;
    /*
     * Detailed debug information.
     */
    const DEBUG     = 100;

    public static $levels = array(
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
    );  
    
    public static function toString($code) 
    {
        if (array_key_exists($code,self::$levels)) {
            return self::$levels[$code];
        }
        return 'Codigo no soportado';
    }


}
