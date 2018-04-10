<?php

require __DIR__ . '/../vendor/autoload.php';

use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->pushEmailMask('$', '*');

$mySensitiveString = 'My email is pachicodev@gmail.com but I need privacy.';

echo $magoo->getMasked($mySensitiveString. PHP_EOL);

// My email is $$$$$$$$$$@********* but I need privacy.
