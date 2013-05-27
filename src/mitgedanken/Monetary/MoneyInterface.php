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
 * <i>Immutable</i></br>
 * This interface specifies a monetary value object based on Money by Martin Fowler.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
interface MoneyInterface {

  /**
   * Return its ammount.
   *
   * @return integer Its ammount.
   */
  function getAmount();

  /**
   * Retuns its currency object.
   *
   * @return \mitgedanken\Monetary\Currency Its currency object.
   */
  function getCurrency();

  /**
   * Checks if its currency is "equal to" the $currency argument.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $other
   * @return boolean <b>TRUE</b> if this monetary value has
   *                 the same currency as the other;
   *                 <b>FALSE</b> otherwise.
   */
  function hasSameCurrency(MoneyInterface $currency);

  /**
   * Return a new MoneyValue object that represents the monetary value
   * of the sum of this Money object and another.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $addend
   * @return \mitgedanken\Monetary\MoneyInterface
   * @throws InvalidArgumentException
   * @throws DifferentCurrenciesException If $addend has a different currency.
   */
  function add(MoneyInterface $addend);

  /**
   * Return a new MoneyValue object that represents the monetary value
   * of the difference of this Money object and another.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $subtrahend
   * @return \mitgedanken\Monetary\MoneyInterface
   * @throws InvalidArgumentException
   */
  function subtract(MoneyInterface $subtrahend);

  /**
   * Return a new MoneyValue object that represents the monetary value
   * of this Money object multiplied by a given factor.
   *
   * @see _multiplyAlgo
   * @param  float|integer $multiplier
   * @return \mitgedanken\Monetary\MoneyInterface
   * @throws InvalidArgumentException
   */
  function multiply($multiplier);

  /**
   * Return a new MoneyValue object that represents the monetary value
   * of this Money object divided by a given divisor.
   *
   * @see _divideAlgo
   * @param  integer|\mitgedanken\Monetary\MoneyInterface $divisor
   * @return \mitgedanken\Monetary\MoneyInterface
   * @throws InvalidArgumentException
   * @throws DivisionByZeroException
   */
  function divide($divisor);

  /**
   * Compares this MoneyValue object to another with the same currency.
   * If both monetary values are zero, they currency must not be the same.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $other the other MoneyValue.
   * @return integer  0 if they are equal,
   *                 -1 if the other amount is greater or
   *                  1 if the other amount is less.
   * @throws InvalidArgumentException
   */
  function compare(MoneyInterface $other);

  /**
   * Checks if this MoneyValue object is greater than the other.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $other
   * @return boolean <b>TRUE</b> if the value is greater than the other;
   *                 <b>FALSE</b> otherwise.
   * @throws InvalidArgumentException
   */
  function greaterThan(MoneyInterface $other);

  /**
   * Checks if this MoneyValue object is less than the other.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $other
   * @return boolean <b>TRUE</b> if the value is less than the other.
   *                 <b>FALSE</b> otherwise.
   * @throws InvalidArgumentException
   */
  function lessThan(MoneyInterface $other);

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
