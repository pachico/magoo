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
use Psr\Log\LogLevel;
use BadMethodCallException;
use InvalidArgumentException;
use Exception;

/**
 * MagooLogger acts as a middleware between your application and a PSR3 logger
 * masking every message passed to it
 *
 * @method null emergency($message, array $context = null)
 * @method null alert($message, array $context = null)
 * @method null critical($message, array $context = null)
 * @method null error($message, array $context = null)
 * @method null warning($message, array $context = null)
 * @method null notice($message, array $context = null)
 * @method null info($message, array $context = null)
 * @method null debug($message, array $context = null)
 * @method null log($level, $message, array $context = null)
 */
class MagooLogger
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
     * @var array Possible PSR3 log levels
     */
    private $logLevels = [
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::DEBUG,
        LogLevel::EMERGENCY,
        LogLevel::ERROR,
        LogLevel::INFO,
        LogLevel::NOTICE,
        LogLevel::WARNING
    ];

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
     * @param string $method
     * @param array $args
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     *
     * @throws BadMethodCallException
     */
    public function __call($method, $args)
    {
        if (in_array($method, $this->logLevels)) {
            $maskedMessage = $this->maskManager->getMasked($args[0]);
            $args[0] = $maskedMessage;
            if (isset($args[1])) {
                $args[1] = $this->magooArray->getMasked($args[1]);
            }
        } elseif ('log' === $method) {
            $maskedMessage = $this->maskManager->getMasked($args[1]);
            $args[1] = $maskedMessage;
            if (isset($args[2])) {
                $args[2] = $this->magooArray->getMasked($args[2]);
            }
        } else {
            throw new BadMethodCallException(
                sprintf('%s method does not exist in %s.', $method, get_class($this->logger))
            );
        }

        try {
            call_user_func_array([$this->logger, $method], $args);
        } catch (Exception $exc) {
            throw $exc;
        }
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
