<?php

/*
 * Copyright (C) 2014 Sascha
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
 *
 * @author Sascha
 */
interface Money extends Monetary {

    /**
     * Return its ammount.<br>
     *
     * @return integer Its ammount.
     */
    public function getAmount();

    /**
     * Retuns its currency object.<br>
     *
     * @return Currency Its currency object.
     */
    public function getCurrency();

    /**
     * Adds the amount of a given <i>Money</i><br/>
     * @see Money::add
     *
     * @param Money $addend
     * @return Money
     */
    public function add(Money $addend);

    /**
     * It returns a new <i>Money</i> object that represents the monetary value
     * of the difference of this <i>Money</i> object and another.
     * @see Money::subtract
     *
     * @param Money $subtrahend
     * @return \mitgedanken\Monetary\Money
     * @throws DifferentCurrencies
     *         If and only if the currency of the given object not
     *         the same as the subtype of this abstract class.
     */
    public function subtract(Money $subtrahend);

    /**
     * Checks if this <i>Money</i> object is equal to the other.<br>
     *
     * @param  Money $money
     * @return boolean <b>TRUE</b> if the value is less than the other.
     *                 <b>FALSE</b> otherwise.
     */
    public function equally(Money $money);
}
