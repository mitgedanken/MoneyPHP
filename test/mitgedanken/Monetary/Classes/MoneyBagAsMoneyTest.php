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
/**
 * Description of MoneyBagAsMoneyTest
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */

namespace mitgedanken\Monetary\Classes;

use PHPUnit_Framework_TestCase;

class MoneyBagAsMoneyTest extends PHPUnit_Framework_TestCase
{

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::__callStatic
   */
  public function callStatic()
  {
    $eur = new MoneyBag(2, new Currency('EUR'));
    $this->assertEquals($eur, MoneyBag::EUR(2));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::isZero
   */
  public function isZero()
  {
    $money = new MoneyBag(0, new Currency('EUR'));
    $this->assertTrue($money->isZero());

    $eur = new MoneyBag(1, new Currency('EUR'));
    $this->assertFalse($eur->isZero());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::isPositive
   */
  public function isPositive()
  {
    $usd = new MoneyBag(1, new Currency('USD'));
    $this->assertTrue($usd->isPositive());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::isNegative
   */
  public function isNegative()
  {
    $usd = new MoneyBag(-1, new Currency('USD'));
    $this->assertTrue($usd->isNegative());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::hasSameCurrency
   * @depends isZero
   */
  public function hasSameCurrency()
  {
    $usd = new MoneyBag(1, new Currency('USD'));
    $this->assertTrue($usd->hasSameCurrency($usd));

    $eur = new MoneyBag(1, new Currency('EUR'));
    $this->assertFalse($usd->hasSameCurrency($eur));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::hasSameAmount
   * @depends hasSameCurrency
   */
  public function hasSameAmount()
  {
    $usd = new MoneyBag(1, new Currency('USD'));
    $this->assertTrue($usd->hasSameAmount($usd));

    $eur = new MoneyBag(1, new Currency('EUR'));
    $this->assertFalse($eur->hasSameAmount($usd));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::equals
   */
  public function equals()
  {
    $usd = new MoneyBag(0, new Currency('USD'));
    $eur = new MoneyBag(0, new Currency('EUR'));

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
    $amount = 20;
    $usd    = new MoneyBag($amount, new Currency('USD'));
    $eur    = new MoneyBag($amount, new Currency('EUR'));

    $this->assertEquals($amount, $usd->getAmount());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::getCurrency
   */
  public function getCurrency()
  {
    $usd = new MoneyBag(1, new Currency('USD'));
    $eur = new MoneyBag(1, new Currency('EUR'));

    $expected = new Currency('USD');
    $this->assertEquals($expected, $usd->getCurrency());
    $this->assertNotEquals($expected, $eur->getCurrency());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::add
   */
  public function add()
  {
    $usd1     = new Money(1, new Currency('USD'));
    $usd2     = new Money(1, new Currency('USD'));
    $expected = new Money(2, new Currency('USD'));
    $this->assertEquals($expected, $usd1->add($usd2));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::add
   * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function addDifferentCurrencies()
  {
    $eur = new MoneyBag(1, new Currency('EUR'));
    $eur->add(new MoneyBag(1, new Currency('USD')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::subtract
   */
  public function subtract()
  {
    $eur      = new MoneyBag(1, new Currency('EUR'));
    $expected = new MoneyBag(0, new Currency('EUR'));
    $this->assertEquals($expected, $eur->subtract($eur));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::subtract
   * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function subtractDifferentCurrencies()
  {
    $usd = new MoneyBag(1, new Currency('USD'));
    $eur = new MoneyBag(1, new Currency('EUR'));
    $eur->subtract($usd);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::negate
   */
  public function negate()
  {
    $usd      = new MoneyBag(1, new Currency('USD'));
    $expected = new MoneyBag(-1, new Currency('USD'));
    $this->assertEquals($expected, $usd->negate());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::multiply
   */
  public function multiply()
  {
    $expected = new MoneyBag(4, new Currency('EUR'));
    $eur      = new MoneyBag(2, new Currency('EUR'));
    $this->assertEquals($expected, $eur->multiply(2));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::multiply
   * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function multiplyDifferentCurrencies()
  {
    $usd = new MoneyBag(1, new Currency('USD'));
    $eur = new MoneyBag(1, new Currency('EUR'));
    $eur->multiply($usd);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::divide
   */
  public function divide()
  {
    $eur      = new MoneyBag(1, new Currency('EUR'));
    $expected = new MoneyBag(1, new Currency('EUR'));
    $this->assertEquals($expected, $eur->divide(1.0));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::divide
   * @expectedException mitgedanken\Monetary\Exceptions\DivisionByZero
   */
  public function divisionByZero()
  {
    $eur = new MoneyBag(1, new Currency('EUR'));
    $eur->divide(new MoneyBag(0, new Currency('EUR')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::compare
   */
  public function compare()
  {
    $usd1 = new MoneyBag(1, new Currency('USD'));
    $usd2 = new MoneyBag(2, new Currency('USD'));

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
    $usd = new MoneyBag(1, new Currency('USD'));
    $eur = new MoneyBag(1, new Currency('EUR'));
    $usd->compare($eur);
  }

  /**
   * @test
   * @depends compare
   * @covers mitgedanken\Monetary\Money::greaterThan
   */
  public function _greaterThan()
  {
    $amount = 20;
    $eur    = new MoneyBag(1, new Currency('EUR'));

    $other = new MoneyBag((float) $amount + 10, new Currency('EUR'));
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
    $usd = new MoneyBag(10, new Currency('USD'));
    $eur = new MoneyBag(20, new Currency('EUR'));
    $eur->greaterThan($usd);
  }

  /**
   * @test
   * @depends compare
   * @covers mitgedanken\Monetary\Money::lessThan
   */
  public function _lessThan()
  {
    $amount = 20;
    $eur    = new MoneyBag(1, new Currency('EUR'));

    $other = new MoneyBag((float) $amount + 10, new Currency('EUR'));
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
    $usd = new MoneyBag(1, new Currency('USD'));
    $eur = new MoneyBag(1, new Currency('EUR'));
    $eur->lessThan($usd);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::__toString
   */
  public function testToString()
  {
    $eur = new MoneyBag(1, new Currency('EUR'));
    $this->assertEquals("1 EUR ''", $eur);
  }

}
