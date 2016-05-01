<?php

/**
 * @author Mariano F.co Benítez Mulet <pachicodev@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo\Mask;

/**
 * Regular expression substitution
 */
class Regex implements Maskinterface
{

	/**
	 *
	 * @var string
	 */
	protected $_replacement = '*';

	/**
	 *
	 * @var string
	 */
	protected $_regex = '';

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

		if (isset($params['regex']) && is_string($params['regex']))
		{
			$this->_regex = $params['regex'];
		}
	}

	/**
	 *
	 * @param string $string
	 * @return string
	 */
	public function mask($string)
	{
		$replacements = [];

		preg_replace_callback($this->_regex, function($matches) use (&$replacements)
		{
			foreach ($matches as $match)
			{
				$replacements[(string) $match] = str_pad('', mb_strlen($match), $this->_replacement);
			}
		}, $string);

		if (empty($replacements))
		{
			return $string;
		}

		return str_replace(array_keys($replacements), array_values($replacements), $string);
	}

}
