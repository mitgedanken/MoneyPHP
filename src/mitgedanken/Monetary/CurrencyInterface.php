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
 * along with this program . If not, see <http://www.gnu.org/licenses/>.
 */

namespace mitgedanken\Monetary;

/**
 * <i>Immutable</i></p>
 * Represents a currency.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
interface CurrencyInterface {

  /**
   * Return its currency code (ISO 4217).
   *
   * @return string Its currency code.
   */
  function getCode();

  /**
   * Return its display name.
   *
   * @return string Its display name.
   */
  function getName();

  /**
   * Determines whether or not two <i>Currency</i> objects are equal. Two
   * instances of <i>CurrencyInterface</i> are equal if they have the same
   * currency code or one of them are an instance of a null object for currencies.
   *
   * @param  object  $object An object to be compared with this CurrencyInterface.
   * @return boolean <i>TRUE</i> if the object to be compared is an instance of
   *                 <i>CurrencyInterface</i> and has the same currency code;
   *                 <i>FALSE</i> otherwise.
   */
  function equals($object);

  /**
   * Return its identifier.<br/>
   * A currency is identifiable by its currency codes.
   *
   * @return string Its identifier.
   */
  function identify();
}
