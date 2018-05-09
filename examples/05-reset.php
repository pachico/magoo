<?php

require __DIR__ . '/../vendor/autoload.php';

use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->pushCreditCardMask()->pushByRegexMask('(\d+)', '_');

$mySensitiveString = 'My CC is 4111 1111 1111 1111 and my telephone number is 639.639.639.';

echo $magoo->getMasked($mySensitiveString. PHP_EOL);

// My CC is ************____ and my telephone number is ___.___.___.

$magoo->reset();

echo $magoo->getMasked($mySensitiveString. PHP_EOL);

// My CC is 4111 1111 1111 1111 and my telephone number is 639.639.639.
