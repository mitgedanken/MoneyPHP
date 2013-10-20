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

namespace mitgedanken\Monetary;

use mitgedanken\Monetary\Exceptions\UnsupportedOperation,
    mitgedanken\Monetary\Exceptions\DifferentCurrencies;

/**
 * <i>Immutable</i><br/>
 * This class is a implementation of <i>MoneyBagInterface</i>.<br/>
 * It guarantees that each element is contained only once.<br/>
 * It can be run in an compat mode. This means adding a <i>Money</i> object with
 * a different currency than this object's currency will throw an Exception.
 *
 * By using own implementation of a <i>MonetaryStorage</i> (\SplObjectStorage)
 * the implementor must guarantee that each element is contained only once.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class MoneyBag extends Money implements \Countable {

  /**
   * Storage for all Money and MoneyBag objects.
   *
   * @var \SplObjectStorage
   */
  protected $storage;

  /**
   * Holds default money
   *
   * @var mitgedanken\Monetary\Abstracts\Money
   */
  protected $defaultMoney;

  /**
   * Constructor.<br/>
   * If <i>$rates</i> is <i>NULL</i> it will instaniate an empty <i>ExchangeRates</i>.<br/>
   * If <i>$storage</i> is <i>NULL</i> it will instaniate an empty <i>MonetaryStorage</i>.
   *
   * @param integer|float $amount
   * @param \mitgedanken\Monetary\Currency $defaultCurrency
   * @param \mitgedanken\Monetary\Interfaces\MonetaryStorage $storage [default: NULL]
   *
   * @param \ArrayAccess $storage [nullable]
   */
  public function __construct($amount, Currency $defaultCurrency, \ArrayAccess $storage = NULL) {
    parent::__construct($amount, $defaultCurrency);
    if (!isset($storage)):
      $storage = new MonetaryStorage();
    endif;
    $this->storage      = $storage;
    $this->defaultMoney = new Money($amount, $defaultCurrency);
    $this->storage->attach($this->defaultMoney);
  }

  /**
   * Clears the backed storage and attachs the default money.
   */
  public function clear() {
    $this->storage = new MonetaryStorage();
    $this->storage->attach($this->defaultMoney);
  }

  /**
   * Adds a <i>Money</i> object to this MoneyBag.
   * @see \mitgedanken\Monetary\Abstracts\Money::add
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $money
   * @return \mitgedanken\Monetary\Abstracts\Money
   */
  public function addMoney(Abstracts\Money $addend, $rounding = FALSE) {
    $found = $this->_findByMoney($addend);
    if (\is_object($found)):
      $add = bcadd($this->amount, $addend->amount, $this->scale);
      settype($add, 'float');
      $new = new Money($add, parent::getCurrency());
      if ($this->hasSameCurrency($found)):
        $this->amount += $new->amount;
      endif;
      $this->storage->attach($new);
      if ($new->amount != $found->amount):
        $this->storage->detach($found);
      endif;
      $result = $new;
    else:
      $result = $addend;
      $this->storage->attach($addend);
    endif;
    return $result;
  }

  /**
   * Adds a MoneyBag object to this MoneyBag.
   * @see \mitgedanken\Monetary\Abstracts\Money::add
   *
   * @param \mitgedanken\Monetary\Abstracts\MoneyBagInterface $moneyBag
   * @return \mitgedanken\Monetary\Abstracts\Money
   */
  public function addMoneyBag(MoneyBag $addendMoneyBag, $rounding = FALSE) {
    /* Converting MoneyBag to Money */
    $money = new Money($addendMoneyBag->amount, $addendMoneyBag->currency);
    return $this->addMoney($money, $rounding);
  }

  /**
   * <i>Override</i>
   * It throws a <i>DifferentCurrencies</i> only if it is used in
   * compat mode. If compat mode is used, it will throw a <i>DifferentCurrencies</i>
   * if <b>$addend</b> has a different currency.
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $addend
   * @param bool $compatMode [default: FALSE] <i>TRUE</i> to using compat mode.
   * @return \mitgedanken\Monetary\Abstracts\Money
   * @throws UnsupportedOperationException
   *    If $addend is not an instance of <i>MoneyBagInterface</i> or <i>MoneyInterface</i>.
   * @throws DifferentCurrencies
   *    If $compatMode is <i>TRUE</i> and if $addend has a different currency.
   */
  public function add(Abstracts\Money $addend, $rounding = FALSE, $compatMode = FALSE) {
    if ($compatMode):
      if (!$this->currency->equals($addend->currency)):
        $message = "The same currency is required
        (expected: $this->currency; but was $addend->currency)";
        throw new DifferentCurrencies($message);
      endif;
    endif;

    if ($addend instanceof MoneyBag):
      $result   = $this->addMoneyBag($addend, $rounding = FALSE);
    elseif ($addend instanceof Abstracts\Money):
      $result   = $this->addMoney($addend, $rounding = FALSE);
    else:
      $message = 'Unsupported object type;
        expected: MoneyInterface or MoneyBagInterface, but was: '
            . \gettype($addend);
      throw new UnsupportedOperation($message);
    endif;
    return $result;
  }

  public function subtract(Abstracts\Money $subtrahend, $rounding = FALSE) {
    $object = $this->_findByMoney($subtrahend);
    if (!\is_object($object)):
      throw new DifferentCurrencies($subtrahend);
    endif;
    $result    = bcsub(parent::getAmount(), $subtrahend->amount, $this->scale);
    var_dump(parent::getAmount());
    settype($result, 'float');
    $newObject = new Money($result, $this->currency);
    $this->storage->attach($newObject);
    $this->storage->detach($object);
    return $newObject;
  }

  /**
   * Return a <i>Money</i> object which total is a conversation of all monetary
   * amounts to the desired curreny. Its conversation is based on an given
   * <i>Exchange</i> object. It returns a <i>Money</i> object with amount
   * of 0 if no suitable exchange rate was found.
   *
   * @param \mitgedanken\Monetary\Currency $currency
   * @return \mitgedanken\Monetary\Abstracts\Money
   */
  public function getMoneyIn(Currency $toCurrency, CurrencyPairRepository $repository) {
    $exchanged = NULL;
    foreach ($this->storage as $value):
      try {
        $pair           = $repository->findByCurrency($value->getCurrency(), $toCurrency);
        $exchangeResult = Exchange::convertMoney($value, $pair);
      } catch (Exceptions\Exception $exc) {
        $exchangeResult = $value;
      }
      if (is_object($exchanged)):
        $result = $exchangeResult->add($result);
      else:
        $result = $exchangeResult;
      endif;
    endforeach;
    return $result;
  }

  /**
   * Return a <i>Money</i> object with total amount of this <i>MoneyBag</i>
   * object which is converted to the desired currency.
   *
   * @param \mitgedanken\Monetary\Currency $currency
   * @return \mitgedanken\Monetary\Abstracts\Money Money with the total amount.
   */
  public function getTotalIn(Currency $inCurrency, CurrencyPairRepository $repository) {
    return $this->getMoneyIn($inCurrency, $repository)->amount;
  }

  /**
   * Return its total amount in the desired currency.
   *
   * @param \mitgedanken\Monetary\Currency $currencys
   * @return integer|float Its total amount.
   */
  public function getTotalOf(Currency $inCurrency) {
    $total = 0;
    if ($this->currency->equals($inCurrency)):
      $total = $this->amount;
    else:
      $total = $this->_findByCurrency($inCurrency)->amount;
    endif;
    return $total;
  }

  /**
   * Sets the amount of this MoneyBag to its total.
   *
   * @return void No return value
   */
  public function toTotalAmount(CurrencyPairRepository $repository) {
    $this->amount = $this->getMoneyIn($this->currency, $repository)->amount;
  }

  /**
   * <i>Changes its state</i><br/>
   * Deletes a money from the storage.
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $delete
   * @param type $onlybyCurrency Set to <i>TRUE</i> if the deletion should be
   *        performed only via currency of argument 1.
   */
  public function deleteMoney(Abstracts\Money $delete, $onlybyCurrency = FALSE) {
    if ($onlybyCurrency):
      $deleteByCurrency = $this->_findByCurrency($delete->getCurrency());
      $this->storage->detach($deleteByCurrency);
    else:
      $this->storage->detach($delete);
    endif;
  }

  /**
   * Return a new Money object that represents the monetary value
   * of this Money object, allocated according to a list of ratio's.
   *
   * @param array $ratios the ratio's.
   * @param boolean $rounding [default: FALSE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\Abstracts\Money[] the allocated monies.
   */
  public function allocate($ratios, $rounding = FALSE) {
    if (isset(static::$allocate_algo)):
      $result = \call_user_func(
            static::$allocate_algo, $this->amount, $ratios, $rounding);
    else:
      $result = $this->_allocateAlgo($ratios, $rounding);
    endif;
    return $result;
  }

  /**
   * <i>Alogrithm</i></p>
   * Return a new Money object that represents the monetary value
   * of this Money object, allocated according to a list of ratio's.
   *
   * @param array $ratios
   * @param boolean $rounding [default: FALSE] rounds the result if </i>TRUE</i>
   * @return array \mitgedanken\Monetary\SimpleMoney
   */
  protected function _allocateAlgo(array $ratios, $rounding = FALSE) {
    $countRatios = count($ratios);
    $total       = array_sum($ratios);
    $remainder   = $this->amount;
    $results     = new \SplFixedArray($countRatios);

    for ($i = 0; $i < $countRatios; $i += 1):
      $mulresult   = $this->_multiplyAlgo($this->amount, $ratios[$i]);
      $result      = $this->_divideAlgo($mulresult, $total, $rounding);
      $results[$i] = $this->_newMoney($result);
      $remainder -= $results[$i]->amount;
    endfor;
    for ($i = 0; $i < $remainder; $i++):
      $results[$i]->amount++;
    endfor;
    return $results;
  }

  /**
   * Adds a storage with slenderized monies and converts to Interface\Money.
   *
   * @param \ArrayAccess $slenderized
   * @throws Exceptions\Exception
   *      If a typ is not <i>SlenderMoney</i> and not <i>Abstracts\Money $</i>.
   */
  public function addSlenderizedStorage(\ArrayAccess $slenderized) {
    foreach ($slenderized as $value):
      if ($value instanceof SlenderMoney):
        $new = new Money($value->getAmount(), $value->getCurrency());
        $this->add($new);
      elseif ($value instanceof Abstracts\Money):
        $this->add($value);
      else:
        throw new Exceptions\Exception('Ignored type: ' . \gettype($value));
      endif;
    endforeach;
  }

  /**
   * Returns a storage with slenderized monies.
   *
   * @param \ArrayAccess $storage [nullable]
   *        If is <i>NULL</i> the internal storage will be slenderized.
   * @return \ArrayAccess
   */
  public function getSlenderizedStorage(\ArrayAccess $storage = NULL) {
    if (isset($storage)):
      $toSlenderize = $storage;
    else:
      $toSlenderize = $this->storage;
    endif;
    $slenderized = new \SplObjectStorage();
    foreach ($this->storage as $value):
      if ($value instanceof Abstracts\Money):
        $slenderized->attach(SlenderMoney::slenderize($value));
      endif;
    endforeach;
    return $slenderized;
  }

  /**
   * Return count of all monies.
   *
   * @return integer Count of all monies.
   */
  public function count() {
    return $this->storage->count();
  }

  /**
   * Return the money with the same currency.
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $money
   * @return \mitgedanken\Monetary\Abstracts\Money Money with the same currency as $money.
   */
  protected function _findByMoney($money) {
    $current = NULL;
    $found   = FALSE;
    $this->storage->rewind();
    while (!$found && $this->storage->valid()):
      $current = $this->storage->current();
      $found   = $current->hasSameCurrency($money);
      $this->storage->next();
    endwhile;
    return $found ? $current : FALSE;
  }

  /**
   * Return the money with the same currency.
   *
   * @param \mitgedanken\Monetary\Currency $currency
   * @return \mitgedanken\Monetary\Abstracts\Money Money with the same currency as $money.
   */
  protected function _findByCurrency(Currency $currency) {
    $current = NULL;
    $found   = FALSE;
    $this->storage->rewind();
    while (!$found && $this->storage->valid()):
      $current = $this->storage->current();
      $found   = $current->currency->equals($currency);
      $this->storage->next();
    endwhile;
    return $found ? $current : FALSE;
  }

}
