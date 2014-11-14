<?php

/*
 * Copyright (C) 2014 Sascha Tasche <hallo@mitgedanken.de>
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
 * Description of Calculator
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
interface Calculator
{

  /**
   * Adds numbers.<br>
   *
   * @param numeric|integer|float $value
   * @return numeric
   * @throws UnexpectedValue
   *         If and only if $value is not a supported type.
   */
  function add($left_operand, $right_operand);

  /**
   * Substract numbers.<br>
   *
   * @param  numeric|integer|float $value
   * @return numeric
   * @throws UnexpectedType
   */
  function subtract($left_operand, $right_operand);

  /**
   * Multiply numbers.<br>
   *
   * @param  numeric|integer|float $value
   * @return numeric
   * @throws UnexpectedType
   */
  function multiply($left_operand, $right_operand);

  /**
   * Divides numbers.<br>
   * Implementors must catch 'Divison by zero'.<br>
   *
   * @param  numeric|integer|float $value
   * @return numeric
   * @throws DifferentCurrencies
   *         If and only if the currency of the given object not
   *         the same as the subtype of this abstract class.
   * @throws DivisionByZero If and only if value of divisor is 0.
   */
  function divide($left_operand, $right_operand);

  /**
   * Remainder of the division of numbers.<br>
   *
   * @param  numeric|integer|float $value
   * @return array an array, with the first element being [n/d]
   *        (the integer result of the division) and the
   *        second being (n - [n/d] * d) (the remainder of the division).
   */
  function getRemainder($left_operand, $right_operand);

  /**
   * Find highest value.<br>
   * If the first and only parameter is an array, max() returns the highest
   * value in that array. If at least two parameters are provided, max()
   * returns the biggest of these values.<br>
   *
   * @param array_or_parameters $_
   * @return numeric
   */
  function max($_);

  /**
   * Find lowest value.<br>
   * If the first and only parameter is an array, max() returns the highest
   * value in that array. If at least two parameters are provided, max()
   * returns the biggest of these values.<br>
   *
   * @param array_or_parameters $_
   * @return numeric
   */
  function min($_);
}
