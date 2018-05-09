<?php

namespace Pachico\MagooTest\Validator;

use Pachico\Magoo\Validator\Luhn;
use PHPUnit_Framework_TestCase;

class LuhnTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Luhn
     */
    private $sut;

    public function setUp()
    {
        parent::setUp();
        $this->sut = new Luhn();
    }

    public function dataProviderCCNumbersExpectedOutput()
    {
        return [
            [4143835214588534, true],
            [4929210174798392, true],
            [4024007116235903, true],
            [4929428366145160, true],
            [4716530394277688, true],
            [5252882130943892, true],
            [5170081853250267, true],
            [5355564750261161, true],
            [5362064223882132, true],
            [5364425492613623, true],
            [6011485263428325, true],
            [6011270452130407, true],
            [6011496863253849, true],
            [6011366775349292, true],
            [6011203243699216, true],
            [342901252683509, true],
            [341052437501561, true],
            [340557208512895, true],
            [346254079031015, true],
            [347593330407822, true],
            ['4539747908866195', true],
            ['4929658424914625', true],
            ['4539622985565871', true],
            ['4916535900086521', true],
            ['4916641132818093', true],
            ['5565663460738496', true],
            ['5320294088639779', true],
            ['5513424292672632', true],
            ['5231371151256489', true],
            ['5496001542770001', true],
            ['6011955655340353', true],
            ['6011886779141585', true],
            ['6011408451013022', true],
            ['6011835424793183', true],
            ['6011132420996931', true],
            [45173835214588534, false],
            [49225210474798392, false],
            [4023047116235903, false],
            [4929423376145160, false],
            [4716530393277288, false],
            ['3519747408836395', false],
            ['492g658424918635', false],
            ['4539622985515862', false],
            ['4916535920066523', false],
            ['4916641132818054', false],
            ['5565663440738493', false],
            ['5320294088636774', false],
            ['aksdla sdlakjs d', false]
        ];
    }

    /**
     * @dataProvider dataProviderCCNumbersExpectedOutput
     *
     * @param string $input
     * @param bool $expectedOutput
     */
    public function testIsValidDetectsCorrectlyCCNumbers($input, $expectedOutput)
    {
        // Act
        $output = $this->sut->isValid($input);
        // Assert
        $this->assertSame($expectedOutput, $output);
    }
}
