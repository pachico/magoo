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
use Pachico\Magoo\MagooArray;

$magoo =new Magoo();
$magoo->pushByRegexMask('(foo)', 'bar');
$magooArray = new MagooArray($magoo);

$mySensitiveArray = [
    'It is time to go to the foo.',
    [
        'It is never too late to go the foo.'
    ],
    new DateTime()
];

$magooArray->getMasked($mySensitiveArray);

/*
Array
(
    [0] => It is time to go to the bar.
    [1] => Array
        (
            [0] => It is never too late to go the bar.
        )

    [2] => DateTime Object
        (
            [date] => 2016-08-21 11:44:33.000000
            [timezone_type] => 3
            [timezone] => UTC
        )

)
*/
