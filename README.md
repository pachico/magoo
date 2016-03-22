#Magoo

[![Build Status](https://travis-ci.org/pachico/magoo.svg?branch=master)](https://travis-ci.org/pachico/magoo) [![codecov.io](https://codecov.io/github/pachico/magoo/coverage.svg?branch=master)](https://codecov.io/github/pachico/magoo?branch=master) [![Codacy Badge](https://api.codacy.com/project/badge/grade/226d0d2e91354a8eac06569a115c056c)](https://www.codacy.com/app/pachico/magoo) [![Codacy Badge](https://api.codacy.com/project/badge/coverage/226d0d2e91354a8eac06569a115c056c)](https://www.codacy.com/app/pachico/magoo)

Magoo will mask sensitive data out of strings. This might be useful, for instance, to log sensitive user input.
It comes with credit card and email data masking, but you can inject custom classes as long as they implement a simple interface.

(If you have suggestions about built-in masks, **please let me know**!)

#Installation
Using composer:

	composer require pachico/magoo dev-master

#Simple usage

	use Pachico\Magoo;
	
	$magoo = new Magoo;

	$magoo
		->maskCreditCard()
		->maskEmail()
		->maskByRegex(['regex' => '/(email)+/m']);

	$my_sensitive_string = 'My email is roy@trenneman.com and my credit card is 6011792594656742';
	
	echo $magoo->executeMasks($my_sensitive_string);
	// 'My **** is ***@trenneman.com and my credit card is ************6742'

### Credit card masking

Credit card masking accepts custom replacement

	$magoo = new Magoo;
	$magoo->maskCreditCards(['replacement' => '$']);
	...

### Regex masking
Regex masking will replace matches with replacements strings that are long as each individual match.

	$magoo = new Magoo;
	$magoo->maskByRegex(['replacement' => '*', 'regex' => '(\d+)']);
	...

### Email masking 
Email masking accepts replacements for *local* and *domain* part individually.
If you don't provide one, it will mask local part automatically.

	$magoo = new Magoo;
	$magoo->maskEmails(['local_replacement' => '*', 'domain_replacement' => '&']);
	...

###Custom masks
Additionally, you can add your own mask as long as it implements *Maskinterface*.

	$custom_mask = new Mask\FooBarMask(['replacement' => 'bar']);
	$magoo = new Magoo;
	$magoo->addCustomMask($custom_mask);
	...

#Help
Please report any bug you might find and/or collaborate with your own masks.

Cheers!

<p align="center">
  <img src="http://i.imgur.com/Cxi86gJ.png" alt="Magoo"/>
</p>

***

<p align="center">
  <iframe src="https://www.linkedin.com/in/marianobenitezmulet" style="border:0px #FFFFFF none;" name="thanksforvisiting" scrolling="no" frameborder="0" marginheight="0px" marginwidth="0px" height="1px" width="1px"></iframe>
</p>
	