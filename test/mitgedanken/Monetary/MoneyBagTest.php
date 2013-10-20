<?php

namespace mitgedanken\Monetary;

class MoneyBagTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var MoneyBag
   */
  protected $object;

  /**
   *
   * @var CurrencyPairRepository
   */
  protected $repository;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $pair             = new CurrencyPair(new Currency('USD'), new Currency('EUR'), 1);
    $this->object     = new MoneyBag(0, new Currency('EUR'));
    $this->repository = new CurrencyPairRepository();
    $this->repository->attach($pair);
  }

  /**
   * @test
   */
  public function implementsCountable() {
    $this->assertTrue(\is_a($this->object, 'Countable'));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::addMoney
   */
  public function addMoney1() {
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
  public function addMoneyBag() {
    $this->object->addMoney(new Money(20, new Currency('USD')));
    $this->object->addMoneyBag(new MoneyBag(0, new Currency('PPP')));
    $this->assertEquals(3, $this->object->count());
  }

  /**
   * @test
   */
  public function addMoney2() {
    $this->object->addMoney(new Money(20, new Currency('USD')));
    $this->object->addMoneyBag(new MoneyBag(1, new Currency('USD')));
    $this->assertEquals(2, $this->object->count());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::add
   * @depends addMoney
   * @depends addMoneyBag
   */
  public function add() {
    $this->object->add(new Money(20, new Currency('USD')));
    $this->assertEquals(2, $this->object->count());

    $this->object->addMoneyBag(new MoneyBag(0, new Currency('USD')));
    $this->assertEquals(2, $this->object->count());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::getMoneyIn
   */
  public function getMoneyIn() {
    $addend = new Money(20, new Currency('USD'));
    $this->object->add($addend);
    $this->assertEquals($addend, $this->object->getMoneyIn(new Currency('EUR'), $this->repository));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::getTotalIn
   */
  public function getTotalIn() {
    $this->object->add(new Money(20, new Currency('USD')));
    $res = $this->object->getTotalIn(new Currency('USD'), $this->repository);
    $this->assertEquals(20, $res);
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::getTotalOf
   */
  public function getTotalOf() {
    $this->object->add(new Money(20, new Currency('PPP')));
    $this->assertEquals(20, $this->object->getTotalOf(new Currency('PPP')));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\MoneyBag::update
   */
  public function toTotalAmount() {
    $this->object->add(new Money(20, new Currency('USD')));
    $this->object->toTotalAmount($this->repository);
    $this->assertEquals(20, $this->object->getAmount());
  }

}
