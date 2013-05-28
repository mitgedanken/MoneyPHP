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
   * [default: FALSE]
   */
  protected $noSuitableException;

  /**
   * Constructor.
   *
   * @param \SplObjectStorage $storage
   * @param boolean $noSuitableException
   *        Set to <i>TRUE</i> to thow a <i>NoSuitableExchangeRate</i> exception
   *        if no suitable exchange rate was found.
   */
  public function __construct(\SplObjectStorage $storage = NULL,
                              $noSuitableException = FALSE)
  {
    $this->noSuitableException = $noSuitableException;
    if (isset($storage)):
      $this->storage = $storage;
    else:
      $this->storage = new \SplObjectStorage();
    endif;
  }

  /**
   * Set if a <i>NoSuitableExchangeRate</i> exception is thrown if no suitable
   * exchange rate was found.
   *
   * @param boolean $boolean
   */
  public function setNoSuitableException($boolean)
  {
    $this->noSuitableException = $boolean;
  }

  /**
   * Attaches a exchange rate.<br/>
   * It is consisting of a <i>CurrencyPair</i> and a exchange rate.
   *
   * @param \mitgedanken\Monetary\CurrencyPairInterface $pair
   * @param type $rate
   */
  public function attach(CurrencyPairInterface $pair, $rate)
  {
    $this->storage->attach($pair, $rate);
  }

  /**
   * Dettaches a exchange rate.<br/>
   *
   * @param \mitgedanken\Monetary\CurrencyPairInterface $pair
   */
  public function dettach(CurrencyPairInterface $pair)
  {
    $this->storage->detach($pair);
  }

  /**
   * Exchanges the given <i>Money</i> object to the given <i>Currency</i>.<br/>
   * It throws a <i>NoSuitableExchangeRate</i> exception if this object has been
   * set for it.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $money
   * @param \mitgedanken\Monetary\CurrencyInterface $toCurrency
   * @return \mitgedanken\Monetary\Money
   * @throws NoSuitableExchangeRate If this object has been set for it..
   */
  public function exchange(MoneyInterface $money, CurrencyInterface $toCurrency)
  {
    $result = NULL;
    $fromCurrency = $money->getCurrency();
    $found = FALSE;
    if (0 < $this->storage->count()):
      $pair = new CurrencyPair($fromCurrency, $toCurrency);
      $this->storage->rewind();
      while (!$found && $this->storage->valid()):
        $rates = $this->storage->getInfo();
        $found = $this->storage->current()->equals($pair);
        $this->storage->next();
      endwhile;
    endif;
    if ($found):
      $exchangeResult = $money->multiply($rates[1])->divide($rates[0]);
      $result = new Money($exchangeResult->getAmount(), $toCurrency);
    else:
      if ($this->noSuitableException):
        throw new NoSuitableExchangeRate($money->getCurrency());
      elseif ($fromCurrency->equals($toCurrency)):
        $result = $money;
      else:
        $result = new Money(0, $toCurrency);
      endif;
    endif;
    return $result;
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

