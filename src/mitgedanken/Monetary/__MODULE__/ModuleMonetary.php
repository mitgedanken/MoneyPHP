<?php

/*
 * Copyright (C) 2013 Sascha Tasche <hallo@mitgedanken.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace mitgedanken\Monetary\METAINF;

define('MODULE_MONETARY', __DIR__ . DIRECTORY_SEPARATOR . 'module');

/**
 * TODO
 * Module
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class ModuleMonetary {

  public function request($service) {

    return $service;
  }

  /**
   *
   */
  public function register($registry) {
    $registry->register(ModuleMonetary::info());
  }

  /**
   * Registers the autoloader.
   *
   * @param string $class
   */
  public static function load($class) {
    set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . DIRECTORY_SEPARATOR . '..' . '..');
    // Autoloader (source: https://gist.github.com/adriengibrat/4761717#comment-773452)
    spl_autoload_register(
          function($class) {
      include_once preg_replace('#\\\|(?!.*\\\)#', '/', $class) . '.php';
    }
    );
  }

  /**
   * Returns the information.
   *
   * @return array
   */
  public static function info() {
    return [
      'name' => 'MoneyPHP',
      'id' => 'mitgedanken::MoneyPHP@13.44.0-alpha',
      'desc' => 'A monetary value object based on Money by Martin Fowler.',
      'version' => '13.44.0-alpha',
      'min_stability' => 'dev',
      'require' => [],
      'forkOf' => '',
      'php' => '>=5.4.0',
      'path' => __DIR__,
      'maintainer' => 'mitgedanken (Sascha Tasche)',
      'authors' => [0 => 'Sascha Tasche'],
      'repository' => 'https://github.com/mitgedanken/MoneyPHP',
      'licence' => 'GPL-3',
      'doc' => '',
      'website' => ''];
    ;
  }

}
