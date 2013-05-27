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
use mitgedanken\Monetary\Exception\UnsupportedOperationException;

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
class MoneyBag extends Money implements MoneyBagInterface {

  /**
   * Exchange rates.
   */
  protected $exchangeRates;

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
   * @param \mitgedanken\Monetary\CurrencyInterface $defaultCurrency
   * @param \mitgedanken\Monetary\ExchangeRates $rates [default: NULL]
   * @param \mitgedanken\Monetary\MonetaryStorage $storage [default: NULL]
   *
   * @param \SplObjectStorage $storage [nullable]
   */
  public function __construct($amount, CurrencyInterface $defaultCurrency,
                              ExchangeRatesInterface $rates = NULL,
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
      $this->exchangeRates = $rates;
    else:
      $this->exchangeRates = new ExchangeRates();
    endif;
  }

  public function addMoney(MoneyInterface $addendMoney)
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

  public function addMoneyBag(MoneyBagInterface $addendMoneyBag)
  {
    /* Converting MoneyBag to Money */
    $money = new Money($addendMoneyBag->amount, $addendMoneyBag->currency);
    return $this->addMoney($money);
  }

  /**
   * <i>Override</i>
   * It throws a <i>DifferentCurrenciesException</i> only if it is used in
   * compat mode. If compat mode is used, it will throw a <i>DifferentCurrenciesException</i>
   * if <b>$addend</b> has a different currency.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $addend
   * @param bool $compatMode [default: FALSE] <i>TRUE</i> to using compat mode.
   * @return \mitgedanken\Monetary\MoneyInterface
   * @throws UnsupportedOperationException
   *    If $addend is not an instance of <i>MoneyBagInterface</i> or <i>MoneyInterface</i>.
   * @throws DifferentCurrenciesException
   *    If $compatMode is <i>TRUE</i> and if $addend has a different currency.
   */
  public function add(MoneyInterface $addend, $compatMode = FALSE)
  {
    if ($compatMode):
      parent::_requiresSameCurrency($addend->getCurrency(), __METHOD__);
    endif;

    if ($addend instanceof MoneyBagInterface):
      $result = $this->addMoneyBag($addend);
    elseif ($addend instanceof MoneyInterface):
      $result = $this->addMoney($addend);
    else:
      $message = 'Unsupported object type;
        expected: MoneyInterface or MoneyBagInterface, but was: '
              . \gettype($addend);
      throw new UnsupportedOperationException($message);
    endif;
    return $result;
  }

  public function getMoneyIn(CurrencyInterface $toCurrency)
  {
    $exchanged = NULL;
    $this->storage->rewind();
    while ($this->storage->valid()):
      $current = $this->storage->current();
      $exchangeRes = $this->exchangeRates->exchange($current, $toCurrency);
      if (is_object($exchanged)):
        $exchanged = $exchangeRes->add($exchanged);
      else:
        $exchanged = $exchangeRes;
      endif;
      $this->storage->next();
    endwhile;
    return $exchanged;
  }

  public function getTotalIn(CurrencyInterface $inCurrency)
  {
    return $this->getMoneyIn($inCurrency)->amount;
  }

  public function getTotalOf(CurrencyInterface $inCurrency)
  {
    $total = 0;
    if ($this->currency->equals($inCurrency)):
      $total = $this->amount;
    else:
      $total = $this->_findByCurrency($inCurrency)->amount;
    endif;
    return $total;
  }

  public function amountToTotal()
  {
    $this->amount = $this->getTotalIn($this->currency);
  }

  public function replaceExchangeRates(ExchangeRatesInterface $exchangeRates)
  {
    $this->exchangeRates = $exchangeRates;
  }

  public function count()
  {
    return $this->storage->count();
  }

  /**
   * Return the money with the same currency.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $money
   * @return \mitgedanken\Monetary\MoneyInterface Money with the same currency as $money.
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
   * @param \mitgedanken\Monetary\CurrencyInterface $currency
   * @return \mitgedanken\Monetary\MoneyInterface Money with the same currency as $money.
   */
  protected function _findByCurrency(CurrencyInterface $currency)
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
