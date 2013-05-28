<?php

if (!defined('ROOT')):
  define('ROOT', __DIR__);
endif;
if (!defined('MODULE_AUTOLOAD')):
// Autoloader (source: https://gist.github.com/adriengibrat/4761717#comment-773452)
  set_include_path(get_include_path() . PATH_SEPARATOR . ROOT);
  spl_autoload_register(function($c) {
            @include preg_replace('#\\\|_(?!.*\\\)#', '/', $c) . '.php';
          });
endif;

define('MODULE_MONEY', __DIR__);
return array(
    'name' => 'MoneyPHP',
    'id' => 'mitgedanken/monetary/moneyphp',
    'desc' => 'A monetary value object based on Money by Martin Fowler.',
    'version' => '13.22.1-alpha',
    'require' => array(),
    'forks' => '',
    'php' => '>=5.4.0',
    'path' => __DIR__,
    'maintainer' => 'mitgedanken (Sascha Tasche)',
    'authors' => array(0 => 'Sascha Tasche'),
    'repository' => 'https://github.com/mitgedanken/MoneyPHP',
    'licence' => 'gpl3',
    'doc' => '',
    'website' => '');