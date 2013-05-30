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
 * Description of CurrencyPairRepository
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
class CurrencyPairRepository implements \Countable {

  /**
   * Holds its currency pairs.
   *
   * @var \Traversable
   */
  private $storage;

  /**
   * @param \Traversable $storage [optional]
   */
  public function __construct(\Traversable $storage = NULL)
  {
    if (isset($storage)):
      $this->storage = $storage;
    else:
      $this->storage = new \SplObjectStorage();
    endif;
  }

  /**
   * Attaches a <i>CurrencyPair</i>.
   *
   * @param \mitgedanken\Monetary\Interfaces\CurrencyPair $pair
   */
  public function attach(CurrencyPair $pair)
  {
    $this->storage->offsetSet($pair, NULL);
  }

  /**
   * Dettaches a <i>CurrencyPair</i>.
   *
   * @param type $pair
   */
  public function detach($pair)
  {
    $this->storage->offsetUnset($pair);
  }

  /**
   * Replaces the backed storage by the given storage.
   *
   * @param \ArrayAccess $storage
   * @return void No return value.
   */
  public function replaceStorage(\Traversable $storage)
  {
    $this->storage = $storage;
  }

  /**
   * This method finds <i>CurrencyPair</i> by a <i>Criteria</i>.<br/>
   * Return a <i>SplObjectStorage</i> with any <i>CurrencyPair</i> object that
   * fulfill the criteria.
   *
   * @param \mitgedanken\Monetary\Criteria $criteria
   * @return \SplObjectStorage
   */
  public function findBy(Criteria $criteria)
  {
    $baseCurrency = $criteria->getBaseCurrency();
    $counterCurrency = $criteria->getCounterCurrency();
    $fulfilled = new \SplObjectStorage();
    foreach ($this->storage as $key):
      if ($key instanceof CurrencyPair):
        if ($baseCurrency instanceof Currency):
          $hasBaseCurrency = $key->has($baseCurrency);
        else:
          $hasBaseCurrency = TRUE;
        endif;

        if ($counterCurrency instanceof Currency):
          $hasCounterCurrency = $key->has($counterCurrency);
        else:
          $hasCounterCurrency = TRUE;
        endif;

        if ($hasBaseCurrency || $hasCounterCurrency):
          $fulfilled->attach($key);
        endif;
      endif;
    endforeach;
    return $fulfilled;
  }

  /**
   * Return the count of all elements in the backed storage.
   *
   * @return integer
   */
  public function count()
  {
    return count($this->storage);
  }
}
