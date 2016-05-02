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
 *
 */
class MagooTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Magoo;
    }

    public function testChainedMasks()
    {
        $custom_mask = new Mask\CustomMask(['replacement' => 'bar']);

        $this->object
            ->maskCreditCards('*')
            ->addCustomMask($custom_mask)
            ->maskEmails('*', '_')
            ->maskByRegex('/(email)+/m', '*');

        $this->assertSame(
            $this->object->getMasked('My foo email is roy@trenneman.com and my credit card is 6011792594656742'),
            'My bar ***** is ***@_____________ and my credit card is ************6742'
        );
    }

    public function testChainableMasks()
    {
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->object->maskCreditCards());
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->object->maskEmails());
        $custom_mask = new Mask\CustomMask([]);
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->object->addCustomMask($custom_mask));
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->object->maskByRegex('/foo/'));
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->object->reset());
    }

    public function testReset()
    {
        $this->object
            ->maskCreditCards('*')
            ->maskEmails('*', '_')
            ->reset();

        $string = 'My foo email is roy@trenneman.com and my credit card is 6011792594656742';

        $this->assertSame($this->object->getMasked($string), $string);
    }

    public function testEmailMask()
    {
        $this->object
            ->maskEmails('*');

        $this->assertSame(
            $this->object->getMasked('My email is roy@trenneman.com and my credit card is 6011792594656742'),
            'My email is ***@trenneman.com and my credit card is 6011792594656742'
        );
    }

    public function testCreditcardMask()
    {
        $this->object
            ->maskCreditCards('*');

        $this->assertSame(
            $this->object->getMasked('My email is roy@trenneman.com and my credit card is 6011792594656742'),
            'My email is roy@trenneman.com and my credit card is ************6742'
        );
    }

    public function testRegexMask()
    {
        $this->object->reset()
            ->maskByRegex('/[a-zA-Z]+/m', '*');

        $this->assertSame(
            $this->object->getMasked('This is 1 string'),
            '**** ** 1 ******'
        );

        $this->object->reset()
            ->maskByRegex('', '*');

        $string = 'This 1 string that will not be masked since there is no valid regex';

        $this->assertSame(
            $this->object->getMasked($string),
            $string
        );
    }

    public function testNoMask()
    {
        $string = 'My email is roy@trenneman.com and my credit card is 6011792594656742';

        $this->assertSame(
            $this->object->getMasked($string),
            $string
        );
    }

    public function testCustomMask()
    {
        $custom_mask = new Mask\CustomMask(['replacement' => 'bar']);

        $this->object->addCustomMask($custom_mask);
        $this->assertSame(
            $this->object->getMasked('Some foo foo foo'),
            'Some bar bar bar'
        );
    }
}
