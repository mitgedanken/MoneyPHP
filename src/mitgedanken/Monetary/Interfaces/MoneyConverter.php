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

namespace mitgedanken\Monetary\Interfaces;

/**
 * Manages exchange rates.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
interface MoneyConverter {

  /**
   * Set if a <i>NoSuitableExchangeRate</i> exception is thrown if no suitable
   * exchange rate was found.
   *
   * @param boolean $boolean
   */
  public function setNoSuitableException($boolean);

  /**
   * Attaches a exchange rate.<br/>
   *
   * A exchange rate is consisting of a <i>CurrencyPair</i> and a exchange rate.
   *
   * @param \mitgedanken\Monetary\Interfaces\CurrencyPair $pair
   * @param array|integer|float $ratios
   */
  function attach(CurrencyPair $pair, $ratios);

  /**
   * Replaces a exchange rate.<br/>
   *
   * A exchange rate is consisting of a <i>CurrencyPair</i> and a exchange rate.
   *
   * @param \mitgedanken\Monetary\Interfaces\CurrencyPair $pair
   * @param array $ratios
   */
  public function replace(CurrencyPair $pair, $ratios);

  /**
   * Dettaches a exchange rate.
   *
   * @param \mitgedanken\Monetary\Interfaces\CurrencyPair $pair
   */
  function detach(CurrencyPair $pair);

  /**
   * Exchanges the given <i>Money</i> object to the given <i>Currency</i>.<br/>
   * It throws a <i>NoSuitableExchangeRate</i> exception if this object has been
   * set for it.
   *
   * @param \mitgedanken\Monetary\Interfaces\Money $money
   * @param \mitgedanken\Monetary\Interfaces\Currency $toCurrency
   * @return \mitgedanken\Monetary\Money
   * @throws NoSuitableExchangeRate If this object has been set for it.
   */
  function convert(Money $money, Currency $inCurrency);

  /**
   * Return its identifier.<br/>
   * Exchange rates are identifiable by its class name.
   *
   * @return string Its identifier.
   */
  function identify();
}

