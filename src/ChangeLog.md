CHANGE LOG
==========


13.44.0-alpha
-------------
maximum-stability: dev

* Changes
 - MoneyConverter replaced by Exchange.
 - new class Algorithms.
 - Money: now without algorithms; see class Algorihtms.
 - MoneyBag without allocation algorithms; moved to class Algorithms


13.43.1-alpha
-------------
maximum-stability: dev

* Changes
 - "Operation: We need interfaces!" canceled.
 - No more NullCurrency nightmares
 - MoneyBag added methods: getSlenderizedStorage, addSlenderizedStorage

* Refactorings
 - Interfaces\Money refactored to Abstracts\Money
 - Money refactored (adapted)

* New classes
 - CurrencyPairRepository
 - SlenderMoney
 - Abstracts\CurrencyPairCriteria

* Some improvements an fixes

13.26.1-alpha
-------------
maximum-stability: dev

* Changes
 - ...

13.22.2-alpha
-------------
* Changes
 - CHANGES replaced by ChangeLog.md
 - ExchangeRates* renamed to MoneyConverter*
 - \mitgedanken\Monetary\Exception\(.*) replaced by \mitgedanken\Monetary\Exceptions\$1
 - \mitgedanken\Monetary\(.*)Interface replaced by \mitgedanken\Monetary\Interface\$1
 - \mitgedanken\Money::allocate deleted
 - New method \mitgedanken\MoneyBag::allocate

* New exception tests
 - MoneyExceptionTest
 - MoneyConverterTest

* Some improvements an fixes.


13.22.1-alpha
-------------
maximum-stability: dev
More features and some fixes and improvements.

* Changes
 - MoneyBag: renamed amountToTotal to toTotalAmount
 - MoneyExtended merged with Money to Money.
 - new file: CHANGES ;)
 - composer.json updated
 - \mitgedanken\Monetary\initiate.php replaced by \scripts\init_monetary.php

* Added features for MoneyBag
 - deleteMoney,
 - subtract,
 - multiply,
 - divide

* Added exceptions
 - RuntimeException,
 - BadFunctionCall,
 - NoExchangeRates

* Some fixes and improvements

* TODO
 - Writing more tests (manly exception tests)


13.22.0-alpha
-------------
maximum-stability: dev
Initial release.