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
	 * Mask identifier
	 */
	CONST MASK_CREDITCARD = 'creditcard';
	CONST MASK_EMAIL = 'email';

	/**
	 * Contains masks that will apply to Magoo instance
	 * @var array
	 */
	protected $_masks = [];

	/**
	 *
	 * @param array $params
	 * @return \Pachico\Magoo\Magoo
	 */
	public function maskCreditCard(array $params = [])
	{
		$this->_masks[static::MASK_CREDITCARD] = new Mask\Creditcard($params);
		return $this;
	}

	/**
	 *
	 * @param array $params
	 * @return \Pachico\Magoo\Magoo
	 */
	public function maskEmail(array $params = [])
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
	 * @param string $input
	 * @return string
	 */
	public function mask($input)
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
