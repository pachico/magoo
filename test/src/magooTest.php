<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo;

class MagooTest extends \PHPUnit_Framework_TestCase
{

	/**
	 *
	 * @var array
	 */
	protected $_valid_creditcards = [
		'4891051197352773' => '************2773',
		'4532575567219126' => '************9126',
		'4485 9413 2667 1154' => '************1154',
		'4716  9033  8292  3052' => '************3052',
		'5280-0820-6382-5564' => '************5564',
		'5407580584508773' => '************8773',
		'5302	5271	5696	6844' => '************6844',
		'6011	9855	9631	9265' => '************9265',
		'6011 - 2203 - 9849 - 8665' => '************8665',
		'6011 7482 5883 0999' => '************0999',
		'3410  3022  4294  731' => '***********4731',
		'3434 - 9270 - 2521 - 548' => '***********1548',
		'343224219275304' => '***********5304',
		'372561741242243' => '***********2243',
		'3455	9496	1453	475' => '***********3475',
		'6011 7797 40314847' => '************4847',
		'20146387 5428 739' => '***********8739',
		'3158123082336839' => '************6839',
	];

	/**
	 *
	 * @var array
	 */
	protected $_invalid_creditcards = [
		'5320294088639739',
		'4891051197352771',
		'4532575567219121',
		'4485 9413 2667 1151',
		'4716  9033  8292  3051',
		'5280-0820-6382-5561',
		'5407580584508771',
		'5302	5271	5696	6841',
		'6011	9855	9631	9261',
		'6011 - 2203 - 9849 - 8661',
		'6011 7482 5883 0991',
		'3410  3022  4294  730',
		'3434 - 9270 - 2521 - 541',
		'343224219275301',
		'372561741242241',
		'3455	9496	1453	471',
		'6011 7797 40314841',
		'20146387 5428 731',
		'3158123082336831',
		'5320294088639739'
	];

	/**
	 *
	 * @var array
	 */
	protected $_insensitive_strings = [
		'l asdk alsdjl aksj dalsd',
		'sldka sld 123123123 askldasd asd ',
		'asd alskd al123k1 2l31ad aslda sdasd',
		'askdl  123k123 lkj898898 ad8a9sd a8sdasd 9as',
		'a9sda98A7SDASDAS AS98D ASDASJLDA SD9AS',
		'ASDÑ AÑS ASLDÑASK	ÑASKD ÑALKSDAA A0',
		'目前全球有六分之一人口使用漢語作為母語。漢語口語主要分為官話、粵語、吳語、湘語、贛語、客家語、閩語等七種；它们的語言學歸屬在西方語言學界存在爭議，或被認為是獨立的語言',
		'نەماڵەیەکی زمانییە کە شێوەزارەکانی کەم یان زۆر لەیە'
	];

	/**
	 *
	 * @var array
	 */
	protected $_sensitive_strings = [
		'l asdk 5320294088639779 alsdjl aksj dalsd' => 'l asdk ************9779 alsdjl aksj dalsd',
		'sldka sld 6011835424793183  askldasd 6011835424793183 asd' => 'sldka sld ************3183  askldasd ************3183 asd',
		'asd alskd al123k1 2l31ad 372611055400307 aslda sdasd' => 'asd alskd al123k1 2l31ad ***********0307 aslda sdasd',
		'askdl  4916535900086521 lkj898898 ad8a9sd a8sdasd 9as' => 'askdl  ************6521 lkj898898 ad8a9sd a8sdasd 9as',
		'a9sda98A7SDASDAS 348168645777620 AS98D ASDASJLDA SD9AS' => 'a9sda98A7SDASDAS ***********7620 AS98D ASDASJLDA SD9AS',
		'ASDÑ AÑS ASLDÑASK 372611055400307	ÑASKD ÑALKSDAA A0 372611055400307' => 'ASDÑ AÑS ASLDÑASK ***********0307	ÑASKD ÑALKSDAA A0 ***********0307',
		'目前全球有六分之一人口使用漢語作為母語。漢語口語主要分為官話 341879256804860 、粵語、吳語、湘語、贛語、客家語、閩語等七種；它们的語言學歸屬在西方語言學界存在爭議，或被認為是獨立的語言' => '目前全球有六分之一人口使用漢語作為母語。漢語口語主要分為官話 ***********4860 、粵語、吳語、湘語、贛語、客家語、閩語等七種；它们的語言學歸屬在西方語言學界存在爭議，或被認為是獨立的語言',
		'نەماڵەیەکی زمانییە کە شێوەزارەکانی کەم یان341879256804860  زۆر لەیە' => 'نەماڵەیەکی زمانییە کە شێوەزارەکانی کەم یان***********4860  زۆر لەیە'
	];

	/**
	 * @var Magoo
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new Magoo;
	}

	/**
	 * @covers Pachico\Magoo\Magoo::__construct
	 */
	public function testConstructor()
	{
		$util = new Utils\Luhn;
		$this->object = new Magoo($util);
	}

	/**
	 * @covers Pachico\Magoo\Magoo::creditCard
	 */
	public function testValidCreditCard()
	{
		foreach ($this->_valid_creditcards as $input => $expected_output)
		{
			$this->assertSame($this->object->creditCard($input), $expected_output);
		}
	}

	/**
	 * @covers Pachico\Magoo\Magoo::creditCard
	 */
	public function testInvalidCreditCard()
	{
		foreach ($this->_invalid_creditcards as $input)
		{
			$this->assertSame($this->object->creditCard($input), $input);
		}
	}

	/**
	 * @covers Pachico\Magoo\Magoo::creditCard
	 */
	public function testInsensitiveStrings()
	{
		foreach ($this->_insensitive_strings as $input)
		{
			$this->assertSame($this->object->creditCard($input), $input);
		}
	}

	/**
	 * @covers Pachico\Magoo\Magoo::creditCard
	 */
	public function testSensitiveStrings()
	{
		foreach ($this->_sensitive_strings as $input => $expected_output)
		{
			$this->assertSame($this->object->creditCard($input), $expected_output);
		}

		// README Examples
		$this->assertSame(
			$this->object->creditCard('My credit card number is 4716497744795464. Oops!'),
			'My credit card number is ************5464. Oops!'
		);

		$this->assertSame(
			$this->object->creditCard("My credit card numbers are: \n"
				. "· 4532 1163 1005 0433\n"
				. "· 6011-2881-3195-2124\n"
				. ". Oops!"),
			"My credit card numbers are: \n"
			. "· ************0433\n"
			. "· ************2124\n"
			. ". Oops!"
		);
	}

}
