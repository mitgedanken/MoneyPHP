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
 * Represents a currency pair repository.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class CurrencyPairRepository implements \Countable
{

  use \mitgedanken\Monetary\Traits\Monetary;

  /**
   * Holds its currency pairs.
   *
   * @var \SplObjectStorage
   */
  private $storage;

  /**
   * @param \SplObjectStorage $storage
   */
  public function __construct(\SplObjectStorage $storage = NULL)
  {
    if (isset($storage)):
      $this->storage = $storage;
    endif;
  }

  /**
   * Attaches a <i>CurrencyPair</i>.
   *
   * @param \mitgedanken\Monetary\CurrencyPair $pair
   * @return void No return value
   */
  public function attach(CurrencyPair $pair)
  {
    $this->storage->offsetSet($pair, NULL);
  }

  /**
   * Detaches a <i>CurrencyPair</i>.
   *
   * @param \mitgedanken\Monetary\CurrencyPair $pair
   * @return void No return value
   */
  public function detach(CurrencyPair $pair)
  {
    $this->storage->offsetUnset($pair);
  }

  /**
   * <p><b>Warning! This method changes the object state!</b></p>
   * Replaces the backed storage with the given storage.<br/>
   *
   * @param \ArrayAccess $storage
   * @return void No return value.
   */
  public function replaceStorage(\SplObjectStorage $storage)
  {
    $this->storage = $storage;
  }

  /**
   * <p><i>Implementation</i></p>
   * Returns the count of all elements in the backed storage.
   *
   * @return integer
   */
  public function count()
  {
    return $this->storage->count();
  }

  /**
   * Finds the first match of a currency pair (without ratio).
   *
   * @param \mitgedanken\Monetary\Currency $baseCurrency
   * @param \mitgedanken\Monetary\Currency $counterCurrency
   * @return \mitgedanken\Monetary\CurrencyPair
   * @throws Exceptions\NoSuitableExchangeRate If no suitable pair was found.
   */
  public function findByCurrency(Currency $baseCurrency, Currency $counterCurrency)
  {
    $fulfilled = NULL;
    foreach ($this->storage as $key):
      if (
            $key->getBaseCurrency()->equals($baseCurrency)
            && $key->getCounterCurrency()->equals($counterCurrency)
      ): $fulfilled = $key;
      endif;
    endforeach;
    if (is_null($fulfilled)):
      throw new \mitgedanken\Monetary\Exceptions\NoSuitablePair();
    endif;
    return $fulfilled;
  }

  /**
   * This method finds <i>CurrencyPair</i> by a <i>Criteria</i>.<br/>
   * Returns a <i>SplObjectStorage</i> with any <i>CurrencyPair</i> object that
   * fulfilled the criteria.
   *
   * @param \mitgedanken\Monetary\Interfaces\CurrencyPairCriteria $criteria
   * @return \SplObjectStorage
   */
  public function findBy(\mitgedanken\Monetary\Interfaces\CurrencyPairCriteria $criteria)
  {
    $baseCurrency    = $criteria->getBaseCurrency();
    $counterCurrency = $criteria->getCounterCurrency();
    $fulfilled       = new \SplObjectStorage();
    foreach ($this->storage as $key):
      if ($baseCurrency instanceof Currency):
        $baseFulfilled = $key->getBaseCurrency()->equals(($baseCurrency));
      else:
        $baseFulfilled = TRUE;
      endif;
      if ($counterCurrency instanceof Currency):
        $counterFulfilled = $key->getCounterCurrency()->equals($counterCurrency);
      else:
        $counterFulfilled = TRUE;
      endif;
      if ($baseFulfilled || $counterFulfilled):
        $fulfilled->attach($key);
      endif;
    endforeach;
    return $fulfilled;
  }

}
