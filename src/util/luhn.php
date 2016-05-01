<?php

/**
 * @author Mariano F.co Benítez Mulet <pachicodev@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo\Util;

/**
 * Simple class related to Luhn algorithm
 * @see https://en.wikipedia.org/wiki/Luhn_algorithm
 */
class Luhn implements Utilinterface
{

	/**
	 *
	 * @param string $input
	 * @return bool
	 */
	public function isLuhn($input)
	{

		if (!is_numeric($input))
		{
			return false;
		}

		$numeric_string = (string) preg_replace('/\D/', '', $input);

		$sum = 0;

		$numDigits = strlen($numeric_string) - 1;

		$parity = $numDigits % 2;

		for ($i = $numDigits; $i >= 0; $i--)
		{
			$digit = substr($numeric_string, $i, 1);

			if (!$parity == ($i % 2))
			{
				$digit <<= 1;
			}

			$digit = ($digit > 9)
				? ($digit - 9)
				: $digit;

			$sum += $digit;
		}

		return (0 == ($sum % 10));
	}

}
