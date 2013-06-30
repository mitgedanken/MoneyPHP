<?php

namespace mitgedanken\TestingOnly;

/**
 * CurrencyPairCriteria only for testing.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
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
