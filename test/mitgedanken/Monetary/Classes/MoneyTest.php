<?php

namespace mitgedanken\Monetary\Classes;

use PHPUnit_Framework_TestCase;

class MoneyTest extends PHPUnit_Framework_TestCase
{

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::__callStatic
   */
  public function callStatic()
  {
    $amount = 20;
    $usd    = new Money($amount, new Currency('USD'));
    $eur    = new Money($amount, new Currency('EUR'));

    $this->assertEquals($eur, Money::EUR($amount));
    $this->assertEquals($usd, Money::USD($amount));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::isZero
   * @depends callStatic
   */
  public function isZero()
  {
    $money = new Money(0, new Currency('EUR'));
    $this->assertTrue($money->isZero());

    $eur = new Money(1, new Currency('EUR'));
    $this->assertFalse($eur->isZero());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::isPositive
   */
  public function isPositive()
  {
    $usd = new Money(1, new Currency('USD'));
    $this->assertTrue($usd->isPositive());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::isNegative
   */
  public function isNegative()
  {
    $usd = new Money(-1, new Currency('USD'));
    $this->assertTrue($usd->isNegative());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::hasSameCurrency
   * @depends isZero
   */
  public function hasSameCurrency()
  {
    $usd = new Money(1, new Currency('USD'));
    $this->assertTrue($usd->hasSameCurrency($usd));

    $eur = new Money(1, new Currency('EUR'));
    $this->assertFalse($usd->hasSameCurrency($eur));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::hasSameAmount
   * @depends hasSameCurrency
   */
  public function hasSameAmount()
  {
    $amount = 20;
    $usd    = new Money($amount, new Currency('USD'));

    $other = new Money($amount, new Currency('USD'));
    $this->assertTrue($usd->hasSameAmount($other));

    $other = new Money($amount, new Currency('EUR'));
    $this->assertFalse($usd->hasSameAmount($other));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::equals
   */
  public function equals()
  {
    $usd = new Money(0, new Currency('USD'));
    $eur = new Money(0, new Currency('EUR'));

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
    $usd    = new Money($amount, new Currency('USD'));
    $eur    = new Money($amount, new Currency('EUR'));

    $this->assertEquals($amount, $usd->getAmount());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::getCurrency
   */
  public function getCurrency()
  {
    $usd = new Money(1, new Currency('USD'));
    $eur = new Money(1, new Currency('EUR'));

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
    $eur = new Money(1, new Currency('EUR'));

    $expected = new Money(2, new Currency('EUR'));
    $this->assertEquals($expected, $eur->add($eur));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::subtract
   */
  public function subtract()
  {
    $eur1 = new Money(1, new Currency('EUR'));
    $eur2 = new Money(1, new Currency('EUR'));

    $expected = new Money(0, new Currency('EUR'));
    $this->assertEquals($expected, $eur1->subtract($eur2));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::negate
   */
  public function negate()
  {
    $amount = 1.0;
    $usd    = new Money($amount, new Currency('USD'));

    $expected = new Money(-$amount, new Currency('USD'));
    $this->assertEquals($expected, $usd->negate());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::multiply
   */
  public function multiply()
  {
    $amount = 1.0;
    $usd    = new Money($amount, new Currency('USD'));

    $multiplier = 2;
    $expected   = new Money((float) $amount * $multiplier, new Currency('USD'));
    $this->assertEquals($expected, $usd->multiply($multiplier));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::divide
   */
  public function divide()
  {
    $amount  = 20.;
    $divisor = 2.0;
    $usd     = new Money($amount, new Currency('USD'));

    $expected = new Money($amount / $divisor, new Currency('USD'));
    $this->assertEquals($expected, $usd->divide($divisor));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::compare
   */
  public function compare()
  {
    $currency = new Currency('USD');
    $money1   = new Money(1.0, $currency);
    $money2   = new Money(2.0, $currency);

    $this->assertEquals(-1, $money1->compare($money2));
    $this->assertEquals(1, $money2->compare($money1));
    $this->assertEquals(0, $money1->compare($money1));
  }

  /**
   * @test
   * @depends compare
   * @covers mitgedanken\Monetary\Money::greaterThan
   */
  public function _greaterThan()
  {
    $amount = 20.0;
    $eur    = new Money(1.0, new Currency('EUR'));

    $other = new Money($amount + 10, new Currency('EUR'));
    $this->assertTrue($other->greaterThan($eur));
    $this->assertFalse($eur->greaterThan($other));
  }

  /**
   * @test
   * @depends compare
   * @covers mitgedanken\Monetary\Money::lessThan
   */
  public function _lessThan()
  {
    $amount = 20.0;
    $eur    = new Money(1, new Currency('EUR'));

    $other = new Money($amount + 10, new Currency('EUR'));
    $this->assertTrue($eur->lessThan($other));
    $this->assertFalse($other->lessThan($eur));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::__toString
   */
  public function testToString()
  {
    $eur = new Money(20, new Currency("EUR"));
    $this->assertEquals("20 EUR ''", $eur);
  }

}
