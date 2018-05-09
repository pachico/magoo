<?php

require __DIR__ . '/../vendor/autoload.php';

use Pachico\Magoo\Magoo;

class FooMask implements \Pachico\Magoo\Mask\MaskInterface
{
    protected $replacement = '*';

    public function __construct(array $params = [])
    {
        if (isset($params['replacement']) && is_string($params['replacement'])) {
            $this->replacement = $params['replacement'];
        }
    }

    public function mask($string)
    {
        return str_replace('foo', $this->replacement, $string);
    }
}

$magoo = new Magoo();
$customMask = new FooMask(['replacement' => 'bar']);
$magoo->pushMask($customMask);

$mySensitiveString = 'It is time to go to the foo.'. PHP_EOL;

echo $magoo->getMasked($mySensitiveString);

// It is time to go to the bar.
