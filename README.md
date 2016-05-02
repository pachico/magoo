# Pachico\Magoo

[![Build Status](https://travis-ci.org/pachico/magoo.svg?branch=master)](https://travis-ci.org/pachico/magoo) [![codecov.io](https://codecov.io/github/pachico/magoo/coverage.svg?branch=master)](https://codecov.io/github/pachico/magoo?branch=master) [![Codacy Badge](https://api.codacy.com/project/badge/grade/226d0d2e91354a8eac06569a115c056c)](https://www.codacy.com/app/pachico/magoo) [![Codacy Badge](https://api.codacy.com/project/badge/coverage/226d0d2e91354a8eac06569a115c056c)](https://www.codacy.com/app/pachico/magoo) [![StyleCI](https://styleci.io/repos/54375622/shield)](https://styleci.io/repos/54375622) 
[![Gitter](https://badges.gitter.im/pachico/magoo.svg)](https://gitter.im/pachico/magoo?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=body_badge)

Magoo will mask sensitive data in strings. Built-in masks use regex to find credit card numbers and emails and will mask only those, leaving the rest of the string intact. This might be useful, for instance, to log sensitive user input.
You can also mask strings that match your own regex or inject masking class as long as they implement a simple interface.

Use the [issues](issues) tool to request masks to implement.

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
$magoo->maskCreditCards()
    ->maskEmails()
    ->maskByRegex('/(email)+/m');

$mySensitiveString = 'My email is roy@trenneman.com and my credit card is 6011792594656742';

echo $magoo->getMasked($mySensitiveString);

// 'My ***** is ***@trenneman.com and my credit card is ************6742'
```
### Mask credit cards

Credit card mask accepts a custom replacement.

```php
use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->maskCreditCards('·');

$mySensitiveString = 'This is my credit card number: 4111 1111 1111 1111.';

echo $magoo->getMasked($mySensitiveString);

// This is my credit card number: ······1111.
```
### Mask emails

Email mask accepts as optional parameters the replacement for local and domain parts.

```php
use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->maskEmails('$', '*');

$mySensitiveString = 'My email is pachicodev@gmail.com but I need privacy.';

echo $magoo->getMasked($mySensitiveString);

// My email is $$$$$$$$$$@********* but I need privacy.
```

### Mask by regex
Regex mask will replace matches with strings that are long as each individual match. It requires a regex and accepts custom replacement.

```php
use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->maskByRegex('(\d+)', '_');

$mySensitiveString = 'My telephone number is 639.639.639. Call me at 19:00!';

echo $magoo->getMasked($mySensitiveString);

// My telephone number is ___.___.___. Call me at __:__!
```
### Reset
You might want to use the same instance of Magoo in your application but not the same masks everytime. You can reset all masks at any time by using the reset() method.
```php
use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->maskCreditCards()->maskByRegex('(\d+)', '_');

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
$magoo->addCustomMask($customMask);

$mySensitiveString = 'It is time to go to the foo.';

echo $magoo->getMasked($mySensitiveString   );

// It is time to go to the bar.
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

