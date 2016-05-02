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

class FooMask implements \Pachico\Magoo\Mask\MaskInterface
{
    protected $replacement = '*';

    public function __construct(array $params = [])
    {
        if (isset($params['replacement']) && is_string($params['replacement'])) {
            $this->replacement = $params['replacement'];
        }
    }

    public function mask($string)
    {
        return str_replace('foo', $this->replacement, $string);
    }
}

$magoo = new Magoo();
$customMask = new FooMask(['replacement' => 'bar']);
$magoo->addCustomMask($customMask);

$mySensitiveString = 'It is time to go to the foo.';

echo $magoo->getMasked($mySensitiveString   );

// It is time to go to the bar.
