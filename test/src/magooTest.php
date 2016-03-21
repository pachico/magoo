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
	 * @covers Pachico\Magoo\Magoo::mask
	 * @covers Pachico\Magoo\Magoo::maskCreditCard
	 * @covers Pachico\Magoo\Magoo::addCustomMask
	 * @covers Pachico\Magoo\Magoo::maskEmail
	 */
	public function testChainableMasks()
	{
		$custom_mask = new Mask\Custommask(['replacement' => 'bar']);

		$this->object
			->maskCreditCard()
			->addCustomMask($custom_mask)
			->maskEmail();

		$this->assertSame(
			$this->object->mask('My foo email is roy@trenneman.com and my credit card is 6011792594656742'),
			'My bar email is ***@trenneman.com and my credit card is ************6742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::mask
	 * @covers Pachico\Magoo\Magoo::maskEmail
	 */
	public function testEmailMask()
	{
		$this->object
			->maskEmail();

		$this->assertSame(
			$this->object->mask('My email is roy@trenneman.com and my credit card is 6011792594656742'),
			'My email is ***@trenneman.com and my credit card is 6011792594656742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::mask
	 * @covers Pachico\Magoo\Magoo::maskCreditCard
	 */
	public function testCreditcardMask()
	{
		$this->object
			->maskCreditCard();

		$this->assertSame(
			$this->object->mask('My email is roy@trenneman.com and my credit card is 6011792594656742'),
			'My email is roy@trenneman.com and my credit card is ************6742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::mask
	 */
	public function testNoMask()
	{
		$this->assertSame(
			$this->object->mask('My email is roy@trenneman.com and my credit card is 6011792594656742'),
			'My email is roy@trenneman.com and my credit card is 6011792594656742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::addCustomMask
	 * @covers Pachico\Magoo\Magoo::mask
	 */
	public function testCustomMask()
	{
		$custom_mask = new Mask\Custommask(['replacement' => 'bar']);

		$this->object->addCustomMask($custom_mask);
		$this->assertSame(
			$this->object->mask('Some foo foo foo'), 'Some bar bar bar'
		);
	}

}
