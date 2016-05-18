<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\Magoo\Mask;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    protected $validEmails = [
        'To donate write to ted@crilly.com, please'
        => 'To donate write to ***@**********, please',
        'In case of dragons, report to dougal@mcguire.net'
        => 'In case of dragons, report to ******@***********',
        'Booz expert: jack@hackett.org'
        => 'Booz expert: ****@***********',
        'To get more sandwitches, mail me at mrs@doyle.co.uk'
        => 'To get more sandwitches, mail me at ***@***********',
        'To report any of the above, contact me at bishop@brennan.biz'
        => 'To report any of the above, contact me at ******@***********'
    ];
    protected $noValidEmails = [
        'This is just a random string',
        'It almost looks like an email: asdas@',
        'This is almost interesting: foo.bar.com'
    ];
    protected $object;

    protected function setUp()
    {
        $this->object = new Email(
            ['localReplacement' => '*', 'domainReplacement' => '*']
        );
    }

    public function testMask()
    {
        foreach ($this->validEmails as $input => $output) {
            $this->assertSame($this->object->mask($input), $output);
        }

        foreach ($this->noValidEmails as $input) {
            $this->assertSame($this->object->mask($input), $input);
        }
    }

    public function testConstruct()
    {
        $this->object = new Email(['localReplacement' => '*']);
        $this->assertSame($this->object->mask('bernard@black.com'), '*******@black.com');

        $this->object = new Email(['domainReplacement' => '*']);
        $this->assertSame($this->object->mask('manny@bianco.co.uk'), 'manny@************');

        $this->object = new Email();
        $this->assertSame($this->object->mask('fran@katzenjammer.net'), '****@katzenjammer.net');
    }
}
