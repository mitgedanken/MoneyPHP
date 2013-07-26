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
class MoneyConverter {
  use Traits\Monetary;
  /**
   * Holds the exchange rates.
   *
   * @var \SplObjectStorage
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
    if (\is_object($storage)):
      $this->storage = $storage;
    else:
      $this->storage = new \SplObjectStorage();
    endif;
  }

  /**
   * Set whether a <i>NoSuitableExchangeRate</i> exception must be thrown if no
   * suitable exchange rate was found.
   *
   * @param boolean $boolean
   */
  public function setNoSuitableException($boolean)
  {
    if (!is_bool($boolean)):
      $type = \gettype($boolean);
      $message = 'Boolean required for $boolean' . "(but was $type)";
      throw new InvalidArgument($message);
    endif;
    $this->noSuitableException = $boolean;
  }

  /**
   * Attaches a exchange rate.<br/>
   *
   * A exchange rate is consisting of a <i>CurrencyPair</i> and a exchange rate.
   *
   * @param \mitgedanken\Monetary\CurrencyPair $pair
   * @param array|integer|float $ratios
   */
  public function attach(CurrencyPair $pair, $ratios)
  {
    $this->_requiresIntegerOrFloatOrArray($ratios, 'ratios', __METHOD__);
    if (!$this->storage->contains($pair)):
      $this->storage->attach($pair, $ratios);
    endif;
  }

  /**
   * Replaces a exchange rate.<br/>
   *
   * A exchange rate is consisting of a <i>CurrencyPair</i> and a exchange rate.
   *
   * @param \mitgedanken\Monetary\CurrencyPair $pair
   * @param array $ratios
   */
  public function replace(CurrencyPair $pair, $ratios)
  {
    $this->_requiresIntegerOrFloatOrArray($ratios, 'ratios', __METHOD__);
    $this->storage->attach($pair, $ratios);
  }

  /**
   * Dettaches a exchange rate.
   *
   * @param \mitgedanken\Monetary\CurrencyPair $pair
   */
  public function detach(CurrencyPair $pair)
  {
    $this->storage->detach($pair);
  }

  /**
   * Exchanges the given <i>Money</i> object to the given <i>Currency</i>.<br/>
   * It throws a <i>NoSuitableExchangeRate</i> exception if this object has been
   * set for it.
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $money
   * @param \mitgedanken\Monetary\Currency $toCurrency
   * @return \mitgedanken\Monetary\Money
   * @throws NoSuitableExchangeRate If this object has been set for it.
   */
  public function convert(Abstracts\Money $money, Currency $toCurrency)
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
      if (0 == $ratios[0]):
        throw new Exceptions\DivisionByZero();
      endif;
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

  /**
   * TODO
   */
  public function equals($object)
  {
    $isEqual = FALSE;
    if ($object instanceof MoneyConverter):
      $isEqual = $this->storage == $object->storage;
    endif;
    return $isEqual;
  }

  /**
   * Return its identifier.<br/>
   * Exchange rates are identifiable by its storage.
   *
   * @return string Its identifier.
   */
  public function identify()
  {
    return serialize($this->storage);
  }

  public function __toString()
  {
    $result = '';
    foreach ($this->storage as $money):
      $result .= '' . $money . "\n";
    endforeach;
    return $result;
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

