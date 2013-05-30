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
 * along with this program . If not, see <http://www.gnu.org/licenses/>.
 */

namespace mitgedanken\Monetary;
use mitgedanken\Monetary\Exceptions\Length,
    mitgedanken\Monetary\Exceptions\InvalidArgument;

/**
 * <i>Immutable</i><br/>
 * Represents a currency.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
class Currency implements Interfaces\Currency {

  /**
   * Holds its display name.
   *
   * @var string its display name.
   */
  private $name;

  /** TODO ISO...?
   * Holds its currency code.
   */
  private $code;

  /**
   * Constructs this Currency object.
   *
   * @param string $locale
   * @param MonetaryFormatter $formatter its formatter
   */
  public function __construct($code, $name = '')
  {
    $name = \trim($name);
    $code = \trim($code);

    if (!\is_string($code)):
      $message = '$code is not a String; given: ' . \gettype($code);
      throw new InvalidArgument($message);
    endif;

    $strlen = \strlen($code);
    if (3 != $strlen):
      $message = '$code must be 3 characters long; given: ' . $strlen;
      throw new Length($message);
    endif;

    $this->code = \strtoupper($code);
    $this->name = $name;
  }

  /**
   * Convenience factory method.
   *
   * @example $dollar = Currency::USD();
   * @example $dollar = Currency::USD('United States dollar');
   *
   * @param string $name
   * @param array $arguments 0:string, currency code; 1:string, display name;
   * @return \mitgedanken\Monetary\Interfaces\Currency
   */
  public static function __callStatic($name, $arguments)
  {
    $cargs = \count($arguments);
    if (1 == $cargs):
      $currency = new Currency($name, $arguments[0]);
    else:
      $currency = new Currency($name);
    endif;
    return $currency;
  }

  public function getCode()
  {
    return $this->code;
  }

  public function getName()
  {
    return $this->name;
  }

  public function equals($object)
  {
    $equals = FALSE;
    if ($object instanceof NullCurrency):
      $equals = TRUE;
    elseif ($object instanceof Currency):
      $equals = ($this->code == $object->getCode());
    endif;
    return $equals;
  }

  public function identify()
  {
    return '' . $this->code;
  }

  /**
   * Return this object as string.
   *
   * @return string ("code 'name'")
   */
  public function __toString()
  {
    return "$this->code '$this->name'";
  }
}
