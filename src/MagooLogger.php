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
     * @param array $rawArguments
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     *
     * @throws BadMethodCallException
     */
    public function __call($method, $rawArguments)
    {
        if (!in_array($method, array_merge($this->logLevels, ['log']))) {
            throw new BadMethodCallException(
                sprintf('%s method does not exist in %s.', $method, get_class($this->logger))
            );
        }

        $arguments = $this->getMaskedArguments($method, $rawArguments);

        try {
            call_user_func_array([$this->logger, $method], $arguments);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @param string $method
     * @param array $rawArguments
     *
     * @return array Masked arguments
     */
    private function getMaskedArguments($method, $rawArguments)
    {
        $arguments = $rawArguments;
        
        if (in_array($method, $this->logLevels)) {
            $maskedMessage = $this->maskManager->getMasked($arguments[0]);
            $arguments[0] = $maskedMessage;
            if (isset($arguments[1]) && !empty($arguments[1])) {
                $arguments[1] = $this->magooArray->getMasked($arguments[1]);
            }

            return $arguments;
        }
        
        $maskedMessage = $this->maskManager->getMasked($arguments[1]);
        $arguments[1] = $maskedMessage;
        if (isset($rawArguments[2]) && !empty($arguments[2])) {
            $arguments[2] = $this->magooArray->getMasked($arguments[2]);
        }
        
        return $arguments;
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
