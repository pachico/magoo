# Pachico\Magoo

[![Build Status](https://travis-ci.org/pachico/magoo.svg?branch=master)](https://travis-ci.org/pachico/magoo) [![codecov.io](https://codecov.io/github/pachico/magoo/coverage.svg?branch=master)](https://codecov.io/github/pachico/magoo?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/226d0d2e91354a8eac06569a115c056c)](https://www.codacy.com/app/pachico/magoo)
[![Codacy Badge](https://api.codacy.com/project/badge/coverage/226d0d2e91354a8eac06569a115c056c)](https://www.codacy.com/app/pachico/magoo)
[![Code Climate](https://codeclimate.com/github/pachico/magoo/badges/gpa.svg)](https://codeclimate.com/github/pachico/magoo)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fc76535f-a2c6-41b9-91df-4176da6d60d1/big.png)](https://insight.sensiolabs.com/projects/fc76535f-a2c6-41b9-91df-4176da6d60d1)

Magoo is a PHP library that will mask sensitive data in strings. 
Built-in masks use regex to find credit card numbers, emails, etc. and will mask only those, leaving the rest of the strings intact. 
This might be useful, for instance, to log sensitive user input.

You can also mask strings that match your own regex or inject masking class as long as they implement a simple interface.

Multidimensional arrays can also be masked and use it can be used to mask [PSR-3](http://www.php-fig.org/psr/psr-3/) compliant logger libraries, such as Monolog.

Use the [issues](https://github.com/pachico/magoo/issues) page to request masks to implement.

## Table of contents

 - [Install](#install)
 - [Usage](#usage)
 - [Generic](#generic)
    - [Masks credit cards](#mask-credit-cards)
    - [Masks emails](#mask-emails)
    - [Mask by regex](#mask-by-regex)
    - [Reset](#reset)
    - [Custom masks](#custom-masks)
 - [Mask arrays](#mask-arrays)
 - [Mask PSR-3 logger](#mask-psr-3-logger)
 - [Testing](#Testing)
 - [Contributing](#contributing)
 - [Security](#security)
 - [Credits](#credits)
 - [License](#license)
 - [Help](#help)

## Install

Via Composer:
```
composer require pachico/magoo
```

## Usage

### Generic

This is a generic usage of the library.

```php
use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->pushCreditCardMask()
    ->pushEmailMask()
    ->pushByRegexMask('/(email)+/m');

$mySensitiveString = 'My email is roy@trenneman.com and my credit card is 6011792594656742';

echo $magoo->getMasked($mySensitiveString);

// 'My ***** is ***@trenneman.com and my credit card is ************6742'
```

### Mask credit cards

Credit card mask accepts a custom replacement.

```php
use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->pushCreditCardMask('·');

$mySensitiveString = 'This is my credit card number: 4111 1111 1111 1111.';

echo $magoo->getMasked($mySensitiveString);

// This is my credit card number: ······1111.
```

### Mask emails

Email mask accepts as optional parameters the replacement for local and domain parts.

```php
use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->pushEmailMask('$', '*');

$mySensitiveString = 'My email is pachicodev@gmail.com but I need privacy.';

echo $magoo->getMasked($mySensitiveString);

// My email is $$$$$$$$$$@********* but I need privacy.
```

### Mask by regex

Regex mask will replace matches with strings that are long as each individual match. It requires a regex and accepts custom replacement.

```php
use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->pushByRegexMask('(\d+)', '_');

$mySensitiveString = 'My telephone number is 639.639.639. Call me at 19:00!';

echo $magoo->getMasked($mySensitiveString);

// My telephone number is ___.___.___. Call me at __:__!
```

### Reset

You might want to use the same instance of Magoo in your application but not the same masks everytime. You can reset all masks at any time by using the reset() method.
```php
use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->pushCreditCardMask()->pushByRegexMask('(\d+)', '_');

$mySensitiveString = 'My CC is 4111 1111 1111 1111 and my telephone number is 639.639.639.';

echo $magoo->getMasked($mySensitiveString);

// My CC is ************____ and my telephone number is ___.___.___.

$magoo->reset();

echo $magoo->getMasked($mySensitiveString);

// My CC is 4111 1111 1111 1111 and my telephone number is 639.639.639.
```

### Custom masks

Additionally, you can add your own mask as long as it implements Maskinterface.
```php
use Pachico\Magoo\Magoo;

class FooMask implements \Pachico\Magoo\Mask\MaskInterface
{
    protected $replacement = '*';

    public function __construct(array $params = [])
    {
        if (isset($params['replacement']) && is_string($params['replacement'])) {
            $this->replacement = $params['replacement'];
        }
    }

    public function mask($string)
    {
        return str_replace('foo', $this->replacement, $string);
    }
}
$magoo = new Magoo();
$customMask = new FooMask(['replacement' => 'bar']);
$magoo->pushMask($customMask);

$mySensitiveString = 'It is time to go to the foo.';

echo $magoo->getMasked($mySensitiveString);

// It is time to go to the bar.
```

## Mask arrays
Magoo includes a handy way to mask multidimensional arrays, which can be useful, for instance, for logger contexts.

```php
use Pachico\Magoo\Magoo;
use Pachico\Magoo\MagooArray;

$magoo =new Magoo();
$magoo->pushByRegexMask('(foo)', 'bar');
$magooArray = new MagooArray($magoo);

$mySensitiveArray = [
    'It is time to go to the foo.',
    [
        'It is never too late to go the foo.'
    ],
    new DateTime()
];

$magooArray->getMasked($mySensitiveArray);
```
will output
```php
Array
(
    [0] => It is time to go to the bar.
    [1] => Array
        (
            [0] => It is never too late to go the bar.
        )

    [2] => DateTime Object
        (
            [date] => 2020-08-21 11:44:33.000000
            [timezone_type] => 3
            [timezone] => UTC
        )

)
```

##Mask PSR-3 logger
You can also use Magoo as a wrapper for PSR-3 compliant loggers.
In this example, we use Monolog.

```php
use Pachico\Magoo\Magoo;
use Pachico\Magoo\MagooLogger;
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

$magoo = new Magoo();
$magoo->pushByRegexMask('(foo)', 'bar');

$logger = new Logger('app');
$handler = new ErrorLogHandler();
$logger->pushHandler($handler);
$magooLogger = new MagooLogger($logger, $magoo);

$mySensitiveString = 'It is time to go to the foo.';

$magooLogger->warning($mySensitiveString);

// [2020-08-20 15:54:34] app.WARNING: It is time to go to the bar. [] []
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email pachicodev@gmail.com instead of using the issue tracker.

## Credits

- [Mariano F.co Benítez Mulet](https://github.com/pachico/magoo)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

# Help

Please report any bug you might find and/or collaborate with your own masks.
