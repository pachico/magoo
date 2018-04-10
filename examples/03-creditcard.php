<?php

require __DIR__ . '/../vendor/autoload.php';

use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->pushCreditCardMask('·');

$mySensitiveString = 'This is my credit card number: 4111 1111 1111 1111.';

echo $magoo->getMasked($mySensitiveString. PHP_EOL);

// This is my credit card number: ······1111.
