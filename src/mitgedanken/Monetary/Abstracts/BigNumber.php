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

namespace mitgedanken\Monetary\Abstracts;

use mitgedanken\Monetary\Interfaces\Number;
use mitgedanken\Monetary\Exceptions\DivisionByZero;

/**
 * <i>Immutable</i><br/>
 * An implementation of <i>mitgedanken\Monetary\Interfaces\Number</i>.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
abstract class BigNumber implements Number {

    use \mitgedanken\Monetary\Traits\Monetary;

    /**
     * Holds its calculator.
     * @var \mitgedanken\Monetary\Interfaces\Calculator
     */
    protected $calculator;

    /**
     * Holds the value of this number.
     * @var string
     */
    protected $value;

    /**
     * Constructor.<br/>
     *
     * @param mixed $value
     */
    protected function construct($value, Configuration $configuration)
    {
        $this->value = $value;
        $this->calculator = $configuration['calculator'];
    }

    /**
     * It returns the result of the additon of its value and value of argument 1.<br>
     *
     * @param numeric|integer|float $number
     * @return numeric
     */
    public function add(Number $number)
    {
        return $this->calculator->add($this->value, $number);
    }

    /**
     * It returns the result of the subtraction of its value and value of argument 1.<br>
     *
     * @param  numeric|integer|float $number
     * @return numeric
     */
    public function subtract(Number $number)
    {
        return $this->calculator->subtract($this->value, $number);
    }

    /**
     * It returns the result of the multiplication of its value and value of argument 1.<br>
     *
     * @param  numeric|integer|float $number
     * @return numeric
     */
    public function multiply(Number $number)
    {
        return $this->calculator->multiply($this->value, $number);
    }

    /**
     * It returns the result of the divison of its value and value of argument 1.<br>
     *
     * @param  numeric|integer|float $number
     * @return numeric
     * @throws DivisionByZero If and only if value of divisor is 0.
     */
    public function divide(Number $number)
    {
        return $this->calculator->divide($this->value, $number);
    }

    /**
     * It returns a <i>BigNumber</i> with negated value of this object.<br>
     * of this <i>BigNumber</i> object.
     *
     * @return Interfaces\Money
     */
    function negate()
    {

    }

    /**
     * Compares this <i>BigNumber</i> object to another with the same currency.
     * If both monetary values are zero, they currency must not be the same.
     *
     * @param  mixed $number
     * @return integer
     *    0 if they are equal,
     *    -1 if the other amount is greater or
     *    1 if the other amount is less.
     */
    public function compare(Number $number)
    {
        return \strcmp($this->value, $number->value);
    }

    /**
     * Checks if this <i>BigNumber</i> object is greater than the other.<br>
     *
     * @param  mixed $number
     * @return boolean
     *    <b>TRUE</b> if the value is greater than the other;
     *    <b>FALSE</b> otherwise.
     */
    public function greaterThan(Number $number)
    {
        return 1 == $this->compare($number);
    }

    /**
     * Checks if this <i>BigNumber</i> object is less than the other.<br>
     *
     * @param  mixed $number
     * @return boolean <b>TRUE</b> if the value is less than the other.
     *                 <b>FALSE</b> otherwise.
     */
    public function lessThan(Number $number)
    {
        return -1 == $this->compare($number);
    }

    /**
     * Checks if this <i>BigNumber</i> object is equal to the other.<br>
     *
     * @param  mixed $number
     * @return boolean <b>TRUE</b> if the value is less than the other.
     *                 <b>FALSE</b> otherwise.
     */
    public function equally(Number $number)
    {
        return 0 == $this->compare($number);
    }

    /**
     * Checks if the amount is zero.
     *
     * @return boolean <b>TRUE</b> if the amount is zero;
     *                 <b>FALSE</b> otherwise.
     */
    public function isZero()
    {
        return 0 == $this->value;
    }

    /**
     * Checks if its value is negativ or not.<br>
     *
     * @return boolean
     *         If and only if its value is negative it will return <i>TRUE</i>;
     *         <i>FALSE</i> otherwise.
     */
    public function isNegative()
    {
        return 0 > $this->value;
    }

    /**
     * Checks if its value is positive or not.<br>
     *
     * @return boolean
     *         If and only if its value is negative it will return <i>TRUE</i>;
     *         <i>FALSE</i> otherwise.
     */
    public function isPositve()
    {
        return 0 <= $this->value;
    }

    /**
     * It returns its value as numeric.
     *
     * @return numeric its value as string.
     */
    public function toString()
    {
        return '' . $this->value;
    }

    /**
     * Return its identifier.
     *
     * @return string value
     */
    public function identify()
    {
        return \get_class() . '(' . $this->value . ')';
    }

    /**
     * Indicates whether this object is "equal to" another.<br/>
     * This object is "equal to" another if it is an instance of <i>BigNumber</i>
     * and if its value is "equal to" the other one.<br/>
     *
     * @param mixed $object
     * @return boolean
     */
    public function equals($object)
    {
        $equals = FALSE;
        if ($object instanceof Number):
            $equals = 0 == strcmp($this->value, $object->value);
        endif;
        return $equals;
    }

    /**
     * Return this <i>BigNumber</i> object as a string.
     *
     * @return string ("amount" "currency")
     * @see \mitgedanken\Monetary\Currency
     */
    public function __toString()
    {
        return \get_class() . '(' . $this->value . ')';
    }

}
