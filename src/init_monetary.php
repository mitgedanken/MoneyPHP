<?php

if (!defined('ROOT')):
    define('ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..');
endif;

if (!defined('MODULE_AUTOLOAD')):
    spl_autoload_extensions(".php");
    spl_autoload_register();
endif;
