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

use mitgedanken\Monetary\Selector\ConfigurationSelector;
use mitgedanken\Monetary\Money as ClassesMoney;
use mitgedanken\Monetary\Interfaces\Factory;

/**
 * Description of Money
 *
 * @author Sascha
 */
class Money implements Factory {

    /**
     * <p><i>Extension</i></p>
     * Convenience factory method.<br/>
     *
     * @example $fiveDollar = <i>Interfaces\Money</i>::USD(500, 'United States Dollar');
     * @example $fiveDollar = <i>Interfaces\Money</i>::USD(500);
     *
     * @param string $code
     * @param array $arguments 0:string, currency code; 1:string, display name;
     * @return ClassesMoney
     */
    public static function __callStatic($code, $arguments) {
        $cargs = \count($arguments);
        $configuration = ConfigurationSelector::getSelection();
        $money_class = $configuration->getMoneyClass();
        $number_class = $configuration->getNumberClass();
        $currency_class = $configuration->getCurrencyClass();
        if (2 == $cargs):
            $newMoney = new $money_class(new $number_class($arguments[0]), new $currency_class($code, $arguments[1]));
        else:
            $newMoney = new $money_class(new $number_class($arguments[0]), new $currency_class($code));
        endif;
        return $newMoney;
    }

    /**
     * Creates a <i>Interfaces\Money</i> object.<br>
     *
     * @param mixed $_
     * @return ClassesMoney
     */
    public static function create($_) {
        $currency_name = '';
        if (\is_numeric($_)):
            $amount = $_;
            $currency = \func_get_arg(1);
            if (3 == \func_num_args()):
                $currency_name = \ucwords(\func_get_arg(2));
            endif;
        elseif (\is_array($_)):
            $amount = $_[0];
            $currency = $_[1];
            if (3 == \count($_)):
                $currency_name = \ucwords($_[2]);
            endif;
        endif;
        $configuration = ConfigurationSelector::getSelection();
        $money_class = $configuration->getMoneyClass();
        $number_class = $configuration->getNumberClass();
        $currency_class = $configuration->getCurrencyClass();
        $money_object = new $money_class(new $number_class($amount), new $currency_class(\strtoupper($currency), $currency_name));
        return $money_object;
    }

}
