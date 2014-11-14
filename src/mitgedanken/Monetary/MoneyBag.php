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
 * This class represents a money bag. It can be filled with different currencies.<br/>
 * This class guarantees that each element is contained only once.<br/>
 *
 * By using your own implementation of a <i>MonetaryStorage</i> (\SplObjectStorage)
 * the implementor must guarantee that each element is contained only once.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class MoneyBag extends Money implements Interfaces\MoneyBag, \Countable {

    /**
     * Storage for all <i>Interfaces\Money</i> and <i>Classes\MoneyBag</i> objects.
     *
     * @var \SplObjectStorage
     */
    protected $moneyStorage;

    /**
     * @var CurrencyPairRepository
     */
    protected $currencyPairRepository;

    /**
     *
     * @param \mitgedanken\Monetary\Interfaces\Number $number
     * @param \mitgedanken\Monetary\Currency $currency
     * @param \mitgedanken\Monetary\Interfaces\Configuration $configuration
     */
    public function __construct(Interfaces\Number $number, Currency $currency, Interfaces\Configuration $configuration)
    {
        parent::__construct($number, $currency);
        $this->configuration = $configuration;
        $this->moneyStorage = $configuration['moneyStorage'];
        $this->currencyPairRepository = $configuration['currencyPairRepository'];
    }

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
    public function toMoney()
    {
        return new Money($this->number, $this->currency, $this->scale);
    }

    /**
     * It returns a new <i>Interfaces\Money</i> object that represents the monetary value
     * of the sum of this <i>Interfaces\Money</i> object and another.
     * @see Interfaces\Money::add
     *
     * @param Interfaces\Money $addend
     * @return Interfaces\Money
     * @throws Exceptions\DifferentCurrencies
     *         If $addend has a different currency as this money.
     * @throws UnexpectedValue
     *         If $addend is not an instance of <i>Abstracts\Money</i> or <i>MoneyInterface</i>.
     */
    public function add(Interfaces\Money $addend)
    {
        parent::_haveSameCurrency($addend);
        return $this->addDifferentCurrency($addend);
    }

    /**
     * It returns a new <i>Interfaces\Money</i> object that represents the monetary value
     * of the difference of this <i>Interfaces\Money</i> object and another.
     * @see Interfaces\Money::subtract
     *
     * @param \mitgedanken\Monetary\Abstracts\Money $subtrahend
     * @param boolean $rounding
     * @return \mitgedanken\Monetary\Money
     * @throws Exceptions\InvalidArgument
     *         If and only if different currencies are given.
     */
    public function subtract(Interfaces\Money $subtrahend)
    {
        parent::_haveSameCurrency($subtrahend);
        return $this->subtractDifferentCurrency($subtrahend);
    }

    /**
     * It returns a new <i>Interfaces\Money</i> object that represents the monetary value
     * of this <i>Interfaces\Money</i> object divided by a given divisor.
     * @see Money::multiply
     *
     * @param float|integer $multiplier
     * @return \mitgedanken\Monetary\Money
     * @throws DifferentCurrencies
     */
    public function multiply(Interfaces\Money $multiplier)
    {
        parent::_haveSameCurrency($multiplier);
        return $this->multiplyDifferentCurrency($multiplier);
    }

    /**
     * It returns a new <i>Interfaces\Money</i> object that represents the monetary value
     * of this <i>Interfaces\Money</i> object multiplied by a given factor.
     * @see Money::divide
     *
     * @param  integer|\mitgedanken\Monetary\Money $divisor
     * @param boolean $rounding [default: FALSE] rounds the result If and only if <i>TRUE</i>
     * @return Interfaces\Money
     * @throws Exceptions\DifferentCurrencies
     * @throws DivisionByZero If and only if amount of divisor is 0.
     */
    public function divide(Interfaces\Money $divisor)
    {
        parent::_haveSameCurrency($divisor);
        return $this->divideDifferentCurrency($divisor);
    }

    /**
     * <p><i>Extension</i></p>
     * Return its total amount in the desired currency.<br/>
     *
     * @param \mitgedanken\Monetary\Currency $inCurrency
     * @return integer Its total amount.
     */
    public function getTotalOf(Currency $inCurrency)
    {
        $total = NULL;
        if ($this->currency->equals($inCurrency)):
            $total = $this->number;
        else:
            try {
                $total = $this->_findByCurrency($inCurrency)->number;
            } catch (Exceptions\NoSuitableCurrency $ex) {
                $total = 0;
                /*
                 * $exc not needed.
                 */
            }
        endif;
        if (\is_null($total)):
            throw new Exceptions\InvalidArgument("No Money object with currency $inCurrency found");
        endif;
        return $total;
    }

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
     * @throws Exceptions\UnsuitablePair If and only if no suitable pair was found.
     */
    public function getMoneyIn(Currency $inCurrency)
    {
        if ($this->currency->equals($inCurrency)):
            $exchanged = $this->number;
        elseif (0 == \count($this->moneyStorage)):
            $code = Exceptions\Runtime::CODE + 16;
            $message = 'Monetary storage is empty';
            throw new Exceptions\Runtime($message, $code);
        else:
            $exchanged = NULL;
            $this->moneyStorage->rewind();
            while (\is_null($exchanged) && $this->moneyStorage->valid()):
                $current = $this->moneyStorage->current();
                try {
                    $pair = $this->currencyPairRepository->findByCurrency($current->currency, $inCurrency);
                    $exchanger = Money::getConfigurationObject()->getExchangeClassName();
                    $exchanged = $exchanger::convert($current->bigNumber->toInteger, $pair);
                } catch (Exceptions\UnsuitablePair $ex) {
                    $message = 'A money cannot be returned; no change done​​.';
                    $code = Exceptions\Runtime::CODE + 16;
                    throw new Exceptions\Runtime($message, $code, $ex);
                }
            endwhile;
        endif;
        return new Money($exchanged, $inCurrency);
    }

    /**
     * <p><i>Extension</i></p>
     * Convenience method.<br>
     * It returns a <i>Interfaces\Money</i> object which value is the total value
     * of this <i>Interfaces\MoneyBag</i> in its currency.
     *
     * @return void No return value
     */
    public function getTotalValue()
    {
        $result = NULL;
        foreach ($this->moneyStorage as $money):
            $result += $this->getMoneyIn($money->currency)->number;
        endforeach;
        return $result + $this->number;
    }

    /**
     * <p><i>Extension</i></p>
     *
     * @param Interfaces\Money $money
     * @return boolean
     *         <i>TRUE</i> if and only if the given object containes
     *         in the backed storage.
     */
    public function containsMoney(Interfaces\Money $money)
    {
        $boolean = $this->currency->equals($money->currency)
                ? : $this->moneyStorage->contains($money);
        return $boolean;
    }

    /**
     * <p><i>Extension</i></p>
     * <b>WARNING: Changes its state</b><br/>
     * Deletes a money from the storage.
     *
     * @param Interfaces\Money $delete
     * @param type $onlybyCurrency Set to <i>TRUE</i> if the deletion should be
     *        performed only via currency of argument 1.
     * @return void No return value
     * @throws Exceptions\BadFunctionCall
     *         If and only if no such money found.
     */
    public function deleteMoney(Interfaces\Money $delete, $onlybyCurrency = FALSE)
    {
        if ($onlybyCurrency && !$this->currency->equals($delete->currency)):
            try {
                $deleteByCurrency = $this->_findByCurrency($delete->getCurrency());
                $this->moneyStorage->detach($deleteByCurrency);
            } catch (NoSuitableCurrency $ex) {
                $message = "$delete cannot be deleted, no such money found.";
                throw new InvalidArgument($message, InvalidArgument::CODE + 16, $ex);
            }
        else:
            $this->moneyStorage->detach($delete);
        endif;
    }

    /**
     * Return \count of all monies.
     *
     * @return integer count of all monies.
     */
    public function count()
    {
        return \count($this->moneyStorage) + 1;
    }

    /**
     * <p><i>Extension</i></p> <i>Internal</i>
     * <b>Attention!</b> This function changes the objects in backed storage.<br>
     * It returns the money with the same currency for manipulating.<br/>
     *
     * @param \mitgedanken\Monetary\Currency $currency
     * @return Interfaces\Money a Money with the same currency as $money.
     * @throws Exceptions\NoSuitableCurrency
     *         If no suitable currency found.
     */
    protected function _findByCurrency(Currency $currency)
    {
        $current = NULL;
        $found = FALSE;
        $this->moneyStorage->rewind();
        while (!$found && $this->moneyStorage->valid()):
            $current = $this->moneyStorage->current();
            $found = $current->currency->equals($currency);
            $this->moneyStorage->next();
        endwhile;
        if (!$found):
            throw new NoSuitableCurrency();
        endif;
        return $current;
    }

    /**
     * Contructs a money with the currency of this object.<br/>
     * @see Money::_newMoney
     *
     * @param Interfaces\Number $number
     * @param Currency $currency
     * @return Interfaces\Money
     */
    protected function internalNewMoney(Interfaces\Number $number, Currency $currency = NULL)
    {
        if (isset($currency)):
            $currency = $this->number->isZero() ? $currency->currency : $this->currency;
            $newMoney = new MoneyBag($number, $currency, $this->configuration);
        else:
            $newMoney = new MoneyBag($number, $this->currency, $this->configuration);
        endif;
        return $newMoney;
    }

}
