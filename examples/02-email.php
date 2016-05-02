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

$magoo->maskEmails('$', '*');

$mySensitiveString = 'My email is pachicodev@gmail.com but I need privacy.';

echo $magoo->getMasked($mySensitiveString);

// My email is $$$$$$$$$$@********* but I need privacy.
