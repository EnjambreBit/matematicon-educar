<?php

namespace Edufw\core\logger;

/**
 * Formato para log hacia archivos
 *
 * @author Gustavo Seip
 */
class ELoggerFileFormatter implements ELoggerFormatterInterface
{
    private $message;

    public function __construct($message='')
    {
        $this->message = $message;
    }
    
    /**
     * @todo Falta definir EXCEPTION
     */
    public function format()
    {
        $s = '';
        if (is_object($this->message)) {
            $s = '['.$this->message->getDateTime().']'.
                '['.$this->message->getLevelName().']'.
                '['.$this->message->getId().'] '.
                $this->message->getMessage().
                $this->contextToString().
                "+--\n";
        }
        return $s;
    }

    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    private function contextToString() {
        $s = "\n";
        $a = $this->message->getContext();
        array_walk($a, function($v,$k) use(&$s) {
            $s .= $k.': '.$v."\n";
        });
        return $s;
    }
}



