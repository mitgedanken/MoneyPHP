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

namespace mitgedanken\Monetary\Abstracts\Configuration;

/**
 * Configuration.<br>
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
abstract class Money
{

  /**
   * @var string
   */
  protected $money_class;

  /**
   * @var string
   */
  protected $number_class;

  /**
   * @var string
   */
  protected $calculator_class;

  /**
   * @var string
   */
  protected $exchanger_class;

  /**
   * @var string
   */
  protected $moneystorage_class;

  /**
   * @var string
   */
  protected $currency_class;

  /**
   * @var string
   */
  protected $currencypair_class;

  /**
   * @var string
   */
  protected $currencypair_repository_class;

  /**
   * It returns the default class name for <i>Interfaces\Money</i> class.<br>
   * @param string $value
   * @return \mitgedanken\Monetary\Money
   */
  public function getMoneyClass()
  {
    return $this->money_class;
  }

  /**
   * It returns the default class name for <i>Currency</i> class.<br>
   * @param string $value
   * @return \mitgedanken\Monetary\Currency
   */
  public function getCurrencyClass()
  {
    return $this->currency_class;
  }

  /**
   * It returns the default class name for <i>MoneyStorage</i> interface.<br>
   * @param string $value
   * @return \mitgedanken\Monetary\Interfaces\MoneyStorage
   */
  public function getMoneyStorageClass()
  {
    return $this->moneystorage_class;
  }

  /**
   * It returns the default class name for <i>Number</i> interface.<br>
   * @param string $value
   * @return \mitgedanken\Monetary\Interfaces\Number
   */
  public function getNumberClass()
  {
    return $this->number_class;
  }

  /**
   * It returns the default object of <i>Calculator</i> interface.<br>
   * @param string $value
   * @return \mitgedanken\Monetary\Interfaces\Calculator
   */
  public function getCalculatorClass()
  {
    return $this->calculator_class;
  }

  /**
   * It returns the default class name for <i>Exchanger</i> interface.<br>
   * @param string $value
   * @return \mitgedanken\Monetary\Interfaces\Exchanger
   */
  public function getExchangerClass()
  {
    return $this->exchanger_class;
  }

  /**
   * It returns the default class name for <i>CurrencyPair</i> class.<br>
   * @param string $value
   * @return \mitgedanken\Monetary\CurrencyPair
   */
  public function getCurrencyPairClass()
  {
    return $this->currencypair_class;
  }

  /**
   * It returns the default class name for <i>CurrencyPairRepository</i> class.<br>
   * @param string $value
   * @return \mitgedanken\Monetary\CurrencyPair
   */
  public function getCurrencyPairRepositoryClass()
  {
    return $this->currencypair_repository_class;
  }

}
