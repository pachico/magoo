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
use Monolog\Handler\ErrorLogHandler;

$magoo = new Magoo();
$magoo->pushByRegexMask('(foo)', 'bar');

$logger = new Logger('app');
$handler = new ErrorLogHandler();
$logger->pushHandler($handler);
$magooLogger = new MagooLogger($logger, $magoo);

$mySensitiveString = 'It is time to go to the foo.';

$magooLogger->warning($mySensitiveString);

// [2016-08-20 15:54:34] app.WARNING: It is time to go to the bar. [] []
