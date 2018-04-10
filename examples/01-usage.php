<?php

require __DIR__ . '/../vendor/autoload.php';

use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->pushCreditCardMask()
    ->pushEmailMask()
    ->pushByRegexMask('/(email)+/m');

$mySensitiveString = 'My email is roy@trenneman.com and my credit card is 6011792594656742';

echo $magoo->getMasked($mySensitiveString . PHP_EOL);

// 'My ***** is ***@trenneman.com and my credit card is ************6742'
