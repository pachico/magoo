<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\Magoo\Mask;

class RegexTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Regex(['replacement' => '*', 'regex' => '(\d+)']);
    }

    public function testMask()
    {
        $this->assertSame(
            $this->object->mask('This string has 12345 digits, which is more than 6789'),
            'This string has ***** digits, which is more than ****'
        );

        $this->assertSame(
            $this->object->mask('1-2-3-4-5-6-7-8-9-0'),
            '*-*-*-*-*-*-*-*-*-*'
        );

        $this->assertSame(
            $this->object->mask('I have no digits'),
            'I have no digits'
        );
    }
}
