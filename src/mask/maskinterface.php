<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Magoo\Mask;

/**
 * Masks must implement this interface since
 * mask() method will be executed for all of them
 */
interface Maskinterface
{

	public function __construct(array $params = []);

	public function mask($string);
}
