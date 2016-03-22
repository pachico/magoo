<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo\Mask;

/**
 * All credit card numbers have been randomly generated using http://www.getcreditcardnumbers.com/
 */
class CreditcardTest extends \PHPUnit_Framework_TestCase
{

	/**
	 *
	 * @var array Strings containing valid credit cards
	 */
	protected $_valid_ccs = [
		'My credit card is 4556168690125914. Please, spread it!' => 'My credit card is ************5914. Please, spread it!',
		'My credit cards are 6011 3885 3731 4927 and 5465-7136-4763-2236. Buy something!' => 'My credit cards are ************4927 and ************2236. Buy something!',
		'Creditcard rampage! 6011 612890653518, 601167 7315389477, 60117 4607031 4770, 6011-3885373-14927, 6011-056532 328271' => 'Creditcard rampage! ************3518, ************9477, ************4770, ************4927, ************8271'
	];

	/**
	 *
	 * @var array Strings without valid credit cards
	 */
	protected $_invalid_ccs = [
		'This string is not sentive',
		'My credit card is 4556168690125814',
		'All my credit cards are fake: 5544239360013512, 4916184779428320'
	];

	/**
	 * @var Pachico\Magoo\Mask\Creditcard
	 */
	protected $object;

	/**
	 *
	 */
	protected function setUp()
	{
		$this->object = new Creditcard;
	}

	/**
	 * @covers Pachico\Magoo\Mask\Creditcard::__construct
	 */
	public function test__constructor()
	{
		$this->object = new Creditcard([
			'replacement' => '$'
			], new \Pachico\Magoo\Util\Luhn);

		foreach ($this->_valid_ccs as $input => $output)
		{
			$output = str_replace('*', '$', $output);
			$this->assertSame($this->object->mask($input), $output);
		}
	}

	/**
	 * @covers Pachico\Magoo\Mask\Creditcard::Mask
	 */
	public function testMask()
	{
		foreach ($this->_valid_ccs as $input => $output)
		{
			$this->assertSame($this->object->mask($input), $output);
		}

		foreach ($this->_invalid_ccs as $input)
		{
			$this->assertSame($this->object->mask($input), $input);
		}
	}

}
