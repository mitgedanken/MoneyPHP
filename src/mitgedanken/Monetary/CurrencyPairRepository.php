<?php

/*
 * Copyright (C) 2014 Sascha Tasche <hallo@mitgedanken.de>
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
 * Represents a currency pair repository.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class CurrencyPairRepository implements \Countable {

    use \mitgedanken\Monetary\Traits\Monetary;

    /**
     * Holds its currency pairs.
     *
     * @var \SplObjectStorage
     */
    private $storage;

    /** Is true if CurrencyPaitRepository is forced to allow an empty storage.
     *
     * @var boolean
     */
    private $allowEmptyStorage;

    /**
     *
     * @param \ArrayAccess $storage
     * @param boolean $allowEmptyStorage [default: FALSE]
     *        If TRUE: CurrencyPairRepository is forced to allow an empty storage
     * @throws EmptyNotAllowed
     */
    public function __construct(\ArrayAccess $storage, $allowEmptyStorage = FALSE)
    {
        assert('!\is_null($storage)', '$storage is not nullabel.');
        if (!$allowEmptyStorage && 0 == \count($storage)):
            throw new EmptyNotAllowed('$storage is empty');
        endif;
        $this->allowEmptyStorage = $allowEmptyStorage;
        $this->storage = $storage;
    }

    /**
     * Attaches a <i>CurrencyPair</i>.
     *
     * @param CurrencyPair $pair
     * @return void No return value
     */
    public function attach(CurrencyPair $pair)
    {
        $this->storage->offsetSet($pair, NULL);
    }

    /**
     * Detaches a <i>CurrencyPair</i>.
     *
     * @param CurrencyPair $pair
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
     * @throw EmptyStorageNotAllowed If and only if the given <i>Array</i> is empty.
     */
    public function replaceStorage(\ArrayAccess $storage)
    {
        if (!$this->allowEmptyStorage && 0 == \count($storage)):
            throw new EmptyNotAllowed('$storage is empty');
        endif;
        $this->storage = $storage;
    }

    /**
     * It returns the \count of all elements in the backed storage.
     *
     * @return integer
     */
    public function count()
    {
        return \count($this->storage);
    }

    /**
     * Finds the first match of a currency pair (without ratio).
     *
     * @param Currency $baseCurrency
     * @param Currency $counterCurrency
     * @return CurrencyPair
     * @throws UnsuitablePair If no suitable pair was found.
     */
    public function findByCurrency(Currency $baseCurrency, Currency $counterCurrency)
    {
        if (0 == \count($this->storage)):
            throw new UnsuitablePair();
        endif;
        $key = NULL;
        foreach ($this->storage as $key):
            if ($key->getBaseCurrency()->equals($baseCurrency) || $key->getcounterCurrency()->equals($counterCurrency)):
                break;
            endif;
        endforeach;
        return $key;
    }

    /**
     * This method finds <i>CurrencyPair</i> by a <i>Criteria</i>.<br/>
     * It returns a <i>SplObjectStorage</i> with any <i>CurrencyPair</i> object that
     * fulfilled the criteria.
     *
     * @param Interface_CurrencyPairCriteria $criteria
     * @return \SplObjectStorage
     */
    public function findBy(Interface_CurrencyPairCriteria $criteria)
    {
        $baseCurrency = $criteria->getBaseCurrency();
        $counterCurrency = $criteria->getCounterCurrency();
        $fulfilled = new \SplObjectStorage();
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

    public function takesAnEmptyStorage()
    {
        return $this->allowEmptyStorage;
    }

}
