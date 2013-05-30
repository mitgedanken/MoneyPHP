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
use mitgedanken\Monetary\Exceptions\UnsupportedOperation,
    mitgedanken\Monetary\Exceptions\DifferentCurrencies,
    mitgedanken\Monetary\Exceptions\NoExchangeRatesDefined;

/**
 * <i>Immutable</i><br/>
 * This class is a implementation of <i>MoneyBagInterface</i>.<br/>
 * It guarantees that each element is contained only once.<br/>
 * It can be run in an compat mode. This means adding a <i>Money</i> object with
 * a different currency than this object's currency will throw an Exception.
 *
 * By using own implementation of a <i>MonetaryStorage</i> (\SplObjectStorage)
 * the implementor must garantee that each element is contained only once.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
class MoneyBag extends Money implements Interfaces\MoneyBag, \Countable {

  /**
   * Exchange rates.
   */
  protected $moneyConverter;

  /**
   * Storage for all Money and MoneyBag objects.
   *
   * @var \SplObjectStorage
   */
  protected $storage;

  /**
   * Constructor.<br/>
   * If <i>$rates</i> is <i>NULL</i> it will instaniate an empty <i>ExchangeRates</i>.<br/>
   * If <i>$storage</i> is <i>NULL</i> it will instaniate an empty <i>MonetaryStorage</i>.
   *
   * @param integer|float $amount
   * @param \mitgedanken\Monetary\Interfaces\Currency $defaultCurrency
   * @param \mitgedanken\Monetary\Interfaces\MoneyConverter $rates [default: NULL]
   * @param \mitgedanken\Monetary\Interfaces\MonetaryStorage $storage [default: NULL]
   *
   * @param \SplObjectStorage $storage [nullable]
   */
  public function __construct($amount, Interfaces\Currency $defaultCurrency,
                              Interfaces\MoneyConverter $rates = NULL,
                              \SplObjectStorage $storage = NULL)
  {
    parent::__construct($amount, $defaultCurrency);
    if (!isset($storage)):
      $storage = new MonetaryStorage();
    endif;
    $this->storage = $storage;
    $defaultMoney = new Money($amount, $defaultCurrency);
    $this->storage->attach($defaultMoney);

    if (isset($rates)):
      $this->moneyConverter = $rates;
    else:
      $this->moneyConverter = new MoneyConverter();
    endif;
  }

  public function addMoney(Interfaces\Money $addendMoney)
  {
    $found = $this->_findByMoney($addendMoney);
    if (\is_object($found)):
      $new = $found->add($addendMoney);
      if ($this->hasSameCurrency($found)):
        $this->amount += $new->amount;
      endif;
      $this->storage->attach($new);
      if ($new->amount != $found->amount):
        $this->storage->detach($found);
      endif;
      $result = $new;
    else:
      $result = $addendMoney;
      $this->storage->attach($addendMoney);
    endif;
    return $result;
  }

  public function addMoneyBag(Interfaces\MoneyBag $addendMoneyBag)
  {
    /* Converting MoneyBag to Money */
    $money = new Money($addendMoneyBag->amount, $addendMoneyBag->currency);
    return $this->addMoney($money);
  }

  public function add(Interfaces\Money $addend, $compatMode = FALSE)
  {
    if ($compatMode):
      parent::_requiresSameCurrency($addend->getCurrency(), __METHOD__);
    endif;

    if ($addend instanceof Interfaces\MoneyBag):
      $result = $this->addMoneyBag($addend);
    elseif ($addend instanceof Money):
      $result = $this->addMoney($addend);
    else:
      $message = 'Unsupported object type;
        expected: MoneyInterface or MoneyBagInterface, but was: '
              . \gettype($addend);
      throw new UnsupportedOperation($message);
    endif;
    return $result;
  }

  public function subtract(Interfaces\Money $subtrahend)
  {
    $object = $this->_findByMoney($subtrahend);
    if (!\is_object($object)):
      throw new DifferentCurrencies($subtrahend);
    endif;
    $newObject = $object->subtract($subtrahend);
    $this->storage->attach($newObject);
    $this->storage->detach($object);
    return $newObject;
  }

  public function getMoneyIn(Interfaces\Currency $toCurrency)
  {
    if (is_null($this->moneyConverter)):
      throw new NoExchangeRatesDefined();
    endif;

    $exchanged = NULL;
    foreach ($this->storage as $value):
      $current = $value;
      $exchangeRes = $this->moneyConverter->convert($current, $toCurrency);
      if (is_object($exchanged)):
        $exchanged = $exchangeRes->add($exchanged);
      else:
        $exchanged = $exchangeRes;
      endif;
    endforeach;
    return $exchanged;
  }

  public function getTotalIn(Interfaces\Currency $inCurrency)
  {
    return $this->getMoneyIn($inCurrency)->amount;
  }

  public function getTotalOf(Interfaces\Currency $inCurrency)
  {
    $total = 0;
    if ($this->currency->equals($inCurrency)):
      $total = $this->amount;
    else:
      $total = $this->_findByCurrency($inCurrency)->amount;
    endif;
    return $total;
  }

  public function toTotalAmount()
  {
    $this->amount = $this->getMoneyIn($this->currency)->amount;
  }

  public function deleteMoney(Interfaces\Money $delete, $onlybyCurrency = FALSE)
  {
    if ($onlybyCurrency):
      $deleteByCurrency = $this->_findByCurrency($delete->getCurrency());
      $this->storage->detach($deleteByCurrency);
    else:
      $this->storage->detach($delete);
    endif;
  }

  public function replaceConverter(Interfaces\MoneyConverter $converter)
  {
    $this->moneyConverter = $converter;
  }

  public function allocate($ratios, $rounding = FALSE)
  {
    if (isset(static::$allocate_algo)):
      $result =
              \call_user_func(
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
  private function _allocateAlgo(array $ratios, $rounding = FALSE)
  {
    $countRatios = count($ratios);
    $total = array_sum($ratios);
    $remainder = $this->amount;
    $results = new \SplFixedArray($countRatios);

    for ($i = 0; $i < $countRatios; $i += 1):
      $mulresult = $this->_multiplyAlgo($this->amount, $ratios[$i]);
      $result = $this->_divideAlgo($mulresult, $total, $rounding);
      $results[$i] = $this->_newMoney($result);
      $remainder -= $results[$i]->amount;
    endfor;

    for ($i = 0; $i < $remainder; $i++):
      $results[$i]->amount++;
    endfor;

    return $results;
  }

  public function count()
  {
    return $this->storage->count();
  }

  /**
   * Return the money with the same currency.
   *
   * @param \mitgedanken\Monetary\Interfaces\Money $money
   * @return \mitgedanken\Monetary\Interfaces\Money Money with the same currency as $money.
   */
  protected function _findByMoney($money)
  {
    $current = NULL;
    $found = FALSE;
    $this->storage->rewind();
    while (!$found && $this->storage->valid()):
      $current = $this->storage->current();
      $found = $current->hasSameCurrency($money);
      $this->storage->next();
    endwhile;
    return $found ? $current : NULL;
  }

  /**
   * Return the money with the same currency.
   *
   * @param \mitgedanken\Monetary\Interfaces\Currency $currency
   * @return \mitgedanken\Monetary\Interfaces\Money Money with the same currency as $money.
   */
  protected function _findByCurrency(Interfaces\Currency $currency)
  {
    $current = NULL;
    $found = FALSE;
    $this->storage->rewind();
    while (!$found && $this->storage->valid()):
      $current = $this->storage->current();
      $found = $current->currency->equals($currency);
      $this->storage->next();
    endwhile;
    return $found ? $current : NULL;
  }
}
