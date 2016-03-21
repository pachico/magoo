#Magoo

[![Build Status](https://travis-ci.org/pachico/magoo.svg?branch=master)](https://travis-ci.org/pachico/magoo) [![codecov.io](https://codecov.io/github/pachico/magoo/coverage.svg?branch=master)](https://codecov.io/github/pachico/magoo?branch=master)

Magoo will help to to mask sensitive data
At the moment of writing these notes, it will just mask creditcard numbers, but it will eventually grow.

#Installation
Using composer:

	composer require pachico/magoo

#Usage

	namespace Pachico\Magoo;
	$magoo = new Magoo;

###single line string

	$sensitive_string = 'My credit card number is 4716497744795464. Oops!'

	$insensitive_string = $magoo->creditCard($sensitve_string);

	echo $insensitive_string;
	// output: My credit card number is ************5464. Oops!

###multiline string
	$sensitive_string = "My credit card numbers are: \n"
				. "· 4532 1163 1005 0433\n"
				. "· 6011-2881-3195-2124\n"
				. ". Oops!"

	$insensitive_string = $magoo->creditCard($sensitve_string);

	echo $insensitive_string;
	// output: My credit card numbers are:
	// ************0433
	// ************2124
	// Oops!

