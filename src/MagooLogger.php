<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\Magoo;

use Pachico\Magoo\MagooArray;
use Psr\Log\LoggerInterface;

/**
 * MagooLogger acts as a middleware between your application and a PSR3 logger
 * masking every message passed to it
 */
class MagooLogger implements LoggerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MaskManagerInterface
     */
    private $maskManager;

    /**
     * @var MagooArray
     */
    private $magooArray;

    /**
     * @param LoggerInterface $logger
     * @param MaskManagerInterface $maskManager
     */
    public function __construct(LoggerInterface $logger, MaskManagerInterface $maskManager)
    {
        $this->logger = $logger;
        $this->maskManager = $maskManager;
        $this->magooArray = new MagooArray($maskManager);
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

    public function emergency($message, array $context = array())
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'emergency'], $maskedArguments);
    }

    public function alert($message, array $context = array())
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'alert'], $maskedArguments);
    }

    public function critical($message, array $context = array())
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'critical'], $maskedArguments);
    }

    public function error($message, array $context = array())
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'error'], $maskedArguments);
    }

    public function warning($message, array $context = array())
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'warning'], $maskedArguments);
    }


    public function notice($message, array $context = array())
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'notice'], $maskedArguments);
    }

    public function info($message, array $context = array())
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'info'], $maskedArguments);
    }

    public function debug($message, array $context = array())
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'debug'], $maskedArguments);
    }

    public function log($level, $message, array $context = array())
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        array_unshift($maskedArguments, $level);
        call_user_func_array([$this->logger, 'log'], $maskedArguments);
    }

    /**
     * @param string $message
     * @param array $context
     *
     * @return array Masked arguments
     */
    private function maskLogArguments($message, array $context)
    {
        return $this->magooArray->getMasked([$message, $context]);
    }
}
