<?php

// last edit: 25/04/14

namespace mitgedanken\Monetary;

class MonetaryStorageTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function implimentsCountable() {
        $this->assertTrue(\is_a(new MonetaryStorage(), '\Countable'));
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\MonetaryStorage::\count
     */
    public function testCount() {
        $storage = new MonetaryStorage();
        $this->assertEquals(0, $storage->count());

        $number = new BigNumber(1);
        $storage->attach(new Money($number, new Currency('EUR')));
        $this->assertEquals(1, $storage->count());
        $storage->attach(new Money($number, new Currency('USD')));
        $this->assertEquals(2, $storage->count());
    }

    /**
     * @test
     * @covers mitgedanken\Monetary\MonetaryStorage::contains
     */
    public function testContains() {
        $number = new BigNumber(1);
        $eur = new Money($number, new Currency('EUR'));
        $storage = new MonetaryStorage();

        $this->assertFalse($storage->contains($eur));
        $storage->attach($eur);
        $this->assertTrue($storage->contains($eur));
    }

}
