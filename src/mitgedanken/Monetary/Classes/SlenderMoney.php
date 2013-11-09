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

namespace mitgedanken\Monetary\Classes;

/**
 * <i>Immutable</i><br/>
 * A slim implementation of Money.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class SlenderMoney
{

  use \mitgedanken\Monetary\Traits\Monetary;

  /**
   * Its amount.
   *
   * @var integer
   */
  protected $amount;

  /**
   * Its currency.
   *
   * @var Currency
   */
  protected $currency;

  /**
   * Returns its amount.<br/>
   *
   * @return mixed
   */
  public function getAmount()
  {
    return $this->amount;
  }

  /**
   * Returns its currency.<br/>
   *
   * @return \mitgedanken\Monetary\Classes\Currency
   */
  public function getCurrency()
  {
    return $this->currency;
  }

  /**
   * Constructs this <i>Money</i> object.
   *
   * @param integer $amount Its amount.
   * @param \mitgedanken\Monetary\Currency $currency Its currency.
   * @throws InvalidArgument
   */
  public function __construct($amount, Currency $currency)
  {
    $this->currency = $currency;
    $this->amount   = $amount;
  }

  public function __call($name, $arguments)
  {
    throw new Exceptions\UnsupportedOperation($name . ' not supported');
  }

  /**
   * Convenience factory method.
   *
   * @example $fiveDollar = <i>Money</i>::USD(500, 'United States dollar');
   * @example $fiveDollar = <i>Money</i>::USD(500);
   *
   * @param string $name
   * @param array $arguments 0:string, currency code; 1:string, display name;
   * @return \mitgedanken\Monetary\Abstracts\Money
   */
  public static function __callStatic($name, $arguments)
  {
    $cargs = \count($arguments);
    if (2 == $cargs):
      $newMoney = new Money($arguments[0], new Currency($name, $arguments[1]));
    else:
      $newMoney = new Money($arguments[0], new Currency($name));
    endif;
    return $newMoney;
  }

  public function __clone()
  {
    throw new Exceptions\UnsupportedOperation('__clone not supported');
  }

  public static function slenderize($money)
  {
    if ($money instanceof \mitgedanken\Monetary\Abstracts\Money):
      $result = new SlenderMoney($money->getAmount(), $money->getCurrency());
    elseif ($money instanceof SlenderMoney):
      $result = $money;
    endif;
    return $result;
  }

  public function equals($object)
  {
    $equals = FALSE;
    if ($object instanceof \mitgedanken\Monetary\Abstracts\Money):
      $equals = $this->currency->equals($object->currency) && ($this->amount == $object->amount);
    endif;
    return $equals;
  }

  public function identify()
  {
    return "$this->amount $this->currency";
  }

}
