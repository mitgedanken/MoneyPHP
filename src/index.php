<?php

define('ROOT', __DIR__);
require_once ROOT . '/mitgedanken/Monetary/initiate.php';
use mitgedanken\Monetary\MonetaryStorage,
    mitgedanken\Monetary\MoneyBag;
use mitgedanken\Monetary\Money,
    mitgedanken\Monetary\Currency,
    mitgedanken\Monetary\CurrencyPair,
    mitgedanken\Monetary\ExchangeRates;

$bag = new MonetaryStorage();
$currency = new Currency('EUR', 'Euro');

$rateStorage = new \SplObjectStorage();
$pair = new CurrencyPair(new Currency('EUR'), new Currency('USD'));
$rateStorage->attach($pair, array(0 => 10, 1 => 2));
$rates = new ExchangeRates($rateStorage);

$object = new MoneyBag(0, $currency, $rates, $bag);

$money = new Money(10, $currency);
$object->addMoney($money);


$money = new Money(20, new Currency('USD', 'United States Dollar'));
$object->addMoney($money);

$money = new Money(30, new Currency('USD', 'United States Dollar'));
$object->addMoney($money);


$object->getTotalOf(new Currency('USD'));
