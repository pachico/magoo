<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\Magoo\Mask;

use Pachico\Magoo\Validator\ValidatorInterface;
use Pachico\Magoo\Validator\Luhn;

/**
 * The concept behind this is:
 * If we can find a valid Luhn long enough to be a CCard, match it via regex
 * If match isn't a valid Luhn, no need to mask it
 */
class Creditcard implements MaskInterface
{

    /**
     * @var string
     */
    protected $replacement = '*';

    /**
     * @var ValidatorInterface
     */
    protected $luhnValidator;

    /**
     * @param array $params
     */
    public function __construct(array $params = [], ValidatorInterface $luhnValidator = null)
    {
        if (isset($params['replacement']) && is_string($params['replacement'])) {
            $this->replacement = $params['replacement'];
        }

        $this->luhnValidator = $luhnValidator ?
            : new Luhn();
    }

    /**
     * This will only mask a CC number if it's a valid Luhn, since,
     * otherwise, it's not a correct CC number.
     *
     * {@inheritDoc}
     */
    public function mask($string)
    {
        $regex = '/(?:\d[ \t-]*?){13,19}/m';

        $matches = [];

        preg_match_all($regex, $string, $matches);

        // No credit card found
        if (!isset($matches[0]) || empty($matches[0])) {
            return $string;
        }

        foreach ($matches as $match_group) {
            foreach ($match_group as $match) {
                $stripped_match = preg_replace('/[^\d]/', '', $match);

                // Is it a valid Luhn one?
                if (false === $this->luhnValidator->isValid($stripped_match)) {
                    continue;
                }

                $card_length = strlen($stripped_match);
                $replacement = str_pad('', $card_length - 4, $this->replacement) . substr($stripped_match, -4);

                // If so, replace the match
                $string = str_replace($match, $replacement, $string);
            }
        }

        return $string;
    }
}
