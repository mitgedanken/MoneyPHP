<?php

namespace mitgedanken\Monetary;

/*
 *
 */

class CurrencyPairRepositoryTest extends \PHPUnit_Framework_TestCase {
    /*
     * @test
     * @expectedException \mitgedanken\Monetary\Exceptions\EmptyStorageNotAllowed
     */

    public function ExceptionOnEmptyStorage()
    {
        $repository = new CurrencyPairRepository(new \SplObjectStorage());
    }

    /**
     * @test
     */
    public function ImplimentationCountable()
    {
        $storage = new \SplObjectStorage();
        $storage->attach(new \stdClass());
        $repository = new CurrencyPairRepository($storage);
        $this->assertTrue(\is_a($repository, '\Countable'));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\CurrencyPairRepository::findBy
     */
    public function eurUsd()
    {
        $usd = new Currency('USD');
        $eur = new Currency('EUR');
        $pair = new CurrencyPair($usd, $eur, 0);
        $storage = new \SplObjectStorage();
        $storage->attach($pair);
        $repository = new CurrencyPairRepository($storage);

        $mock = new \mitgedanken\TestingOnly\CurrencyPairCriteriaMock();
        $mock->set($usd, $eur);

        $expected = new \SplObjectStorage();
        $expected->attach($pair);
        $this->assertEquals($expected, $repository->findBy($mock));

        $expected = new \SplObjectStorage();
        $mock->set($eur, $usd);
        $this->assertEquals($expected, $repository->findBy($mock));
    }

}
