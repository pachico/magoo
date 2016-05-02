<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\Magoo;

/**
 * Magoo will mask sensitive data from strings
 */
class Magoo
{
    /**
     * Mask identifier
     */
    const MASK_CREDITCARD = 'creditcard';
    const MASK_EMAIL = 'email';

    /**
     * Contains masks that will apply to Magoo instance
     * @var array Contains masks to apply
     */
    protected $masks = [];

    /**
     * @param string $replacement Character to replace matches
     *
     * @return \Pachico\Magoo\Magoo
     */
    public function maskCreditCards($replacement = '*')
    {
        $this->masks[static::MASK_CREDITCARD] = new Mask\Creditcard(['replacement' => (string) $replacement]);
        return $this;
    }

    /**
     *
     * @param string $regex Regex to be executed
     * @param string $replacement Character to replace matches
     *
     * @return \Pachico\Magoo\Magoo
     */
    public function maskByRegex($regex, $replacement = '*')
    {
        $uniqueId = uniqid('mask-');

        $this->masks[$uniqueId] = new Mask\Regex(
            [
                'regex' => empty($regex) ? '/^$/' : (string) $regex,
                'replacement' => (string) $replacement
            ]
        );

        return $this;
    }

    /**
     * @param string $localReplacement Character to replace local part of email
     * @param string $domainReplacement Character to replace domain part of email
     *
     * @return \Pachico\Magoo\Magoo
     */
    public function maskEmails($localReplacement = '*', $domainReplacement = null)
    {
        $params = [
            'localReplacement' => null,
            'domainReplacement' => null,
        ];

        $this->masks[static::MASK_EMAIL] = new Mask\Email(
            array_merge($params, [
                'localReplacement' => $localReplacement,
                'domainReplacement' => $domainReplacement,
            ])
        );

        return $this;
    }

    /**
     * @param \Pachico\Magoo\Mask\MaskInterface $customMask
     *
     * @return \Pachico\Magoo\Magoo
     */
    public function addCustomMask(Mask\MaskInterface $customMask)
    {
        $unique_id = uniqid('mask-');
        $this->masks[$unique_id] = $customMask;

        return $this;
    }

    /**
     * Reset Magoo by deleting all previously added masks
     *
     * @return \Pachico\Magoo\Magoo
     */
    public function reset()
    {
        $this->masks = [];

        return $this;
    }

    /**
     * @param string $input Input string to be masked
     *
     * @return string Masked string
     */
    public function getMasked($input)
    {
        if (empty($this->masks)) {
            return $input;
        }

        foreach ($this->masks as $mask) {
            $input = $mask->mask($input);
        }

        return $input;
    }
}
