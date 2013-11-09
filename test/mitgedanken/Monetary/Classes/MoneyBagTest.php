<?php

namespace mitgedanken\Monetary\Classes;

class MoneyBagTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @test
   */
  public function ImplimentationCountable()
  {
    $eur = new MoneyBag(0, new Currency('EUR'));
    $this->assertTrue(\is_a($eur, 'Countable'));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::addMoney
   */
  public function addMoney()
  {
    $repository = new CurrencyPairRepository(new \SplObjectStorage());

    $this->assertEquals(0, $repository->count());

    $repository->attach(new CurrencyPair(new Currency('PPP'), new Currency('QQQ'), 1));
    $this->assertEquals(1, $repository->count());

    $repository->attach(new CurrencyPair(new Currency('PPP'), new Currency('QQQ'), 1));
    $this->assertEquals(2, $repository->count());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::addMoneyBag
   * @todo   Implement testAddMoneyBag().
   */
  public function addMoneyBag()
  {
    $bag = new MoneyBag(1, new Currency('USD'));
    $this->assertEquals(1, $bag->count());

    $bag->addMoney(new Money(1, new Currency('USD')));
    $this->assertEquals(1, $bag->count());

    $bag->addMoneyBag(new MoneyBag(1, new Currency('PPP')));
    $this->assertEquals(2, $bag->count());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::add
   * @depends addMoney
   * @depends addMoneyBag
   */
  public function add()
  {
    $bag = new MoneyBag(1, new Currency('USD'));

    $result = $bag->add(new Money(1, new Currency('USD')));
    $this->assertEquals(1, $result->count());

    $result = $bag->addMoneyBag(new MoneyBag(1, new Currency('EUR')));
    $this->assertEquals(2, $bag->count());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::getMoneyIn
   */
  public function getMoneyIn()
  {
    $bag      = new MoneyBag(1, new Currency('EUR'));
    $money    = $bag->addMoney(new Money(1, new Currency('EUR')));
    $result   = $bag->getMoneyIn(new Currency('EUR'));
    $expected = new Money(2, new Currency('EUR'));
    $this->assertEquals($expected, $money);

    $bag      = new MoneyBag(1, new Currency('EUR'));
    $money    = $bag->addMoney(new Money(2, new Currency('USD')));
    $result   = $bag->getMoneyIn(new Currency('USD'));
    $expected = new Money(2, new Currency('USD'));
    $this->assertEquals($expected, $money);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::getTotalOf
   */
  public function getTotalOf()
  {
    $qqq = new MoneyBag(2, new Currency('QQQ'));
    $this->assertEquals(2, $qqq->getTotalOf(new Currency('QQQ')));

    $qqq->addMoney(new Money(1, new Currency('PPP')));
    $this->assertEquals(1, $qqq->getTotalOf(new Currency('PPP')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::update
   */
  public function toTotalAmount()
  {
    $bag      = new MoneyBag(1, new Currency('USD'));
    $currency = new Currency('USD');
    $bag->toTotalAmount($currency);
    $this->assertEquals(1, $bag->getAmount());
  }

  /**
   * @test
   */
  public function equal()
  {
    $usd = new MoneyBag(1, new Currency('USD'));
    $eur = new MoneyBag(1, new Currency('EUR'));

    $this->assertTrue($usd->equals($usd));

    $this->assertFalse($usd->equals($eur));
    $this->assertFalse($eur->equals($usd));

    $other = new \stdClass();
    $this->assertFalse($eur->equals($other));
  }

}
