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
	 * @covers Pachico\Magoo\Magoo::Executemasks
	 * @covers Pachico\Magoo\Magoo::maskCreditCards
	 * @covers Pachico\Magoo\Magoo::addCustomMask
	 * @covers Pachico\Magoo\Magoo::maskEmails
	 */
	public function testChainableMasks()
	{
		$custom_mask = new Mask\Custommask(['replacement' => 'bar']);

		$this->object
			->maskCreditCards()
			->addCustomMask($custom_mask)
			->maskEmails()
			->maskByRegex(['regex' => '/(email)+/m']);
		;

		$this->assertSame(
			$this->object->executeMasks('My foo email is roy@trenneman.com and my credit card is 6011792594656742'), 'My bar ***** is ***@trenneman.com and my credit card is ************6742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::Executemasks
	 * @covers Pachico\Magoo\Magoo::maskEmails
	 */
	public function testEmailMask()
	{
		$this->object
			->maskEmails();

		$this->assertSame(
			$this->object->executeMasks('My email is roy@trenneman.com and my credit card is 6011792594656742'), 'My email is ***@trenneman.com and my credit card is 6011792594656742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::Executemasks
	 * @covers Pachico\Magoo\Magoo::maskCreditCards
	 */
	public function testCreditcardMask()
	{
		$this->object
			->maskCreditCards();

		$this->assertSame(
			$this->object->executeMasks('My email is roy@trenneman.com and my credit card is 6011792594656742'), 'My email is roy@trenneman.com and my credit card is ************6742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::executeMasks
	 * @covers Pachico\Magoo\Magoo::MaskByRegex
	 */
	public function testRegexMask()
	{
		$this->object
			->maskByRegex([
				'regex' => '/[a-zA-Z]+/m',
				'replacement' => '*'
		]);

		$this->assertSame(
			$this->object->executeMasks('This is 1 string'), '**** ** 1 ******'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::Executemasks
	 */
	public function testNoMask()
	{
		$this->assertSame(
			$this->object->executeMasks('My email is roy@trenneman.com and my credit card is 6011792594656742'), 'My email is roy@trenneman.com and my credit card is 6011792594656742'
		);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::addCustomMask
	 * @covers Pachico\Magoo\Magoo::Executemasks
	 */
	public function testCustomMask()
	{
		$custom_mask = new Mask\Custommask(['replacement' => 'bar']);

		$this->object->addCustomMask($custom_mask);
		$this->assertSame(
			$this->object->executeMasks('Some foo foo foo'), 'Some bar bar bar'
		);
	}

}
