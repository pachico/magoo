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
	 * @param array $params
	 * @return \Pachico\Magoo\Magoo
	 */
	public function maskCreditCards(array $params = [])
	{
		$this->_masks[static::MASK_CREDITCARD] = new Mask\Creditcard($params);
		return $this;
	}

	/**
	 * 
	 * @param array $params
	 * @return \Pachico\Magoo\Magoo
	 */
	public function maskByRegex(array $params = [])
	{
		$unique_id = uniqid('mask-');
		$this->_masks[$unique_id] = new Mask\Regex($params);
		return $this;
	}

	/**
	 *
	 * @param array $params
	 * @return \Pachico\Magoo\Magoo
	 */
	public function maskEmails(array $params = [])
	{
		$this->_masks[static::MASK_EMAIL] = new Mask\Email($params);
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
	 *
	 * @param string $input Input string to be masked
	 * @return string Masked string
	 */
	public function executeMasks($input)
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
