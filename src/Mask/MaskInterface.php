<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\Magoo\Mask;

/**
 * Masks must implement this interface since
 * mask() method will be executed for all of them
 */
interface MaskInterface
{
    /**
     * @param array $params
     */
    public function __construct(array $params = []);

    /**
     * Masks a given string
     *
     * @param string $string
     */
    public function mask($string);
}
