<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

require __DIR__ . '/../vendor/autoload.php';

use Pachico\Magoo\Magoo;

$magoo = new Magoo();

$magoo->maskCreditCards()->maskByRegex('(\d+)', '_');

$mySensitiveString = 'My CC is 4111 1111 1111 1111 and my telephone number is 639.639.639.';

echo $magoo->getMasked($mySensitiveString);

// My CC is ************____ and my telephone number is ___.___.___.

$magoo->reset();

echo $magoo->getMasked($mySensitiveString);

// My CC is 4111 1111 1111 1111 and my telephone number is 639.639.639.