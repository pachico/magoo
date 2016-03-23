<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo;

/**
 *
 */
class MagooTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Pachico\Magoo\Magoo
	 */
	protected $object;

	/**
	 *
	 */
	protected function setUp()
	{
		$this->object = new Magoo;
	}

	/**
	 * @covers Pachico\Magoo\Magoo::getMasked
	 * @covers Pachico\Magoo\Magoo::maskCreditCards
	 * @covers Pachico\Magoo\Magoo::addCustomMask
	 * @covers Pachico\Magoo\Magoo::maskEmails
	 */
	public function testChainableMasks()
	{
		$custom_mask = new Mask\Custommask(['replacement' => 'bar']);

		$this->object
			->maskCreditCards('*')
			->addCustomMask($custom_mask)
			->maskEmails('*', '_')
			->maskByRegex('/(email)+/m', '*')
		;


		$this->assertSame(
			$this->object->getMasked('My foo email is roy@trenneman.com and my credit card is 6011792594656742'),
			'My bar ***** is ***@_____________ and my credit card is ************6742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::getMasked
	 * @covers Pachico\Magoo\Magoo::maskEmails
	 */
	public function testEmailMask()
	{
		$this->object
			->maskEmails('*');

		$this->assertSame(
			$this->object->getMasked('My email is roy@trenneman.com and my credit card is 6011792594656742'),
			'My email is ***@trenneman.com and my credit card is 6011792594656742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::getMasked
	 * @covers Pachico\Magoo\Magoo::maskCreditCards
	 */
	public function testCreditcardMask()
	{
		$this->object
			->maskCreditCards('*');

		$this->assertSame(
			$this->object->getMasked('My email is roy@trenneman.com and my credit card is 6011792594656742'),
			'My email is roy@trenneman.com and my credit card is ************6742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::getMasked
	 * @covers Pachico\Magoo\Magoo::maskByRegex
	 */
	public function testRegexMask()
	{
		$this->object = (new Magoo)
			->maskByRegex('/[a-zA-Z]+/m', '*');

		$this->assertSame(
			$this->object->getMasked('This is 1 string'), '**** ** 1 ******'
		);

		$this->object = (new Magoo)
			->maskByRegex('', '*');

		$string = 'This 1 string that will not be masked since there is no valid regex';

		$this->assertSame(
			$this->object->getMasked($string), $string
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::getMasked
	 */
	public function testNoMask()
	{
		$this->assertSame(
			$this->object->getMasked('My email is roy@trenneman.com and my credit card is 6011792594656742'),
			'My email is roy@trenneman.com and my credit card is 6011792594656742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::addCustomMask
	 * @covers Pachico\Magoo\Magoo::getMasked
	 */
	public function testCustomMask()
	{
		$custom_mask = new Mask\Custommask(['replacement' => 'bar']);

		$this->object->addCustomMask($custom_mask);
		$this->assertSame(
			$this->object->getMasked('Some foo foo foo'), 'Some bar bar bar'
		);
	}

}
