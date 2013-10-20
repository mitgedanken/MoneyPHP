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

namespace mitgedanken\Monetary;
use mitgedanken\Monetary\Exceptions\DivisionByZero;

/**
 * <i>Immutable</i><br/>
 * An implementation of <i>Money</i>.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class Money extends Abstracts\Money {

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
   * Holds the current rounding algorithm.<br/>
   * Arguments: ($input, $precision, $mode).
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
   * Convenience factory method.
   *
   * @example $fiveDollar = <i>Money</i>::USD(500, 'United States Dollar');
   * @example $fiveDollar = <i>Money</i>::USD(500);
   *
   * @param string $code
   * @param array $arguments 0:string, currency code; 1:string, display name;
   * @return \mitgedanken\Monetary\Money
   */
  public static function __callStatic($code, $arguments)
  {
    $cargs = \count($arguments);
    if (2 == $cargs):
      $money = new Money(
              $arguments[0], new Currency($code, $arguments[1]));
    else:
      $money = new Money($arguments[0], new Currency($code));
    endif;
    return $money;
  }

  /**
   * Checks if this <i>Money</i> has the same amount as the other.
   * <i>The objects must have the same currency.</i></p>
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $other
   * @return boolean <b>TRUE</b> if this monetary value has
   *                 the same amount and the same currency as the other;
   *                 <b>FALSE</b> otherwise.
   */
  public function hasSameAmount(Abstracts\Money $other)
  {
    return $this->hasSameCurrency($other)
            && ($this->amount == $other->amount);
  }

  /**
   * Checks if its currency is "equal to" the $currency argument.
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $other
   * @return boolean <b>TRUE</b> if this monetary value has
   *                 the same currency as the other;
   *                 <b>FALSE</b> otherwise.
   */
  public function hasSameCurrency(Abstracts\Money $other)
  {
    return $this->currency->equals($other->currency);
  }

  /**
   * Return a new <i>Money</i> object that represents the negated monetary value
   * of this <i>Money</i> object.
   *
   * @return \mitgedanken\Monetary\Abstracts\Money
   */
  public function negate()
  {
    return $this->_newMoney(-$this->amount);
  }

  /**
   * <i>Override</i>
   */
  public function multiply($multiplier, $rounding = FALSE)
  {
    $multiplier = parent::_pickValue($multiplier, __METHOD__);
    $result = $this->_multiplyAlgo($this->amount, $multiplier, $rounding);
    return $this->_newMoney($result);
  }

  /**
   * <i>Alogrithm</i><br/>
   * Return a new Money object that represents the monetary value
   * of this Money object multiplied by a given factor.
   *
   * @param float|integer $factor.
   * @return float product; the default algorithm Return float.
   */
  private function _multiplyAlgo($factor1, $factor2, $rounding = FALSE)
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

  /**
   * <i>Override</i>
   */
  public function divide($divisor, $rounding = FALSE)
  {
    $divisor = parent::_pickValue($divisor, __METHOD__);
    if ($divisor == 0):
      throw new DivisionByZero();
    else:
      $result = $this->_divideAlgo($this->amount, $divisor, $rounding);
      return $this->_newMoney($result);
    endif;
  }

  /**
   * <i>Alogrithm</i><br/>
   * Divides its amount by a given divisor.
   *
   * @param float|integer $divisor
   * @param boolean $rounding [default: TRUE] rounds the result if </i>TRUE</i>
   * @return float quotient.
   * @throws DivisionByZero
   */
  private function _divideAlgo($numerand, $divisor, $rounding = FALSE)
  {
    if ($numerand == $divisor):
      $result = 1;
    elseif (0 == $numerand):
      $result = 0;
    else:
      if (isset(static::$divide_algo)):
        $result = \call_user_func(
                static::$divide_algo, $this->amount, $divisor, $rounding);
      elseif ($rounding):
        $result = $numerand / $divisor;
        $this->_roundingAlgo($result);
      else:
        $result = $numerand / $divisor;
      endif;
    endif;
    return $result;
  }

  /**
   * <i>Alogrithm</i><br/>
   * Rounds the result. It calls the user's rounding algorithm or it use
   * the PHP function <i>\round</i></p>.
   *
   * @param float|integer $input
   * @param boolean $rounding [default: TRUE] rounds the result if </i>TRUE</i>
   * @return float the rounding result.
   */
  private function _roundingAlgo($input, $rounding = TRUE)
  {
    if ($rounding):
      if (isset(static::$rounding_algo)):
        $rounded =
                \call_user_func(static::$rounding_algo, $input,
                                static::$rounding_precision,
                                static::$rounding_mode);
      else:
        $rounded = \round($input, static::$rounding_precision,
                          static::$rounding_mode);
      endif;
    else:
      $rounded = $input;
    endif;
    return $rounded;
  }

  /**
   * <i>Changes the state of all Money objects</i></p>
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
   * <i>Changes the state of all Money objects</i></p>
   * Sets the rounding algorithm.
   * </p>
   * If $algo is <i>NULL</i> the default will be used.
   *
   * @param \Closure $algo Rounding algorithm or <i>NULL</i>.
   */
  public static function setRoundingAlgo(\Closure $algo = NULL)
  {
    static::$rounding_algo = $algo;
  }

  /**
   * <i>Changes the state of all Money objects</i></p>
   * Sets the multiply algorithm.
   * </p>
   * If $algo is <i>NULL</i> the default will be used.
   *
   * @param \Closure $algo Rounding algorithm <i>NULL</i>.
   */
  public static function setMultiplyAlgo(\Closure $algo = NULL)
  {
    static::$multiply_algo = $algo;
  }

  /**
   * <i>Changes the state of all Money objects</i></p>
   * Sets the multiply algorithm.
   * </p>
   * If $algo is <i>NULL</i> the default will be used.
   *
   * @param \Closure $algo Rounding algorithm <i>NULL</i>.
   */
  public static function setDivideAlgo(\Closure $algo = NULL)
  {
    static::$divide_algo = $algo;
  }

  protected function _newMoney($amount, $other = NULL)
  {
    if (isset($other)):
      $newMoney = new Money($amount, $this->_pickCurrency($other));
    else:
      $newMoney = new Money($amount, $this->currency);
    endif;
    return $newMoney;
  }
}
