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

$magoo
    ->maskCreditCards()
    ->maskEmails()
    ->maskByRegex('/(email)+/m');

$mySensitiveString = 'My email is roy@trenneman.com and my credit card is 6011792594656742';

echo $magoo->getMasked($mySensitiveString);

// 'My ***** is ***@trenneman.com and my credit card is ************6742'
