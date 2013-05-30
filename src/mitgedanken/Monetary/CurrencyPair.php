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
 * Description of CurrencyPair
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
class CurrencyPair implements Interfaces\CurrencyPair {

  /**
   * Its base currency.
   *
   * @var \mitgedanken\Monetary\Interfaces\Currency
   */
  protected $baseCurrency;

  /**
   * Its counter currency.
   *
   * @var \mitgedanken\Monetary\Interfaces\Currency
   */
  protected $counterCurrency;

  /**
   * Constructs this currency pair with a base currency and a counter currency.
   *
   * @param \mitgedanken\Monetary\Interfaces\Currency $baseCurrency
   * @param \mitgedanken\Monetary\Interfaces\Currency $counterCurrency
   */
  public function __construct(Currency $baseCurrency, Currency $counterCurrency)
  {
    $this->baseCurrency = $baseCurrency;
    $this->counterCurrency = $counterCurrency;
  }

  public function has(Interfaces\Currency $currency)
  {
    return $this->baseCurrency->equals($currency)
            || $this->counterCurrency->equals($currency);
  }

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

  public function identify()
  {
    return "$this->baseCurrency $this->counterCurrency";
  }

  public function __toString()
  {
    return "$this->baseCurrency $this->counterCurrency";
  }
}
