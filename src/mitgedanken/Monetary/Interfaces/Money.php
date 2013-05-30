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
 * <i>Immutable</i></br>
 * This interface specifies a monetary value object based on Money by Martin Fowler.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
interface Money {

  /**
   * Return its ammount.
   *
   * @return integer Its ammount.
   */
  function getAmount();

  /**
   * Retuns its currency object.
   *
   * @return \mitgedanken\Monetary\Interfaces\Currency Its currency object.
   */
  function getCurrency();

  /**
   * Return a new <i>Money</i> object that represents the monetary value
   * of the sum of this Money object and another.
   *
   * @param \mitgedanken\Monetary\Interfaces\Money $addend
   * @return \mitgedanken\Monetary\Interfaces\Money
   * @throws InvalidArgumentException
   * @throws DifferentCurrencies If $addend has a different currency.
   */
  function add(Money $addend);

  /**
   * Return a new <i>Money</i> object that represents the monetary value
   * of the difference of this Money object and another.
   *
   * @param \mitgedanken\Monetary\Interfaces\Money $subtrahend
   * @return \mitgedanken\Monetary\Interfaces\Money
   * @throws InvalidArgumentException
   */
  function subtract(Money $subtrahend);

  /**
   * Return a new <i>Money</i> object that represents the monetary value
   * of this Money object multiplied by a given factor.
   *
   * @see _multiplyAlgo
   * @param  float|integer $multiplier
   * @param boolean $rounding [default: TRUE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\Interfaces\Money
   * @throws InvalidArgumentException
   */
  function multiply($multiplier, $rounding = TRUE);

  /**
   * Return a new <i>Money</i> object that represents the monetary value
   * of this Money object divided by a given divisor.
   *
   * @param  integer|\mitgedanken\Monetary\Interfaces\Money $divisor
   * @param boolean $rounding [default: TRUE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\Interfaces\Money
   * @throws InvalidArgumentException
   * @throws DivisionByZero
   */
  function divide($divisor, $rounding = TRUE);

  /**
   * Compares this <i>Money</i> object to another with the same currency.
   * If both monetary values are zero, they currency must not be the same.
   *
   * @param \mitgedanken\Monetary\Interfaces\Money $other the other <i>Money</i>.
   * @return integer  0 if they are equal,
   *                 -1 if the other amount is greater or
   *                  1 if the other amount is less.
   * @throws InvalidArgumentException
   */
  function compare(Money $other);

  /**
   * Checks if this <i>Money</i> object is greater than the other.
   *
   * @param \mitgedanken\Monetary\Interfaces\Money $other
   * @return boolean <b>TRUE</b> if the value is greater than the other;
   *                 <b>FALSE</b> otherwise.
   * @throws InvalidArgumentException
   */
  function greaterThan(Money $other);

  /**
   * Checks if this <i>Money</i> object is less than the other.
   *
   * @param \mitgedanken\Monetary\Interfaces\Money $other
   * @return boolean <b>TRUE</b> if the value is less than the other.
   *                 <b>FALSE</b> otherwise.
   * @throws InvalidArgumentException
   */
  function lessThan(Money $other);

  /**
   * Checks if the amount is zero.
   *
   * @return boolean <b>TRUE</b> if the amount is zero;
   *                 <b>FALSE</b> otherwise.
   */
  function isZero();

  /**
   * Checks if the amount is positive.
   *
   * @return boolean <b>TRUE</b> if the amount is positive;
   *                 <b>FALSE</b> otherwise.
   */
  function isPositive();

  /**
   * Checks if the amount is negative.
   *
   * @return boolean <b>TRUE</b> if the amount is negative;
   *                 <b>FALSE</b> otherwise.
   */
  function isNegative();

  /**
   * Determines whether or not two Money objects are equal. Two instances of
   * IMoney are equal if the amount are the same and if their currencies are
   * equal.
   *
   * @param  object  $object An object to be compared with this IMoney.
   * @return boolean <b>TRUE</b> if the object to be compared is an
   *                 instance of IMoney and has the same amount and currency;
   *                 <b>FALSE</b> otherwise.
   */
  function equals($object);

  /**
   * Return its identifier.<br/>
   * A money is identifiable by its currency and amount.
   *
   * @return string Its identifier.
   */
  function identify();
}
