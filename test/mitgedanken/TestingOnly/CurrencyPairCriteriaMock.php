<?php

namespace mitgedanken\TestingOnly;

/**
 * CurrencyPairCriteria only for testing.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class CurrencyPairCriteriaMock
        implements \mitgedanken\Monetary\Interfaces\CurrencyPairCriteria {

  private $baseCurrency;
  private $counterCurrency;

  public function set($baseCurrency, $counterCurrency)
  {
    $this->baseCurrency = $baseCurrency;
    $this->counterCurrency = $counterCurrency;
  }

  public function getBaseCurrency()
  {
    return $this->baseCurrency;
  }

  public function getCounterCurrency()
  {
    return $this->counterCurrency;
  }
}
