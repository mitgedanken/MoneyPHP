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

use mitgedanken\Monetary\Exceptions\DivisionByZero,
    mitgedanken\Monetary\Exceptions\DifferentCurrencies,
    mitgedanken\Monetary\Abstracts\Money as AbstractsMoney;

/**
 * <i>Immutable</i><br/>
 * An implementation of <i>Money</i>.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class Money extends AbstractsMoney
{

  use \mitgedanken\Monetary\Traits\Monetary;

  /**
   * <p><i>Extension</i></p>
   * Convenience factory method.<br/>
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
      $newMoney = new Money($arguments[0], new Currency($code, $arguments[1]));
    else:
      $newMoney = new Money($arguments[0], new Currency($code));
    endif;
    return $newMoney;
  }

  /**
   * <p><i>Extension</i></p>
   * Checks if this <i>Money</i> has the same amount as the other.<br/>
   * <i>The objects must have the same currency.</i></p>
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $other
   * @return boolean <b>TRUE</b> if this monetary value has
   *                 the same amount and the same currency as the other;
   *                 <b>FALSE</b> otherwise.
   */
  public function hasSameAmount(AbstractsMoney $other)
  {
    return $this->hasSameCurrency($other) && ($this->amount == $other->amount);
  }

  /**
   * <p><i>Extension</i></p>
   * Checks if its currency is "equal to" the $currency argument.<br/>
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $other
   * @return boolean <b>TRUE</b> if this monetary value has
   *                 the same currency as the other;
   *                 <b>FALSE</b> otherwise.
   */
  public function hasSameCurrency(AbstractsMoney $other)
  {
    return $this->currency->equals($other->currency);
  }

  /**
   * <p><i>Implimentation</i></p>
   * Adds the amount of a given <i>Money</i><br/>
   * @see \mitgedanken\Monetary\Abstracts\Money::add
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $addend
   * @return \mitgedanken\Monetary\Abstracts\Money
   */
  public function add(AbstractsMoney $addend)
  {
    parent::_testIfHaveSameCurrency($addend);
    $result = Algorithms::add($this->amount, $addend->amount, $this->scale);
    \settype($result, 'integer');
    return $this->_newMoney($result);
  }

  /**
   * <p><i>Implimentation</i></p>
   * <br/>
   * @see \mitgedanken\Monetary\Abstracts\Money::subtract
   *
   * @param \mitgedanken\Monetary\Abstracts\Money $subtrahend
   * @param integer $rounding
   * @return type
   */
  public function subtract(AbstractsMoney $subtrahend)
  {
    parent::_testIfHaveSameCurrency($subtrahend);
    $result = Algorithms::subtract($this->amount, $subtrahend->amount, $this->scale);
    \settype($result, 'integer');
    return $this->_newMoney($result);
  }

  /**
   * <p><i>Implimentation</i></p>
   * <br/>
   * @see \mitgedanken\Monetary\Abstracts\Money::subtract
   *
   * @param numeric|\mitgendanken\Monetary\Abtracts\Money $multiplier
   * @return \mitgendanken\Monetary\Abtracts\Money
   */
  public function multiply($multiplier)
  {
    $multiplier = parent::_pickValue($multiplier, __METHOD__);
    $result     = Algorithms::multiply($this->amount, $multiplier, $this->scale);
    \settype($result, 'integer');
    return $this->_newMoney($result);
  }

  /**
   * <p><i>Implimentation</i></p>
   * Divides.<br/>
   * @see \mitgedanken\Monetary\Abstracts\Money::divide
   *
   * @param numeric|\mitgendanken\Monetary\Abtracts\Money $divisor
   * @return \mitgendanken\Monetary\Abtracts\Money
   * @throws DivisionByZero Only if the value of $divisor is 0.
   */
  public function divide($divisor)
  {
    $divisor = parent::_pickValue($divisor, __METHOD__);
    \settype($divisor, 'integer');
    if ($divisor == 0):
      throw new DivisionByZero();
    else:
      $result = Algorithms::divide($this->amount, $divisor, $this->scale);
      \settype($result, 'integer');
      return $this->_newMoney($result);
    endif;
  }

  /**
   * <p><i>Implimentation</i></p>
   * Contructs a money with the currency of this object.<br/>
   * @see \mitgedanken\Monetary\Abstracts\Money::_newMoney
   *
   * @param numeric $amount
   * @param \mitgedanken\Monetary\Abstracts\Money $other
   * @return \mitgedanken\Monetary\Abstracts\Money
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

}
