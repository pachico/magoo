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
 * Tests Magoo class and its masks
 */
class MagooTest extends \PHPUnit_Framework_TestCase
{
    protected $magoo;

    protected function setUp()
    {
        $this->magoo = new Magoo;
    }

    /**
     * Test multiple masks applied to a string using fluid interface
     */
    public function testChainedMasks()
    {
        // Arrange
        $custom_mask = new Mask\CustomMask(['replacement' => 'bar']);

        // Act
        $this->magoo
            ->pushCreditCardMask('*')
            ->pushMask($custom_mask)
            ->pushEmailMask('*', '_')
            ->pushByRegexMask('/(email)+/m', '*');

        // Assert
        $this->assertSame(
            $this->magoo->getMasked('My foo email is roy@trenneman.com and my credit card is 6011792594656742'),
            'My bar ***** is ***@_____________ and my credit card is ************6742'
        );
    }

    /**
     * Test chainable interface
     */
    public function testChainableMasks()
    {
        // Arrange

        // Act

        // Assert
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->magoo->pushCreditCardMask());
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->magoo->pushEmailMask());
        $custom_mask = new Mask\CustomMask([]);
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->magoo->pushMask($custom_mask));
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->magoo->pushByRegexMask('/foo/'));
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->magoo->reset());
    }

    /**
     * Test that reset method deletes all previously added masks
     */
    public function testReset()
    {
        // Arrange
        $this->magoo
            ->pushCreditCardMask('*')
            ->pushEmailMask('*', '_')
            ->reset();

        $string = 'My foo email is roy@trenneman.com and my credit card is 6011792594656742';

        // Act

        // Assert
        $this->assertSame($this->magoo->getMasked($string), $string);
    }

    /**
     * Test test email masking
     */
    public function testEmailMask()
    {
        // Arrange
        $this->magoo
            ->pushEmailMask('*');

        // Act

        // Assert
        $this->assertSame(
            $this->magoo->getMasked('My email is roy@trenneman.com and my credit card is 6011792594656742'),
            'My email is ***@trenneman.com and my credit card is 6011792594656742'
        );
    }

    /**
     * Test credit card masking
     */
    public function testCreditcardMask()
    {
        // Arrange
        $this->magoo
            ->pushCreditCardMask('*');

        // Act

        // Assert
        $this->assertSame(
            $this->magoo->getMasked('My email is roy@trenneman.com and my credit card is 6011792594656742'),
            'My email is roy@trenneman.com and my credit card is ************6742'
        );
    }

    /**
     * Test Regex mask
     */
    public function testRegexMask()
    {
        // Arrange
        $this->magoo->reset()
            ->pushByRegexMask('/[a-zA-Z]+/m', '*');

        // Act

        // Arrange
        $this->assertSame(
            $this->magoo->getMasked('This is 1 string'),
            '**** ** 1 ******'
        );

        $this->magoo->reset()
            ->pushByRegexMask('', '*');

        $string = 'This 1 string that will not be masked since there is no valid regex';

        $this->assertSame(
            $this->magoo->getMasked($string),
            $string
        );
    }

    /**
     * Test that if no mask is pushed, the passed string is not altered
     */
    public function testNoMask()
    {
        // Arrange
        $string = 'My email is roy@trenneman.com and my credit card is 6011792594656742';

        // Act

        // Assert
        $this->assertSame(
            $this->magoo->getMasked($string),
            $string
        );
    }

    /**
     * Test that if a custom mask is added it will be called
     */
    public function testCustomMask()
    {
        // Arrange
        $custom_mask = new Mask\CustomMask(['replacement' => 'bar']);

        // Act
        $this->magoo->pushMask($custom_mask);

        // Assert
        $this->assertSame(
            $this->magoo->getMasked('Some foo foo foo'),
            'Some bar bar bar'
        );
    }

    /**
     * Test that only strings can be passed to getMasked
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Message to be masked needs to string - array passed.
     */
    public function testGetMaskedException()
    {
        // Arrange

        // Act
        $this->magoo->getMasked(['Not a string']);

        // Assert
    }
}
