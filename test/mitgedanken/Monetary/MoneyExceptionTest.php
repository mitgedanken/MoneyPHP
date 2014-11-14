<?php

namespace mitgedanken\Monetary;

class MoneyExceptionTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::__construct
   * @expectedException \mitgedanken\Monetary\Exceptions\InvalidArgument
   */
  public function constructInvalidArgument()
  {
    new Money(new \stdClass(), new Currency('PPP'));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::__construct
   * @expectedException \mitgedanken\Monetary\Exceptions\InvalidArgument
   */
  public function constructInvalidArgument2()
  {
    new Money(TRUE, new Currency('PPP'));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::add
   * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function addDifferentCurrencies()
  {
    $money = new Money(0, new Currency('PPP'));
    $money->add(new Money(0, new Currency('QQQ')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::subtract
   * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function subtractDifferentCurrencies()
  {
    $money = new Money(0, new Currency('PPP'));
    $money->subtract(new Money(0, new Currency('QQQ')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::multiply
   * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function multiplyDifferentCurrencies()
  {
    $money = new Money(0, new Currency('PPP'));
    $money->multiply(new Money(0, new Currency('QQQ')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::divide
   * @expectedException \mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function divideDifferentCurrencies()
  {
    $money = new Money(0, new Currency('PPP'));
    $money->divide(new Money(0, new Currency('QQQ')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::compare
   * @expectedException mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function compareDifferentCurrencies()
  {
    $money = new Money(0, new Currency('PPP'));
    $money->compare(new Money(0, new Currency('QQQ')));
  }

  /**
   * @test If they currencies are unequal, an exception must be thrown.
   * @depends compareException
   * @covers mitgedanken\Monetary\Money::greaterThan
   * @expectedException mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function greaterThanDifferentCurrencies()
  {
    $other = new Money($this->amount + 10, new Currency('PPP'));
    $other->greaterThan(new Money(0, new Currency('QQQ')));
  }

  /**
   * @test If they currencies are unequal, an exception must be thrown.
   * @depends compareException
   * @covers mitgedanken\Monetary\Money::lessThan
   * @expectedException mitgedanken\Monetary\Exceptions\DifferentCurrencies
   */
  public function lessThanDifferentCurrencies()
  {
    $other = new Money($this->amount + 10, new Currency('PPP'));
    $other->lessThan(new Money(0, new Currency('QQQ')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Money::divide
   * @expectedException mitgedanken\Monetary\Exceptions\DivisionByZero
   */
  public function divisionByZero()
  {
    $money = new Money(1.0, new Currency('PPP'));
    $money->divide(0);
  }

}
