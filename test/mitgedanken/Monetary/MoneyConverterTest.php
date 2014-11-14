<?php

namespace mitgedanken\Monetary;

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

    $numberClassName = Money::getConfigurationObject()->getExchangeClassName();
    $money = new Money(new $numberClassName(1), new Currency('PPP'));
    $expected = new Money(new $numberClassName(1.95583), new Currency('QQQ'));
    $this->assertEquals($expected, Exchange::convert($money, $pair));
  }

}
