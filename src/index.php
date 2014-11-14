<?php

\define('ROOT', __DIR__);
require_once ROOT . '/init_monetary.php';
require_once 'mitgedanken/Monetary/Functions/ErrorHandler.php';

// define('MODULE_AUTOLOAD')
// autoload
//use mitgedanken\TestingOnly;
//use mitgedanken\Monetary\Money,
//    mitgedanken\Monetary\Currency,
//    mitgedanken\Monetary\BigNumber,
//    mitgedanken\Monetary\Calculator;
//use mitgedanken\Monetary\Abstracts\BigNumber as NumberAbstracts;
//use mitgedanken\Monetary\Configuration\DefaultConfiguration;
//use mitgedanken\Monetary\Factories\Money as MoneyFactory;


var_dump(\class_implements(mitgedanken\Monetary\BigNumber::class));


print '##################';

//$m = MoneyFactory::create(1, 'eur');
//var_dump($m->add($m));
//new Money(new BigNumber(1), new Currency('###'));
//$t = new Calculator();
//$t->addition(2, 2);
//var_dump($t);
#-------------------------------------------------------------------------------
$print = function ($string) {
    print nl2br($string);
};
