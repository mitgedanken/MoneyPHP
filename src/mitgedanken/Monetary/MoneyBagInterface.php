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
 * It must be guarantee that each element is contained only once.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
interface MoneyBagInterface extends MoneyInterface {

  /**
   * Adds a MoneyValue object to this MoneyBag.
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
  function amountToTotal();

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

