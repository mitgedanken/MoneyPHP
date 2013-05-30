<?php

namespace mitgedanken\Monetary;

class MoneyBagTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var MoneyBag
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $moneyConverter = new MoneyConverter();
    $pair = new CurrencyPair(new Currency('USD'), new Currency('EUR'));
    $moneyConverter->attach($pair, array(0 => 2, 1 => 3));
    $this->object = new MoneyBag(0, new Currency('EUR'), $moneyConverter);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::addMoney
   */
  public function addMoney()
  {
    $this->assertEquals(1, $this->object->count());


    $addend = new Money(0, new Currency('EUR'));
    $this->object->addMoney($addend);
    $this->assertEquals(1, $this->object->count());


    $addend = new Money(20, new Currency('EUR'));
    $this->object->addMoney($addend);
    $this->assertEquals(1, $this->object->count());

    $this->object->addMoney($addend);
    $this->assertEquals(1, $this->object->count());


    $addend = new Money(20, new Currency('USD'));
    $this->object->addMoney($addend);
    $this->assertEquals(2, $this->object->count());

    $addend = new Money(0, new Currency('USD'));
    $this->object->addMoney($addend);
    $this->assertEquals(2, $this->object->count());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::addMoneyBag
   * @todo   Implement testAddMoneyBag().
   */
  public function addMoneyBag()
  {
    $addend = new Money(20, new Currency('USD'));
    $this->object->addMoney($addend);
    $bag = new MoneyBag(0, new Currency('PPP'));
    $this->object->addMoneyBag($bag);
    $this->assertEquals(3, $this->object->count());
  }

  /**
   * @test
   */
  public function addMoneyBag2()
  {
    $addend = new Money(20, new Currency('USD'));
    $this->object->addMoney($addend);

    $bag = new MoneyBag(0, new Currency('USD'));
    $this->object->addMoneyBag($bag);
    $this->assertEquals(2, $this->object->count());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::add
   * @depends addMoney
   * @depends addMoneyBag
   */
  public function add()
  {
    $addend = new Money(20, new Currency('USD'));
    $this->object->add($addend);
    $this->assertEquals(2, $this->object->count());

    $bag = new MoneyBag(0, new Currency('USD'));
    $this->object->addMoneyBag($bag);
    $this->assertEquals(2, $this->object->count());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::getMoneyIn
   */
  public function getMoneyIn()
  {
    $addend = new Money(20, new Currency('USD'));
    $this->object->add($addend);
    $this->assertEquals($addend, $this->object->getMoneyIn(new Currency('USD')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::getTotalIn
   */
  public function getTotalIn()
  {
    $addend = new Money(20, new Currency('USD'));
    $this->object->add($addend);
    $this->assertEquals(20, $this->object->getTotalIn(new Currency('USD')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::getTotalOf
   */
  public function getTotalOf()
  {
    $addend = new Money(20, new Currency('PPP'));
    $this->object->add($addend);
    $this->assertEquals(20, $this->object->getTotalOf(new Currency('PPP')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::update
   */
  public function toTotalAmount()
  {
    $addend = new Money(20, new Currency('USD'));
    $this->object->add($addend);
    $this->object->toTotalAmount();
    $this->assertEquals(30, $this->object->getAmount());
  }
}
