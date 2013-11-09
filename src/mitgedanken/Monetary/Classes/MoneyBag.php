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

use mitgedanken\Monetary\Exceptions\UnsupportedOperation,
    mitgedanken\Monetary\Exceptions\DifferentCurrencies,
    mitgedanken\Monetary\Classes\CurrencyPairRepository,
    mitgedanken\Monetary\Abstracts\Money as AbstractsMoney;

/**
 * <i>Immutable</i><br/>
 * This class represents a money bag. It can be filled with different currencies.<br/>
 * This class guarantees that each element is contained only once.<br/>
 *
 * By using your own implementation of a <i>MonetaryStorage</i> (\SplObjectStorage)
 * the implementor must guarantee that each element is contained only once.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class MoneyBag extends Money implements \Countable
{

  use \mitgedanken\Monetary\Traits\Monetary;

  /**
   * Storage for all Money and MoneyBag objects.
   *
   * @var \SplObjectStorage
   */
  protected $storage;

  /**
   * Holds default money
   *
   * @var \mitgedanken\Monetary\Abstracts\Money
   */
  protected $defaultMoney;

  /**
   * @var \mitgedanken\Monetary\Classes\CurrencyPairRepository
   */
  protected $repository;

  /**
   * <p><i>Override</i></p>
   * Constructor.<br/>
   * If <i>$rates</i> is <i>NULL</i> it will instaniate an empty <i>ExchangeRates</i>.<br/>
   * If <i>$storage</i> is <i>NULL</i> it will instaniate an empty <i>MonetaryStorage</i>.
   *
   * @param integer|float $amount
   * @param \mitgedanken\Monetary\Currency $defaultCurrency
   * @param \mitgedanken\Monetary\Interfaces\MonetaryStorage $storage [default: NULL]
   * @param \ArrayAccess $storage [nullable]
   */
  public function __construct($amount, Currency $currency, $scale = 12, $repository = NULL, \ArrayAccess $storage = NULL)
  {
    parent::__construct($amount, $currency, $scale);
    if (!isset($storage)):
      $storage = new MonetaryStorage();
    endif;
    $this->storage      = $storage;
    $this->defaultMoney = new Money($amount, $currency);
    $this->storage->attach($this->defaultMoney);
    if (is_null($repository)):
      $this->repository = new CurrencyPairRepository(); /* empty */
    else:
      $this->repository = $repository;
    endif;
  }

  /**
   * <p><i>Extension</i></p>
   * Convenience factory method.<br/>
   *
   * @example $fiveDollar = <i>MoneyBag</i>::USD(500, 'United States Dollar');
   * @example $fiveDollar = <i>MoneyBag</i>::USD(500);
   *
   * @param string $code
   * @param array $arguments 0:string, currency code; 1:string, display name;
   * @return \mitgedanken\Monetary\Money
   */
  public static function __callStatic($code, $arguments)
  {
    $cargs = \count($arguments);
    if (2 == $cargs):
      $newMoney = new MoneyBag($arguments[0], new Currency($code, $arguments[1]));
    else:
      $newMoney = new MoneyBag($arguments[0], new Currency($code));
    endif;
    return $newMoney;
  }

  /**
   * Clears the backed storage and attachs the default money.<br/>
   *
   * @return void No return value.
   */
  public function clear()
  {
    $this->storage = new MonetaryStorage();
    $this->storage->attach($this->defaultMoney);
  }

  /**
   * <p><i>Extension</i></p>
   * Adds a <i>Money</i> object to this MoneyBag.<br/>
   * <p>Note: Different currencies are allowed.</p>
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $money
   * @return \mitgedanken\Monetary\Abstracts\Money
   */
  public function addMoney(AbstractsMoney $addend)
  {
    if ($this->currency->equals($addend->currency)):
      $result   = Algorithms::add($this->amount, $addend->amount, $this->scale);
      \settype($result, 'integer');
      $newMoney = new Money($result, $addend->currency);
    else:
      $pair     = $this->repository->findByCurrency($addend->currency, $this->currency);
      $newMoney = Exchange::convertMoney($addend, $pair);
    endif;
    $this->storage->attach($newMoney);
    return $newMoney;
  }

  /**
   * <p><i>Extension</i></p>
   * Adds a <i>MoneyBag</i> object to this <i>MoneyBag</i>.<br/>
   * <p>Note: Different currencies are allowed.</p>
   * @see \mitgedanken\Monetary\Abstracts\Money::add
   *
   * @param \mitgedanken\Monetary\Classes\MoneyBag $moneyBag
   * @return \mitgedanken\Monetary\Classes\MoneyBag
   */
  public function addMoneyBag(MoneyBag $addendMoneyBag)
  {
    $addendMoneyBag->toTotalAmount($addendMoneyBag->currency);
    /* Converting MoneyBag to Money */
    $newMoney = $this->addMoney(new Money($addendMoneyBag->amount, $addendMoneyBag->currency));
    $this->storage->attach($newMoney);
    return new MoneyBag($addendMoneyBag->amount, $addendMoneyBag->currency);
  }

  /**
   * <p><i>Override</i></p>
   * <br/>
   * @see \mitgedanken\Monetary\Abstracts\Money::add
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $addend
   * @return \mitgedanken\Monetary\Abstracts\Money
   * @throws UnsupportedOperationException
   *         If $addend is not an instance of <i>MoneyBagInterface</i> or <i>MoneyInterface</i>.
   * @throws DifferentCurrencies
   */
  public function add(AbstractsMoney $addend)
  {
    parent::_testIfHaveSameCurrency($addend);
    if ($addend instanceof MoneyBag):
      $result = $this->addMoneyBag($addend);
    elseif ($addend instanceof AbstractsMoney):
      $result = $this->addMoney($addend);
    else:
      $message = 'Unsupported object type;
        expected: \mitgedanken\Monetary\Abstracts\Money
        or \mitgedanken\Monetary\Classes\Money, but was: ' . \gettype($addend);
      throw new UnsupportedOperation($message);
    endif;
    return $result;
  }

  /**
   * <p><i>Override</i></p>
   * Returns a <i>Money</i> object which total is the result of a subtraction.
   *
   * @param \mitgedanken\Monetary\Classes\Abstracts\Money $subtrahend
   * @param boolean $rounding
   * @return \mitgedanken\Monetary\Classes\Money
   * @throws DifferentCurrencies
   */
  public function subtract(AbstractsMoney $subtrahend)
  {
    parent::_testIfHaveSameCurrency($subtrahend);
    $money = $this->_findByMoney($subtrahend);
    if (!\is_object($money)):
      throw new DifferentCurrencies($subtrahend);
    endif;
    $result   = Algorithms::subtract($this->amount, $subtrahend->amount, $this->scale);
    \settype($result, 'integer');
    $newMoney = $this->_newMoney($result);
    $this->storage->attach($newMoney);
    $this->storage->detach($money);
    return $newMoney;
  }

  /**
   * <p><i>Extension</i></p>
   * Returns a <i>Money</i> object which total is a conversation of all monetary
   * amounts to the desired currency. Its conversation is based on an given
   * <i>Exchange</i> object. It returns a <i>Money</i> object with amount of 0
   * if no suitable exchange rate was found.<br/>
   *
   * @param \mitgedanken\Monetary\Currency $currency
   * @return \mitgedanken\Monetary\Abstracts\Money
   */
  public function getMoneyIn(Currency $inCurrency)
  {
    if ($this->currency->equals($inCurrency)):
      $exchanged = $this;
    else:
      if (0 == $this->repository->count()):
        $message = 'Empty repository.';
        throw new \mitgedanken\Monetary\Exceptions\Logic($message);
      endif;
      foreach ($this->storage as $value):
        try {
          $pair      = $this->repository->findByCurrency($value->currency, $inCurrency);
          $exchanged = Exchange::convertMoney($value, $pair);
        } catch (\mitgedanken\Monetary\Exceptions\NoSuitablePair $exc) {
          $exchanged = new Money(0, $inCurrency);
        }
      endforeach;
    endif;
    return $exchanged;
  }

  /**
   * <p><i>Extension</i></p>
   * Return its total amount in the desired currency.<br/>
   *
   * @param \mitgedanken\Monetary\Currency $currencys
   * @return integer|float Its total amount.
   */
  public function getTotalOf(Currency $inCurrency)
  {
    $total = 0;
    if ($this->currency->equals($inCurrency)):
      $total = $this->amount;
    else:
      $total = $this->_findByCurrency($inCurrency)->amount;
    endif;
    return $total;
  }

  /**
   * <p><i>Extension</i></p>
   * Sets the amount of this <i>MoneyBag</i> to its total.<br/>
   *
   * @return void No return value
   */
  public function toTotalAmount(Currency $inCurrency)
  {
    $this->amount = $this->getMoneyIn($inCurrency)->amount;
  }

  /**
   * <p><i>Extension</i></p>
   * <b>WARNING: Changes its state</b><br/>
   * Deletes a money from the storage.
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $delete
   * @param type $onlybyCurrency Set to <i>TRUE</i> if the deletion should be
   *        performed only via currency of argument 1.
   * @return void No return value
   */
  public function deleteMoney(AbstractsMoney $delete, $onlybyCurrency = FALSE)
  {
    if ($onlybyCurrency):
      $deleteByCurrency = $this->_findByCurrency($delete->getCurrency());
      $this->storage->detach($deleteByCurrency);
    else:
      $this->storage->detach($delete);
    endif;
  }

  /**
   * <p><i>Extension</i></p>
   * Adds a storage with slenderized monies and converts to Abstracts\Money.<br/>
   *
   * @param \ArrayAccess $slenderized
   * @return void No return value
   * @throws Exceptions\Exception
   *      If the typ is not <i>SlenderMoney</i> nor <i>Abstracts\Money</i>.
   */
  public function addSlenderizedStorage(\ArrayAccess $slenderized)
  {
    foreach ($slenderized as $value):
      if ($value instanceof SlenderMoney):
        $this->add($this->_newMoney($value->amount));
      else:
        throw new Exceptions\Exception('Ignored type: ' . \gettype($value));
      endif;
    endforeach;
  }

  /**
   * <p><i>Extension</i></p>
   * Returns a storage with slenderized monies.<br/>
   * The internal storage will be slenderized.
   *
   * @return \SplObjectStorage
   */
  public function getSlenderizedStorage()
  {
    $slenderized = new \SplObjectStorage();
    foreach ($this->storage as $value):
      if ($value instanceof AbstractsMoney):
        $slenderized->attach(SlenderMoney::slenderize($value));
      endif;
    endforeach;
    return $slenderized;
  }

  /**
   * <p><i>Implimentation -> Countable</i></p>
   * Return count of all monies.
   *
   * @return integer Count of all monies.
   */
  public function count()
  {
    return $this->storage->count();
  }

  /**
   * <p><i>Extension</i></p>
   * Return the money with the same currency.<br/>
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $money
   * @return \mitgedanken\Monetary\Abstracts\Money Money with the same currency as $money.
   */
  protected function _findByMoney($money)
  {
    $current = NULL;
    $found   = FALSE;
    $this->storage->rewind();
    while (!$found && $this->storage->valid()):
      $current = $this->storage->current();
      $found   = $current->hasSameCurrency($money);
      $this->storage->next();
    endwhile;
    return $found ? $current : $this->_newMoney(0);
  }

  /**
   * <p><i>Extension</i></p>
   * Return the money with the same currency.<br/>
   *
   * @param \mitgedanken\Monetary\Currency $currency
   * @return \mitgedanken\Monetary\Abstracts\Money Money with the same currency as $money.
   */
  protected function _findByCurrency(Currency $currency)
  {
    $current = NULL;
    $found   = FALSE;
    $this->storage->rewind();
    while (!$found && $this->storage->valid()):
      $current = $this->storage->current();
      $found   = $current->currency->equals($currency);
      $this->storage->next();
    endwhile;
    return $found ? $current : $this->_newMoney(0);
  }

  /**
   * <p><i>Implimentation</i></p>
   * Contructs a money with the currency of this object.<br/>
   * @see \mitgedanken\Monetary\Abstracts\Money::_newMoney
   *
   * @param numeric $amount
   * @param \mitgedanken\Monetary\Abstracts\Money $other
   * @return \mitgedanken\Monetary\Abstracts\Money
   */
  protected function _newMoney($amount, $other = NULL)
  {
    settype($amount, 'integer');
    if (isset($other)):
      $newMoney = new MoneyBag($amount, $this->_pickCurrency($other));
    else:
      $newMoney = new MoneyBag($amount, $this->currency);
    endif;
    return $newMoney;
  }

}
