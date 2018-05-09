<?php

namespace Pachico\MagooTest\Mask;

use Pachico\Magoo\Mask\Regex;

class RegexTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Regex
     */
    protected $sut;

    protected function setUp()
    {
        $this->sut = new Regex(['replacement' => '*', 'regex' => '(\d+)']);
    }

    public function dataProviderIntputExpectedOutput()
    {
        return [
            ['This string has 12345 digits, which is more than 6789',
                'This string has ***** digits, which is more than ****'],
            ['1-2-3-4-5-6-7-8-9-0', '*-*-*-*-*-*-*-*-*-*'],
            ['I have no digits', 'I have no digits']
        ];
    }

    /**
     * @dataProvider dataProviderIntputExpectedOutput
     *
     * @param string $input
     * @param string $expectedOutput
     */
    public function testMaskRedactsCorrectly($input, $expectedOutput)
    {
        // Arrange
        // Act
        $output = $this->sut->mask($input);
        // Assert
        $this->assertSame($expectedOutput, $output);
    }
}
