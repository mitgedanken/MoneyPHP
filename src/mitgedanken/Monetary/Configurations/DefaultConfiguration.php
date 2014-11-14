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

namespace mitgedanken\Monetary\Configuration;

use mitgedanken\Monetary\Abstracts\Configuration\Money;

/**
 * Default configuration for monetary classes.<br>
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class DefaultConfiguration extends Money {

    /**
     * @var string
     */
    protected $money_class = '\mitgedanken\Monetary\Money';

    /**
     * @var string
     */
    protected $number_class = '\mitgedanken\Monetary\BigNumber';

    /**
     * @var string
     */
    protected $calculator_class = '\mitgedanken\Monetary\Calculator';

    /**
     * @var string
     */
    protected $exchanger_class = '\mitgedanken\Monetary\Exchange';

    /**
     * @var string
     */
    protected $moneystorage_class = '\mitgedanken\Monetary\MoneyStorage';

    /**
     * @var string
     */
    protected $currency_class = '\mitgedanken\Monetary\Currency';

    /**
     * @var string
     */
    protected $currencypair_class = '\mitgedanken\Monetary\CurrencyPair';

    /**
     * @var string
     */
    protected $currencypair_repository_class = '\mitgedanken\Monetary\CurrencyPairRepository';

}
