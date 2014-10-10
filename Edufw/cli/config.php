<?php
$fwconfig['CORE_APP'] = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR . 'Edufw/cli')) . DIRECTORY_SEPARATOR; // /
$fwconfig['CORE_NAMESPACE'] = 'Edufw';
$fwconfig['CORE_LIB'] = "{$fwconfig['CORE_APP']}{$fwconfig['CORE_NAMESPACE']}" . DIRECTORY_SEPARATOR; // "/Edufw/"
$fwconfig['APP_SRC'] = $fwconfig['CORE_APP'].'src'.DIRECTORY_SEPARATOR;
$fwconfig['APP_URL'] = '';
$fwconfig['APP_HOST_NAME'] = '';
$fwconfig['APP_ROOT'] = __DIR__;
$fwconfig['APP_NAME'] = '';
$fwconfig['APP_LIB'] = '';
$fwconfig['VENDOR_PATH'] = $fwconfig['CORE_LIB'].'vendor'.DIRECTORY_SEPARATOR;
$fwconfig['APP_MODE'] = 'dev';