<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\Magoo;

use Psr\Log\LoggerInterface;

class MagooLogger
{
    /**
     * @var \LoggerInterface
     */
    protected $logger;
    /**
     * @var Magoo
     */
    protected $maskManager;

    /**
     * @param LoggerInterface $logger
     * @param \Pachico\Magoo\Magoo $magoo
     */
    public function __construct(LoggerInterface $logger, MaskManagerInterface $maskManager)
    {
        $this->logger = $logger;
        $this->maskManager = $maskManager;
    }

    public function __call($method, $args)
    {

        $message = $args[0];

        $maskedMessage = $this->maskManager->getMasked($message);

        $args[0] = $maskedMessage;

        return call_user_func_array(
            array(
                $this->logger,
                $method
            ),
            $args
        );
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return MaskManagerInterface
     */
    public function getMagoo()
    {
        return $this->maskManager;
    }
}
