<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo\Util;

class LuhnTest extends \PHPUnit_Framework_TestCase
{

	/**
	 *
	 * @var array
	 */
	protected $_valid_luhns = [
		4143835214588534,
		4929210174798392,
		4024007116235903,
		4929428366145160,
		4716530394277688,
		5252882130943892,
		5170081853250267,
		5355564750261161,
		5362064223882132,
		5364425492613623,
		6011485263428325,
		6011270452130407,
		6011496863253849,
		6011366775349292,
		6011203243699216,
		342901252683509,
		341052437501561,
		340557208512895,
		346254079031015,
		347593330407822,
		'4539747908866195',
		'4929658424914625',
		'4539622985565871',
		'4916535900086521',
		'4916641132818093',
		'5565663460738496',
		'5320294088639779',
		'5513424292672632',
		'5231371151256489',
		'5496001542770001',
		'6011955655340353',
		'6011886779141585',
		'6011408451013022',
		'6011835424793183',
		'6011132420996931'
	];

	/**
	 *
	 * @var array
	 */
	protected $_invalid_luhns = [
		45173835214588534,
		49225210474798392,
		4023047116235903,
		4929423376145160,
		4716530393277288,
		'3519747408836395',
		'492g658424918635',
		'4539622985515862',
		'4916535920066523',
		'4916641132818054',
		'5565663440738493',
		'5320294088636774',
		'aksdla sdlakjs d'
	];

	/**
	 * @var Luhn
	 */
	protected $object;

	/**
	 *
	 */
	protected function setUp()
	{
		$this->object = new Luhn;
	}

	/**
	 * @covers Pachico\Magoo\Util\Luhn::isLuhn
	 */
	public function testIsLuhn()
	{
		foreach ($this->_valid_luhns as $number)
		{
			$this->assertTrue($this->object->isLuhn($number));
		}

		foreach ($this->_invalid_luhns as $number)
		{
			$this->assertFalse($this->object->isLuhn($number));
		}
	}

}
