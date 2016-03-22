<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo\Mask;

/**
 * Email regex detection is still controversial.
 * Please report, any improvements
 */
class Email implements Maskinterface
{

	/**
	 *
	 * @var string
	 */
	protected $_replacement_local = null;

	/**
	 *
	 * @var string
	 */
	protected $_replacement_domain = null;

	/**
	 *
	 * @param array $params
	 */
	public function __construct(array $params = [])
	{
		if (isset($params['local_replacement']) && is_string($params['local_replacement']))
		{
			$this->_replacement_local = $params['local_replacement'];
		}

		if (isset($params['domain_replacement']) && is_string($params['domain_replacement']))
		{
			$this->_replacement_domain = $params['domain_replacement'];
		}

		if (is_null($this->_replacement_local) && is_null($this->_replacement_domain))
		{
			$this->_replacement_local = '*';
		}
	}

	/**
	 *
	 * @param string $match
	 * @return string
	 */
	protected function _mask_individual_email_match($match)
	{
		$match_replacement = $match;

		if ($this->_replacement_local)
		{
			$local_part = substr($match, 0, stripos($match, '@'));
			$match_replacement = str_replace($local_part, str_pad('', strlen($local_part), $this->_replacement_local),
				$match_replacement);
		}

		if ($this->_replacement_domain)
		{
			$domain_part = substr($match, stripos($match, '@') + 1);
			$match_replacement = str_replace($domain_part, str_pad('', strlen($domain_part), $this->_replacement_domain),
				$match_replacement);
		}

		return $match_replacement;
	}

	/**
	 *
	 * @param string $string
	 * @return string
	 */
	public function mask($string)
	{

		$regex = "/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";

		$matches = [];

		preg_match_all($regex, $string, $matches);

		// No email found
		if (!isset($matches[0]) || empty($matches[0]))
		{
			return $string;
		}

		foreach ($matches as $match_group)
		{
			foreach ($match_group as $match)
			{
				$string = str_replace($match, $this->_mask_individual_email_match($match), $string);
			}
		}

		return $string;
	}

}
