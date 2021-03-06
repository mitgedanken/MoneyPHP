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
interface GeneralConfiguration {

    /**
     * @return \mitgedanken\Monetary\Interfaces\Calculator
     */
    function getCalculator();

    /**
     * @return \mitgedanken\Monetary\Interfaces\Number
     */
    function getNumber();

    /**
     * @return \mitgedanken\Monetary\Interfaces\Exchanger
     */
    function getExchanger();

    /**
     * @return \mitgedanken\Monetary\Interfaces\MoneyStorage
     */
    function getMoneyStorage();

    /**
     * @return \mitgedanken\Monetary\Interfaces\Money
     */
    function getMoney();

    /**
     * @return \mitgedanken\Monetary\Interfaces\MoneyBag
     */
    function getMoneyBag();
}
