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
use mitgedanken\Monetary\Exception\InvalidArgumentException,
    mitgedanken\Monetary\Exception\FunctionNotCallableException,
    mitgedanken\Monetary\Exception\DivisionByZeroException;

/**
 * <i>Immutable</i><br/>
 * An implementation of MoneyValue.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
class MoneyExtended extends Money {
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
  private static $rounding_mode = MoneyExtended::HALF_EVEN;

  /**
   * Holds the current rounding precision.<br/>
   * Number of digits after the decimal point.
   *
   * @var integer [default: 4]
   */
  private static $rounding_precision = 4;

  /**
   * Holds the current rounding algorithm.
   *
   * @var callable rounding algorithm.
   */
  private static $rounding_algo;

  /**
   * Holds the current multiply algorithm.
   *
   * @var callable multiply algorithm.
   */
  private static $multiply_algo;

  /**
   * Holds the current dividing algorithm.
   *
   * @var callable dividing algorithm.
   */
  private static $divide_algo;

  /**
   * Holds the current allocation algorithm.
   *
   * @var callable allocation algorithm.
   */
  private static $allocate_algo;

  /**
   * <i>Override</i></p>
   * Return a new MoneyValue object that represents the monetary value
   * of this MoneyValue object multiplied by a given factor.
   *
   * @see _multiplyAlgo
   * @param  float|integer $multiplier
   * @param boolean $rounding [default: TRUE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\MoneyInterface
   * @throws InvalidArgumentException
   */
  public function multiply($multiplier, $rounding = TRUE)
  {
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

  /**
   * <i>Override</i></p>
   * Return a new Money object that represents the monetary value
   * of this Money object divided by a given divisor.
   *
   * @param  integer|\mitgedanken\Monetary\MoneyInterface $divisor
   * @param boolean $rounding [default: TRUE] rounds the result if </i>TRUE</i>
   * @return \mitgedanken\Monetary\MoneyInterface
   * @throws InvalidArgumentException
   * @throws DivisionByZeroException
   * @see _divideAlgo
   */
  public function divide($divisor, $rounding = TRUE)
  {
    $result = $this->_divideAlgo($this->amount, $divisor, $rounding);
    return $this->_newMoney($result);
  }

  /**
   * <i>Alogrithm</i></p>
   * Divides its amount by a given divisor.
   *
   * @param float|integer $divisor
   * @param boolean $rounding [default: TRUE] rounds the result if </i>TRUE</i>
   * @return float quotient.
   * @throws DivisionByZeroException
   */
  private function _divideAlgo($numerand, $divisor, $rounding = TRUE)
  {
    if ($divisor == 0):
      throw new DivisionByZeroException();
    endif;

    if (isset(static::$divide_algo)):
      $result = \call_user_func(
              static::$divide_algo, $this->amount, $divisor, $rounding);
      if ($result === FALSE):
        throw new FunctionNotCallableException(static::$divide_algo);
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
                static::$rounding_precision, static::$rounding_mode);
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
              static::$allocate_algo, static::$amount, $ratios, $rounding);
    else:
      if ($ratios instanceof \SplFixedArray):
        $result = $this->_allocateAlgoFixedArray($ratios, $rounding);
      else:
        $result = $this->_allocateAlgoArray($ratios, $rounding);
      endif;
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
  private function _allocateAlgoArray(array $ratios, $rounding = FALSE)
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

  /**
   * <i>Alogrithm</i></p>
   * Return a new Money object that represents the monetary value
   * of this Money object, allocated according to a list of ratio's.
   *
   * @param \SplFixedArray $ratios
   * @param boolean $rounding [default: TRUE] rounds the result if </i>TRUE</i>
   * @return array \mitgedanken\Monetary\SimpleMoney
   */
  private function _allocateAlgoFixedArray(\SplFixedArray $ratios,
          $rounding = FALSE)
  {
    $total = array_sum($ratios);
    $remainder = $this->amount;
    $results = new \SplFixedArray($ratios->getSize());

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
    endfor;

    return $results->toArray();
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
      throw new FunctionNotCallableException('Function not callable; ' + $algo);
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
      throw new FunctionNotCallableException('Function not callable; ' + $algo);
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
      throw new FunctionNotCallableException('Function not callable; ' + $algo);
    endif;
    static::$divide_algo = $algo;
  }

  /**
   * <i>Override</i>
   * Constructs a new Money object with the same currency as this Money object.
   *
   * @param integer $amount
   * @return \mitgedanken\Monetary\MoneyExtended
   * @throws InvalidArgumentException
   */
  protected function _newMoney($amount, $object = NULL)
  {
    if (isset($object)):
      $currency = $this->_pickCurrency($object);
      $newMoney = new MoneyExtended($amount, $currency);
    else:
      $currency = ($amount == 0) ? new NullCurrency() : $this->currency;
      $newMoney = new MoneyExtended($amount, $currency);
    endif;
    return $newMoney;
  }

}
