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
use Psr\Log\LogLevel;

/**
 * MagooLogger acts as a middleware between your application and a PSR3 logger
 * masking every message passed to it
 *
 * @method null emergency($message, array $context)
 * @method null alert($message, array $context)
 * @method null critical($message, array $context)
 * @method null error($message, array $context)
 * @method null warning($message, array $context)
 * @method null notice($message, array $context)
 * @method null info($message, array $context)
 * @method null debug($message, array $context)
 * @method null log($message, array $context)
 */
class MagooLogger
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var MaskManagerInterface
     */
    protected $maskManager;

    /**
     * @param LoggerInterface $logger
     * @param MaskManagerInterface $maskManager
     */
    public function __construct(LoggerInterface $logger, MaskManagerInterface $maskManager)
    {
        $this->logger = $logger;
        $this->maskManager = $maskManager;
    }

    /**
     * @param string $method
     * @param array $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        switch ($method) {
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
            case LogLevel::DEBUG:
            case LogLevel::EMERGENCY:
            case LogLevel::ERROR:
            case LogLevel::INFO:
            case LogLevel::NOTICE:
            case LogLevel::WARNING:
                $maskedMessage = $this->maskManager->getMasked($args[0]);
                $args[0] = $maskedMessage;
                break;
            case 'log':
                $maskedMessage = $this->maskManager->getMasked($args[1]);
                $args[1] = $maskedMessage;
                break;
        }

        call_user_func_array(
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
    public function getMaskManager()
    {
        return $this->maskManager;
    }
}
