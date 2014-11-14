<?php

// last edit: 25/04/14

namespace mitgedanken\Monetary;

use mitgedanken\Monetary\Interfaces\ConfigurationContainer as ContainerInterface;

class MoneyBagTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function implementsCountable()
    {
        $moneyBag = new MoneyBag(0, new Currency('EUR'));
        $this->assertTrue(\is_a($moneyBag, '\Countable'));
    }

    /**
     * @test
     */
    public function initiatedMoneyBagCountsAsOne()
    {
        $bag = new MoneyBag(1, new Currency('USD'));
        $this->assertEquals(1, $bag->count());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\MoneyBag::addWholeBag
     */
    public function addDifferentCurrency()
    {
        $bag = new MoneyBag(1, new Currency('USD'));
        $bag->addDifferentCurrency(new Money(1, new Currency('EUR')));
        $this->assertEquals(2, $bag->count());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\MoneyBag::containsMoney
     */
    public function containsMoney()
    {
        $bag = new MoneyBag(1, new Currency('USD'));
        $money = new Money(2, new Currency('EUR'));

        $this->assertTrue($bag->containsMoney($bag));

        $bag->addDifferentCurrency($money);
        $this->assertTrue($bag->containsMoney($money));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\MoneyBag::deleteMoney
     * @depens containsMoney
     */
    public function deleteMoney()
    {
        $bag1 = new MoneyBag(1, new Currency('USD'));
        $bag2 = new MoneyBag(2, new Currency('EUR'));

        $bag1->deleteMoney($bag1);
        $this->assertTrue($bag1->containsMoney($bag1));

        $bag1->deleteMoney($bag2);
        $this->assertFalse($bag1->containsMoney($bag2));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\MoneyBag::add
     */
    public function add()
    {
        $bag = new MoneyBag(1, new Currency('USD'));
        $this->assertEquals(1, $bag->count());

        $bag->addDifferentCurrency(new MoneyBag(2, new Currency('EUR')));
        $this->assertEquals(2, $bag->count());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\MoneyBag::getMoneyIn
     * @depens add
     */
    public function getMoneyInSameCurrency()
    {
        $bag = new MoneyBag(1, new Currency('EUR'));
        $bag->add(new Money(2, new Currency('EUR')));
        $this->assertEquals(1, $bag->count());

        $expected = new Money(3, new Currency('EUR'));
        $this->assertEquals($expected, $bag->getMoneyIn(new Currency('EUR')));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\MoneyBag::getMoneyIn
     * @depens add
     */
    public function getMoneyInDifferentCurrency()
    {
        $bag = new MoneyBag(1, new Currency('EUR'));
        $bag->addDifferentCurrency(new Money(2, new Currency('USD')));
        $this->assertEquals(2, $bag->count());

        $expected = new Money(2, new Currency('USD'));
        $this->assertEquals($expected, $bag->getMoneyIn(new Currency('USD')));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\MoneyBag::getTotalOf
     */
    public function getTotalOf()
    {
        $qqq = new MoneyBag(2, new Currency('QQQ'));
        $this->assertEquals(2, $qqq->getTotalOf(new Currency('QQQ')));

        $qqq->addDifferentCurrency(new MoneyBag(1, new Currency('PPP')));
        $this->assertEquals(1, $qqq->getTotalOf(new Currency('PPP')));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\MoneyBag::getTotalValue
     */
    public function getTotalValue()
    {
        $currencyUsd = new Currency('USD');
        $currencyEur = new Currency('EUR');


        $bag1 = new MoneyBag(1, $currencyUsd);
        $this->assertEquals(1, $bag1->getTotalValue());

        $bag2 = new MoneyBag(2, $currencyUsd);
        $bag1->add($bag2);
        $this->assertEquals(3, $bag1->getTotalValue());


        $currencyPairStorage = new \SplObjectStorage();
        $currencyPair = new CurrencyPair($currencyUsd, $currencyEur, 2);
        $currencyPairStorage->attach($currencyPair);
        $repository = new CurrencyPairRepository($currencyPairStorage);

        $scale = ContainerInterface::DEFAULT_SCALE;
        $bagUsd = new MoneyBag(5, $currencyUsd, $scale, $repository);
        $bagEur = new MoneyBag(10, new Currency('EUR'));

        $bagUsd->addDifferentCurrency($bagEur);
        $this->assertEquals(10, $bagUsd->getTotalValue());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\MoneyBag::equal
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
