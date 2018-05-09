<?php

namespace Pachico\MagooTest;

use Pachico\Magoo\Magoo;

class MagooTest extends \PHPUnit_Framework_TestCase
{

    protected $sut;

    protected function setUp()
    {
        $this->sut = new Magoo();
    }

    public function testChainedMasksShouldWorkAsExpected()
    {
        // Arrange
        $customMask = new Mask\CustomMask(['replacement' => 'bar']);

        // Act
        $this->sut->pushCreditCardMask('*')
            ->pushMask($customMask)
            ->pushEmailMask('*', '_')
            ->pushByRegexMask('/(email)+/m', '*');

        // Assert
        $this->assertSame(
            $this->sut->getMasked('My foo email is roy@trenneman.com and my credit card is 6011792594656742'),
            'My bar ***** is ***@_____________ and my credit card is ************6742'
        );
    }

    public function testPushingMasksReturnMagooInstance()
    {
        // Arrange
        // Act
        // Assert
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->sut->pushCreditCardMask());
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->sut->pushEmailMask());
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->sut->pushMask(new Mask\CustomMask([])));
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->sut->pushByRegexMask('/foo/'));
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->sut->reset());
    }

    public function testResetCleansAnyPreviouslySetMask()
    {
        // Arrange
        $this->sut->pushCreditCardMask('*')->pushEmailMask('*', '_')->reset();
        $string = 'My foo email is roy@trenneman.com and my credit card is 6011792594656742';
        // Act
        $output = $this->sut->getMasked($string);
        // Assert
        $this->assertSame($string, $output);
    }

    public function testEmailMaskRedactsEmailsCorrectly()
    {
        // Arrange
        $this->sut->pushEmailMask('*');
        // Act
        $output = $this->sut->getMasked('My email is roy@trenneman.com and my credit card is 6011792594656742');
        // Assert
        $this->assertSame('My email is ***@trenneman.com and my credit card is 6011792594656742', $output);
    }

    public function testCreditcardMaskRedactsCCCorrectly()
    {
        // Arrange
        $this->sut->pushCreditCardMask('*');
        // Act
        $output = $this->sut->getMasked('My email is roy@trenneman.com and my credit card is 6011792594656742');
        // Assert
        $this->assertSame('My email is roy@trenneman.com and my credit card is ************6742', $output);
    }

    /**
     * @return array
     */
    public function dataProviderRegexMaskRedactsStringsCorrectly()
    {
        return [
            ['/[a-zA-Z]+/m', 'This is 1 string', '**** ** 1 ******'],
            [
                '',
                'This 1 string that will not be masked since there is no valid regex',
                'This 1 string that will not be masked since there is no valid regex'
            ]
        ];
    }

    /**
     * @dataProvider dataProviderRegexMaskRedactsStringsCorrectly
     *
     * @param string $regex
     * @param string $input
     * @param string $expectedOutput
     */
    public function testRegexMaskRedactsStringsCorrectly($regex, $input, $expectedOutput)
    {
        // Arrange
        $this->sut->reset()->pushByRegexMask($regex, '*');
        // Act
        $output = $this->sut->getMasked($input);
        // Arrange
        $this->assertSame($expectedOutput, $output);
    }

    public function testNoMaskReturnsUnalteredInput()
    {
        // Arrange
        $string = 'My email is roy@trenneman.com and my credit card is 6011792594656742';
        // Act
        $output = $this->sut->getMasked($string);
        // Assert
        $this->assertSame($string, $output);
    }

    public function testCustomMaskAreCalledIfPassed()
    {
        // Arrange
        $customMask = new Mask\CustomMask(['replacement' => 'bar']);
        $this->sut->pushMask($customMask);
        // Act
        $output = $this->sut->getMasked('Some foo foo foo');
        // Assert
        $this->assertSame('Some bar bar bar', $output);
    }

    /**
     * Test that only strings can be passed to getMasked
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Message to be masked needs to string - array passed.
     */
    public function testGetMaskedThrowsExceptionIfWrongInput()
    {
        // Arrange
        
        // Act
        $this->sut->getMasked(['Not a string']);

        // Assert
    }
}
