<?php

namespace mitgedanken\Monetary;

class MoneyConverterTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var CurrencyPair
   */
  protected $pair;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->pair = new CurrencyPair(new Currency('PPP'), new Currency('QQQ'), 1.95583);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\Interfaces\MoneyConverter::exchange
   * @todo   Implement testExchange().
   */
  public function convert() {
    $money    = new Money(1, new Currency('PPP'));
    $expected = new Money(1, new Currency('QQQ'));
    $this->assertEquals($expected, Exchange::convertMoney($money, $this->pair));
  }

}
