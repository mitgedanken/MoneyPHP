<?php

namespace mitgedanken\Monetary;

class CurrencyPairRepositoryTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var CurrencyPairRepository
   */
  protected $object;

  /**
   * @var \mitgedanken\TestingOnly\CurrencyPairCriteriaMock
   */
  protected $mock;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new CurrencyPairRepository();
    $this->mock = new \mitgedanken\TestingOnly\CurrencyPairCriteriaMock();
  }

  /**
   * @test
   */
  public function implementsCountable()
  {
    $this->assertTrue(\is_a($this->object, 'Countable'));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\CurrencyPairRepository::findBy
   */
  public function eurUsd()
  {
    $usd = new Currency('USD');
    $eur = new Currency('EUR');
    $pair = new CurrencyPair($usd, $eur);
    $this->object->attach($pair);
    $this->mock->set($usd, $eur);
    $expected = new \SplObjectStorage();
    $expected->attach($pair);
    $this->assertEquals($expected, $this->object->findBy($this->mock));

    $expected = new \SplObjectStorage();
    $this->mock->set($eur, $usd);
    $this->assertEquals($expected, $this->object->findBy($this->mock));
  }
}
