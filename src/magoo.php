<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo;

/**
 *
 */
class Magoo
{

	/**
	 *
	 * @var Utilsinterface
	 */
	protected $_util_luhn;

	/**
	 *
	 * @param Utils\Utilsinterface $util_luhn
	 */
	public function __construct(Utils\Utilsinterface $util_luhn = null)
	{
		$this->_util_luhn = $util_luhn ?
			: new Utils\Luhn();
	}

	/**
	 *
	 * @param string $string
	 * @param string $replacement
	 * @return string
	 */
	public function creditCard($string, $replacement = '*')
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
				$replacement = str_pad('', $card_length - 4, $replacement) . substr($stripped_match, -4);

				// If so, replace the match
				$string = str_replace($match, $replacement, $string);
			}
		}

		return $string;
	}

}
