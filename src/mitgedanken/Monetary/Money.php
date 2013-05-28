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
use mitgedanken\Monetary\NullCurrency;
use mitgedanken\Monetary\Exception\InvalidArgument,
    mitgedanken\Monetary\Exception\FunctionNotCallable,
    mitgedanken\Monetary\Exception\DivisionByZero,
    mitgedanken\Monetary\Exception\DifferentCurrencies;

/**
 * <i>Immutable</i><br/>
 * An implementation of <i>Money</i>.
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

  /** Round halves up. */
  const HALF_UP = \PHP_ROUND_HALF_UP;

  /** Round halves down. */
  const HALF_DOWN = \PHP_ROUND_HALF_DOWN;

  /** Round halves to even numbers. */
  const HALF_EVEN = \PHP_ROUND_HALF_EVEN;

  /** Round halves to odd numbers. */
  const HALF_ODD = \PHP_ROUND_HALF_ODD;

  /**
   * Holds the current rounding mode.
   *
   * @var integer [default: Money::HALF_EVEN]
   */
  protected static $rounding_mode = Money::HALF_EVEN;

  /**
   * Holds the current rounding precision.<br/>
   * Number of digits after the decimal point.
   *
   * @var integer [default: 4]
   */
  protected static $rounding_precision = 4;

  /**
   * Holds the current rounding algorithm.
   *
   * @var callable rounding algorithm.
   */
  protected static $rounding_algo;

  /**
   * Holds the current multiply algorithm.
   *
   * @var callable multiply algorithm.
   */
  protected static $multiply_algo;

  /**
   * Holds the current dividing algorithm.
   *
   * @var callable dividing algorithm.
   */
  protected static $divide_algo;

  /**
   * Holds the current allocation algorithm.
   *
   * @var callable allocation algorithm.
   */
  protected static $allocate_algo;

  /**
   * Constructs this <i>Money</i> object.
   *
   * @param integer $amount Its amount.
   * @param \mitgedanken\Monetary\Currency $currency Its currency.
   * @throws InvalidArgument
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
   * @example $fiveDollar = <i>Money</i>::USD(500, 'United States dollar');
   * @example $fiveDollar = <i>Money</i>::USD(500);
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

  public function hasSameAmount(MoneyInterface $other)
  {
    return $this->hasSameCurrency($other)
            && ($this->amount == $other->amount);
  }

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
   * Return a new <i>Money</i> object that represents the negated monetary value
   * of this <i>Money</i> object.
   *
   * @return \mitgedanken\Monetary\MoneyInterface
   */
  public function negate()
  {
    return $this->_newMoney(-$this->amount);
  }

  /**
   * <i>Override</i></p>
   * Return a new <i>Money</i> object that represents the monetary value
   * of this <i>Money</i> object multiplied by a given factor.
   *
   * @see _multiplyAlgo
   * @param  float|integer $multiplier
   * @param boolean $rounding [default: TRUE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\MoneyInterface
   * @throws InvalidArgument
   */
  public function multiply($multiplier, $rounding = TRUE)
  {
    $multiplier = $this->_pickValue($multiplier, __METHOD__);
    $result = $this->_multiplyAlgo($this->amount, $multiplier, $rounding);
    return $this->_newMoney($result);
  }

  /**
   * <i>Alogrithm</i></p>
   * Return a new Money object that represents the monetary value
   * of this Money object multiplied by a given factor.
   *
   * @param float|integer $factor.
   * @return float product; the default algorithm Return float.
   */
  private function _multiplyAlgo($factor1, $factor2, $rounding = TRUE)
  {
    if (isset(static::$multiply_algo)):
      $result = \call_user_func(
              static::$multiply_algo, $factor1, $factor2, $rounding);
    else:
      $result = $factor1 * $factor2;
    endif;

    if ($rounding && (1 < $factor1 || 1 < $factor2)):
      $result = $this->_roundingAlgo($result);
    endif;
    return $result;
  }

  public function divide($divisor, $rounding = TRUE)
  {
    $divisor = $this->_pickValue($divisor, __METHOD__);
    if ($divisor == 0):
      throw new DivisionByZero();
    else:
      $result = $this->_divideAlgo($this->amount, $divisor, $rounding);
      return $this->_newMoney($result);
    endif;
  }

  /**
   * <i>Alogrithm</i></p>
   * Divides its amount by a given divisor.
   *
   * @param float|integer $divisor
   * @param boolean $rounding [default: TRUE] rounds the result if </i>TRUE</i>
   * @return float quotient.
   * @throws DivisionByZero
   */
  private function _divideAlgo($numerand, $divisor, $rounding = TRUE)
  {
    if ($divisor == 0):
      throw new DivisionByZero();
    endif;

    if (isset(static::$divide_algo)):
      $result = \call_user_func(
              static::$divide_algo, $this->amount, $divisor, $rounding);
      if ($result === FALSE):
        throw new FunctionNotCallable(static::$divide_algo);
      endif;
    else:
      $result = $numerand / $divisor;
    endif;

    if ($rounding && $numerand != $divisor):
      $result = static::_roundingAlgo($result);
    endif;
    return $result;
  }

  /**
   * <i>Alogrithm</i></p>
   * Rounds the result. It calls the user's rounding algorithm or it use
   * the PHP function <i>\round</i></p>.
   *
   * @param float|integer $result
   * @param boolean $rounding [default: TRUE] rounds the result if </i>TRUE</i>
   * @return float the rounding result.
   */
  private function _roundingAlgo($result, $rounding = TRUE)
  {
    if ($rounding):
      if (isset(static::$rounding_algo)):
        $rounded = \call_user_func(static::$rounding_algo, $result,
                                   static::$rounding_precision,
                                   static::$rounding_mode);
      else:
        $rounded = \round($result, static::$rounding_precision,
                          static::$rounding_mode);
      endif;
    else:
      $rounded = $result;
    endif;
    return $rounded;
  }

  /**
   * Return a new Money object that represents the monetary value
   * of this Money object, allocated according to a list of ratio's.
   *
   * @param array $ratios the ratio's.
   * @param boolean $rounding [default: FALSE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\MoneyInterface[] the allocated monies.
   */
  public function allocate($ratios, $rounding = FALSE)
  {
    if (isset(static::$allocate_algo)):
      $result =
              \call_user_func(
              static::$allocate_algo, $this->amount, $ratios, $rounding);
    else:
      $result = $this->_allocateAlgo($ratios, $rounding);
    endif;
    return $result;
  }

  /**
   * <i>Alogrithm</i></p>
   * Return a new Money object that represents the monetary value
   * of this Money object, allocated according to a list of ratio's.
   *
   * @param array $ratios
   * @param boolean $rounding [default: FALSE] rounds the result if </i>TRUE</i>
   * @return array \mitgedanken\Monetary\SimpleMoney
   */
  private function _allocateAlgo($ratios, $rounding = FALSE)
  {
    $total = array_sum($ratios);
    $remainder = $this->amount;
    $results = array();

    foreach ($ratios as $ratio):
      $mulresult = $this->_multiplyAlgo($this->amount, $ratio, FALSE);
      $result = $this->_divideAlgo($mulresult, $total, FALSE);
      if ($rounding):
        $result = $this->_roundingAlgo($result, $rounding);
      endif;
      $results[] = $this->_newMoney($result);
      $remainder -= $result;
    endforeach;

    for ($i = 0; $i < $remainder; $i++):
      $results[$i]->amount++;
      $remainder++;
    endfor;
    return $results;
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
   * <i>Changes the state for all Money objects</i></p>
   * Sets the rounding mode and precision. If no mode or scale given, its
   * defaults will be used.
   *
   * @param int $mode [optional] rounding mode.
   * @param int $precision [optional] rounding precision.
   */
  public static function setRounding($mode = NULL, $precision = NULL)
  {
    if ($mode > 0):
      static::$rounding_mode = $mode;
    endif;
    if ($precision > 0):
      static::$rounding_precision = $precision;
    endif;
  }

  /**
   * Return information about the rounding; mode, precision, and if a user
   * algorithm is set or not.
   * </p>
   * ['mode'] and ['precision'] Return an integer value,
   * ['algorithm'] can return String('user') or String('default').
   *
   * @return array rounding states; mode, precision, algorithm.
   */
  public static function getRoundingStates()
  {
    $algo = isset(static::$rounding_algo) ? 'default' : 'user';
    $info = array('mode' => static::$rounding_mode,
        'precision' => static::$rounding_precision,
        'algorithm' => $algo);
    return $info;
  }

  /**
   * <i>Changes the state for all Money objects</i></p>
   * Sets the rounding algorithm.
   * </p>
   * If $algo is <i>NULL</i> the default will be used.
   *
   * @param \Closure $algo Rounding algorithm or <i>NULL</i>.
   */
  public static function setRoundingAlgo(\Closure $algo = NULL)
  {
    if (!\is_callable($algo)):
      throw new FunctionNotCallable('Function not callable; ' + $algo);
    endif;
    static::$rounding_algo = $algo;
  }

  /**
   * <i>Changes the state for all Money objects</i></p>
   * Sets the multiply algorithm.
   * </p>
   * If $algo is <i>NULL</i> the default will be used.
   *
   * @param \Closure $algo Rounding algorithm <i>NULL</i>.
   */
  public static function setMultiplyAlgo(\Closure $algo = NULL)
  {
    if (!\is_callable($algo)):
      throw new FunctionNotCallable('Function not callable; ' + $algo);
    endif;
    static::$multiply_algo = $algo;
  }

  /**
   * <i>Changes the state for all Money objects</i></p>
   * Sets the multiply algorithm.
   * </p>
   * If $algo is <i>NULL</i> the default will be used.
   *
   * @param \Closure $algo Rounding algorithm <i>NULL</i>.
   */
  public static function setDivideAlgo(\Closure $algo = NULL)
  {
    if (!\is_callable($algo)):
      throw new FunctionNotCallable('Function not callable; ' + $algo);
    endif;
    static::$divide_algo = $algo;
  }

  /**
   * Validates an argument.
   *
   * @param integer|float $argument argument to validate.
   * @param string $name argument as string.
   * @param string $method method name which uses this function.
   * @throws InvalidArgument
   */
  protected function _requiresIntegerOrFloat($argument, $name, $method)
  {
    if (!\is_int($argument) && !\is_float($argument)):
      $type = \gettype($argument);
      $message = "Integer or float required for $name
        (but was $type; in $method)";
      throw new InvalidArgument($message);
    endif;
  }

  /**
   * Checks if the this <i>Money</i> object has the same Currency as the other.
   *
   * @param \mitgedanken\Monetary\MoneyInterface $other
   * @param boolean $throw if
   * @return boolean <b>TRUE</b> if all requirement are met.
   * @throws DifferentCurrencies
   */
  protected function _requiresSameCurrency(Currency $other, $method)
  {
    if (!$this->currency->equals($other)):
      $message = "The same currency is required
        (expected: $this->currency; but was $other; in: $method)";
      throw new DifferentCurrencies($message);
    endif;
    return TRUE;
  }

  /**
   * Constructs a new <i>Money</i> object with the same currency as this object.
   *
   * @param integer $amount
   * @param \mitgedanken\Monetary\MoneyInterface $other
   * @return \mitgedanken\Monetary\MoneyInterface
   * @throws InvalidArgument
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
   * Return an integer of <i>$given</i>. If it is a <i><i>Money</i></i>,
   * it Return its amount, only if it is Zero;
   * if it is an <i>integer</i>, it Return its value.
   *
   * @param integer|\mitgedanken\Monetary\MoneyInterface $given
   *                               Amount of <i><i>Money</i></i> or integer value.
   * @return integer amount of Money or the given integer value.
   * @throws InvalidArgument
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
   * Return this <i>Money</i> object as a string.
   *
   * @return string ("amount" "currency")
   * @see \mitgedanken\Monetary\Currency
   */
  public function __toString()
  {
    return "$this->amount $this->currency";
  }
}
