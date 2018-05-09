<?php

namespace Pachico\MagooTest\Dummy;

/**
 * Dummy class to be used in test suite only
 */
class Stringable
{
    protected $value;

    /**
     * Dummy class with __toString implementation
     * for test purposes
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
