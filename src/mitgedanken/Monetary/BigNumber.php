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

namespace mitgedanken\Monetary;

/**
 * <i>Immutable</i><br/>
 * An extention of <i>mitgedanken\Monetary\Abstracts\BigNumber</i>.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class BigNumber extends Abstracts\BigNumber {

    /**
     * Constructor.<br/>
     *
     * @param mixed $value
     * @throws Runtime
     *         If and only if the requirements not met.
     * @throws UnexpectedValue
     *         If and only if <i>$number<i/> not an integer, float or a numeric.
     */
    public function __construct($value, Configuration $configuration)
    {
        parent::construct($value, $configuration);
    }

    /**
     * It returns a new <i>BigNumber</i> object that holds a value
     * added by a given value.<br>
     *
     * @param numeric $number
     * @return numeric_string
     * @throws UnexpectedValue
     *         If and only if $number is not a supported type.
     */
    public function add(Interfaces\Number $number)
    {
        return new BigNumber(parent::addNumeric($number->value));
    }

    /**
     * It returns a new <i>BigNumber</i> object that holds a value
     * subtracted by a given value.<br>
     *
     * @param  numeric $number
     * @return numeric_string
     * @throws UnexpectedType
     */
    public function subtract(Interfaces\Number $number)
    {
        return new BigNumber(parent::subtractNumeric($number->value));
    }

    /**
     * It returns a new <i>BigNumber</i> object that holds a value
     * multiplyed by a given value.<br>
     *
     * @param  numeric $number
     * @return numeric_string
     * @throws UnexpectedType
     */
    public function multiply(Interfaces\Number $number)
    {
        return new BigNumber(parent::multiplyNumeric($number->value));
    }

    /**
     * It returns a new <i>BigNumber</i> object that holds a value
     * divided by a given value.<br>
     *
     * @param  numeric $number
     * @return numeric_string
     * @throws DifferentCurrencies
     *         If and only if the currency of the given object not
     *         the same as the subtype of this abstract class.
     * @throws DivisionByZero If and only if value of divisor is 0.
     */
    public function divide(Interfaces\Number $number)
    {
        return new BigNumber(parent::divideNumeric($number->value));
    }

    /**
     * It returns a <i>BigNumber</i> with negated value of this object.
     * of this <i>BigNumber</i> object.
     *
     * @return Interfaces\Money
     */
    public function negate()
    {
        return new BigNumber(-$this->value);
    }

    /**
     * <p><i>Extension</i></p>
     * It returns (n - [n/d] * d).<br>
     *
     * @param  numeric $number
     * @return string
     */
    public function getRemainder(Number $number)
    {
        $this->_validateParam($number);
        return fmod($this->value, $number);
    }

    /**
     * <p><i>Extension</i></p>
     * Divides its value and given value and get they quotient and remainder.<br>
     *
     * @param  numeric $number
     * @return array an array, with the first element being [n/d]
     *        (the integer result of the division) and the
     *        second being (n - [n/d] * d) (the remainder of the division).
     */
    public function getQuotientAndRemainder(Number $number)
    {
        $this->_validateParam($number);
        $resultArray = [];
        /* Why \intval(..) instead of \floor(..)?
         * With \floor(..) the result of -1.6 will be -2.
         * That's not what we wanted to have.
         */
        $resultArray[0] = \intval($this->value / $number);
        $resultArray[1] = fmod($this->value, $number);
        return $resultArray;
    }

    /**
     * <p><i>Extension</i></p>
     * Return a float which represents rounded value of this object.<br>
     *
     * @param integer $precision [default: 0] Precision of rounding.
     * @param $roundingMethod [default: PHP_ROUND_HALF_DOWN] Rounding behavior.
     * @return numeric_string
     */
    public static function round($precision, $roundingMethod)
    {
        return round($this->value, $precision, $roundingMethod);
    }

}
