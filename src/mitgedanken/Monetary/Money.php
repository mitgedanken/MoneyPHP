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
 * <i>Immutable</i><br/>
 * An implementation of <i>Money</i> by Fowler.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class Money extends Abstracts\Money {

    /**
     * This amount.
     *
     * @var Interfaces\Money
     */
    protected $number;

    /**
     * Holds the Currency object.
     * @var Currency
     */
    protected $currency;

    /**
     * Holds the scale used by all bc* functions.
     * @var integer
     */
    protected $scale;

    /**
     * Holds the configuration.
     * @var \mitgedanken\Monetary\Abstracts\Configuration\Money
     */
    protected $configuration;

    /**
     * Constructor.<br/>
     * If <i>$rates</i> is <i>NULL</i> it will
     * instaniate an empty <i>Class\ExchangeRates</i>.<br/>   *
     * If <i>$storage</i> is <i>NULL</i> it will
     * instaniate an empty <i>Interface\MonetaryStorage</i>.
     *
     * @param integer|float $amount Its amount.
     * @param Currency $currency Its currency.
     * @param CurrencyPairRepository $currencyPairRepository [optional but recommended].
     * @param MoneyStorage $monetaryStorage [optional but recommended].
     */
    public function __construct(Interfaces\Number $number, Currency $currency)
    {
        $this->number = $number;
        $this->currency = $currency;
    }

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
     * <p><i>Extension</i></p>
     * Checks if this <i>Interfaces\Money</i> has the same amount as the other.<br/>
     * <i>The objects must have the same currency.</i></p>
     *
     * @param Interfaces\Money $money
     * @return boolean <b>TRUE</b> if this monetary value has
     *                 the same amount and the same currency as the other;
     *                 <b>FALSE</b> otherwise.
     */
    public function hasSameAmount(Interfaces\Money $money)
    {
        return $this->hasSameCurrency($money) && ($this->number->equals($money->number));
    }

    /**
     * <p><i>Extension</i></p>
     * Checks if its currency is "equal to" the $currency argument.<br/>
     *
     * @param Interfaces\Money $money
     * @return boolean <b>TRUE</b> if this monetary value has
     *                 the same currency as the other;
     *                 <b>FALSE</b> otherwise.
     */
    public function hasSameCurrency(Interfaces\Money $money)
    {
        return $this->currency->equals($money->currency);
    }

    /**
     * Adds the amount of a given <i>Interfaces\Money</i><br/>
     * @see Interfaces\Money::add
     *
     * @param Interfaces\Money $addend
     * @return Interfaces\Money
     */
    public function add(Interfaces\Money $addend)
    {
        $value = $this->number->isZero() ? 0 : $this->number->subtract($addend->number);
        return $this->internalNewMoney($value);
    }

    /**
     * It returns a new <i>Interfaces\Money</i> object that represents the monetary value
     * of the difference of this <i>Interfaces\Money</i> object and another.
     * @see Interfaces\Money::subtract
     *
     * @param Interfaces\Money $subtrahend
     * @return Interfaces\Money
     * @throws Exceptions\DifferentCurrencies
     *         If and only if the currency of the given object not
     *         the same as the subtype of this abstract class.
     */
    public function subtract(Interfaces\Money $subtrahend)
    {
        $value = $this->number->isZero() ? 0 : $this->number->subtract($subtrahend->number);
        return $this->internalNewMoney($value);
    }

    /**
     * It returns a new <i>Interfaces\Money</i> object that represents the monetary value
     * of this <i>Interfaces\Money</i> object multiplied by a given money object.
     * @see Interfaces\Money::multiply
     *
     * @param  integer $multiplier
     * @return \mitgedanken\Monetary\Interfaces\Money
     * @throws Exceptions\DifferentCurrencies
     *         If and only if the currency of the given object not
     *         the same as the subtype of this abstract class.
     */
    public function multiply(Interfaces\Money $multiplier)
    {
        $value = $this->number->isZero() ? 0 : $this->number->multiply($multiplier);
        return $this->internalNewMoney($value);
    }

    /**
     * It returns a new <i>Interfaces\Money</i> object that represents the monetary value
     * of this <i>Interfaces\Money</i> object divided by a given money object.
     * @see Interfaces\Money::divide
     *
     * @param  \mitgedanken\Monetary\Interfaces\Money $divisor
     * @return \mitgedanken\Monetary\Interfaces\Money
     * @throws Exceptions\DifferentCurrencies
     *         If and only if the currency of the given object not
     *         the same as the subtype of this abstract class.
     * @throws Exceptions\DivisionByZero If and only if value of argument 1 is 0.
     */
    public function divide(Interfaces\Money $divisor)
    {
        if ($divisor->number->isZero()):
            throw new DivisionByZero("Argument 1 ");
        endif;
        $value = $this->number->isZero() ? 0 : $this->number->divide($divisor->number);
        return $this->internalNewMoney($value);
    }

    /**
     * Return a new <i>Interfaces\Money</i> object that represents the negated monetary value
     * of this <i>Interfaces\Money</i> object.<br>
     *
     * @return Interfaces\Money
     */
    public function negate()
    {
        return $this->internalNewMoney($this->number->negate());
    }

    /**
     * Checks if its value is negativ or not.<br>
     *
     * @return boolean
     *         If and only if its value is negative it will return <i>TRUE</i>;
     *         <i>FALSE</i> otherwise.
     */
    public function isNegative()
    {
        return 0 > $this->number;
    }

    /**
     * Checks if its value is positive or not.<br>
     *
     * @return boolean
     *         If and only if its value is negative it will return <i>TRUE</i>;
     *         <i>FALSE</i> otherwise.
     */
    public function isPositve()
    {
        return 0 <= $this->number;
    }

    /**
     * Return its ammount as string.<br>
     *
     * @return integer Its ammount.
     */
    public function toString()
    {
        return $this->number->toString();
    }

    /**
     * Constructs a new <i>Money</i> object with the same currency as this object.
     * @see Money::_newMoney
     *
     * @param Interfaces\Money $number
     * @return Interfaces\Money
     */
    protected function internalNewMoney(Interfaces\Number $number, Currency $currency = NULL)
    {
        if (isset($currency)):
            $newMoney = new Money($number, $currency);
        else:
            $newMoney = new Money($number, $this->currency);
        endif;
        return $newMoney;
    }

}
