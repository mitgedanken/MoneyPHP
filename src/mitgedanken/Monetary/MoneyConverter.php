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
use mitgedanken\Monetary\Exceptions\NoSuitableExchangeRate,
    mitgedanken\Monetary\Exceptions\InvalidArgument;

/**
 * Manages exchange rates. Implementation of <i>MoneyConverter</i>.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
class MoneyConverter implements Interfaces\MoneyConverter {

  /**
   * Holds the exchange rates.
   */
  protected $storage;

  /**
   * Holds the rounding algorithm.
   *
   * @var \Closure Its current rounding algorithm,
   */
  protected $rounding_algo;

  /**
   * [default: FALSE]
   */
  protected $noSuitableException;

  /**
   * Constructor.
   *
   * @param \Closure $rounding_algo The rounding algorithm.
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

  public function setNoSuitableException($boolean)
  {
    if (!is_bool($boolean)):
      $type = \gettype($boolean);
      $message = 'Boolean required for $boolean' . "(but was $type)";
      throw new InvalidArgument($message);
    endif;
    $this->noSuitableException = $boolean;
  }

  public function attach(Interfaces\CurrencyPair $pair, $ratios)
  {
    $this->_requiresIntegerOrFloatOrArray($ratios, 'ratios', __METHOD__);
    if (!$this->storage->contains($pair)):
      $this->storage->attach($pair, $ratios);
    endif;
  }

  public function replace(Interfaces\CurrencyPair $pair, $ratios)
  {
    $this->_requiresIntegerOrFloatOrArray($ratios, 'ratios', __METHOD__);
    $this->storage->attach($pair, $ratios);
  }

  public function detach(Interfaces\CurrencyPair $pair)
  {
    $this->storage->detach($pair);
  }

  public function convert(Interfaces\Money $money,
                          Interfaces\Currency $toCurrency)
  {
    $result = NULL;
    $fromCurrency = $money->getCurrency();
    $found = FALSE;
    if (0 < $this->storage->count()):
      $pair = new CurrencyPair($fromCurrency, $toCurrency);
      $this->storage->rewind();
      while (!$found && $this->storage->valid()):
        $ratios = $this->storage->getInfo();
        $found = $this->storage->current()->equals($pair);
        $this->storage->next();
      endwhile;
    endif;
    if ($found):
      $exchangeResult = $money->multiply($ratios[1])->divide($ratios[0]);
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

  /**
   * Validates an argument.
   *
   * @param integer|float $argument argument to validate.
   * @param string $name argument as string.
   * @param string $method method name which uses this function.
   * @throws InvalidArgument
   */
  protected function _requiresIntegerOrFloatOrArray($argument, $name, $method)
  {
    if (!\is_int($argument) && !\is_float($argument) && !\is_array($argument)):
      $type = \gettype($argument);
      $message = "Integer or float or array required for $name
        (but was $type; in $method)";
      throw new InvalidArgument($message);
    endif;
  }
}

