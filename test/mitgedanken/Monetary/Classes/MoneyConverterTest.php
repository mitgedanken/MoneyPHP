<?php

namespace mitgedanken\Monetary\Classes;

class MoneyConverterTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @test
   * @covers mitgedanken\Monetary\Interfaces\MoneyConverter::exchange
   * @todo   Implement testExchange().
   */
  public function convert()
  {
    $pair = new CurrencyPair(new Currency('PPP'), new Currency('QQQ'), 1.95583);

    $money    = new Money(1, new Currency('PPP'));
    $expected = new Money(1, new Currency('QQQ'));
    $this->assertEquals($expected, Exchange::convertMoney($money, $pair));
  }

}
