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
 * <i>Immutable</i><br/>
 * This interface specifies a money bag implementation.<br/>
 * It guarantees that each element is contained only once.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
interface MoneyBagInterface extends MoneyInterface {

  /**
   * <i>Override</i>
   * It throws a <i>DifferentCurrencies</i> only if it is used in
   * compat mode. If compat mode is used, it will throw a <i>DifferentCurrencies</i>
   * if <b>$addend</b> has a different currency.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $addend
   * @param bool $compatMode [default: FALSE] <i>TRUE</i> to using compat mode.
   * @return \mitgedanken\Monetary\MoneyInterface
   * @throws UnsupportedOperationException
   *    If $addend is not an instance of <i>MoneyBagInterface</i> or <i>MoneyInterface</i>.
   * @throws DifferentCurrencies
   *    If $compatMode is <i>TRUE</i> and if $addend has a different currency.
   */
  function add(MoneyInterface $addend, $compatMode = FALSE);

  /**
   * Adds a <i>Money</i> object to this MoneyBag.
   * @see \mitgedanken\Monetary\MoneyInterface::add
   *
   * @param \mitgedanken\Monetary\MoneyInterface $money
   * @return \mitgedanken\Monetary\MoneyInterface
   */
  function addMoney(MoneyInterface $addendMoney);

  /**
   * Adds a MoneyBag object to this MoneyBag.
   * @see \mitgedanken\Monetary\MoneyInterface::add
   *
   * @param \mitgedanken\Monetary\MoneyBagInterface $moneyBag
   * @return \mitgedanken\Monetary\MoneyInterface
   */
  function addMoneyBag(MoneyBagInterface $addendMoneyBag);

  /**
   * Return a <i>Money</i> object which total is a conversation of all monetary
   * amounts to the desired curreny. Its conversation is based on an given
   * <i>ExchangeRate</i> object. It returns a <i>Money</i> object with amount
   * of 0 if no suitable exchange rate was found.
   *
   * @param \mitgedanken\Monetary\CurrencyInterface $currency
   * @return \mitgedanken\Monetary\MoneyInterface
   */
  function getMoneyIn(CurrencyInterface $desiredCurrency);

  /**
   * Return a <i>Money</i> object with total amount of this <i>MoneyBag</i>
   * object which is converted to the desired currency.
   *
   * @param \mitgedanken\Monetary\CurrencyInterface $currency
   * @return \mitgedanken\Monetary\MoneyInterface Money with the total amount.
   */
  function getTotalIn(CurrencyInterface $desiredCurrency);

  /**
   * Return its total amount in the desired currency.
   *
   * @param \mitgedanken\Monetary\CurrencyInterface $currencys
   * @return integer|float Its total amount.
   */
  function getTotalOf(CurrencyInterface $desiredCurrency);

  /**
   * Sets the amount of this MoneyBag to its total.
   *
   * @return void No return value
   */
  function toTotalAmount();

  /**
   * <i>Changes its state</i><br/>
   * Deletes a money from the storage.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $delete
   * @param type $onlybyCurrency Set to <i>TRUE</i> if the deletion should be
   *        performed only via currency of $delete.
   */
  function deleteMoney(MoneyInterface $delete, $onlybyCurrency = FALSE);

  /**
   * Return count of all monies.
   *
   * @return integer Count of all monies.
   */
  function count();

  /**
   * Replaces the ExchangeRates object of this MoneyBag.
   *
   * @return void No return value.
   */
  function replaceExchangeRates(ExchangeRatesInterface $exchangeRates);

  /**
   * <i>Override</i>
   * Determines whether or not two MoneyBag objects are equal. Two instances of
   * IMoneyBag are equal if their contens are the same.
   *
   * @param  object  $object An object to be compared with this IMoneyBag.
   * @return boolean <b>TRUE</b> if this object is equal to another;
   *                 <b>FALSE</b> otherwise.
   */
  function equals($object);

  /*
   * Return the count of all elements.
   */
}

