<?php

define('ROOT', __DIR__);
require_once ROOT . '/scripts/init_monetary.php';
// define('MODULE_AUTOLOAD')
use mitgedanken\Monetary\Money,
    mitgedanken\Monetary\Currency;

$amount = 20;
$usd = new Money($amount, new Currency('USD'));
$eur = new Money($amount, new Currency('EUR'));

Money::EUR($amount);