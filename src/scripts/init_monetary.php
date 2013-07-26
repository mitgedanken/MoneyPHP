<?php

if (!defined('ROOT')):
  define('ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..');
endif;

if (!defined('MODULE_AUTOLOAD')):
// Autoloader (source: https://gist.github.com/adriengibrat/4761717#comment-773452)
  set_include_path(get_include_path() . PATH_SEPARATOR . ROOT);
  spl_autoload_register(
          function($c)
          {
            include_once preg_replace('#\\\|_(?!.*\\\)#', '/', $c) . '.php';
          }
  );
endif;
