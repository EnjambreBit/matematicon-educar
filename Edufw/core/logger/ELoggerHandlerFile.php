<?php

namespace Edufw\core\logger;

use Edufw\core\EWebApp;

class ELoggerHandlerFile implements ELoggerHandlerInterface
{

    private $messages;
    private $eLoggerFormater;
    private $rootpath;

    public function __construct($config=array()) {
        $this->messages = array();
        $this->eLoggerFormater = new $config['formatter']();
        $this->rootpath = $config['rootpath'];
    }
    
    public function dispatchAllMessages()
    {
        //pop all array
    }

    public function dispatchMessage()
    {
        //pop array
        $message = array_pop($this->messages);
        $logOutput = $this->eLoggerFormater->setMessage($message)->format();
        $logFilePath = $this->rootpath.$message->getPrefixFilename().'.log';
        error_log($logOutput, 3, $logFilePath);
    }

    public function pushMessage($message)
    {
        array_push($this->messages,$message);
        return $this;
    }

    public function handle()
    {
        return 'FILE';
    }


}


