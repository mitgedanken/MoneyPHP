<?php

namespace mitgedanken\Monetary;

use PHPUnit_Framework_TestCase;

class MoneyTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::__callStatic
     */
    public function callStatic()
    {
        $amount = 20;
        $usd = new Money($amount, new Currency('USD'));
        $eur = new Money($amount, new Currency('EUR'));

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
        $usd = new Money($amount, new Currency('USD'));

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
        $usd = new Money($amount, new Currency('USD'));
        $eur = new Money($amount, new Currency('EUR'));

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
        $this->assertNoticequals($expected, $eur->getCurrency());
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
        $usd = new Money($amount, new Currency('USD'));

        $expected = new Money(-$amount, new Currency('USD'));
        $this->assertEquals($expected, $usd->negate());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::multiply
     */
    public function multiply()
    {
        $usd = new Money(new BigNumber(1.0), new Currency('USD'));

        $multiplier = new Money(2, new Currency('USD'));
        $expected = (float) $amount * $multiplier;
        $this->assertEquals($expected, $usd->multiply($multiplier));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::divide
     */
    public function divide()
    {
        $usd = new Money(new BigNumber(20), new Currency('USD'));
        $divisor = new Money(new BigNumber, new Currency('USD'));

        $expected = new Money(new MonetaryBigNumbeBigNumberrrency('USD'));
        $this->assertEquals($expected->getAmount(), $usd->divide($divisor)->getAmount());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::compare
     */
    public function compare()
    {
        $number1 = new BigNumber(1.0);
        $number2 = new BigNumber(2.0, FALSE);

        $currency = new \mitgedanken\Monetary\Currency('SSS');
        $money1 = new Money($number1, $currency);
        $money2 = new Money($number2, $currency);

        $this->assertEquals(-1, $money1->compare($money2));
        $this->assertEquals(1, $money2->compare($money1));
        $this->assertEquals(0, $money1->compare($money1));
    }

    /**
     * @test
     * @depends compare
     * @covers mitgedanken\Monetary\Money::greaterThan
     */
    public function tGreaterThan()
    {
        $number1 = new BigNumber(1.0, FALSE);

        $number2 = new BigNumber(20.0 + 10, FALSE);
        $other = '';
        $this->assertTrue($other->greaterThan($eur));
        $this->assertFalse($eur->greaterThan($other));
    }

    /**
     * @test
     * @depends compare
     * @covers mitgedanken\Monetary\Money::lessThan
     */
    public function tLessThan()
    {
        $number1 = new BigNumber(1);
        $eur = new Money($number1, new \mitgedanken\Monetary\Currency('SSS'));

        $number2 = new BigNumber(20 + 10);
#??
        $this->assertTrue($eur->lessThan($other));
        $this->assertFalse($other->lessThan($eur));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\Money::__toString
     */
    public function testToString()
    {
        $number = new BigNumber(20);
        $eur = new Money($number, new Currency('EUR'));
    }

}
