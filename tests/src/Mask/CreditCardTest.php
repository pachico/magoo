<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\Magoo\Mask;

/**
 * All credit card numbers have been randomly generated using http://www.getcreditcardnumbers.com/
 */
class CreditCardTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var array Strings containing valid credit cards
     */
    protected $validCcs = [
        'My credit card is 4556168690125914. Please, spread it!' =>
        'My credit card is ************5914. Please, spread it!',
        'My credit cards are 6011 3885 3731 4927 and 5465-7136-4763-2236. Buy something!' =>
        'My credit cards are ************4927 and ************2236. Buy something!',
        'Creditcard rampage! 6011 612890653518, 601167 7315389477, 60117 4607031 4770, 6011-3885373-14927' =>
        'Creditcard rampage! ************3518, ************9477, ************4770, ************4927'
    ];

    /**
     * @var array Strings without valid credit cards
     */
    protected $invalidCcs = [
        'This string is not sentive',
        'My credit card is 4556168690125814',
        'All my credit cards are fake: 5544239360013512, 4916184779428320'
    ];

    /**
     * @var Pachico\Magoo\Mask\Creditcard
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Creditcard;
    }

    public function testConstructor()
    {
        $this->object = new Creditcard(
            [
            'replacement' => '$'
            ],
            new \Pachico\Magoo\Util\Luhn
        );

        foreach ($this->validCcs as $input => $output) {
            $output = str_replace('*', '$', $output);
            $this->assertSame($this->object->mask($input), $output);
        }
    }

    public function testMask()
    {
        foreach ($this->validCcs as $input => $output) {
            $this->assertSame($this->object->mask($input), $output);
        }

        foreach ($this->invalidCcs as $input) {
            $this->assertSame($this->object->mask($input), $input);
        }
    }
}
