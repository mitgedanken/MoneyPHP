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

namespace \mitgedanken\Monetary\Traits;
/**
 * ComponentBuiltIn
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
trait ComponentBuiltIn {

  /**
   * Return information about this component.
   *
   * @return type
   */
  public static function info()
  {
    static $info = [];
    if (empty($info)):
      $splitArray = preg_split('#\\\|_(?!.*\\\)#', get_called_class());
      $info['name'] = $splitArray[count($splitArray) - 1];
      $info['id'] = get_called_class();
      $info['module'] = 'mitgedanken\Monetary';
      $info['__built_in'] = TRUE;
      $info['version'] = '13.43.0-alpha';
      $info['min_version'] = '13.43.0-alpha';
      $info['maintainer'] = 'Sascha Tasche';
      $info['authors'] = [];
      $info['website'] = '';
      $info['doc'] = '';
      $info['licence'] = 'gpl3';
      $info['repository'] = 'https://github.com/mitgedanken/MoneyPHP';
      unset($splitArray);
    endif;
    return $info;
  }
}

