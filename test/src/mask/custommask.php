<?php

/**
 * @author Mariano F.co Benítez Mulet <pachicodev@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo\Mask;

class Custommask implements Maskinterface
{

	/**
	 *
	 * @var string
	 */
	protected $_replacement = '*';

	/**
	 *
	 * @param array $params
	 */
	public function __construct(array $params = [])
	{
		if (isset($params['replacement']) && is_string($params['replacement']))
		{
			$this->_replacement = $params['replacement'];
		}
	}

	/**
	 *
	 * @param string $string
	 */
	public function mask($string)
	{
		return str_replace('foo', $this->_replacement, $string);
	}

}
