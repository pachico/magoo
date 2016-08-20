<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\Magoo\Validator;

/**
 * Validators must implement this interface
 */
interface ValidatorInterface
{
    /**
     * @param string|int $input
     *
     * @return bool If sequence is valid Luhn
     */
    public function isValid($input);
}
