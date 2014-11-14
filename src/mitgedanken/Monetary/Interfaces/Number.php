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
 * <i>Immutable</i><br/>
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
interface Number extends Monetary {

    /**
     * It returns a new <i>BigNumber</i> object that holds a value
     * added by a given value.<br>
     *
     * @param integer $value
     * @return mixed
     * @throws UnexpectedValue
     *         If and only if $value is not a supported type.
     */
    function add(Number $value);

    /**
     * It returns a new <i>BigNumber</i> object that holds a value
     * subtracted by a given value.<br>
     *
     * @param  Number $value
     * @return mixed
     * @throws UnexpectedType
     */
    function subtract(Number $value);

    /**
     * It returns a new <i>BigNumber</i> object that holds a value
     * multiplyed by a given value.<br>
     *
     * @param  Number $value
     * @return mixed
     * @throws UnexpectedType
     */
    function multiply(Number $value);

    /**
     * It returns a new <i>BigNumber</i> object that holds a value
     * divided by a given value.<br>
     *
     * @param  Number $value
     * @return mixed
     * @throws DifferentCurrencies
     *         If and only if the currency of the given object not
     *         the same as the subtype of this abstract class.
     * @throws DivisionByZero If and only if value of divisor is 0.
     */
    function divide(Number $value);

    /**
     * It returns a <i>BigNumber</i> with negated value of this object.
     * of this <i>Interfaces\Money</i> object.
     *
     * @return Interfaces\Money
     */
    function negate();

    /**
     * Compares this <i>Interfaces\Money</i> object to another with the same currency.
     * If both monetary values are zero, they currency must not be the same.
     *
     * @param  integer $value
     * @return integer
     *    0 if they are equal,
     *    -1 if the other amount is greater or
     *    1 if the other amount is less.
     * @throws InvalidArgumentException
     */
    function compare(Number $value);

    /**
     * Checks if this <i>Interfaces\Money</i> object is greater than the other.
     *
     * @param  integer $value
     * @return boolean
     *    <b>TRUE</b> if the value is greater than the other;
     *    <b>FALSE</b> otherwise.
     * @throws InvalidArgumentException
     */
    function greaterThan(Number $value);

    /**
     * Checks if this <i>Interfaces\Money</i> object is less than the other.
     *
     * @param  integer $value
     * @return boolean <b>TRUE</b> if the value is less than the other.
     *                 <b>FALSE</b> otherwise.
     * @throws InvalidArgumentException
     */
    function lessThan(Number $value);

    /**
     * Checks if the amount is zero.
     *
     * @return boolean <b>TRUE</b> if the amount is zero;
     *                 <b>FALSE</b> otherwise.
     */
    function isZero();

    /**
     * Checks if its value is negativ or not.<br>
     *
     * @return boolean
     *         If and only if its value is negative it will return <i>TRUE</i>;
     *         <i>FALSE</i> otherwise.
     */
    function isNegative();

    /**
     * Checks if its value is positive or not.<br>
     *
     * @return boolean
     *         If and only if its value is negative it will return <i>TRUE</i>;
     *         <i>FALSE</i> otherwise.
     */
    function isPositve();

    /**
     * Indicates whether this object is "equal to" another.<br/>
     * This object is "equal to" another if this is an instance of
     * <i>BigNumber</i> and if their values are "equal to".<br/>
     *
     * @param mixed $object
     * @return boolean
     */
    function equals($object);

    /**
     * Return its identifier.
     *
     * @return string "(amount) (currency)"
     */
    function identify();

    /**
     * Return its value as string.<br>
     */
    function toString();

    /**
     * Return this <i>Interfaces\Money</i> object as a string.
     *
     * @return string ("amount" "currency")
     * @see \mitgedanken\Monetary\Currency
     */
    function __toString();
}
