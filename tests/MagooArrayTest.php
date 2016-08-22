<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\Magoo;

use Pachico\Magoo\Dummy;

/**
 * Test MagooArray class by passing arrays and see the masked output
 */
class MagooArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing that class iterates over given array
     */
    public function testGetMasked()
    {
        // Arrange
        $object = new \stdClass();
        $object->foo = 'bar';
        $object->bin = 'din';
        $object->arr = ['foo', 'faa', 'fee'];

        $inputArray = [
            'foo',
            $object,
            [
                'foo@bar.com',
                'foo bar',
                [
                    0 => 'bin zip',
                    1 => 'din zin',
                    2 =>
                    [
                        0 => 'kas',
                        1 => 'bar'
                    ]
                ]
            ],
            'dot',
            [
                '4111 1111 1111 1111',
                'bar@foo.com',
                'din@don'
            ],
            'tip',
            new Dummy\Stringable('foo string')
        ];

        $expectedOutputArray = [
            'foo',
            $object,
            [
                '***@bar.com',
                'foo bar',
                [
                    0 => 'bin zip',
                    1 => 'din zin',
                    2 =>
                    [
                        0 => 'kas',
                        1 => 'bar'
                    ]
                ]
            ],
            'dot',
            [
                '************1111',
                '***@foo.com',
                'din@don'
            ],
            'tip',
            'foo string'
        ];

        $magoo = new Magoo();
        $magoo->pushEmailMask()->pushCreditCardMask();

        $magooArray = new MagooArray($magoo);

        // Act
        $outputArray = $magooArray->getMasked($inputArray);

        // Assert
        $this->assertSame($expectedOutputArray, $outputArray);
    }

    /**
     * Test that getMaskManager returns Magoo or compatible
     */
    public function testGetMaskManager()
    {
        // Arrange
        $magoo = new Magoo();
        $magoo->pushEmailMask()->pushCreditCardMask();

        $magooArray = new MagooArray($magoo);

        // Act

        // Assert
        $this->assertSame($magoo, $magooArray->getMaskManager());
    }
}
