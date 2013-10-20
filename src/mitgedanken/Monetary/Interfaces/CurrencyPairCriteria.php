<?php

/*
 * Copyright (C) 2013 Sascha Tasche <hallo@mitgedanken.de>
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
 * Criteria for searching of a <i>CurrencyPair</i>.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
interface CurrencyPairCriteria {

  /**
   * Return the searched base currency or <i>NULL</i> if this criterion must
   * not be fulfilled.
   *
   * @return Currency|NULL (NULL only if this criterion must not be fulfilled)
   */
  function getBaseCurrency();

  /**
   * Return the searched counter currency or <i>NULL</i> if this criterion must
   * not be fulfilled.
   *
   * @return Currency|NULL (NULL only if this criterion must not be fulfilled)
   */
  function getCounterCurrency();
}
