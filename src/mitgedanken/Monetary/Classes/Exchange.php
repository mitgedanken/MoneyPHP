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
 * Exchanges currency pairs by its ratio.
 */
class Exchange
{

  use \mitgedanken\Monetary\Traits\Monetary;

  /**
   * Holds the scale value.
   *
   * @var integer
   */
  protected $scale = 12;

  /**
   * Converts a currency pair and returns the result as a string.
   *
   * @param integer $amount
   * @param \mitgedanken\Monetary\CurrencyPair $pair
   * @param integer $scale
   * @return string
   */
  public static function convert($amount, CurrencyPair $pair, $scale = 4)
  {
    if (!is_integer($scale)):
      throw new Exceptions\InvalidArgument('Argument 3 is not an integer');
    endif;
    if ($currency->equals($pair->getBaseCurrency())):
      $result = \bcmul($amount, $pair->getRatio(), $scale);
    elseif ($currency->equals($pair->getCounterCurrency())):
      $result = \bcdiv($amount, $pair->getRatio(), $scale);
    else:
      throw new Exceptions\NoSuitableExchangeRate('Argument 1 has wrong currency');
    endif;
    \settype($result, 'integer');
    return $result;
  }

  /**
   * Converts a money to a money by its ratio.
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $money
   * @param \mitgedanken\Monetary\CurrencyPair $pair
   * @param integer $scale
   * @throws Exceptions\InvalidArgument If the 1 argument has wrong currency
   */
  public static function convertMoney(\mitgedanken\Monetary\Abstracts\Money $money, CurrencyPair $pair, $scale = 4)
  {
    if (!is_integer($scale)):
      throw new Exceptions\InvalidArgument('Argument 3 is not an integer');
    endif;
    $currency = $money->getCurrency();
    if ($currency->equals($pair->getBaseCurrency())):
      $result = \bcmul($money->getAmount(), $pair->getRatio(), 6);
      \settype($result, 'integer');
      $money  = new Money($result, $pair->getCounterCurrency());
    elseif ($currency->equals($pair->getBaseCurrency())):
      $result = \bcdiv($money->getAmount(), $pair->getRatio(), 6);
      \settype($result, 'integer');
      $money  = new Money($result, $pair->getBaseCurrency());
    else:
      throw new \mitgedanken\Monetary\Exceptions\NoSuitableExchangeRate('Argument 1 has wrong currency');
    endif;
    return $money;
  }

}
