<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\MagooTest\Mask;

use Pachico\Magoo\Mask\MaskInterface;

class CustomMask implements MaskInterface
{
    /**
     * @var string
     */
    protected $replacement = '*';

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if (isset($params['replacement']) && is_string($params['replacement'])) {
            $this->replacement = $params['replacement'];
        }
    }

    /**
     * @param string $string
     */
    public function mask($string)
    {
        return str_replace('foo', $this->replacement, $string);
    }
}
