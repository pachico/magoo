<?php

/**
 * @author Mariano F.co Benítez Mulet <pachicodev@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo\Mask;

/**
 *
 */
class RegexTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Pachico\Magoo\Mask\Regex
	 */
	protected $object;

	/**
	 *
	 */
	protected function setUp()
	{
		$this->object = new Regex(
			['replacement' => '*', 'regex' => '(\d+)']
		);
	}

	public function testMask()
	{
		$this->assertSame(
			$this->object->mask(
				'This string has 12345 digits, which is more than 6789'), 'This string has ***** digits, which is more than ****'
		);

		$this->assertSame(
			$this->object->mask(
				'1-2-3-4-5-6-7-8-9-0'), '*-*-*-*-*-*-*-*-*-*'
		);

		$this->assertSame(
			$this->object->mask(
				'I have no digits'), 'I have no digits'
		);
	}

	public function test__construct()
	{
		
	}

}
