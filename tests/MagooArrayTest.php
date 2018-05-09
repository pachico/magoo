<?php

namespace Pachico\MagooTest;

use Pachico\Magoo\Magoo;
use Pachico\Magoo\MagooArray;
use Pachico\MagooTest\Dummy;
use stdClass;

class MagooArrayTest extends \PHPUnit_Framework_TestCase
{
    
    public function testGetMaskedRedactsArrayCorrectly()
    {
        // Arrange
        $object = new stdClass();
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

    public function testGetMaskManagerReturnsManager()
    {
        // Arrange
        $magoo = new Magoo();
        $magoo->pushEmailMask()->pushCreditCardMask();
        $magooArray = new MagooArray($magoo);
        // Act
        $output = $magooArray->getMaskManager();
        // Assert
        $this->assertSame($magoo, $output);
    }
}
