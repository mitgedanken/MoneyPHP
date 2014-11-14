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

namespace mitgedanken\Monetary\Abstracts;

use mitgedanken\Monetary\Interfaces\Money as InterfacesMoney;
use mitgedanken\Monetary\Currency;

/**
 * <i>Immutable</i><br/>
 * An implementation of <i>Interfaces\Money</i> by Fowler.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
abstract class Money implements InterfacesMoney {

    use Traits\Monetary;

    /**
     * This amount.
     *
     * @var mitgedanken\Monetary\Interfaces\Number
     */
    protected $number;

    /**
     * Holds the Currency object.
     * @var Currency
     */
    protected $currency;

    /**
     * Return its ammount.<br>
     *
     * @return integer Its ammount.
     */
    public function getAmount()
    {
        return $this->number->toInteger();
    }

    /**
     * Retuns its currency object.<br>
     *
     * @return Currency Its currency object.
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Compares this <i>Interfaces\Money</i> object to another with the same currency.<br>
     * If both monetary values are zero, they currency must not be the same.<br>
     *
     * @param \mitgedanken\Monetary\Money $money the other <i>Money</i>.
     * @return integer
     *    0 if they are equal,
     *    -1 if the other amount is greater or
     *    1 if the other amount is less.
     */
    public function compare(Interfaces\Money $money)
    {
        $this->_OnWrongCurrency_ThrowException($money);
        return $this->number->compare($money->number);
    }

    /**
     * Checks if this <i>Interfaces\Money</i> object is greater than the other.<br>
     *
     * @param  mixed $money
     * @return boolean
     *    <b>TRUE</b> if the value is greater than the other;
     *    <b>FALSE</b> otherwise.
     */
    public function greaterThan(Interfaces\Money $money)
    {
        return 1 == $this->compare($money);
    }

    /**
     * Checks if this <i>Interfaces\Money</i> object is less than the other.<br>
     *
     * @param  mixed $money
     * @return boolean <b>TRUE</b> if the value is less than the other.
     *                 <b>FALSE</b> otherwise.
     */
    public function lessThan(Interfaces\Money $money)
    {
        return -1 == $this->compare($money);
    }

    /**
     * Checks if this <i>Interfaces\Money</i> object is equal to the other.<br>
     *
     * @param  Interfaces\Money $money
     * @return boolean <b>TRUE</b> if the value is less than the other.
     *                 <b>FALSE</b> otherwise.
     */
    public function equally(Interfaces\Money $money)
    {
        return 0 == $this->compare($money);
    }

    /**
     * Return its identifier.
     *
     * @return string value
     */
    public function identify()
    {
        return \get_class() . '(' . $this->number->identify() . ')#'
                . $this->currency->identify();
    }

    /**
     * Indicates whether this object is "equal to" another.<br/>
     * This object is "equal to" another if it is an instance of <i>Interfaces\Money</i>
     * and if its value and currency are "equal to" the other one.<br/>
     *
     * @param mixed $object
     * @return boolean
     */
    public function equals($object)
    {
        $equals = FALSE;
        if ($object instanceof Interfaces\Money):
            $equals = 0 == strcmp($this->number, $object->number);
        endif;
        return $equals;
    }

    public function __toString()
    {
        return \get_class() . '(' . $this->number->toString() . $this->currency->getName() . ')';
    }

}
