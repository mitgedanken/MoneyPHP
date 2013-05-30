<?php

namespace mitgedanken\Monetary;

class MoneyConverterTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var MoneyConverter
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new MoneyConverter();
    $pair = new CurrencyPair(new Currency('PPP'), new Currency('QQQ'));
    $this->object->attach($pair, array(0 => 2, 1 => 3));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Interfaces\MoneyConverter::setNoSuitableException
   * @expectedException mitgedanken\Monetary\Exceptions\NoSuitableExchangeRate
   */
  public function setNoSuitableException()
  {
    $this->object->setNoSuitableException(TRUE);
    $toCurrency = new Currency('RRR');
    $this->object->convert(new Money(0, new Currency('PPP')), $toCurrency);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Interfaces\MoneyConverter::setNoSuitableException
   * @expectedException mitgedanken\Monetary\Exceptions\NoSuitableExchangeRate
   */
  public function setNoSuitableException2()
  {
    $this->object->setNoSuitableException(TRUE);
    $fromCurrency = new Currency('RRR');
    $this->object->convert(new Money(0, $fromCurrency), new Currency('PPP'));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Interfaces\MoneyConverter::exchange
   * @todo   Implement testExchange().
   */
  public function convert()
  {
    $money = new Money(20, new Currency('PPP'));
    $qqq = new Currency('QQQ');
    $expected = new Money(30, $qqq);
    $this->assertEquals($expected, $this->object->convert($money, $qqq));

    $money = new Money(30, new Currency('QQQ'));
    $ppp = new Currency('PPP');
    $expected = new Money(45, $ppp);
    $this->assertEquals($expected, $this->object->convert($money, $ppp));
  }
}

