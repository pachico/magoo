<?php

require __DIR__ . '/../vendor/autoload.php';

use Pachico\Magoo\Magoo;

$magoo = new Magoo();
$magoo->pushByRegexMask('(\d+)', '_');

$mySensitiveString = 'My telephone number is 639.639.639. Call me at 19:00!';

echo $magoo->getMasked($mySensitiveString. PHP_EOL);

// My telephone number is ___.___.___. Call me at __:__!
