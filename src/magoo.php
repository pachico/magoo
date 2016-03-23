<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo;

/**
 * Magoo will mask sensitive data from strings
 */
class Magoo
{

	/**
	 * Mask identifier
	 */
	CONST MASK_CREDITCARD = 'creditcard';
	CONST MASK_EMAIL = 'email';

	/**
	 * Contains masks that will apply to Magoo instance
	 * @var array Contains masks to apply
	 */
	protected $_masks = [];

	/**
	 *
	 * @param string $replacement Character to replace matches
	 * @return \Pachico\Magoo\Magoo
	 */
	public function maskCreditCards($replacement = '*')
	{
		$this->_masks[static::MASK_CREDITCARD] = new Mask\Creditcard(['replacement' => (string) $replacement]);
		return $this;
	}

	/**
	 *
	 * @param string $regex Regex to be executed
	 * @param string $replacement Character to replace matches
	 * @return \Pachico\Magoo\Magoo
	 */
	public function maskByRegex($regex, $replacement = '*')
	{
		$unique_id = uniqid('mask-');
		$this->_masks[$unique_id] = new Mask\Regex(['regex' => empty($regex)
				? '/^$/'
				: (string) $regex, 'replacement' => (string) $replacement]);
		return $this;
	}

	/**
	 *
	 * @param string $local_replacement Character to replace local part of email
	 * @param string $domain_replacement Character to replace domain part of email
	 * @return \Pachico\Magoo\Magoo
	 */
	public function maskEmails($local_replacement = '*', $domain_replacement = null)
	{
		$this->_masks[static::MASK_EMAIL] = new Mask\Email([
			'local_replacement' => is_null($local_replacement)
				? null
				: (string) $local_replacement,
			'domain_replacement' => is_null($domain_replacement)
				? null
				: (string) $domain_replacement
			]
		);

		return $this;
	}

	/**
	 *
	 * @param \Pachico\Magoo\Mask\Maskinterface $customMask
	 * @return \Pachico\Magoo\Magoo
	 */
	public function addCustomMask(Mask\Maskinterface $customMask)
	{
		$unique_id = uniqid('mask-');
		$this->_masks[$unique_id] = $customMask;
		return $this;
	}

	/**
	 * Reset Magoo by deleting all previously added masks
	 * @return \Pachico\Magoo\Magoo
	 */
	public function reset()
	{
		$this->_masks = [];
		return $this;
	}

	/**
	 *
	 * @param string $input Input string to be masked
	 * @return string Masked string
	 */
	public function getMasked($input)
	{
		if (empty($this->_masks))
		{
			return $input;
		}

		foreach ($this->_masks as $mask)
		{
			$input = $mask->mask($input);
		}

		return $input;
	}

}
