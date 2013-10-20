<?php

/*
 * Copyright (C) 2013 Sascha Tasche <hallo@mitgedanken.de>
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

namespace mitgedanken\Monetary\Abstracts;

use mitgedanken\Monetary\Currency;
use mitgedanken\Monetary\Exceptions\InvalidArgument,
    mitgedanken\Monetary\Exceptions\DivisionByZero,
    mitgedanken\Monetary\Exceptions\DifferentCurrencies;

/**
 * <i>Immutable</i></br>
 * This interface specifies a monetary value object based on Money by Martin Fowler.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
abstract class Money {

  use \mitgedanken\Monetary\Traits\Monetary;

  /**
   * This amount.
   *
   * @var integer|float
   */
  protected $amount;

  /**
   * Holds the Currency object.
   *
   * @var \mitgedanken\Monetary\Currency
   */
  protected $currency;

  /**
   * Holds the scale used by all bc* functions.
   *
   * @var integer
   */
  protected $scale;

  /**
   * Constructs this <i>Money</i> object.
   *
   * @param integer $amount Its amount.
   * @param \mitgedanken\Monetary\Currency $currency Its currency.
   * @throws InvalidArgument
   */
  public function __construct($amount, Currency $currency, $scale = 12) {
    if (!\is_integer($amount) && !\is_float($amount)):
      $type    = \gettype($amount);
      $message = "Argument 1 requires integer or float, but was $type";
      throw new InvalidArgument($message);
    endif;
    $this->currency = $currency;
    $this->amount   = $amount;
    $this->scale    = $scale;
  }

  /**
   * <i>Class is immutable</i><br/>
   * Cloning is not supported.
   *
   * @throws Exceptions\UnsupportedOperation
   */
  public function __clone() {
    throw new Exceptions\UnsupportedOperation('__clone not supported');
  }

  /**
   * Return its ammount.
   *
   * @return integer Its ammount.
   */
  public function getAmount() {
    return $this->amount;
  }

  /**
   * Retuns its currency object.
   *
   * @return \mitgedanken\Monetary\Currency Its currency object.
   */
  public function getCurrency() {
    return $this->currency;
  }

  /**
   * Return a new <i>Money</i> object that represents the monetary value
   * of the sum of this <i>Money</i> object and another.
   *
   * @param \mitgedanken\Monetary\Money $addend
   * @param boolean $rounding [default: FALSE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\Money
   * @throws InvalidArgumentException
   * @throws DifferentCurrencies If $addend has a different currency.
   */
  public function add(Money $addend, $rounding = FALSE) {
    if (!$this->currency->equals($addend->currency)):
      $message = "The same currency is required
        (expected: $this->currency; but was $addend->currency)";
      throw new DifferentCurrencies($message);
    endif;
    $result = bcadd($this->amount, $addend->amount, 12);
    settype($result, 'float');
    if ($rounding):
      $result = \round($result, 4, PHP_ROUND_HALF_EVEN);
    endif;
    return $this->_newMoney($result, $addend);
  }

  /**
   * <i>Override</i>
   * Return a new <i>Money</i> object that represents the monetary value
   * of the difference of this <i>Money</i> object and another.
   *
   * @param \mitgedanken\Monetary\Money $subtrahend
   * @param boolean $rounding [default: FALSE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\Money
   * @throws InvalidArgumentException
   */
  public function subtract(Money $subtrahend, $rounding = FALSE) {
    if (!$this->currency->equals($subtrahend->currency)):
      $message = "The same currency is required
        (expected: $this->currency; but was $subtrahend->currency)";
      throw new DifferentCurrencies($message);
    endif;
    if ($this->isZero()):
      $result = $subtrahend->amount;
    else:
      $result = \bcsub($this->amount, $subtrahend->amount, $this->scale);
      settype($result, 'float');
    endif;
    if ($rounding):
      $result = \round($result, $this->scale, PHP_ROUND_HALF_EVEN);
    endif;
    return $this->_newMoney($result);
  }

  /**
   * Return a new <i>Money</i> object that represents the monetary value
   * of this <i>Money</i> object multiplied by a given factor.
   *
   * @see _multiplyAlgo
   * @param  float|integer $multiplier
   * @param boolean $rounding [default: FALSE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\Money
   * @throws InvalidArgument
   */
  public function multiply($multiplier, $rounding = FALSE) {
    $multiplier = $this->_pickValue($multiplier, __METHOD__);
    $result     = $this->_newMoney(bcmul($this->amount, $multiplier, $this->scale));
    settype($result, 'float');
    if ($rounding):
      $result = \round($result, $this->scale, PHP_ROUND_HALF_EVEN);
    endif;
    return $result;
  }

  /**
   * Return a new <i>Money</i> object that represents the monetary value
   * of this <i>Money</i> object divided by a given divisor.
   *
   * @param  integer|\mitgedanken\Monetary\Money $divisor
   * @param boolean $rounding [default: FALSE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\Money
   * @throws InvalidArgumentException
   * @throws DivisionByZero
   */
  public function divide($divisor, $rounding = FALSE) {
    $divisor = $this->_pickValue($divisor, __METHOD__);
    if ($divisor == 0):
      throw new DivisionByZero();
    else:
      $result = bcdiv($this->amount, $divisor, $this->scale);
      if ($rounding):
        $result = \round($result, $this->scale, PHP_ROUND_HALF_EVEN);
      endif;
      settype($result, 'float');
      return $result;
    endif;
  }

  /**
   * Compares this <i>Money</i> object to another with the same currency.
   * If both monetary values are zero, they currency must not be the same.
   *
   * @param \mitgedanken\Monetary\Money $other the other <i>Money</i>.
   * @return integer
   *    0 if they are equal,
   *    -1 if the other amount is greater or
   *    1 if the other amount is less.
   * @throws InvalidArgumentException
   */
  public function compare(Money $other) {
    if (!$this->currency->equals($other->currency)):
      $message = "The same currency is required
        (expected: $this->currency; but was $other->currency)";
      throw new DifferentCurrencies($message);
    endif;
    if ($this->amount == $other->amount):
      $compared = 0;
    else:
      $compared = ($this->amount < $other->amount) ? -1 : 1;
    endif;
    return $compared;
  }

  /**
   * Checks if this <i>Money</i> object is greater than the other.
   *
   * @param \mitgedanken\Monetary\Money $other
   * @return boolean
   *    <b>TRUE</b> if the value is greater than the other;
   *    <b>FALSE</b> otherwise.
   * @throws InvalidArgumentException
   */
  public function greaterThan(Money $other) {
    return 1 == $this->compare($other);
  }

  /**
   * Checks if this <i>Money</i> object is less than the other.
   *
   * @param \mitgedanken\Monetary\Money $other
   * @return boolean <b>TRUE</b> if the value is less than the other.
   *                 <b>FALSE</b> otherwise.
   * @throws InvalidArgumentException
   */
  public function lessThan(Money $other) {
    return -1 == $this->compare($other);
  }

  /**
   * Checks if the amount is zero.
   *
   * @return boolean <b>TRUE</b> if the amount is zero;
   *                 <b>FALSE</b> otherwise.
   */
  public function isZero() {
    return 0 == $this->amount;
  }

  /**
   * Checks if the amount is positive.
   *
   * @return boolean <b>TRUE</b> if the amount is positive;
   *                 <b>FALSE</b> otherwise.
   */
  public function isPositive() {
    return 0 < $this->amount;
  }

  /**
   * Checks if the amount is negative.
   *
   * @return boolean <b>TRUE</b> if the amount is negative;
   *                 <b>FALSE</b> otherwise.
   */
  public function isNegative() {
    return 0 > $this->amount;
  }

  /**
   * Constructs a new <i>Money</i> object with the same currency as this object.
   *
   * @param integer $amount
   * @param \mitgedanken\Monetary\Money $other
   * @return \mitgedanken\Monetary\Money
   * @throws InvalidArgument
   */
  abstract protected function _newMoney($amount, $other = NULL);

  /**
   * Return an integer of <i>$given</i>. If it is a <i><i>Money</i></i>,
   * it Return its amount, only if it is Zero;
   * if it is an <i>integer</i>, it Return its value.
   *
   * @param integer|\mitgedanken\Monetary\Money $given
   *    Amount of <i>Money</i> or integer value.
   * @return integer amount of <i>Money</i> or the given integer value.
   * @throws InvalidArgument
   */
  protected function _pickValue($given, $method = NULL) {
    if ($given instanceof Money):
      if (!$this->currency->equals($given->currency)):
        $message = "The same currency is required
        (expected: $this->currency; but was $given->currency)";
        throw new DifferentCurrencies($message);
      endif;
      $method = (isset($method)) ? __METHOD__ : $method;
      $value  = $given->amount;
    else:
      if (!\is_integer($given) && !\is_float($given)):
        $type    = \gettype($given);
        $message = "Argument 1 requires integer or float, but was $type";
        throw new InvalidArgument($message);
      endif;
      $value = $given;
    endif;
    return $value;
  }

  /**
   * Return the Currency object of the other object, if amount is 0.
   *
   * @return \mitgedanken\Monetary\Currency
   *    <i>The Currency object of the other object</i>, if amount is 0;
   *    <i>This Currency object</i></p> otherwise.
   */
  protected function _pickCurrency(Money $other) {
    return ($this->amount == 0) ? $other->currency : $this->currency;
  }

  /**
   * TODO
   * Indicates whether this object is "equal to" another.<br/>
   * This object is "equal to" another if that is an instance of <i>Money</i>
   * and if their amounts and currencies are "equal to".<br/>
   * <i>Note</i>: "Equal to" for their amounts is indicated via '=='.
   *
   * @param mixed $object
   * @return boolean
   */
  public function equals($object) {
    $equals = FALSE;
    if ($object instanceof Money):
      $equals = $this->currency->equals($object->currency) && $this->amount == $object->amount;
    endif;
    return $equals;
  }

  /**
   * Return its identifier.
   *
   * @return type
   */
  public function identify() {
    return "$this->amount $this->currency";
  }

  /**
   * Return this <i>Money</i> object as a string.
   *
   * @return string ("amount" "currency")
   * @see \mitgedanken\Monetary\Currency
   */
  public function __toString() {
    return "$this->amount $this->currency";
  }

}
