# Pachico\Magoo

[![Build Status](https://travis-ci.org/pachico/magoo.svg?branch=master)](https://travis-ci.org/pachico/magoo) [![codecov.io](https://codecov.io/github/pachico/magoo/coverage.svg?branch=master)](https://codecov.io/github/pachico/magoo?branch=master) [![Codacy Badge](https://api.codacy.com/project/badge/grade/226d0d2e91354a8eac06569a115c056c)](https://www.codacy.com/app/pachico/magoo) [![Codacy Badge](https://api.codacy.com/project/badge/coverage/226d0d2e91354a8eac06569a115c056c)](https://www.codacy.com/app/pachico/magoo)

Magoo will mask sensitive data in strings. Built-in masks use regex to find credit card numbers and emails and will mask only those, leaving the rest of the string intact.  This might be useful, for instance, to log sensitive user input.
You can also mask strings that match your own regex or inject masking class as long as they implement a simple interface.


(If you have suggestions about more masks to implement, **please let me know**!)

## Install
Via Composer:

	composer require pachico/magoo

## Usage

```php
use Pachico\Magoo;

$magoo = new Magoo;

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [:author_name][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

	$custom_mask = new Mask\FooBarMask(['replacement' => 'bar']);
	$magoo = new Magoo;
	$magoo->addCustomMask($custom_mask);
	...

# Help
Please report any bug you might find and/or collaborate with your own masks.

Cheers!

<p align="center">
  <img src="https://camo.githubusercontent.com/2bc8f355f403cd00bafaee23fbf279ed69567f65/687474703a2f2f692e696d6775722e636f6d2f4378693836674a2e706e67" alt="Magoo"/>
</p>

