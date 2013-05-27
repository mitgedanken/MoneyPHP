<?php

/*
 * Copyright (C) 2013 Sascha Tasche <sascha@mitgedanken.de>
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

namespace mitgedanken\Monetary;

/**
 * NullObject for ICurrency.
 */
class NullCurrency extends Currency {

  /**
   * <i>Override</i>
   */
  public function __construct($code = '', $name = '')
  {

  }

  /**
   * <i>Override</i>
   *
   * @param string $name [unused]
   * @param array $arguments [unused]
   * @return \mitgedanken\Monetary\NullCurrency
   */
  public static function __callStatic($name = NULL, $arguments = NULL)
  {
    return new NullCurrency();
  }

  /** <i>Override</i> */
  public function getCode()
  {
    return NULL;
  }

  /** <i>Override</i> */
  public function getName()
  {
    return NULL;
  }

  /** <i>Override</i>
   *
   * @param mixed $object
   */
  public function equals($object)
  {
    return ($object instanceof CurrencyInterface);
  }

}
