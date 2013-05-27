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
 * Manages exchange rates.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
interface ExchangeRatesInterface {

  /**
   * Attaches a currency pair with its exchange rate.
   *
   * @param \mitgedanken\Monetary\CurrencyPairInterface $pair
   * @param integer|float $rate
   */
  function attach(CurrencyPairInterface $pair, $rate);

  /**
   * Dettaches a currency pair.
   */
  function dettach(CurrencyPairInterface $pair);

  /**
   * Return the exchange result as a money object.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $money
   * @param \mitgedanken\Monetary\CurrencyInterface $inCurrency
   * @return \mitgedanken\Monetary\MoneyInterface Exchange result.
   */
  function exchange(MoneyInterface $money, CurrencyInterface $inCurrency);

  /**
   * Return its identifier.<br/>
   * Exchange rates are identifiable by its class name.
   *
   * @return string Its identifier.
   */
  function identify();
}

