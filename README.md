# Pachico\Magoo

[![Join the chat at https://gitter.im/pachico/magoo](https://badges.gitter.im/pachico/magoo.svg)](https://gitter.im/pachico/magoo?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/pachico/magoo.svg?branch=master)](https://travis-ci.org/pachico/magoo) [![codecov.io](https://codecov.io/github/pachico/magoo/coverage.svg?branch=master)](https://codecov.io/github/pachico/magoo?branch=master) [![Codacy Badge](https://api.codacy.com/project/badge/grade/226d0d2e91354a8eac06569a115c056c)](https://www.codacy.com/app/pachico/magoo) [![Codacy Badge](https://api.codacy.com/project/badge/coverage/226d0d2e91354a8eac06569a115c056c)](https://www.codacy.com/app/pachico/magoo)

Magoo will mask sensitive data in strings. Built-in masks use regex to find credit card numbers and emails and will mask only those, leaving the rest of the string intact.  This might be useful, for instance, to log sensitive user input.
You can also mask strings that match your own regex or inject masking class as long as they implement a simple interface.


(If you have suggestions about more masks to implement, **please let me know**!)

# Installation
Using composer:

	composer require pachico/magoo dev-master

# Usage

	use Pachico\Magoo;
	
	$magoo = new Magoo;

	$magoo
		->maskCreditCards()
		->maskEmails()
		->maskByRegex('/(email)+/m');

	$my_sensitive_string = 'My email is roy@trenneman.com and my credit card is 6011792594656742';
	
	echo $magoo->getMasked($my_sensitive_string);
	// 'My **** is ***@trenneman.com and my credit card is ************6742'

### Credit card masking

Credit card mask accepts custom replacement

	$magoo = new Magoo;
	$magoo->maskCreditCards('$');
	...

### Regex masking
Regex mask will replace matches with strings that are long as each individual match.
It requires a regex and accepts custom replacement.

	$magoo = new Magoo;
	$magoo->maskByRegex('(\d+)', '*');
	...

### Email masking 
Email mask accepts replacements for *local* and *domain* part individually.
If you don't provide one, it will mask local part.

	$magoo = new Magoo;
	$magoo->maskEmails('*', '&');
	...

### Reset
You might want to use the same instance of Magoo in your application but not the same masks everytime.
You can reset all masks at any time by using the *reset()* method.

	$magoo = new Magoo;
	$magoo->maskEmails('*', '&')
	...
	$magoo->reset();
	...
	// This will not mask any string unless you add masks

### Custom masks
Additionally, you can add your own mask as long as it implements *Maskinterface*.

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

