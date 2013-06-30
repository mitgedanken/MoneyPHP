<?php

namespace mitgedanken\Monetary;
use PHPUnit_Framework_TestCase;
use mitgedanken\Monetary\Money,
    mitgedanken\Monetary\Currency;

class MoneyTest extends PHPUnit_Framework_TestCase {

  /** Test amount. @var int */
  protected $amount;

  /** @var mitgedanken\Monetary\Money */
  protected $usd;

  /** @var mitgedanken\Monetary\Money */
  protected $eur;

  protected function setUp()
  {
    $this->amount = 20;
    $this->usd = new Money($this->amount, new Currency('USD'));
    $this->eur = new Money($this->amount, new Currency('EUR'));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::__callStatic
   */
  public function callStatic()
  {
    $this->assertEquals($this->eur, Money::EUR($this->amount));
    $this->assertEquals($this->usd, Money::USD($this->amount));
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
   * @covers mitgedanken\Monetary\Money::divide
   */
  public function divide()
  {
    $expected = new Money(1, new Currency('EUR'));
    $this->assertEquals($expected, $this->eur->divide($this->amount));
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
   * @test
   * @covers mitgedanken\Monetary\Money::__toString
   */
  public function testToString()
  {
    $this->assertEquals("$this->amount EUR ''", $this->eur);
  }
}
