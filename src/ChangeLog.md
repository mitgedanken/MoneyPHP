CHANGE LOG
==========
Attention!
In alpha versions functions or classes can be deleted without notice.

THIS REPOSITORY IS DEPRICATED!
NO BUGFIXES!

0.0.1-alpha+2714m0
--------------
maximum-stability: dev

! New version schema !
MAJOR.MINOR.PATCH+[calender week,00..99][day of week,0..9][year,00..99]
or
MAJOR.MINOR.PATCH+(...)m[milestone, 0..9]
If [milestone] is 0 non milestone is reached.

! PHP >=5.5.* is now needed !
Many changes have happened ...

* TODO
  - Writing more test cases.
  - Spliting in two projects (Core and Extention) [DONE]
  - Delete type hint tests [DONE]

 * Changes
  - [mv] All mitgedanken\Monetary\Classes\* moved to mitgedanken\Monetary\
  - Exception codes changed for systematical assignment.
  - Functions:
    - [added] ./Functions/Errorhandler
  - Deprecated classes:
    - [/] Classes/Algorithms
      - Replaced by Calculator
  - Added classes:
    - Interfaces\MonetaryConfiguration
    - Excpetions\Error
   - Renamed classes:
    - MonetaryStorage to MoneyStorage to say what it is exectly.
    - Interfaces\MonetaryStorage to Interfaces\MoneyStorage ^^^
  - Added interfaces:
    - Monetary
    - Money
  - Added exceptions:
    - [added] BadMethodCall
    - [added] UnsupportedType
    - [added] UnexpectedValue
    - [added] NoSuitableCurrency
    - [added] EmptyStorageNotAllowed
    - [+x] DifferentCurrencies extends now UnexpectedValue
  - Changed classes:
    - Exceptions\Exception
       - [O->] implements Monetary
    - Money
        - [+f] new functions
        - [O->] implements Money
    - MoneyBag
        - Changed order of parameters.
        - Improvements and bug fixing.
        - [O->] [O/>] Uses Interface\MoneyStorage instead of MonetaryStorage.
        - Expects now Interface\MoneyStorage if set [optional].
            - ! You are now forced to use an Interface\MoneyStorage implementation in personal responsibility.
        - [added] ::toMoney(..)
        - [added] ::addDifferentCurrency(..)
        - [added] ::getTotalValue(..)
        - [added] ::containsMoney(..)
    - SlenderMoney
        - [added] ::slenderizeStorage; static.
        - [O->] implements Money
    - CurrencyPairRepository
        - Improvements.
    - Abstracts\Money
        - Edited, a little bit.
        - implements Interfaces\Monetary
        - [deleted] ::?? (forgotten)
    - Abstracts\Money
        - [+f] new functions implemented (forgotten)
        - [deleted] ::addMoneyBag(..)
        - [deleted] ::addMoney(..)
        - [deleted] ::toTotalAmount(..)
        - [deleted] ::clear(..)
        - [deleted] ::addSlenderizedStorage(..)
        - [deleted] ::getSlenderizedStorage(..)
        - [deleted] ::?? (forgotten)
  - Some test files are changed.

 * Known issues
    - none; please report!


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

* Some improvements and fixes

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
 - \mitgedanken\Monetary\(.*)Interface replaced by \mitgedanken\Monetary\Interfaces\$1
 - \mitgedanken\Money::allocate deleted
 - New method \mitgedanken\MoneyBag::allocate

* New exception tests
 - MoneyExceptionTest
 - MoneyConverterTest

* Some improvements and fixes.


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


0
-------------
Divison by zero.