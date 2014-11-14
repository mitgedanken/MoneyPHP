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
/**
 * Description of MoneyBagAsMoneyTest
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */

namespace mitgedanken\Monetary;

use PHPUnit_Framework_TestCase;

class MoneyBagAsMoneyTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::__callStatic
     */
    public function callStatic()
    {
        $number = new BigNumber(2);
        $eur = new MoneyBag($number, new Currency('EUR'));
        $this->assertEquals($eur, MoneyBag::EUR(2));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::isZero
     */
    public function isZero()
    {
        $number = new BigNumber(0);
        $money = new MoneyBag($number, new Currency('EUR'));
        $this->assertTrue($money->isZero());

        $number = new BigNumber(1);
        $eur = new MoneyBag($number, new Currency('EUR'));
        $this->assertFalse($eur->isZero());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::isPositive
     */
    public function isPositive()
    {
        $number = new BigNumber(1);
        $usd = new MoneyBag($number, new Currency('USD'));
        $this->assertTrue($usd->isPositive());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::isNegative
     */
    public function isNegative()
    {
        $number = new BigNumber(-1);
        $usd = new MoneyBag($number, new Currency('USD'));
        $this->assertTrue($usd->isNegative());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::hasSameCurrency
     * @depends isZero
     */
    public function hasSameCurrency()
    {
        $number = new BigNumber(1);

        $usd = new MoneyBag($number, new Currency('USD'));
        $this->assertTrue($usd->hasSameCurrency($usd));

        $eur = new MoneyBag($number, new Currency('EUR'));
        $this->assertFalse($usd->hasSameCurrency($eur));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::hasSameAmount
     * @depends hasSameCurrency
     */
    public function hasSameAmount()
    {
        $number = new BigNumber(2);

        $usd = new MoneyBag($number, new Currency('USD'));
        $this->assertTrue($usd->hasSameAmount($usd));

        $eur = new MoneyBag($number, new Currency('EUR'));
        $this->assertFalse($eur->hasSameAmount($usd));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::equals
     */
    public function equals()
    {
        $number = new BigNumber(0);

        $usd = new MoneyBag($number, new Currency('USD'));
        $eur = new MoneyBag($number, new Currency('EUR'));

        $this->assertTrue($usd->equals($usd));

        $this->assertFalse($usd->equals($eur));
        $this->assertFalse($eur->equals($usd));

        $other = new \stdClass();
        $this->assertFalse($eur->equals($other));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::getAmount
     */
    public function getAmount()
    {
        $number = new BigNumber(20);

        $usd = new MoneyBag($number, new Currency('USD'));
        $eur = new MoneyBag($number, new Currency('EUR'));

        $this->assertEquals($eur->getAmount(), $usd->getAmount());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::getCurrency
     */
    public function getCurrency()
    {
        $number = new BigNumber(20);

        $usd = new MoneyBag($number, new Currency('USD'));
        $eur = new MoneyBag($number, new Currency('EUR'));

        $expected = new Currency('USD');
        $this->assertEquals($expected, $usd->getCurrency());
        $this->assertNoticequals($expected, $eur->getCurrency());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::add
     */
    public function add()
    {
        $number1 = new BigNumber(1);
        $number2 = new BigNumber(2);

        $usd1 = new Money($number1, new Currency('USD'));
        $usd2 = new Money($number1, new Currency('USD'));
        $expected = new Money($number2, new Currency('USD'));
        $this->assertEquals($expected, $usd1->add($usd2));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::add
     * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
     */
    public function addDifferentCurrencies()
    {
        $number = new BigNumber(20);

        $eur = new MoneyBag($number, new Currency('EUR'));
        $eur->add(new MoneyBag($number, new Currency('USD')));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::subtract
     */
    public function subtract()
    {
        $number = new BigNumber(1);

        $eur = new MoneyBag($number, new Currency('EUR'));
        $this->assertEquals(0, $eur->subtract($eur)->getAmount());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::subtract
     * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
     */
    public function subtractDifferentCurrencies()
    {
        $number = new BigNumber(20);

        $usd = new MoneyBag($number, new Currency('USD'));
        $eur = new MoneyBag($number, new Currency('EUR'));
        $eur->subtract($usd);
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::negate
     */
    public function negate()
    {
        $number = new BigNumber(1);

        $usd = new MoneyBag($number, new Currency('USD'));
        $this->assertEquals(-1, $usd->negate()->getAmount());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::multiply
     */
    public function multiply()
    {
        $number = new BigNumber(2);

        $eur = new MoneyBag($number, new Currency('EUR'));
        $eur2 = new MoneyBag($number, new Currency('EUR'));
        $this->assertEquals(4, $eur->multiply($eur2)->getAmount());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::multiply
     * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
     */
    public function multiplyDifferentCurrencies()
    {
        $number = new BigNumber(1);

        $usd = new MoneyBag($number, new Currency('USD'));
        $eur = new MoneyBag($number, new Currency('EUR'));
        $eur->multiply($usd);
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::divide
     */
    public function divide()
    {
        $number1 = new BigNumber(4);
        $number2 = new BigNumber(2);

        $eur = new MoneyBag($number1, new Currency('EUR'));
        $eur2 = new MoneyBag($number2, new Currency('EUR'));
        $this->assertEquals(2, $eur->divide($eur2)->getAmount());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::divide
     * @expectedException mitgedanken\Monetary\Exceptions\DivisionByZero
     */
    public function divisionByZero()
    {
        $number1 = new BigNumber(1);
        $number2 = new BigNumber(0);

        $eur = new MoneyBag($number1, new Currency('EUR'));
        $eur->divide(new MoneyBag($number2, new Currency('EUR')));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::compare
     */
    public function compare()
    {
        $number1 = new BigNumber(1);
        $number2 = new BigNumber(2);

        $usd1 = new MoneyBag($number1, new Currency('USD'));
        $usd2 = new MoneyBag($number2, new Currency('USD'));

        $this->assertEquals(-1, $usd1->compare($usd2));
        $this->assertEquals(1, $usd2->compare($usd1));
        $this->assertEquals(0, $usd1->compare($usd1));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::compare
     * @expectedException mitgedanken\Monetary\Exceptions\DifferentCurrencies
     */
    public function compareException()
    {
        $number = new BigNumber(1);
        $usd = new MoneyBag($number, new Currency('USD'));
        $eur = new MoneyBag($number, new Currency('EUR'));
        $usd->compare($eur);
    }

    /**
     * @test
     * @depends compare
     * @covers mitgedanken\Monetary\Money::greaterThan
     */
    public function tGreaterThan()
    {
        $number1 = new BigNumber(1);
        $eur = new MoneyBag($number1, new Currency('EUR'));


        $number2 = new BigNumber(20 + 10);
        $other = new MoneyBag($number2, new Currency('EUR'));
        $this->assertTrue($other->greaterThan($eur));
        $this->assertFalse($eur->greaterThan($other));
    }

    /**
     * @test If they currencies are unequal, an exception must be thrown.
     * @depends compareException
     * @covers mitgedanken\Monetary\Money::greaterThan
     * @expectedException mitgedanken\Monetary\Exceptions\DifferentCurrencies
     */
    public function greaterThanDifferentCurrencies()
    {
        $number1 = new BigNumber(10);
        $number2 = new BigNumber(20);

        $usd = new MoneyBag($number1, new Currency('USD'));
        $eur = new MoneyBag($number2, new Currency('EUR'));
        $eur->greaterThan($usd);
    }

    /**
     * @test
     * @depends compare
     * @covers mitgedanken\Monetary\Money::lessThan
     */
    public function tLessThan()
    {
        $number1 = new BigNumber(1);
        $eur = new MoneyBag($number1, new Currency('EUR'));

        $number2 = new BigNumber(20 + 10);
        $other = new MoneyBag($number2, new Currency('EUR'));
        $this->assertTrue($eur->lessThan($other));
        $this->assertFalse($other->lessThan($eur));
    }

    /**
     * @test If they currencies are unequal, an exception must be thrown.
     * @depends compareException
     * @covers mitgedanken\Monetary\Money::lessThan
     * @expectedException mitgedanken\Monetary\Exceptions\DifferentCurrencies
     */
    public function lessThanException()
    {
        $number = new BigNumber(1);
        $usd = new MoneyBag($number, new Currency('USD'));
        $eur = new MoneyBag($number, new Currency('EUR'));
        $eur->lessThan($usd);
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::__toString
     */
    public function testToString()
    {
        $number = new BigNumber(1);
        $eur = new MoneyBag($number, new Currency('EUR'));
        $this->assertEquals("1 EUR ''", $eur);
    }

}
