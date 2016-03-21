<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo\Mask;

/**
 *
 */
class EmailTest extends \PHPUnit_Framework_TestCase
{

	/**
	 *
	 * @var array
	 */
	protected $_strings_with_valid_email_addresses = [
		'To donate write to ted@crilly.com, please' => 'To donate write to ***@**********, please',
		'In case of dragons, report to dougal@mcguire.net' => 'In case of dragons, report to ******@***********',
		'Booz expert: jack@hackett.org' => 'Booz expert: ****@***********',
		'To get more sandwitches, mail me at mrs@doyle.co.uk' => 'To get more sandwitches, mail me at ***@***********',
		'To report any of the above, contact me at bishop@brennan.biz' => 'To report any of the above, contact me at ******@***********',
	];

	/**
	 *
	 * @var array
	 */
	protected $_strings_without_valid_email = [
		'This is just a random string',
		'It almost looks like an email: asdas@',
		'This is almost interesting: foo.bar.com'
	];

	/**
	 * @var Pachico\Magoo\Mask\Email
	 */
	protected $object;

	/**
	 *
	 */
	protected function setUp()
	{
		$this->object = new Email(
			['local_replacement' => '*', 'domain_replacement' => '*']
		);
	}

	/**
	 * @covers Pachico\Magoo\Mask\Email::mask
	 * @covers Pachico\Magoo\Mask\Email::_mask_individual_email_match
	 */
	public function testMask()
	{
		foreach ($this->_strings_with_valid_email_addresses as $input => $output)
		{
			$this->assertSame($this->object->mask($input), $output);
		}

		foreach ($this->_strings_without_valid_email as $input)
		{
			$this->assertSame($this->object->mask($input), $input);
		}
	}

	/**
	 * @covers Pachico\Magoo\Mask\Email::__construct
	 */
	public function test__construct()
	{
		$this->object = new Email(['local_replacement' => '*']);
		$this->assertSame(
			$this->object->mask('bernard@black.com'), '*******@black.com'
		);

		$this->object = new Email(['domain_replacement' => '*']);
		$this->assertSame(
			$this->object->mask('manny@bianco.co.uk'), 'manny@************'
		);

		$this->object = new Email();
		$this->assertSame(
			$this->object->mask('fran@katzenjammer.net'), '****@katzenjammer.net'
		);
	}

}
