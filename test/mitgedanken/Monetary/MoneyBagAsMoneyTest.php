<?php

/*
 * Copyright (C) 2013 Sascha Tasche <sascha@mitgedanken.de>
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
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */

namespace mitgedanken\Monetary;
use PHPUnit_Framework_TestCase;
use mitgedanken\Monetary\Money,
    mitgedanken\Monetary\Currency;

class MoneyBagAsMoneyTest extends PHPUnit_Framework_TestCase {

  /** Test amount. @var int */
  protected $amount;

  /** @var mitgedanken\Monetary\Money */
  protected $usd;

  /** @var mitgedanken\Monetary\Money */
  protected $eur;

  protected function setUp()
  {
    $usdc = new Currency('USD');
    $eurc = new Currency('EUR');

    $this->amount = 20;
    $this->usd = new MoneyBag($this->amount, $usdc);
    $this->eur = new MoneyBag($this->amount, $eurc);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::__callStatic
   */
  public function callStatic()
  {
    $usd = new Money($this->amount, new Currency('USD'));
    $eur = new Money($this->amount, new Currency('EUR'));
    $this->assertEquals($eur, Money::EUR($this->amount));
    $this->assertEquals($usd, Money::USD($this->amount));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::isZero
   * @depends callStatic
   */
  public function isZero()
  {
    $this->assertTrue(Money::zero()->isZero());

    $money = new Money(0, new Currency('EUR'));
    $this->assertTrue($money->isZero());

    $this->assertFalse($this->eur->isZero());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::isPositive
   */
  public function isPositive()
  {
    $other = new Money(1, new Currency('USD'));
    $this->assertTrue($other->isPositive());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::isNegative
   */
  public function isNegative()
  {
    $other = new Money(-1, new Currency('USD'));
    $this->assertTrue($other->isNegative());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::hasSameCurrency
   * @depends isZero
   */
  public function hasSameCurrency()
  {
    $other = new Money(1, new Currency('USD'));
    $this->assertTrue($this->usd->hasSameCurrency($other));
    $this->assertTrue($other->hasSameCurrency($this->usd));

    $this->assertFalse($this->eur->hasSameCurrency($other));
    $this->assertFalse($other->hasSameCurrency($this->eur));

    $other = Money::zero();
    $this->assertTrue($this->eur->hasSameCurrency($other));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::hasSameAmount
   * @depends hasSameCurrency
   */
  public function hasSameAmount()
  {
    $other = new Money($this->amount, new Currency('USD'));
    $this->assertTrue($this->usd->hasSameAmount($other));

    $other = new Money($this->amount, new Currency('EUR'));
    $this->assertFalse($this->usd->hasSameAmount($other));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::equals
   */
  public function equals()
  {
    $this->assertTrue($this->usd->equals($this->usd));
    $this->assertFalse($this->usd->equals($this->eur));

    $other = new Money($this->amount + 1, new Currency('USD'));
    $this->assertFalse($this->usd->equals($other));
    $this->assertFalse($other->equals($this->usd));

    $other = new Money($this->amount, new Currency('USD'));
    $this->assertTrue($other->equals($this->usd));
    $this->assertTrue($this->usd->equals($other));

    $other = new \stdClass();
    $this->assertFalse($this->eur->equals($other));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::getAmount
   */
  public function getters()
  {
    $this->assertEquals($this->amount, $this->usd->getAmount());

    $expected = new Currency('USD');
    $this->assertEquals($expected, $this->usd->getCurrency());
    $this->assertNotEquals($expected, $this->eur->getCurrency());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::add
   */
  public function add()
  {
    $currency = new Currency('EUR');
    $other = new Money(1, $currency);
    $expected = new Money($this->amount + 1, $currency);
    $this->assertEquals($expected, $this->eur->add($other));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::add
   * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function addDifferentCurrencies()
  {
    $other = new Money(1, new Currency('USD'));
    $this->eur->add($other, TRUE);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::subtract
   */
  public function subtract()
  {
    $currency = new Currency('EUR');
    $other = new Money(1, $currency);
    $expected = new Money($this->amount - 1, $currency);
    $this->assertEquals($expected, $this->eur->subtract($other));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::subtract
   * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function subtractDifferentCurrencies()
  {
    $other = new Money(1, new Currency('USD'));
    $this->eur->subtract($other);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::negate
   */
  public function negate()
  {
    $expected = new Money(-$this->amount, new Currency('USD'));
    $this->assertEquals($expected, $this->usd->negate());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::multiply
   */
  public function multiply()
  {
    $multiplier = 2;
    $expected = new Money($this->amount * 2, new Currency('EUR'));
    $this->assertEquals($expected, $this->eur->multiply($multiplier));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::multiply
   * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function multiplyDifferentCurrencies()
  {
    $this->eur->multiply($this->usd);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::divide
   */
  public function divide()
  {
    $expected = new Money(1, new Currency('EUR'));
    $this->assertEquals($expected, $this->eur->divide($this->amount));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::divide
   * @expectedException mitgedanken\Monetary\Exceptions\DivisionByZero
   */
  public function divisionByZero()
  {
    $this->eur->divide(0);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::compare
   */
  public function compare()
  {
    $currency = new Currency('USD');
    $money1 = new Money(1, $currency);
    $money2 = new Money(2, $currency);

    $this->assertEquals(-1, $money1->compare($money2));
    $this->assertEquals(1, $money2->compare($money1));
    $this->assertEquals(0, $money1->compare($money1));

    $money3 = new Money(2, $currency);
    $this->assertEquals(0, $money2->compare($money3));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::compare
   * @expectedException mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function compareException()
  {
    $this->eur->compare($this->usd);
  }

  /**
   * @test
   * @depends compare
   * @covers mitgedanken\Monetary\Money::greaterThan
   */
  public function _greaterThan()
  {
    $other = new Money($this->amount + 10, new Currency('EUR'));
    $this->assertTrue($other->greaterThan($this->eur));
    $this->assertFalse($this->eur->greaterThan($other));
  }

  /**
   * @test If they currencies are unequal, an exception must be thrown.
   * @depends compareException
   * @covers mitgedanken\Monetary\Money::greaterThan
   * @expectedException mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function greaterThanDifferentCurrencies()
  {
    $other = new Money($this->amount + 10, new Currency('EUR'));
    $other->greaterThan($this->usd);
  }

  /**
   * @test
   * @depends compare
   * @covers mitgedanken\Monetary\Money::lessThan
   */
  public function _lessThan()
  {
    $other = new Money($this->amount + 10, new Currency('EUR'));
    $this->assertTrue($this->eur->lessThan($other));
    $this->assertFalse($other->lessThan($this->eur));
  }

  /**
   * @test If they currencies are unequal, an exception must be thrown.
   * @depends compareException
   * @covers mitgedanken\Monetary\Money::lessThan
   * @expectedException mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function lessThanException()
  {
    $other = new Money($this->amount + 10, new Currency('EUR'));
    $other->lessThan($this->usd);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::isZero
   * @depends equals
   * @depends add
   * @depends subtract
   * @depends divide
   * @depends multiply
   */
  public function zeroTest()
  {
    $this->assertSame(Money::zero(), Money::zero(),
                      'Creating zero objects should always return the same instance.');

    $this->assertEquals(Money::zero(), Money::zero());
    $this->assertEquals(new Money(0, new NullCurrency()), Money::zero());
    $this->assertEquals(Money::zero(), new Money(0, new NullCurrency()));

// equals()
    $this->assertTrue(Money::zero()->equals(Money::zero()));
    $this->assertTrue(Money::zero()->equals(Money::USD(0)));
    $this->assertTrue(Money::USD(0)->equals(Money::zero()));
    $this->assertFalse(Money::USD(0)->equals(Money::EUR(0)));

// add()
    $this->assertEquals(Money::EUR(5), Money::EUR(5)->add(Money::zero()));

// subtract()
    $this->assertEquals(Money::EUR(5), Money::EUR(5)->subtract(Money::zero()));

// multiply()
    $this->assertEquals(Money::EUR(0), Money::EUR(5)->multiply(Money::zero()));

// divide()
    $this->assertEquals(Money::EUR(0), Money::EUR(0)->divide(Money::EUR(5)));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::__toString
   */
  public function testToString()
  {
    $this->assertEquals("$this->amount EUR ''", $this->eur);
  }
}
