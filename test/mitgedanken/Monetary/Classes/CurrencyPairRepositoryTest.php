<?php

namespace mitgedanken\Monetary\Classes;

/*
 *
 */

class CurrencyPairRepositoryTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @test
   */
  public function ImplimentationCountable()
  {
    $repository = new CurrencyPairRepository(new \SplObjectStorage());
    $this->assertTrue(\is_a($repository, 'Countable'));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\CurrencyPairRepository::findBy
   */
  public function eurUsd()
  {
    $repository = new CurrencyPairRepository(new \SplObjectStorage());
    $mock       = new \mitgedanken\TestingOnly\CurrencyPairCriteriaMock();

    $usd  = new Currency('USD');
    $eur  = new Currency('EUR');
    $pair = new CurrencyPair($usd, $eur, 0);
    $repository->attach($pair);
    $mock->set($usd, $eur);

    $expected = new \SplObjectStorage();
    $expected->attach($pair);
    $this->assertEquals($expected, $repository->findBy($mock));

    $expected = new \SplObjectStorage();
    $mock->set($eur, $usd);
    $this->assertEquals($expected, $repository->findBy($mock));
  }

}
