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
use Pachico\Magoo\MagooLogger;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$magoo = new Magoo();
$magoo->pushByRegexMask('(foo)', 'bar');

$logger = new Logger('app');
$logger->pushHandler(new StreamHandler('php://stdout'));
$magooLogger = new MagooLogger($logger, $magoo);

$mySensitiveString = 'It is time to go to the foo.';

$magooLogger->warning($mySensitiveString,
    [
    'first' => 'It is time to go to the foo.',
    'second' => 'Nothing like Living in foocelona'
    ]
);

$magooLogger->log('debug', $mySensitiveString, [
    'It is time to go to the foo.'
]);

$magooLogger->foo('debug', $mySensitiveString, [
    'It is time to go to the foo.'
]);


//[2016-09-30 13:01:28] app.WARNING: It is time to go to the bar. {"first":"It is time to go to the bar.","second":"Nothing like Living in barcelona"} []
//[2016-09-30 13:01:28] app.DEBUG: It is time to go to the bar. ["It is time to go to the bar."] []

