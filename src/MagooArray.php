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
 * MagooArray will mask strings in multidimensional arrays
 * using Magoo masks
 */
class MagooArray
{
    /**
     * @var MaskManagerInterface
     */
    protected $maskManager;

    /**
     * @param MaskManagerInterface $maskManager
     */
    public function __construct(MaskManagerInterface $maskManager)
    {
        $this->maskManager = $maskManager;
    }

    /**
     * @param mixed $input
     *
     * @return mixed
     */
    protected function maskIndividualValue($input)
    {
        switch (gettype($input)) {
            case 'array':
                $input = $this->maskMultiDimensionalStructure($input);
                break;
            case 'string':
            case 'float':
            case 'double':
            case 'int':
                $input = $this->maskManager->getMasked((string) $input);
                break;
            case 'object':
                if (method_exists($input, '__toString')) {
                    $input = $this->maskManager->getMasked((string) $input);
                }
                break;
        }

        return $input;
    }

    /**
     * @param array $input
     *
     * @return array
     */
    protected function maskMultiDimensionalStructure(array $input)
    {
        foreach ($input as &$value) {
            $value = $this->maskIndividualValue($value);
        }

        return $input;
    }

    /**
     * @return MaskManagerInterface
     */
    public function getMaskManager()
    {
        return $this->maskManager;
    }

    /**
     * @param array $input
     *
     * @return array
     */
    public function getMasked(array $input)
    {
        $output = $this->maskMultiDimensionalStructure($input);

        return $output;
    }
}
