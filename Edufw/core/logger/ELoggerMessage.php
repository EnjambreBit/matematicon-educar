<?php

namespace Edufw\core\logger;

use Edufw\core\logger\ELoggerLevel;

/**
 * Mensajes de error para ELogger.
 *
 * @author Gustavo Seip
 */

class ELoggerMessage 
{

    private $message;
    private $context;
    private $level;
    private $levelName;
    private $channel;
    private $datetime;
    private $id;
    private $prefixFilename;

    public function __construct($message,$channel,$errorcode) 
    {
        $this->message = $message;
        $this->channel = $channel;
        $this->level = $errorcode;
        $this->levelName = ELoggerLevel::toString($errorcode);
        $this->context = '';
        $this->setDatetime();
        $this->id = uniqid();
        $this->setPrefixFilename();
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    public function setLevelName($levelName)
    {
        $this->levelName = ELoggerLevel::$levels[$this->level];
        return $this;
    }

    public function setChannel($channel)
    {
        $this->channel = $channel;
        return $this;
    }

    public function setDatetime($datetime=FALSE)
    {
        if ($datetime===FALSE) {
            $this->datetime = date("d-m-Y H:i:s");
        } else {
            $this->datetime = $datetime;
        }
        return $this;
    }

    public function setPrefixFilename($prefixFilename=FALSE) 
    {
        if ($prefixFilename===FALSE) {
            $this->prefixFilename = $this->channel.'_'.$this->levelName.'_'.date("Ymd");
        } else {
            $this->prefixFilename = $prefixFilename;
        }
        return $this;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getContext() {
        return $this->context;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getLevelName() {
        return $this->levelName;
    }

    public function getChannel() {
        return $this->channel;
    }

    public function getDateTime() {
        return $this->datetime;
    }

    public function getId() {
        return $this->id;
    }

    public function getPrefixFilename() {
        return $this->prefixFilename;
    }

}

