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
 * Manages exchange rates. Implementation of <i>ExchangeRatesInterface</i>.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
class ExchangeRates implements ExchangeRatesInterface {

  /**
   * Holds the exchange rates.
   */
  protected $storage;

  /**
   * Constructor.
   *
   * @param \SplObjectStorage $storage
   */
  public function __construct(\SplObjectStorage $storage = NULL)
  {
    if (isset($storage)):
      $this->storage = $storage;
    else:
      $this->storage = new \SplObjectStorage();
    endif;
  }

  public function attach(CurrencyPairInterface $pair, $rate)
  {
    $this->storage->attach($pair, $rate);
  }

  public function dettach(CurrencyPairInterface $pair)
  {
    $this->storage->detach($pair);
  }

  public function exchange(MoneyInterface $money, CurrencyInterface $toCurrency)
  {
    $result = 0;
    $fromCurrency = $money->getCurrency();
    if ($fromCurrency->equals($toCurrency)):
      $result = $money->getAmount();
    else:
      $found = FALSE;
      if (0 < $this->storage->count()):
        $pair = new CurrencyPair($fromCurrency, $toCurrency);
        $this->storage->rewind();
        while (!$found && $this->storage->valid()):
          $rates = $this->storage->getInfo();
          $found = $this->storage->current()->equals($pair);
          $this->storage->next();
          if ($found):
            $result = $rates[1] * $money->getAmount() / $rates[0];
          endif;
        endwhile;
      endif;
    endif;
    return new Money($result, $toCurrency);
  }

  public function identify()
  {
    return __CLASS__;
  }

  public function __toString()
  {
    return __CLASS__;
  }
}

