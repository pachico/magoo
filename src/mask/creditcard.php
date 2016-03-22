<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo\Mask;

use Pachico\Magoo\Util;

/**
 * The concept behind this is:
 * If we can find a valid Luhn long enough to be a CCard, mask it
 * If it is not a valid Luhn, no need to mask it
 */
class Creditcard implements Maskinterface
{

	/**
	 *
	 * @var string
	 */
	protected $_replacement = '*';

	/**
	 *
	 * @var Util\Utilinterface
	 */
	protected $_util_luhn;

	/**
	 *
	 * @param array $params
	 */
	public function __construct(array $params = [], Util\Utilinterface $util_luhn = null)
	{
		if (isset($params['replacement']) && is_string($params['replacement']))
		{
			$this->_replacement = $params['replacement'];
		}

		$this->_util_luhn = $util_luhn ?
			: new Util\Luhn();
	}

	/**
	 * This will only mask a CC number if it's a valid Luhn, since, otherwise, it's not a correct CC number.
	 * @param string $string
	 * @return string
	 */
	public function mask($string)
	{
		$regex = '/(?:\d[ \t-]*?){13,19}/m';

		$matches = [];

		preg_match_all($regex, $string, $matches);

		// No credit card found
		if (!isset($matches[0]) || empty($matches[0]))
		{
			return $string;
		}

		foreach ($matches as $match_group)
		{
			foreach ($match_group as $match)
			{
				$stripped_match = preg_replace('/[^\d]/', '', $match);

				// Is it a valid Luhn one?
				if (false === $this->_util_luhn->isLuhn($stripped_match))
				{
					continue;
				}

				$card_length = strlen($stripped_match);
				$replacement = str_pad('', $card_length - 4, $this->_replacement) . substr($stripped_match, -4);

				// If so, replace the match
				$string = str_replace($match, $replacement, $string);
			}
		}

		return $string;
	}

}
