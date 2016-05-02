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
 * Email regex detection is still controversial.
 * Please report any improvements
 */
class Email implements MaskInterface
{

    /**
     * @var string
     */
    protected $replacementLocal = null;

    /**
     * @var string
     */
    protected $replacementDomain = null;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if (isset($params['localReplacement']) && is_string($params['localReplacement'])) {
            $this->replacementLocal = $params['localReplacement'];
        }

        if (isset($params['domainReplacement']) && is_string($params['domainReplacement'])) {
            $this->replacementDomain = $params['domainReplacement'];
        }

        if (is_null($this->replacementLocal) && is_null($this->replacementDomain)) {
            $this->replacementLocal = '*';
        }
    }

    /**
     * @param string $match
     *
     * @return string
     */
    protected function maskIndividualEmailMatch($match)
    {
        $match_replacement = $match;

        if ($this->replacementLocal) {
            $local_part = substr($match, 0, stripos($match, '@'));
            $match_replacement = str_replace(
                $local_part,
                str_pad('', strlen($local_part), $this->replacementLocal),
                $match_replacement
            );
        }

        if ($this->replacementDomain) {
            $domain_part = substr($match, stripos($match, '@') + 1);
            $match_replacement = str_replace(
                $domain_part,
                str_pad('', strlen($domain_part), $this->replacementDomain),
                $match_replacement
            );
        }

        return $match_replacement;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function mask($string)
    {
        $regex = "/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*"
            . "|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")"
            . "@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]"
            . "*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}"
            . "(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:"
            . "(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";

        $matches = [];

        preg_match_all($regex, $string, $matches);

        // No email found
        if (!isset($matches[0]) || empty($matches[0])) {
            return $string;
        }

        foreach ($matches as $match_group) {
            foreach ($match_group as $match) {
                $string = str_replace($match, $this->maskIndividualEmailMatch($match), $string);
            }
        }

        return $string;
    }
}
