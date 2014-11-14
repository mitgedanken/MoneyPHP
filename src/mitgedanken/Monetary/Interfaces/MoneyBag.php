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
interface MoneyBag extends Money, \Countable {

    /**
     * <p><i>Extension</i></p>
     * Convert a <i>MoneyBag</i> object to a <i>Money</i> object.
     * @see Interfaces\Money
     *
     * @param Interfaces\Money $money
     * @param integer $scale
     * @param \mitgedanken\Monetary\CurrencyPairRepository $repository
     * @param \mitgedanken\Monetary\MoneyStorage $storage
     * @return \mitgedanken\Monetary\MoneyBag
     */
    public function toMoney();

    /**
     * <p><i>Extension</i></p>
     * Return its total amount in the desired currency.<br/>
     *
     * @param \mitgedanken\Monetary\Currency $inCurrency
     * @return integer|float Its total amount.
     */
    public function getTotalOf(Currency $inCurrency);

    /**
     * <p><i>Extension</i></p>
     * It returns a <i>Interfaces\Money</i> object which total is the sum of all conversed monies amount.
     * Its conversion is based on an given <i>Exchange</i> object.
     * It returns a <i>Interfaces\Money</i> object with amount of 0 If and only if no suitable
     * exchange rate was found.<br/>
     *
     * @param \mitgedanken\Monetary\Currency $inCurrency
     * @param string $toType Description TODO
     * @return Interfaces\Money
     * @throws UnsuitablePair If and only if no suitable pair was found.
     */
    public function getMoneyIn(Currency $inCurrency);

    /**
     * <p><i>Extension</i></p>
     * Convenience method.<br>
     * It returns a <i>Interfaces\Money</i> object which value is the total value of this
     * <i>MoneyBag</i> in its currency.
     *
     * @return void No return value
     */
    public function getTotalValue();

    /**
     * Return \count of all monies.
     *
     * @return integer \count of all monies.
     */
    public function count();
}
