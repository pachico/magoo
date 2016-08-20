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
 * Simple class related to Luhn algorithm
 * @see https://en.wikipedia.org/wiki/Luhn_algorithm
 */
class Luhn implements ValidatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function isValid($input)
    {
        if (!is_numeric($input)) {
            return false;
        }

        $numeric_string = (string) preg_replace('/\D/', '', $input);

        $sum = 0;

        $numDigits = strlen($numeric_string) - 1;

        $parity = $numDigits % 2;

        for ($i = $numDigits; $i >= 0; $i--) {
            $digit = substr($numeric_string, $i, 1);

            if (!$parity == ($i % 2)) {
                $digit <<= 1;
            }

            $digit = ($digit > 9)
                ? ($digit - 9)
                : $digit;

            $sum += $digit;
        }

        return (0 == ($sum % 10));
    }
}
