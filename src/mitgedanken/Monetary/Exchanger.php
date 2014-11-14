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
 * Exchanges currency pairs by its ratio.
 */
class Exchanger implements Interfaces\Exchanger {

    use \mitgedanken\Monetary\Traits\Monetary;

    const DEFAULT_TYPE = 'double';

    /**
     * Holds the scale value.
     *
     * @var integer
     */
    protected $scale;

    /**
     * Converts a currency pair and returns the result as a string.
     *
     * @param Interfaces\Money $money
     * @param CurrencyPair $pair
     * @return Interfaces\Number
     */
    public static function convert(Interfaces\Money $money, CurrencyPair $pair, Interfaces\Configuration $configuration)
    {
        $scale = $configuration['scale'];
        $currency = $money->getCurrency();
        $numberClassName = $configuration['numberClassName'];
        $resultNumber = new $numberClassName($money->getAmountAsString());
        if ($currency->equals($pair->getBaseCurrency())):
            $resultNumber->multiply($pair->getRatioAsString());
        elseif ($currency->equals($pair->getCounterCurrency())):
            $resultNumber->divide($pair->getRatioAsString());
        else:
            $message = 'Given currency (' . $currency->getCode() . ' not found.)';
            $code = NoSuitableCurrency::CODE + 16;
            throw new NoSuitableCurrency($message, $code);
        endif;
        return $resultNumber;
    }

}
