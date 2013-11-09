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

namespace mitgedanken\Monetary\Classes;

/**
 * A set of algorithms.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class Algorithms
{

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
  protected static $rounding_mode = Algorithms::HALF_EVEN;

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
   * Holds the current addition algorithm.
   *
   * @var callable addition algorithm.
   */
  protected static $addition_algo;

  /**
   * Holds the current subtract algorithm.
   *
   * @var callable subtract algorithm.
   */
  protected static $subtract_algo;

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
   * Adds.
   *
   * @param numeric $addend1
   * @param numeric $addend2
   * @param integer $scale
   * @return numeric
   */
  public static function add($addend1, $addend2, $scale)
  {
    if (!is_numeric($addend1)):
      $message = 'Argument 1 is not numeric; (was: ' + gettype($scale) + ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (!is_numeric($addend2)):
      $message = 'Argument 2 is not numeric; (was: ' . gettype($scale) . ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (!is_integer($scale)):
      $message = 'Argument 3 is not an integer; (was: ' . gettype($scale) . ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (isset(static::$addition_algo)):
      $result = call_user_func(static::$addition_algo, $addend1, $addend2, $scale);
    else:
      $result = \bcadd($addend1, $addend2, $scale);
    endif;
    return $result;
  }

  /**
   * Subtracts.
   *
   * @param numeric $minuend
   * @param numeric $subtrahend
   * @param integer $scale
   * @return numeric
   */
  public static function subtract($minuend, $subtrahend, $scale)
  {
    if (!is_numeric($minuend)):
      $message = 'Argument 1 is not numeric; (was: ' + gettype($scale) + ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (!is_numeric($subtrahend)):
      $message = 'Argument 2 is not numeric; (was: ' + gettype($scale) + ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (!is_integer($scale)):
      $message = 'Argument 3 is not an integer; (was: ' + gettype($scale) + ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (isset(static::$subtract_algo)):
      $result = call_user_func(static::$subtract_algo, $minuend, $subtrahend, $scale);
    else:
      $result = \bcsub($minuend, $subtrahend, $scale);
    endif;
    return $result;
  }

  /**
   * Multiplys.
   *
   * @param numeric $multiplicand
   * @param numeric $multiplier
   * @param integer $scale
   * @return numeric
   */
  public static function multiply($multiplicand, $multiplier, $scale)
  {
    if (!is_numeric($multiplicand)):
      $message = 'Argument 1 is not numeric; (was: ' + gettype($scale) + ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (!is_numeric($multiplier)):
      $message = 'Argument 2 is not numeric; (was: ' + gettype($scale) + ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (!is_integer($scale)):
      $message = 'Argument 3 is not an integer; (was: ' + gettype($scale) + ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (isset(static::$multiply_algo)):
      $result = call_user_func(static::$multiply_algo, $multiplicand, $multiplier, $scale);
    else:
      $result = bcmul($multiplicand, $multiplier, $scale);
    endif;
    return $result;
    ;
  }

  /**
   * Divides.
   *
   * @param numeric $dividend
   * @param numeric $divisor
   * @param integer $scale
   * @return numeric
   * @throws \mitgedanken\Monetary\Exceptions\DivisionByZero
   */
  public static function divide($dividend, $divisor, $scale)
  {
    if (!is_numeric($dividend)):
      $message = 'Argument 1 is not numeric; (was: ' + gettype($scale) + ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (!is_numeric($divisor)):
      $message = 'Argument 2 is not numeric; (was: ' + gettype($scale) + ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (!is_integer($scale)):
      $message = 'Argument 3 is not an integer; (was: ' + gettype($scale) + ')';
      throw new \mitgedanken\Monetary\Exceptions\InvalidArgument($message);
    endif;
    if (isset(static::$divide_algo)):
      $result = call_user_func(static::$divide_algo, $dividend, $divisor, $scale);
    else:
      $result = bcdiv($dividend, $divisor, $scale);
      if ($result === NULL):
        throw new \mitgedanken\Monetary\Exceptions\DivisionByZero();
      endif;
    endif;
    return $result;
  }

  /**
   * Sets the addition algorithm.
   *
   * @param \Closure $algo
   */
  public static function setAdditionAlgo(\Closure $algo = NULL)
  {
    static::$addition_algo = $algo;
  }

  /**
   * Sets the subtraction algorithm.
   *
   * @param \Closure $algo
   */
  public static function setSubtractionAlgo(\Closure $algo = NULL)
  {
    static::$subtract_algo = $algo;
  }

  /**
   * Sets the multiplication algorithm.
   *
   * @param \Closure $algo
   */
  public static function setMultiplicationAlgo(\Closure $algo = NULL)
  {
    static::$multiply_algo = $algo;
  }

  /**
   * Sets the division algorithm.
   *
   * @param \Closure $algo
   */
  public static function setDivisionAlgo(\Closure $algo = NULL)
  {
    static::$divide_algo = $algo;
  }

  /**
   * Rounds a value.
   *
   * @param numeric $value
   * @return numeric
   */
  public static function rounding($value, $precision)
  {
    if (isset(static::$rounding_algo_algo)):
      $result = call_user_func(static::$rounding_algo_algo, $value, $precision);
    else:
      $result = round($value, $precision);
    endif;
    return $result;
  }

  /**
   * Returns a new <i>Money</i> object that represents the monetary value
   * whitch is allocated according to a list of ratio's.
   *
   * @param array $ratios the ratio's.
   * @param numeric $number
   * @param boolean $rounding [default: FALSE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\Abstracts\Money[] the allocated monies.
   */
  public static function allocate($ratios, $number, $rounding = FALSE)
  {
    if (isset(static::$allocate_algo)):
      $result = \call_user_func(static::$allocate_algo, $number, $ratios, $rounding);
    else:
      \settype($number, 'integer');
      $countRatios = count($ratios);
      $total       = array_sum($ratios);
      $remainder   = $number;
      $results     = new \SplFixedArray($countRatios);
      for ($i = 0; $i < $countRatios; $i += 1):
        $mulresult   = \bcmul($number, $ratios[$i], $rounding);
        $result      = $this->divideAlgo($mulresult, $total, $rounding);
        $results[$i] = $result;
        $remainder -= $results[$i]->amount;
      endfor;
      for ($i = 0; $i < $remainder; $i++):
        $results[$i]->amount++;
      endfor;
      return $results;
    endif;
    return $result;
  }

}
