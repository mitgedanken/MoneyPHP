<?php

ini_set('include_path',
        ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) . '/../../../../LIBARY/PHP');
ini_set('include_path',
        ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) . '/../src');
define('DEVELOPMENT', TRUE);
require_once '/scripts/init_monetary.php';

