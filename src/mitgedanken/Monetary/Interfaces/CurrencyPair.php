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
 * Represents a currency pair.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
interface CurrencyPair {

  /**
   * Checks if this currency pair has a currency.
   *
   * @param \mitgedanken\Monetary\Interfaces\Currency $currency
   * @return boolean <i>TRUE</i> if this currency pair has $currency;
   *                 <i>FALSE</i> otherwise.
   */
  function has(Currency $currency);

  /**
   * Determines whether or not two <i>CurrencyPair</i> objects are equal. Two
   * instances of CurrencyPairInterface are equal if they currencys are equal.
   *
   * @param  object  $object An object to be compared with this CurrencyPaiInterface.
   * @return boolean <i>TRUE</i> if the object to be compared is an instance of
   *                 <i>CurrencyPair</i> and has the same currency code;
   *                 <i>FALSE</i> otherwise.
   */
  function equals($object);

  /**
   * Return its identifier.<br/>
   * A currency pair is identifiable by its pair's currency codes.
   *
   * @return string Its identifier.
   */
  function identify();
}

