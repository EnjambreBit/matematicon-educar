<?php

namespace Edufw\core\logger;

interface ELoggerHandlerInterface {

    public function dispatchAllMessages();

    public function dispatchMessage();

    public function pushMessage($message);

    public function handle();

}


