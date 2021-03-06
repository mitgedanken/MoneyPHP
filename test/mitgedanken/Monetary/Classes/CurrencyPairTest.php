<?php

namespace mitgedanken\Monetary\Classes;

/**
 *
 */
class CurrencyPairTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @var CurrencyPair
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $usd = new Currency('USD');
    $eur = new Currency('EUR');

    $this->object = new CurrencyPair($usd, $eur, 0.0);
  }

  public function getRatio()
  {
    $usd = new Currency('USD');
    $eur = new Currency('EUR');

    $object = new CurrencyPair($usd, $eur, 1);
    $this->assertEquals(1.0, $object->getRatio());
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\CurrencyPair::has
   * @todo   Implement testHas().
   */
  public function has()
  {
    $usd    = new Currency('USD');
    $eur    = new Currency('EUR');
    $object = new CurrencyPair($usd, $eur, 0.0);

    $this->assertTrue($this->object->has($usd));
    $this->assertTrue($this->object->has($eur));

    $ppp = new Currency('PPP');
    $this->assertFalse($this->object->has($ppp));
  }

  /**
   * @test
   * @covers mitgedanken\Monetary\CurrencyPair::equals
   * @todo   Implement testEquals().
   */
  public function equals()
  {
    $usd = new Currency('USD');
    $eur = new Currency('EUR');

    $object = new CurrencyPair($usd, $eur, 0.0);
    $this->assertTrue($this->object->equals($object));

    $object = new CurrencyPair($eur, $usd, 0.0);
    $this->assertTrue($this->object->equals($object));


    $ppp    = new Currency('PPP');
    $object = new CurrencyPair($ppp, $usd, 0.0);
    $this->assertFalse($this->object->equals($object));

    $object = new CurrencyPair($eur, $ppp, 0.0);
    $this->assertFalse($this->object->equals($object));
  }

}
