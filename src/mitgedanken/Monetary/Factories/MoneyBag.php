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

namespace mitgedanken\Monetary\Factories;

/**
 * Description of MoneyBag
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class MoneyBag implements Factory {

    /**
     * <p><i>Extension</i></p>
     * Convenience factory method.<br/>
     *
     * @example $fiveDollar = <i>MoneyBag</i>::USD(500, 'United States Dollar');
     * @example $fiveDollar = <i>MoneyBag</i>::USD(500);
     *
     * @param string $code
     * @param array $arguments
     *        0:amount (integer), 1:display name (string), 2:scale (integer),
     *        3:repository (CurrencyPairRepository), 4:storage (MonetaryStorage);
     * @return \mitgedanken\Monetary\Money
     */
    public static function __callStatic($code, $arguments) {
        $cargs = \count($arguments);
        $number = new BigNumber($arguments[0]);
        switch ($cargs):
            case (2):
                $newMoney = new MoneyBag($number, new Currency($code, $arguments[1]));
                break;
            case (3):
                $newMoney = new MoneyBag($number, new Currency($code, $arguments[1]), $arguments[2]);
                break;
            case (4):
                $newMoney = new MoneyBag($number, new Currency($code, $arguments[1]), $arguments[2], $arguments[3]);
                break;
            case(5):
                $newMoney = new MoneyBag($number, new Currency($code, $arguments[1]), $arguments[2], $arguments[3], $arguments[4]);
                break;
            default:
                $newMoney = new MoneyBag($number, new Currency($code));
        endswitch;
        return $newMoney;
    }

}
