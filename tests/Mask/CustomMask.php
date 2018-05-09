<?php

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
