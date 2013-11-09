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
 * Represent a currency pair with ratio.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class CurrencyPair
{

  use \mitgedanken\Monetary\Traits\Monetary;

  /**
   * Its base currency.
   *
   * @var \mitgedanken\Monetary\Currency
   */
  protected $baseCurrency;

  /**
   * Its counter currency.
   *
   * @var \mitgedanken\Monetary\Currency
   */
  protected $counterCurrency;

  /**
   * Its ratio.
   *
   * @var integer|float
   */
  protected $ratio;

  /**
   * Constructs this currency pair with a base currency and a counter currency.
   *
   * @param \mitgedanken\Monetary\Currency $baseCurrency
   * @param \mitgedanken\Monetary\Currency $counterCurrency
   * @param integer|float $ratio
   */
  public function __construct(Currency $baseCurrency, Currency $counterCurrency, $ratio)
  {
    if (!\is_integer($ratio) && !\is_float($ratio)):
      $type    = \gettype($ratio);
      $message = "Argument 1 requires integer or float, but was $type";
      throw new InvalidArgument($message);
    endif;
    $this->baseCurrency    = $baseCurrency;
    $this->counterCurrency = $counterCurrency;
    $this->ratio           = $ratio;
  }

  /**
   * Returns its base currency.
   *
   * @return \mitgedanken\Monetary\Currency
   */
  public function getBaseCurrency()
  {
    return $this->baseCurrency;
  }

  /**
   * Returns its counter currency.
   *
   * @return \mitgedanken\Monetary\Currency
   */
  public function getCounterCurrency()
  {
    return $this->counterCurrency;
  }

  /**
   * Returns its ratio.
   *
   * @return float
   */
  public function getRatio()
  {
    return $this->ratio;
  }

  /**
   * Checks whether this pair has the given currency.<br/>
   * This is <i>TRUE</i> if its base currency or its counter currency is
   * "equal to" the given currency.
   *
   * @param \mitgedanken\Monetary\Currency $currency The given curreny to check against.
   * @return boolean
   */
  public function has(Currency $currency)
  {
    return $this->baseCurrency->equals($currency) || $this->counterCurrency->equals($currency);
  }

  /**
   * Indicates whether this object is "equal to" another.</br>
   * This object is "equal to" another if it is an instance of <i>CurrencyPair</i>
   * and if the base currency is "equal to" the other's base currency or counter
   * currency and if its counter currency is "equal to" other's counter
   * currency or base currency.
   *
   * @param \mitgedanken\Monetary\CurrencyPair $object
   * @return boolean
   */
  public function equals($object)
  {
    $isEqual = FALSE;
    if ($object instanceof CurrencyPair):
      $isEqual = ($this->baseCurrency->equals($object->baseCurrency)
            || $this->baseCurrency->equals($object->counterCurrency))
            && ($this->counterCurrency->equals($object->counterCurrency)
            || $this->counterCurrency->equals($object->baseCurrency));
    endif;
    return $isEqual;
  }

  /**
   * Return its identifier.
   *
   * @return string
   */
  public function identify()
  {
    return "$this->baseCurrency $this->counterCurrency";
  }

  public function __toString()
  {
    return "$this->baseCurrency $this->counterCurrency $this->ratio";
  }

}
