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
use mitgedanken\Monetary\Exception\InvalidArgumentException,
    mitgedanken\Monetary\Exception\DifferentCurrenciesException,
    mitgedanken\Monetary\Exception\DivisionByZeroException;

/**
 * <i>Immutable</i><br/>
 * This class implements a monetary value object.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
class Money implements MoneyInterface {

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
   * Constructs this MoneyValue object.
   *
   * @param integer $amount Its amount.
   * @param \mitgedanken\Monetary\Currency $currency Its currency.
   * @throws InvalidArgumentException
   */
  public function __construct($amount, CurrencyInterface $currency)
  {
    $this->_requiresIntegerOrFloat($amount, 'amount', __METHOD__);
    $this->currency = $currency;
    $this->amount = $amount;
  }

  /**
   * Convenience factory method.
   *
   * @example $fiveDollar = MoneyValue::USD(500, 'United States dollar');
   * @example $fiveDollar = MoneyValue::USD(500);
   *
   * @param string $name
   * @param array $arguments 0:string, currency code; 1:string, display name;
   * @return \mitgedanken\Monetary\MoneyInterface
   */
  public static function __callStatic($name, $arguments)
  {
    $cargs = \count($arguments);
    if (2 == $cargs):
      $money = new Money(
              $arguments[0], new Currency($name, $arguments[1]));
    else:
      $money = new Money($arguments[0], new Currency($name));
    endif;
    return $money;
  }

  public static function zero()
  {
    static $zero = null;
    if (!isset($zero)):
      $zero = new Money(0, new NullCurrency());
    endif;
    return $zero;
  }

  public function getAmount()
  {
    return $this->amount;
  }

  /**
   * Checks if this MoneyValue has the same amount as the other.
   * <i>The objects must have the same currency.</i></p>
   *
   * @param \mitgedanken\Monetary\MoneyInterface $other
   * @return boolean <b>TRUE</b> if this monetary value has
   *                 the same amount and the same currency as the other;
   *                 <b>FALSE</b> otherwise.
   */
  public function hasSameAmount(MoneyInterface $other)
  {
    return $this->hasSameCurrency($other)
            && ($this->amount == $other->amount);
  }

  /**
   * Return its currency object.
   *
   * @return CurrencyInterface Its currency object.
   */
  public function getCurrency()
  {
    return $this->currency;
  }

  public function hasSameCurrency(MoneyInterface $other)
  {
    return $this->currency->equals($other->currency);
  }

  public function add(MoneyInterface $addend)
  {
    $this->_requiresSameCurrency($addend->currency, __METHOD__);
    return $this->_newMoney($this->amount + $addend->amount, $addend);
  }

  public function subtract(MoneyInterface $subtrahend)
  {
    $this->_requiresSameCurrency($subtrahend->currency, __METHOD__);
    if ($this->isZero()):
      $amount = $subtrahend->getAmount();
    else:
      $amount = $this->amount - $subtrahend->amount;
    endif;
    return $this->_newMoney($amount, $subtrahend);
  }

  /**
   * Return a new MoneyValue object that represents the negated monetary value
   * of this MoneyValue object.
   *
   * @return \mitgedanken\Monetary\MoneyInterface
   */
  public function negate()
  {
    return $this->_newMoney(-$this->amount);
  }

  public function multiply($multiplier)
  {
    $multiplier = $this->_pickValue($multiplier, __METHOD__);
    return $this->_newMoney($this->amount * $multiplier);
  }

  public function divide($divisor)
  {
    $divisor = $this->_pickValue($divisor, __METHOD__);
    if ($divisor == 0):
      throw new DivisionByZeroException();
    else:
      return $this->_newMoney($this->amount / $divisor);
    endif;
  }

  public function compare(MoneyInterface $other)
  {
    $this->_requiresSameCurrency($other->currency, __METHOD__);
    if ($this->amount == $other->amount):
      $compared = 0;
    else:
      $compared = ($this->amount < $other->amount) ? -1 : 1;
    endif;
    return $compared;
  }

  public function greaterThan(MoneyInterface $other)
  {
    return 1 == $this->compare($other);
  }

  public function lessThan(MoneyInterface $other)
  {
    return -1 == $this->compare($other);
  }

  public function isZero()
  {
    return 0 == $this->amount;
  }

  public function isPositive()
  {
    return 0 < $this->amount;
  }

  public function isNegative()
  {
    return 0 > $this->amount;
  }

  public function equals($object)
  {
    $equals = FALSE;
    if ($object instanceof MoneyInterface):
      $equals = $this->hasSameAmount($object);
    endif;
    return $equals;
  }

  /**
   * Validates an argument.
   *
   * @param integer|float $argument argument to validate.
   * @param string $name argument as string.
   * @param string $method method name which uses this function.
   * @throws InvalidArgumentException
   */
  protected function _requiresIntegerOrFloat($argument, $name, $method)
  {
    if (!\is_int($argument) && !\is_float($argument)):
      $type = \gettype($argument);
      $message = "Integer or float required for $name
        (but was $type; in $method)";
      throw new InvalidArgumentException($message);
    endif;
  }

  /**
   * Checks if the this MoneyValue object has the same Currency as the other.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $other
   * @param boolean $throw if
   * @return boolean <b>TRUE</b> if all requirement are met.
   * @throws DifferentCurrenciesException
   */
  protected function _requiresSameCurrency(Currency $other, $method)
  {
    if (!$this->currency->equals($other)):
      $message = "The same currency is required
        (expected: $this->currency; but was $other; in: $method)";
      throw new DifferentCurrenciesException($message);
    endif;
    return TRUE;
  }

  /**
   * Constructs a new MoneyValue object with the same currency as this object.
   *
   * @param integer $amount
   * @param \mitgedanken\Monetary\MoneyInterface $other
   * @return \mitgedanken\Monetary\MoneyInterface
   * @throws InvalidArgumentException
   */
  protected function _newMoney($amount, $other = NULL)
  {
    if (isset($other)):
      $newMoney = new Money($amount, $this->_pickCurrency($other));
    else:
      $newMoney = new Money($amount, $this->currency);
    endif;
    return $newMoney;
  }

  /**
   * Return an integer of <i>$given</i>. If it is a <i>MoneyValue</i>,
   * it Return its amount, only if it is Zero;
   * if it is an <i>integer</i>, it Return its value.
   *
   * @param integer|\mitgedanken\Monetary\MoneyInterface $given
   *                               Amount of <i>MoneyValue</i> or integer value.
   * @return integer amount of Money or the given integer value.
   * @throws InvalidArgumentException
   */
  protected function _pickValue($given, $method = NULL)
  {
    if ($given instanceof MoneyInterface):
      $method = (isset($method)) ? __METHOD__ : $method;
      $this->_requiresSameCurrency($given->currency, $method);
      $value = $given->getAmount();
    else:
      $this->_requiresIntegerOrFloat($given, '$denominator', $method);
      $value = $given;
    endif;
    return $value;
  }

  /**
   * Return the Currency object of the other object, if amount is 0.
   *
   * @return \mitgedanken\Monetary\Currency <i>The Currency object of the other object</i>,
   *                            if amount is 0;
   *                            <i>This Currency object</i></p> otherwise.
   */
  protected function _pickCurrency(MoneyInterface $other)
  {
    return ($this->amount == 0) ? $other->currency : $this->currency;
  }

  public function identify()
  {
    return "$this->amount $this->currency";
  }

  /**
   * Return this MoneyValue object as a string.
   *
   * @return string ("amount" "currency")
   * @see \mitgedanken\Monetary\Currency
   */
  public function __toString()
  {
    return "$this->amount $this->currency";
  }
}
